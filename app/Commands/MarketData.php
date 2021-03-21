<?php

namespace App\Commands;

use App\Parser\Kopani;
use App\Parser\Pochatok;
use App\Parser\Shuvar;
use App\Parser\Stolychnuy;

class MarketData extends BaseCommand
{

    function processCommand()
    {
        $market_list = [
            'shuvar' => [
                'name' => 'Шувар',
                'class' => Shuvar::class
            ],
            'stolychnuy' => [
                'name' => 'Столичний',
                'class' => Stolychnuy::class
            ],
            'pochatok' => [
                'name' => 'Початок',
                'class' => Pochatok::class
            ],
            'kopani' => [
                'name' => 'Копани',
                'class' => Kopani::class
            ],
        ];

        if ($market_list[$this->parser::getMessage()]) {
            $data = (new $market_list[$this->parser::getMessage()]['class']())->parse();
            if ($data) {
                $buttons = [];
                foreach ($market_list as $key => $market) {
                    $buttons[] = [
                        'Columns' => 6,
                        'Rows' => 1,
                        'ActionType' => 'reply',
                        'BgColor' => '#D1EDF2',
                        'TextOpacity' => 60,
                        'TextSize' => 'large',
                        'ActionBody' => $key,
                        'Text' => $key == $this->parser::getMessage() ? $market['name'].'☑️' : $market['name']
                    ];
                }
                $buttons[] = [
                    'Columns' => 6,
                    'Rows' => 1,
                    'ActionType' => 'reply',
                    'BgColor' => '#D1EDF2',
                    'TextOpacity' => 60,
                    'TextSize' => 'large',
                    'ActionBody' => 'main_menu',
                    'Text' => '🛍Главное меню'
                ];
                $this->viber->sendMessageWithKeyboard($data, $buttons);
            } else {
                $this->viber->sendMessage('Что-то пошло не так');
            }
        }
    }

}