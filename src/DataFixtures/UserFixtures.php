<?php

namespace App\DataFixtures;

use App\Entity\AdminUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new AdminUser();
        $admin->setEmail("admin@gmail.com");
        $admin->setUsername("Admin");
        $admin->setPassword($this->passwordEncoder->encodePassword($user, '123'));
        $admin->setRoles( ["ROLE_ADMIN" ]);
        $manager->persist($admin);

        $manager->flush();
    }
}
