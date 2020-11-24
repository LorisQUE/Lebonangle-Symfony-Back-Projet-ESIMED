<?php


namespace App\EventListener;

use App\Entity\Advert;
use Doctrine\ORM\Event\LifecycleEventArgs;

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
}