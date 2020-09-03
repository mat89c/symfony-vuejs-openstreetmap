<?php

namespace App\Service;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Exception\ApiException;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\User;
use App\Entity\MapPoint;
use App\Repository\ReviewRepository;
use App\Entity\MapPointCategory;
use App\Entity\ReviewImage;
use App\Helper\ImageInterface;
use App\Helper\ImagesInterface;

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

    public function validateImage(ImageInterface $image):void
    {
        $violations = $this->validator->validate($image->getImage(), [
            new File([
                'maxSize' => 2097152,
                'maxSizeMessage' => $this->translator->trans('image.max_size'),
                'mimeTypes' => ['image/png', 'image/jpg', 'image/jpeg'],
                'mimeTypesMessage' => $this->translator->trans('image.mime_types')
            ])
        ]);

        if (0 !== count($violations)) {
            throw new ApiException($violations[0]->getMessage(), 400);
        }
    }

    public function validateImages(ImagesInterface $images): void
    {
        foreach ($images->getImages() as $image) {
            $this->validateImage($image);
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

    public function validateMapPointCategories(array $categories): void
    {
        if (!$categories)
            throw new ApiException($this->translator->trans('category.not_blank'), 400);

        if (count($categories) > 5)
            throw new ApiException($this->translator->trans('category.max_qty'), 400);
    }

    public function validateMapPointCategory(?MapPointCategory $mapPointCategory): void
    {
        if (!$mapPointCategory)
            throw new ApiException($this->translator->trans('category.not_found'), 400);
    }

    public function validateUserCanDeleteReviewImage(ReviewImage $reviewImage, User $user): void
    {
        if (!$reviewImage)
            throw new ApiException($this->translator->trans('review.image.not_found'), 404);

        if ($reviewImage->getReview()->getUser()->getId() !== $user->getId())
            throw new ApiException($this->translator->trans('review.image.cant_delete'), 400);
    }
}