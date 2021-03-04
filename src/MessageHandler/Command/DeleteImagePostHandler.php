<?php


namespace App\MessageHandler\Command;


use App\Message\Command\DeleteImagePost;
use App\Message\Event\ImagePostDeleteEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteImagePostHandler implements MessageSubscriberInterface
{
    /** @var MessageBusInterface */
    private $eventBus;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        MessageBusInterface $eventBus
    )
    {
        $this->entityManager = $entityManager;
        $this->eventBus = $eventBus;
    }

    public function __invoke($deleteImagePost)
    {
        $imagePost = $deleteImagePost->getImagePost();
        $filename = $imagePost->getFilename();

        $this->entityManager->remove($imagePost);
        $this->entityManager->flush();

        $this->eventBus->dispatch(new ImagePostDeleteEvent($filename));
    }

    public static function getHandledMessages(): iterable
    {
        yield DeleteImagePost::class =>[
            'method' => '__invoke',
            // priority vs other handlers once message is handled
            // but unless you use priority transports... the message
            // will still be handled in the order it was received
            'priority' => 10,
            // unnecessary: useful if a message has multiple handlers
            // and you want to "send" each handler to a separate transport
//            'from_transport' => 'async'
        ];


    }
}