<?php

use App\Commands\{Analytic,
    BadGuys,
    Barter,
    ChatRules,
    CommunityParticipationButtons,
    Contacts,
    MainMenu,
    MarketData,
    MarketList,
    Partners,
    Search};

return [
    'main_menu'      => MainMenu::class,
    'market_actions' => MarketList::class,

    'shuvar' => MarketData::class,
    'stolychnuy' => MarketData::class,
    'pochatok' => MarketData::class,
    'kopani' => MarketData::class,
    'bronisze' => MarketData::class,

    'search' => Search::class,
    'analytic' => Analytic::class,

    'community_participation' => CommunityParticipationButtons::class,
    'chat_rules' => ChatRules::class,
    'barter' => Barter::class,
    'partners' => Partners::class,
    'bad_guys' => BadGuys::class,
    'contacts' => Contacts::class,

];