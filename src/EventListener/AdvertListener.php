<?php


namespace App\EventListener;

use App\Entity\Advert;
use App\Entity\Picture;
use App\Notification\AdvertCreateNotification;
use App\Notification\AdvertPublishingNotification;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Notifier\NotifierInterface;

class AdvertListener
{
    private NotifierInterface $notifier;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(NotifierInterface $notifier, EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->notifier = $notifier;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
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
        if($advert instanceof Advert) {


        $arrayPics = json_decode($this->requestStack->getCurrentRequest()->getContent())->arrayPic;

        dump($arrayPics);

        foreach ($arrayPics as $pic) {
            preg_match("|\d+|", $pic, $id);
            $picture = $this->entityManager->getRepository(Picture::class)->find($id[0]);
            $picture->setAdvert($advert);
            $this->entityManager->persist($picture);
        }
            $this->entityManager->flush();




            $this->notifier->send(new AdvertCreateNotification($advert,$this->entityManager),...$this->notifier->getAdminRecipients());
        }
    }
}