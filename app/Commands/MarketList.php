<?php

namespace App\Commands;

class MarketList extends BaseCommand
{

    function processCommand()
    {
        $this->viber->sendMessageWithKeyboard('🛍️Цены оптовых рынков', [
            [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#D1EDF2',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'shuvar',
                'Text' => '🛍Список рынков'
            ], [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#D1EDF2',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'search',
                'Text' => '🔍Поиск по словам'
            ], [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#D1EDF2',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'analytic',
                'Text' => '📊Аналитика'
            ], [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#D1EDF2',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'main_menu',
                'Text' => '🛍Главное меню'
            ]
        ]);
    }

}