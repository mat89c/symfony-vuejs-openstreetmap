<?php

namespace App\Controller\Admin\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/logout", name="logout", methods={"GET"})
 */
final class LogoutController extends AbstractController
{
    public function __invoke()
    {
        throw new \Exception('This method can be blank');
    }
}