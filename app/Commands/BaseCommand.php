<?php

namespace App\Commands;

use App\Models\User;
use App\ViberHelpers\ViberApi;
use App\ViberHelpers\ViberParser;

/**
 * Class BaseCommand
 * @package App\Commands
 */
abstract class BaseCommand
{

    /**
     * @var User
     */
    protected $user;

    protected $update;
    /**
     * @var ViberParser
     */
    protected $parser;

    /**
     * @var ViberApi
     */
    protected $viber;

    public function __construct($update)
    {
        $this->update = $update;
        $this->parser = new ViberParser($update);
        $this->viber = new ViberApi();
        $this->viber->chat_id = $this->parser::getChatId();
        $this->user = User::where('chat_id', $this->parser::getChatId())->first();
    }

    function handle()
    {
        $this->processCommand();
    }

    /**
     * @param $class
     */
    function triggerCommand($class)
    {
        (new $class($this->update))->handle();
    }

    abstract function processCommand();

}