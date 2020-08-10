<?php

namespace App\EventListener;

use App\Exception\ApiException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use App\Response\ApiResponse;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApiException) {
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
            // $response =  new JsonResponse([
            //     'error' => [
            //         'status' => $exception->getCode(),
            //         'message' => $exception->getMessage(),
            //     ],
            // ], $exception->getCode());

            $event->setResponse($response);
        }
    }
}