<?php

namespace App\Parser;

use App\Models\Cache;
use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as DB;
use PHPHtmlParser\Dom;

class Shuvar
{

    private $curl;

    public function parse()
    {
        $this->curl = curl_init();
        $data = DB::select('SELECT * FROM cache WHERE Date(created_at) = CURDATE() AND market = "shuvar"');
        if ($data) {
            $message = 'Ціни станом на ' . date('Y.m.d') . ' на ринку Шувар' . "\n";
            foreach ($data as $key => $datum) {
                $message .= $key + 1 . '. ' . $datum->title . "\n";
                $message .= $datum->min . ' | ' . $datum->avar . ' | ' . $datum->max . $datum->stat . "\n" . "\n";
            }
        } else {
            $dom = new Dom;
            $dom->load($this->do('https://info.shuvar.com/price'));
            $need_attributes = ['Продукт', 'Ціна (Мінімум)', 'Ціна (Середня)', 'Ціна (Максимум)', 'Динаміка'];
            $message = 'Ціни станом на ' . date('Y.m.d') . ' на ринку Шувар' . "\n";

            $table_data = $dom->load($this->do('https://info.shuvar.com/analytics/loadTablePrice'));
            $i = 0;
            $insert_data = [];
            $table_data->find('td')->each(function ($td) use ($need_attributes, &$message, &$i, &$insert_data) {
                $label = $td->getAttribute('data-label');
                if ($label && in_array($label, $need_attributes)) {

                    if ($label == 'Продукт') {
                        $insert_data['title'] = trim($td->text);
                        $i++;
                        $message .= $i . '. ';
                    }

                    $message .= trim($td->text);
                    if ($label == 'Ціна (Максимум)') {
                        $insert_data['max'] = trim($td->text);
                    }

                    if ($label == 'Ціна (Мінімум)' || $label == 'Ціна (Середня)') {
                        if ($label == 'Ціна (Мінімум)') {
                            $insert_data['min'] = trim($td->text);
                        } else {
                            $insert_data['avar'] = trim($td->text);
                        }
                        $message .= ' | ';
                    }
                    if ($label == 'Продукт') {
                        $message .= "\n";
                    }
                    if ($label == 'Динаміка') {
                        $insert_data['stat'] = '';
                        if ($td->getAttribute('class') == 'equal') {
                            $insert_data['stat'] .= ' ↔️ ';
                            $message .= ' ↔️ ';
                        } elseif ($td->getAttribute('class') == 'decrease') {
                            $insert_data['stat'] .= ' ⬇️️️ ';
                            $message .= ' ⬇️️ ';
                        } else {
                            $insert_data['stat'] .= ' ⬆️️️️ ';
                            $message .= ' ⬆️ ';
                        }

                        $insert_data['stat'] .= $td->find('span')[3]->text;
                        $insert_data['created_at'] = Carbon::now();
                        $insert_data['market'] = 'shuvar';
                        $message .= $td->find('span')[3]->text;
                        $message .= "\n";
                        $message .= "\n";

                        Cache::insert([$insert_data]);
                        $insert_data = [];
                    }
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
