<?php


namespace App\EventListener;

use App\Entity\Advert;
use App\Notification\AdvertPublishingNotification;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Notifier\NotifierInterface;

class AdvertListener
{
    private NotifierInterface $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if(property_exists($entity, "createdAt") && $entity instanceof Advert){
            $entity->setCreatedAt(new \DateTimeImmutable());
        }
    }

    /**
     * @param LifecycleEventArgs $args
     * @ORM\PreUpdate()
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        if($entity instanceof Advert && $entity->getState() === 'published' && $entity->getpublishedAt() === null){
            $entity->setPublishedAt(new \DateTimeImmutable());
            $notification = new AdvertPublishingNotification();
            $this->notifier->send($notification->setAdvert($entity), ...$this->notifier->getAdminRecipients());
        }
    }
}