<?php

namespace App\Twig;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AdminBadgeExtension extends AbstractExtension
{
    private ?int $pendingReviewCount = null;

    public function __construct(private readonly ReviewRepository $reviewRepository) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pending_review_count', fn (): int => $this->getPendingReviewCount()),
        ];
    }

    private function getPendingReviewCount(): int
    {
        if ($this->pendingReviewCount === null) {
            $this->pendingReviewCount = $this->reviewRepository->countByStatus(Review::STATUS_PENDING);
        }

        return $this->pendingReviewCount;
    }
}
