<?php
/**
 * README
 * This configuration file is intended to be used as the main script for the PHP Telegram Bot Manager.
 * Uncommented parameters must be filled
 *
 * For the full list of options, go to:
 * https://github.com/php-telegram-bot/telegram-bot-manager#set-extra-bot-parameters
 */
// Load composer

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/functions.php';

use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\TelegramLog;
use TelegramBot\TelegramBotManager\BotManager;
use TelegramBot\TelegramBotManager\Exception\InvalidAccessException;
use TelegramBot\TelegramBotManager\Exception\InvalidActionException;
use TelegramBot\TelegramBotManager\Exception\InvalidParamsException;
use TelegramBot\TelegramBotManager\Exception\InvalidWebhookException;
use TextOnImage\Text\TextConfig;
use TextOnImage\Helper\AliasHelper;
use TextOnImage\Helper\Database;

// Enable error reporting
error_reporting(E_ALL);

Database::initialize();
$fontConfig = TextConfig::getInstance();
$fontConfig->setFont(AliasHelper::getPath("@res/LiberationSans-Bold.ttf"));
$fontConfig->setFontSize(25);
$fontConfig->setLineSpacing(40);

// Add you bot's username (also to be used for log file names)
$bot_username = 'ImagePlusTextBot';
try {
    $bot = new BotManager([
        'api_key'      => '539623257:AAGBn9wboPc2g491yuzJHcHBGIWoljE2TJs',
        'bot_username' => $bot_username,
        'secret'       => 'super_secret',
        'webhook'      => [
            'certificate' => '/etc/ssl/certs/server.crt',
            'max_connections' => 5,
        ],
        'commands' => [
            // Define all paths for your custom commands
            'paths'   => [
                '/home/staralex/textImageGenerator/Commands/',
            ],
        ],
        'mysql' => [
            'host'     => 'localhost',
            'user'     => 'root',
            'password' => '82348234',
            'database' => 'text_over_image_bot',
        ],
        // Logging (Error, Debug and Raw Updates)
        'logging'  => [
            'debug'  => __DIR__ . "/{$bot_username}_debug.log",
            'error'  => __DIR__ . "/{$bot_username}_error.log",
            'update' => __DIR__ . "/{$bot_username}_update.log",
        ],
        // Set custom Upload and Download paths
        'paths'    => [
            'download' => __DIR__ . '/Download',
            'upload'   => __DIR__ . '/Upload',
        ],
        // Requests Limiter (tries to prevent reaching Telegram API limits)
        'limiter'      => ['enabled' => true],
    ]);
    // Run the bot!
    $bot->run();
} catch (TelegramException $e) {
    echo $e;
    TelegramLog::error($e);
} catch (InvalidActionException $e) {
    TelegramLog::error($e);
} catch (InvalidParamsException $e) {
    TelegramLog::error($e);
} catch (InvalidAccessException $e) {
    TelegramLog::error($e);
} catch (InvalidWebhookException $e) {
    TelegramLog::error($e);
} catch (Exception $e) {
    TelegramLog::error($e);
}