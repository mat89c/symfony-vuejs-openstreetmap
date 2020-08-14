<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User as AppUser;
use App\Exception\ApiException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser)
        return;

        if (!$user->getIsActive()) {
            throw new ApiException('User not activated', 403);
        }
    }
}