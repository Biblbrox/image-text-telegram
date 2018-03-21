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

        $stmt = Database::$connection->prepare("SELECT path FROM image WHERE user_id = ?");
        $stmt->execute([$user_id]);

        $row = $stmt->fetch(PDO::FETCH_LAZY);

        $image = new Image($row ? $row['path'] : null);

        // Start conversation
        $conversation = new Conversation($user_id, $chat_id, $this->getName());
        $message_type = $message->getType();
        if ($message_type === 'photo') {
            $doc = $message->getPhoto();
            // For photos, get the best quality!
            $doc = end($doc);
            $file_id = $doc->getFileId();
            $file    = Request::getFile(['file_id' => $file_id]);
            if ($file->isOk() && Request::downloadFile($file->getResult())) {
                $data['text'] = "Ok. Type the text which you want to see on image";
            } else {
                $data['text'] = 'Failed to download.';
            }
            $stmt = Database::$connection->prepare("DELETE FROM image WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $stmt = Database::$connection->prepare("INSERT INTO image (user_id, path) VALUES (?, ?)");
            $stmt->execute([$user_id, $this->telegram->getDownloadPath() . '/' . $file->getResult()->getFilePath()]);
        } else if($message_type === "text") {
            if (!$image->imageLoaded()) {
                $data['text'] = 'Please upload the photo before setting text';
            } else {
                $image->addText($message->getText());
                $stmt = Database::$connection->prepare("SELECT path FROM image WHERE user_id = ?");
                $stmt->execute([$user_id]);
                $row = $stmt->fetch(PDO::FETCH_LAZY);
                $file_path = $row['path'];
                $data['photo'] = Request::encodeFile($file_path);
                $conversation->update();
                $conversation->stop();
                FileHelper::deleteFilesInDir($this->telegram->getDownloadPath() . '/photos/');
                $stmt = Database::$connection->prepare("DELETE FROM image WHERE user_id = ?");
                $stmt->execute([$user_id]);
            }
        } else {
            $data['text'] = 'Please upload the photo now';
        }

        $return = isset($data['text']) ? Request::sendMessage($data) : Request::sendPhoto($data);

        return $return;
    }
}
