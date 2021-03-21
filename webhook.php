<?php

use App\Models\User;
use App\ViberHelpers\ViberParser;
require_once(__DIR__ . '/bootstrap.php');

$update = \json_decode(file_get_contents('php://input'), TRUE);
$viber_parser = new ViberParser($update);

if ($viber_parser::getChatId()) {
    $handlers = include(__DIR__ . '/app/config/keyboard_commands.php');
    $user = User::where('chat_id', $viber_parser::getChatId())->first();
    if (!$user) {
        $user = User::create([
            'chat_id' => $viber_parser::getChatId(),
            'user_name' => $viber_parser::getUserName(),
            'status' => \App\Services\Status\UserStatusService::NEW
        ]);
    }

    if ($handlers[$viber_parser::getMessage()]) {
        (new $handlers[$viber_parser::getMessage()]($update))->handle();
    } else {
        $handlers = include(__DIR__ . '/app/config/status_сommands.php');
        if ($user && $handlers[$user->status]) {
            (new $handlers[$user->status]($update))->handle();
        }
    }
}