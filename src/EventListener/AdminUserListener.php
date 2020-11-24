<?php


namespace App\EventListener;


use App\Entity\AdminUser;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserListener
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if($entity instanceof AdminUser){
            $entity->setPassword($this->passwordEncoder->encodePassword($entity, $entity->getPlainPassword()));
            $entity->setRoles(["ROLE_ADMIN"]);
        }
    }
}