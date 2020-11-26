<?php


namespace App\EventListener;

use App\Entity\Advert;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

class AdvertListener
{
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
        }
    }
}