<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
class TelegramController extends Controller
{
    public function __invoke()
    {
    	$config = [
		    'telegram' => config('botman.telegram')
		];
		// Load the driver(s) you want to use
		DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);
		// Create an instance
		$botman = BotManFactory::create($config);
		// Give the bot something to listen for.
		$botman->hears('/pass', function (BotMan $bot) {
		    $bot->reply('Bạn Anh Hoàng.');
		});

		$botman->hears('/hi', function (BotMan $bot) {
		    $bot->reply('Hi cc.');
		});

		$botman->hears('{mess}', function (BotMan $bot, $mess) {
			$userInfo = $bot->getUser()->getId();
			
			$bot->reply('OK: ' . $mess);

			
			
		});
		// Start listening
		$botman->listen();
    }
}