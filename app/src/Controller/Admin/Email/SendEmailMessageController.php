<?php

namespace App\Controller\Admin\Email;

use App\Messenger\Command\SendEmailMessageCommand;
use App\Repository\UserRepository;
use App\Response\ApiResponse;
use App\Service\ValidatorService;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/email/send-message", methods={"POST"})
 * @Security("is_granted('ROLE_ADMIN')")
 */
class SendEmailMessageController
{
    private $userRepository;

    private $validatorService;

    private $commandBus;

    private $translator;

    public function __construct(
        UserRepository $userRepository,
        MessageBusInterface $commandBus,
        ValidatorService $validatorService,
        TranslatorInterface $translator)
    {
        $this->userRepository = $userRepository;
        $this->commandBus = $commandBus;
        $this->validatorService = $validatorService;
        $this->translator = $translator;
    }

    public function __invoke(Request $request): ApiResponse
    {
        $params = json_decode($request->getContent(), true);
        $message = $params['message'];
        $this->validatorService->validateEmailMessage($message);

        $user = $this->userRepository->findOneBy(['email' => $message['receiverEmail']]);
        $this->validatorService->validateUserExists($user);

        $this->commandBus->dispatch(new SendEmailMessageCommand($message));

        return new ApiResponse(
            $this->translator->trans('email.message.sent'),
            $this->translator->trans('email.message.sent'),
            null,
            [],
            200
        );
    }
}