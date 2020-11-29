<?php


namespace App\EventListener;

use App\Entity\Advert;
use App\Notification\AdvertCreateNotification;
use App\Notification\AdvertPublishingNotification;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Notifier\NotifierInterface;

class AdvertListener
{
    private NotifierInterface $notifier;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(NotifierInterface $notifier, EntityManagerInterface $entityManager)
    {
        $this->notifier = $notifier;
        $this->entityManager = $entityManager;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if(property_exists($entity, "createdAt") && $entity instanceof Advert){
            $entity->setState("draft");
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

    public function postPersist(LifecycleEventArgs $args){
        /** @var Advert $advert */
        $advert = $args->getEntity();
        $this->notifier->send(new AdvertCreateNotification($advert,$this->entityManager),...$this->notifier->getAdminRecipients());
    }
}