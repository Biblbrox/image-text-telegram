<?php

require_once __DIR__ . "/vendor/autoload.php";

$bot_api_key = '539623257:AAGBn9wboPc2g491yuzJHcHBGIWoljE2TJs';
$bot_username = "ImagePlusTextBot";

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    $command_paths = [
        __DIR__ . '/Commands/'
    ];
    $telegram->addCommandsPaths($command_paths);
    // Handle telegram webhook request
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
    echo $e->getMessage();
}