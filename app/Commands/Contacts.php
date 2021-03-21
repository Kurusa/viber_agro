<?php

namespace App\Commands;

class Contacts extends BaseCommand
{

    function processCommand()
    {

        $this->viber->sendMessageWithKeyboard('Связаться с Супер-Администратором
+380990234564 Дмитрий Козак', [
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
