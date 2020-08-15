<?php

namespace App\EventListener;

use App\Exception\ApiException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use App\Response\ApiResponse;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApiException || $exception instanceof HandlerFailedException) {
            $response = new ApiResponse(
                '',
                '',
                null,
                [
                    'status' => $exception->getCode(),
                    'message' => $exception->getMessage()
                ],
                $exception->getCode(),
            );

            $event->setResponse($response);
        }
    }
}