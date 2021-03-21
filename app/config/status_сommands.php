<?php

use App\Services\Status\UserStatusService;

return [
    UserStatusService::NEW => \App\Commands\MainMenu::class,
    UserStatusService::DONE => \App\Commands\MainMenu::class,
    UserStatusService::SEARCH => \App\Commands\Search::class,
];