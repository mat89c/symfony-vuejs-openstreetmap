<?php

namespace App\Service;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Exception\ApiException;

class ValidatorService
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(Object $object): void
    {
        $errors = $this->validator->validate($object);

        if (count($errors) > 0) {
            throw new ApiException($errors[0]->getMessage(), 400);
        }
    }
}