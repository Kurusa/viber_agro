<?php

namespace App;

define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT']);
require_once(SITE_ROOT . '/vendor/autoload.php');

use App\Commands\MainMenu;
use App\Commands\Start;
use PHPtricks\Orm\Database;

class WebhookController {

    public function run()
    {
        $db = Database::connect();
        $update = \json_decode(file_get_contents('php://input'), TRUE);
        $chatId = $update['sender']['id'];
        $userData = $db->table('userList')->where('viberChatId', $chatId)->select()->results();
        $unknownCommand = true;

        if (!$userData[0]) {
            $unknownCommand = false;
            (new Start())->handle($update);
        } else {
            $handlers = include('config/keyboard_commands.php');
            if ($handlers[$update['message']['text']]) {
                $unknownCommand = false;
                (new $handlers[$update['message']['text']]($update))->handle($update);
            } else {
                $handlers = include('config/mode_commands.php');
                if ($handlers[$userData[0]['mode']]) {
                    $unknownCommand = false;
                    (new $handlers[$userData[0]['mode']]($update))->handle($update);
                }
            }
        }

        if ($unknownCommand && $update['message']['text'] !== 'http://www.3dcenter.com.ua/adresa') {
            (new MainMenu())->handle($update);
        }
    }

}
