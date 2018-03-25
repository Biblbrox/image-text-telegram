<?php


namespace Longman\TelegramBot\Commands\SystemCommands;

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\KeyboardButton;
use Longman\TelegramBot\Request;
use PDO;
use TextOnImage\Image\Image;
use TextOnImage\Helper\Database;
use TextOnImage\Helper\FileHelper;

class AddtextCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'addtext';
    protected $description = 'Add text to the image';
    protected $usage = '/addtext';
    protected $version = '0.1.0';
    protected $need_mysql = true;


    /**#@-*/
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $suppFormats = ['jpg', 'jpeg', 'png', 'ico', 'bmp'];
        $message = $this->getMessage();
        $chat    = $message->getChat();
        $chat_id = $chat->getId();
        $user_id = $message->getFrom()->getId();
        // Preparing Response
        $data = [
            'chat_id'      => $chat_id,
            'reply_markup' => Keyboard::remove(),
        ];
        if ($chat->isGroupChat() || $chat->isSuperGroup()) {
            // Reply to message id is applied by default
            $data['reply_to_message_id'] = $message->getMessageId();
            // Force reply is applied by default to so can work with privacy on
            $data['reply_markup'] = Keyboard::forceReply(['selective' => true]);
        }

        $stmt = Database::$connection->prepare('SELECT path FROM image WHERE user_id = ?');
        $stmt->execute([$user_id]);

        $row = $stmt->fetch(PDO::FETCH_LAZY);

        $image = new Image($row ? $row['path'] : null);

        // Start conversation
        $conversation = new Conversation($user_id, $chat_id, $this->getName());
//        $conversation->stop();
        $notes = &$conversation->notes;
        if (!isset($notes['prev_action'])) {
            $notes['prev_action'] = 'begin';
        }
        $buttons = [
            new KeyboardButton('Top'),
            new KeyboardButton('Middle'),
            new KeyboardButton('Bottom')
        ];
        $message_type = $message->getType();
        switch ($notes['prev_action']) {
            case 'begin': // Init state
                $data['text'] = 'Please upload the photo now';
                $notes['prev_action'] = 'init-message-send';
                $conversation->update();
                break;
            case 'init-message-send': // Downloading photo step.
                if ($message_type !== 'photo') {
                    $data['text'] = 'You must send photo now';
                    break;
                }
                $doc = $message->getPhoto();
                // For photos, get the best quality!
                $message_type === "photo" && $doc = end($doc);
                $file_id = $doc->getFileId();
                $file    = Request::getFile(['file_id' => $file_id]);
                if ($file->isOk() && Request::downloadFile($file->getResult())) {
                    $data['text'] = 'Ok. Type the text which you want to see on image';
                } else {
                    $data['text'] = 'Failed to download.';
                }
                $filePath = $this->telegram->getDownloadPath() . '/' . $file->getResult()->getFilePath();
                $ext = pathinfo($filePath, PATHINFO_EXTENSION);
                if (!in_array(strtolower($ext), $suppFormats)) {
                    $formats = implode(', ', $suppFormats);
                    $data['text'] = sprintf('Image must have one of these formats: %s', $formats);
                    FileHelper::deleteFile($filePath);
                    break;
                }
                $stmt = Database::$connection->prepare('DELETE FROM image WHERE user_id = ?');
                $stmt->execute([$user_id]);
                $stmt = Database::$connection->prepare('INSERT INTO image (user_id, path) VALUES (?, ?)');
                $stmt->execute([$user_id, $filePath]);
                $notes['prev_action'] = 'download-photo';
                $conversation->update();
                break;
            case 'download-photo': // Setting position step
                if ($message_type !== 'text') {
                    $data['text'] = 'You must send text now';
                    break;
                }
                $notes['text'] = $message->getText();
                $data['reply_markup'] = (new Keyboard($buttons))->setOneTimeKeyboard(true)
                                                                ->setResizeKeyboard(true)
                                                                ->setSelective(true);
                $data['text'] = 'Choose text position';
                $notes['prev_action'] = 'choose-pos';
                $conversation->update();
                break;
            case 'choose-pos': // Adding text step
                if ($message_type !== 'text') {
                    $data['text'] = 'You must send position now';
                    break;
                }
                $pos = ['top', 'middle', 'bottom'];
                if (!in_array(strtolower($message->getText()), $pos)) {
                    $data['text'] = sprintf('Wrong position. Send one of these: %s', implode(', ', $pos));
                    $data['reply_markup'] = (new Keyboard($buttons))->setOneTimeKeyboard(true)
                        ->setResizeKeyboard(true)
                        ->setSelective(true);
                    break;
                }
                $image->addText($notes['text'], trim($message->getText()));
                $stmt = Database::$connection->prepare('SELECT path FROM image WHERE user_id = ?');
                $stmt->execute([$user_id]);
                $row = $stmt->fetch(PDO::FETCH_LAZY);
                $file_path = $row['path'];
                $data['photo'] = Request::encodeFile($file_path);
                FileHelper::deleteFilesInDir($this->telegram->getDownloadPath() . '/photos/');
                $stmt = Database::$connection->prepare('DELETE FROM image WHERE user_id = ?');
                $stmt->execute([$user_id]);
                $notes['prev_action'] = 'add-text';
                $conversation->update();
                $conversation->stop();
                break;
            default:
                break;
        }

        $return = isset($data['text']) ? Request::sendMessage($data) : Request::sendPhoto($data);

        return $return;
    }
}
