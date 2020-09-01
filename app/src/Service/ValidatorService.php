<?php

namespace App\Service;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Exception\ApiException;
use App\Helper\MapPointFile;
use App\Helper\MapPointFiles;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\User;
use App\Entity\MapPoint;
use App\Repository\ReviewRepository;

class ValidatorService
{
    private $validator;

    private $translator;

    private $reviewRepository;

    public function __construct(
        ValidatorInterface $validator,
        TranslatorInterface $translator,
        ReviewRepository $reviewRepository)
    {
        $this->validator = $validator;
        $this->translator = $translator;
        $this->reviewRepository = $reviewRepository;
    }

    public function validate(Object $object): void
    {
        $violations = $this->validator->validate($object);

        if (count($violations) > 0) {
            throw new ApiException($violations[0]->getMessage(), 400);
        }
    }

    public function validateMapPointFile(MapPointFile $mapPointFile):void
    {
        $violations = $this->validator->validate($mapPointFile->getFile(), [
            new File([
                'maxSize' => 2097152,
                'maxSizeMessage' => $this->translator->trans('map_point.file.max_size'),
                'mimeTypes' => ['image/png', 'image/jpg', 'image/jpeg'],
                'mimeTypesMessage' => $this->translator->trans('map_point.file.mime_types')
            ])
        ]);

        if (0 !== count($violations)) {
            throw new ApiException($violations[0]->getMessage(), 400);
        }
    }

    public function validateMapPointFiles(MapPointFiles $mapPointFiles): void
    {
        foreach ($mapPointFiles->getFiles() as $file) {
            $this->validateMapPointFile($file);
        }
    }

    public function validateUserAlreadyPublishedReview(User $user, MapPoint $mapPoint): void
    {
        $review = $this->reviewRepository->findOneBy(['user' => $user, 'mapPoint' => $mapPoint]);

        if ($review && !$review->getIsActive())
            throw new ApiException($this->translator->trans('error.review.exists_and_inactive'), 400);

        if ($review)
            throw new ApiException($this->translator->trans('error.review.exists'), 400);
    }
}