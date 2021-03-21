<?php

namespace App\Commands;

class Analytic extends BaseCommand
{

    function processCommand()
    {

        $this->viber->sendMessageWithKeyboard('В разработке, недостаточно финансирования', [
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
