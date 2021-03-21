<?php

namespace App\Commands;

class BadGuys extends BaseCommand
{

    function processCommand()
    {

        $this->viber->sendMessageWithKeyboard('Если уж наломал дров, то всегда найдутся те, кто раздуют из этого огонь.', [
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
