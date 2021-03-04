<?php

namespace App\MessageHandler\Event;

use App\Message\Event\ImagePostDeleteEvent;
use App\Photo\PhotoFileManager;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RemoveFileWhenImagePostDeleted implements MessageHandlerInterface
{
    private $photoFileManager;

    public function __construct(PhotoFileManager $photoFileManager)
    {
        $this->photoFileManager = $photoFileManager;
    }

    public function __invoke(ImagePostDeleteEvent $event)
    {
        $this->photoFileManager->deleteImage($event->getFilename());
    }
}