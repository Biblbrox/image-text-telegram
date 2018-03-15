<?php

require_once __DIR__ . "/vendor/autoload.php";

use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;

$bot_api_key = '539623257:AAGBn9wboPc2g491yuzJHcHBGIWoljE2TJs';
$bot_username = "ImagePlusTextBot";
$hook_url = "https://imageptextbot.loc/hook.php";

try {
    $telegram = new Telegram($bot_api_key, $bot_username);

    $result = $telegram->setWebhook($hook_url, ['certificate' => '/etc/ssl/certs/server.crt']);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (TelegramException $e) {
    print_r($e->getMessage());
}