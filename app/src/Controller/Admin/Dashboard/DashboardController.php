<?php

namespace App\Controller\Admin\Dashboard;

use App\MessageBus\QueryBus;
use App\Messenger\Query\GetLoggedUserQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/dashboard", name="dashboard", methods={"GET"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
final class DashboardController extends AbstractController
{
    private $queryBus;

    public function __construct(QueryBus $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function __invoke()
    {
        $user = $this->queryBus->query(new GetLoggedUserQuery($this->getUser()));

        return $this->render('admin/dashboard.html.twig', [
            'user' => $user
        ]);
    }
}