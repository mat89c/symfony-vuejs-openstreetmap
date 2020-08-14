<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setName('Admin');
        $password = $this->userPasswordEncoder->encodePassword($user, 'admin1234');
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setIsActive(true);

        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setName('User');
        $password = $this->userPasswordEncoder->encodePassword($user, 'user1234');
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER']);
        $user->setIsActive(true);

        $manager->persist($user);
        $manager->flush();
    }
}
