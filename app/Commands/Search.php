<?php

namespace App\Commands;

use App\Models\Cache;
use App\Services\Status\UserStatusService;
use Carbon\Carbon;

class Search extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::SEARCH) {
            $data = Cache::where('title', 'like', '%' . $this->parser::getMessage() . '%')->whereDate('created_at', Carbon::today())->get();
            if ($data) {
                $market_list = [
                    'shuvar' => [
                        'name' => 'Шувар',
                    ],
                    'stolychnuy' => [
                        'name' => 'Столичний',
                    ],
                    'pochatok' => [
                        'name' => 'Початок',
                    ],
                    'kopani' => [
                        'name' => 'Копани',
                    ],
                ];
                $message = '';
                foreach ($data as $key => $datum) {
                    $message .= $key + 1 . '. ' . $market_list[$datum['market']]['name'] . "\n";
                    $message .= $datum['title'] . ': ';
                    switch ($datum['market']) {
                        case 'kopani':
                            $message .= $datum['date'] . ' - ' . $datum['min'];
                            break;
                        case 'pochatok':
                            $message .= $datum['min'] . ' | ' . $datum['avar'] . ' | ' . $datum['max'] . $datum['comment'];
                            break;
                        case 'stolychnuy':
                            $message .= $datum['min'] . ' | ' . $datum['avar'] . ' | ' . $datum['max'];
                            break;
                        case 'shuvar':
                            $message .= $datum['min'] . ' | ' . $datum['avar'] . ' | ' . $datum['max'] . $datum['stat'];
                            break;
                    }
                    $message .= "\n" . "\n";
                }
                $this->viber->sendMessageWithKeyboard($message, [
                    [
                        'Columns' => 6,
                        'Rows' => 1,
                        'ActionType' => 'reply',
                        'BgColor' => '#D1EDF2',
                        'TextOpacity' => 60,
                        'TextSize' => 'large',
                        'ActionBody' => 'main_menu',
                        'Text' => '<b>📊Главное меню</b>'
                    ]
                ]);
            } else {
                $this->viber->sendMessageWithKeyboard('По вашему запросу ничего не найдено', [
                    [
                        'Columns' => 6,
                        'Rows' => 1,
                        'ActionType' => 'reply',
                        'BgColor' => '#D1EDF2',
                        'TextOpacity' => 60,
                        'TextSize' => 'large',
                        'ActionBody' => 'main_menu',
                        'Text' => '<b>📊Главное меню</b>'
                    ]
                ]);
            }
        } else {
            $this->user->status = UserStatusService::SEARCH;
            $this->user->save();

            $this->viber->sendMessageWithKeyboard('Введите название продукта', [
                [
                    'Columns' => 6,
                    'Rows' => 1,
                    'ActionType' => 'reply',
                    'BgColor' => '#D1EDF2',
                    'TextOpacity' => 60,
                    'TextSize' => 'large',
                    'ActionBody' => 'main_menu',
                    'Text' => '<b>📊Главное меню</b>'
                ]
            ]);
        }
    }

}
