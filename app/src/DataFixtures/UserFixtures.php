<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends BaseFixtures
{
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setName('Admin');
        $password = $this->userPasswordEncoder->encodePassword($user, 'admin1234');
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setIsActive(true);
        $manager->persist($user);
        $this->addReference('App\Entity\User_0', $user);

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setName('User');
        $password = $this->userPasswordEncoder->encodePassword($user, 'user1234');
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER']);
        $user->setIsActive(true);
        $manager->persist($user);
        $this->addReference('App\Entity\User_1', $user);

        $user = new User();
        $user->setEmail('mat@example.com');
        $user->setName('Mat');
        $password = $this->userPasswordEncoder->encodePassword($user, 'mat1234');
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER']);
        $user->setIsActive(true);
        $manager->persist($user);
        $this->addReference('App\Entity\User_2', $user);

        $user = new User();
        $user->setEmail('doe@example.com');
        $user->setName('John Doe');
        $password = $this->userPasswordEncoder->encodePassword($user, 'doe1234');
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER']);
        $user->setIsActive(true);
        $manager->persist($user);
        $this->addReference('App\Entity\User_3', $user);

        $manager->flush();
    }
}
