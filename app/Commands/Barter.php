<?php

namespace App\Commands;

class Barter extends BaseCommand
{

    function processCommand()
    {

        $this->viber->sendMessageWithKeyboard(' "Я согласен(на) выполнять правила чата, в случае нарушения претензий не имею." Автоматическое соглашения после финансовой помощи на карту
МОНО БАНК 5375414113434418 или ПриватБанк 4149629392057246', [
            [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#D1EDF2',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'chat_rules',
                'Text' => 'Правила Чата'
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
