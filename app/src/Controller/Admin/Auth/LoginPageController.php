<?php

namespace App\Controller\Admin\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin2020", name="login_page", methods={"GET"})
 */
final class LoginPageController extends AbstractController
{
    public function __invoke()
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('admin/login.html.twig');
    }
}