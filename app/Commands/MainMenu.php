<?php

namespace App\Commands;

use App\Services\Status\UserStatusService;

class MainMenu extends BaseCommand
{

    function processCommand()
    {
        $this->user->status = UserStatusService::DONE;
        $this->user->save();

        $this->viber->sendMessageWithKeyboard('Главное меню', [
            [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#D1EDF2',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'market_actions',
                'Text' => 'Цены оптовых рынков'
            ], [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#D1EDF2',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'community_participation',
                'Text' => 'Участие в Сообществе'
            ], [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#D1EDF2',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'partners',
                'Text' => 'Наши партнеры'
            ], [
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#D1EDF2',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'bad_guys',
                'Text' => 'Недобросовестные участники'
            ],[
                'Columns' => 6,
                'Rows' => 1,
                'ActionType' => 'reply',
                'BgColor' => '#D1EDF2',
                'TextOpacity' => 60,
                'TextSize' => 'large',
                'ActionBody' => 'contacts',
                'Text' => 'Контакты'
            ],
        ]);
    }
}
