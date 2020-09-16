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
use App\Entity\MapPointImage;
use App\Entity\Review;
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

    public function validateMapPointCategories(?array $categories): void
    {
        if (!$categories)
            throw new ApiException($this->translator->trans('category.not_blank'), 400);

        if (count($categories) > 5)
            throw new ApiException($this->translator->trans('category.max_qty'), 400);
    }

    public function validateMapPointCategory(?MapPointCategory $mapPointCategory): void
    {
        if (!$mapPointCategory)
            throw new ApiException($this->translator->trans('category.not_found'), 404);
    }

    public function validateUserCanDeleteReviewImage(ReviewImage $reviewImage, User $user): void
    {
        if (!$reviewImage)
            throw new ApiException($this->translator->trans('review.image.not_found'), 404);

        if ($reviewImage->getReview()->getUser()->getId() !== $user->getId())
            throw new ApiException($this->translator->trans('review.image.cant_delete'), 400);
    }

    public function validateMapPoint(?MapPoint $mapPoint): void
    {
        if (!$mapPoint)
            throw new ApiException($this->translator->trans('map_point.not_found'), 404);
    }

    public function validateUserExists(?User $user): void
    {
        if (!$user)
            throw new ApiException($this->translator->trans('user.not_found'), 404);
    }

    public function validateReviewExists(?Review $review): void
    {
        if (!$review)
            throw new ApiException($this->translator->trans('review.not_found'), 404);
    }

    public function validateReviewImageExists(?ReviewImage $reviewImage): void
    {
        if (!$reviewImage)
            throw new ApiException($this->translator->trans('review.image.not_found'), 404);
    }

    public function validateMapPointImageExists(?MapPointImage $mapPointImage): void
    {
        if (!$mapPointImage)
            throw new ApiException($this->translator->trans('map_point.image.not_found'), 404);
    }

    public function validateValueExists($value): void
    {
        if (!$value)
            throw new ApiException($this->translator->trans('value.required'), 400);
    }

    public function preventChangesOnAdminAccount(User $user):void
    {
        if ($user->getEmail() === 'admin@example.com')
            throw new ApiException($this->translator->trans('user.admin_account.not_editable'), 400);
    }

    public function validateEmailMessage(array $message): void
    {
        if (empty($message['subject']))
            throw new ApiException($this->translator->trans('email.message.subject.not_blank'), 400);

        if (empty($message['receiverEmail']))
            throw new ApiException($this->translator->trans('email.message.receiver_email.not_blank'), 400);

        if (empty($message['receiverName']))
            throw new ApiException($this->translator->trans('email.message.receiver_name.not_blank'), 400);
    }
}