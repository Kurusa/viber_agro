<?php

namespace App\Parser;

use App\Models\Cache;
use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as DB;
use PHPHtmlParser\Dom;

class Stolychnuy
{

    private $curl;

    public function parse()
    {
        $this->curl = curl_init();
        $data = DB::select('SELECT * FROM cache WHERE Date(created_at) = CURDATE() AND market = "stolychnuy"');
        if ($data) {
            $message = 'Ціни станом на ' . date('Y.m.d') . ' на ринку Столичний' . "\n";
            foreach ($data as $key => $datum) {
                $message .= $key + 1 . '. ' . $datum->title . "\n";
                $message .= $datum->min . ' | ' . $datum->avar . ' | ' . $datum->max . "\n" . "\n";
            }
        } else {
            $dom = new Dom;

            $dom->load($this->do('https://kyivopt.com/ua/dlya-pokupciv/tsini/ovochi-ta-frukti3'));
            $description = $dom->find('.article-date')->text;
            $message = $description . 'на ринку Столичний' . "\n";

            $table_data = $dom->find('.text-box.bordered > table > tbody');
            $i = 0;
            $table_data->find('tr')->each(function ($tr) use (&$message, &$i) {
                $insert_data = [];
                $i++;
                $td_i = 0;
                $tr->find('td')->each(function ($td) use (&$message, &$i, &$td_i, &$insert_data) {
                    if ($td->text) {
                        $td_i++;
                        if ($td_i === 1) {
                            $message .= $i . '.';
                            $insert_data['title'] = trim($td->text);
                        } elseif ($td_i > 2 && $td_i <= 4) {
                            if ($td_i == 3) {
                                $insert_data['avar'] = trim($td->text);
                            } else {
                                $insert_data['max'] = trim($td->text);
                            }
                            $message .= ' | ';
                        } elseif ($td_i === 2) {
                            $insert_data['min'] = trim($td->text);
                            $message .= "\n";
                        }

                        if ($td_i !== 2) {
                            $message .= ' ';
                        }

                        $message .= str_replace('&nbsp;', '', trim($td->text));
                        if ($td_i === 4) {
                            $message .= "\n";
                            $message .= "\n";

                            $insert_data['max'] = trim($td->text);
                            $insert_data['created_at'] = Carbon::now();
                            $insert_data['market'] = 'stolychnuy';
                            Cache::insert([$insert_data]);
                        }
                    } else {
                        $i = 0;
                    }
                });
            });
        }
        return $message;
    }

    private function do(string $url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($this->curl);
        return $result;
    }

    public function __destruct()
    {
        $this->curl = curl_close($this->curl);
    }


}
