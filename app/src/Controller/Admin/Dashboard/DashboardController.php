<?php

namespace App\Controller\Admin\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/dashboard", name="dashboard", methods={"GET"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class DashboardController extends AbstractController
{
    public function __invoke()
    {
        return $this->render('admin/dashboard.html.twig');
    }
}