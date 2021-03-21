<?php

namespace App\Parser;

use App\Models\Cache;
use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as DB;
use PHPHtmlParser\Dom;

class Kopani
{

    private $curl;

    public function parse()
    {
        $this->curl = curl_init();
        $data = DB::select('SELECT * FROM cache WHERE Date(created_at) = CURDATE() AND market = "kopani"');
        if ($data) {
            $message = 'Ціни станом на ' . date('Y.m.d') . ' на ринку Копани' . "\n";
            foreach ($data as $key => $datum) {
                $message .= $key + 1 . '. ' . $datum->title . "\n";
                $message .= $datum->date . ' - ' . $datum->min . "\n" . "\n";
            }
        } else {
            $dom = new Dom;

            $dom->load($this->do('http://kopani.org.ua/ceny-na-rynke/'));
            $message = 'Ціни станом на ' . date('Y.m.d') . ' на ринку Копани' . "\n";

            $table_data = $dom->find('blockquote > table > tbody');
            $i = 0;
            $table_data->find('tr')->each(function ($tr) use (&$message, &$i) {
                $insert_data = [];
                $i++;
                if ($i > 4) {
                    $td_i = 0;
                    $tr->find('td')->each(function ($td) use (&$message, &$i, &$td_i, &$insert_data) {
                        $td_i++;
                        if ($td_i == 1) {
                            $insert_data['title'] = trim($td->text);
                            $message .= $i - 4 . '.';
                        } elseif ($td_i == 2) {
                            $insert_data['date'] = trim($td->text);
                            $message .= "\n";
                        } elseif ($td_i === 3) {
                            $insert_data['min'] = trim($td->text);
                            $message .= ' - ' . trim($td->text);
                            $message .= "\n";
                            $message .= "\n";

                            $insert_data['created_at'] = Carbon::now();
                            $insert_data['market'] = 'kopani';
                            Cache::insert([$insert_data]);

                            $td_i = 0;
                        }

                        if ($td_i !== 0) {
                            $message .= trim($td->text);
                        }
                    });
                }
            });
        }
        return $message;
    }

    private function do(string $url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLINFO_HEADER_OUT, true); // enable tracking
        $result = curl_exec($this->curl);
        return $result;
    }

    public function __destruct()
    {
        $this->curl = curl_close($this->curl);
    }

}

