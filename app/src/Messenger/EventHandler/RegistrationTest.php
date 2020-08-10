<?php

namespace App\Messenger\EventHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\User;
use App\Messenger\Event\UserRegisteredEvent;

class RegistrationTest implements MessageHandlerInterface
{
    private $em;

    private $passwordEncoder;

    private $validator;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $passwordEncoder,
        ValidatorInterface $validator
    )
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
    }

    public function __invoke(UserRegisteredEvent $registerUserCommand)
    {
        $user = new User();
        $user->setName($registerUserCommand->getName());
        $user->setEmail($registerUserCommand->getEmail());

        $password = $this->passwordEncoder->encodePassword($user, $registerUserCommand->getPassword());
        $user->setPassword($password);

        $user->setRoles(['ROLE_USER']);

        //$errors = $this->validator->validate($user);
        //dd($errors[0]->getMessage());

        $this->em->persist($user);
        $this->em->flush();

        //throw new \Exception('ok');
    }
}



