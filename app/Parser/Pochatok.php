<?php

namespace App\Parser;

use App\Models\Cache;
use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as DB;
use PHPHtmlParser\Dom;

class Pochatok
{

    private $curl;

    public function parse()
    {
        $this->curl = curl_init();

        $data = DB::select('SELECT * FROM cache WHERE Date(created_at) = CURDATE() AND market = "pochatok" AND category = 1');
        if ($data) {
            $message = 'Ціни станом на ' . date('Y.m.d') . ' на ринку Початок' . "\n";
            $message .= 'Овочі' . "\n";
            foreach ($data as $key => $datum) {
                $message .= $key + 1 . '. ' . $datum->title . "\n";
                $message .= $datum->min . ' | ' . $datum->avar . ' | ' . $datum->max . $datum->comment . "\n" . "\n";
            }

            $data = DB::select('SELECT * FROM cache WHERE Date(created_at) = CURDATE() AND market = "pochatok" AND category = 0');
            $message .= 'Зелень' . "\n";
            foreach ($data as $key => $datum) {
                $message .= $key + 1 . '. ' . $datum->title . "\n";
                $message .= $datum->min . ' | ' . $datum->avar . ' | ' . $datum->max . $datum->comment . "\n" . "\n";
            }
        } else {
            $dom = new Dom;
            $dom->load($this->do('https://www.pochatok.od.ua/ru/ovoshhi'));
            $message = 'Ціни станом на ' . date('Y.m.d') . ' на ринку Початок' . "\n";
            $message .= 'Овочі' . "\n";
            $div = $dom->find('.entry-content > div')[1];
            $table_data = $div->find('table > tbody');
            $i = 0;
            $table_data->find('tr')->each(function ($tr) use (&$message, &$i) {
                $insert_data = [];
                $i++;
                $td_i = 0;
                if ($tr->find('td')[0]->text !== 'Наименование товара') {
                    $tr->find('td')->each(function ($td) use (&$message, &$i, &$td_i, &$insert_data) {
                        $td_i++;
                        if ($td_i == 1) {
                            $message .= $i - 1 . '.';
                            $insert_data['title'] = trim($td->text);
                        } elseif ($td_i > 2 && $td_i <= 4) {
                            if ($td_i == 3) {
                                $insert_data['avar'] = trim($td->text);
                            } else {
                                $insert_data['max'] = trim($td->text);
                            }
                            $message .= ' |';
                        } elseif ($td_i === 2) {
                            $insert_data['min'] = trim($td->text);
                            $message .= "\n";
                        } elseif ($td_i === 5) {
                            if (strlen(trim($td->text))) {
                                $insert_data['comment'] = ' (' . trim($td->text) . ')';
                                $message .= ' (' . trim($td->text) . ')';
                            }
                            $message .= "\n";
                            $message .= "\n";

                            $insert_data['created_at'] = Carbon::now();
                            $insert_data['market'] = 'pochatok';
                            $insert_data['category'] = 1;
                            Cache::insert([$insert_data]);
                        }
                        if ($td_i !== 2) {
                            $message .= ' ';
                        }

                        if ($td_i !== 5) {
                            $message .= trim($td->text);
                        }
                    });
                }
            });

            $message .= "\n" . 'Зелень' . "\n";
            $div = $dom->find('.entry-content > div')[2];
            $table_data = $div->find('table > tbody');
            $i = 0;
            $table_data->find('tr')->each(function ($tr) use (&$message, &$i) {
                $insert_data = [];
                $i++;
                $td_i = 0;
                if ($tr->find('td')[0]->text !== 'Наименование товара') {
                    $tr->find('td')->each(function ($td) use (&$message, &$i, &$td_i, &$insert_data) {
                        $td_i++;
                        if ($td_i == 1) {
                            $message .= $i - 1 . '.';
                            $insert_data['title'] = trim($td->text);
                        } elseif ($td_i > 2 && $td_i <= 4) {
                            if ($td_i == 3) {
                                $insert_data['avar'] = trim($td->text);
                            } else {
                                $insert_data['max'] = trim($td->text);
                            }
                            $message .= ' |';
                        } elseif ($td_i === 2) {
                            $insert_data['min'] = trim($td->text);
                            $message .= "\n";
                        } elseif ($td_i === 5) {
                            if ($td->text !== '0') {
                                $insert_data['comment'] = ' (пучок, грн ' . trim($td->text) . ')';
                                $message .= ' (пучок, грн ' . trim($td->text) . ')';
                            }
                            $message .= "\n";
                            $message .= "\n";

                            $insert_data['created_at'] = Carbon::now();
                            $insert_data['market'] = 'pochatok';
                            $insert_data['category'] = 0;
                            Cache::insert([$insert_data]);
                        }
                        if ($td_i !== 2) {
                            $message .= ' ';
                        }

                        if ($td_i !== 5) {
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
