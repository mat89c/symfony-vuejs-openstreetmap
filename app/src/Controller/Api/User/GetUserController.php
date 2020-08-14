<?php

namespace App\Controller\Api\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Response\ApiResponse;
use App\Exception\ApiException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/api/get-user", methods={"GET"})
 */
final class GetUserController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function __invoke(): ApiResponse
    {
        if (!$this->getUser())
            throw new ApiException($this->translator->trans('user.not_logged'));

        $user = $this->getUser();

        return new ApiResponse(
            '',
            '',
            [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
                'isActive' => $user->getIsActive()
            ],
            [],
            200
        );
    }
}