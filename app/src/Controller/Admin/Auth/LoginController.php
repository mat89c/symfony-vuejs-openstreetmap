<?php

namespace App\Controller\Admin\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/login", name="admin_login", methods={"POST"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class LoginController extends AbstractController
{
    public function __invoke()
    {
        return new JsonResponse([
            'redirect' => '/dashboard',
        ], 200);
    }
}