<?php

namespace App\Commands;

class Partners extends BaseCommand
{

    function processCommand()
    {

        $this->viber->sendMessageWithKeyboard('Наши партнеры', [
            [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'open-url',
                'BgColor' => '#D1EDF2',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'https://info.shuvar.com',
                'Text' => 'SHUVAR info'
            ], [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'open-url',
                'BgColor' => '#D1EDF2',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'http://zorya.plus',
                'Text' => 'ZORYA-PLUS'
            ], [
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
