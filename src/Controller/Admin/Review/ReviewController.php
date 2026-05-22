<?php

namespace App\Controller\Admin\Review;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/review', name: 'app_admin_review_')]
class ReviewController extends AbstractController
{
    private const array ALLOWED_FILTERS = [
        Review::STATUS_PENDING,
        Review::STATUS_APPROVED,
        Review::STATUS_HIDDEN,
        'all',
    ];

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request, ReviewRepository $reviewRepository): Response
    {
        $status = $request->query->get('status', Review::STATUS_PENDING);
        if (!in_array($status, self::ALLOWED_FILTERS, true)) {
            $status = Review::STATUS_PENDING;
        }

        $reviews = $reviewRepository->findByStatus($status === 'all' ? null : $status);

        $counts = [
            'pending' => $reviewRepository->countByStatus(Review::STATUS_PENDING),
            'approved' => $reviewRepository->countByStatus(Review::STATUS_APPROVED),
            'hidden' => $reviewRepository->countByStatus(Review::STATUS_HIDDEN),
        ];
        $counts['all'] = $counts['pending'] + $counts['approved'] + $counts['hidden'];

        return $this->render('admin/review/index.html.twig', [
            'reviews' => $reviews,
            'currentStatus' => $status,
            'counts' => $counts,
        ]);
    }

    #[Route('/{id}/approve', name: 'approve', methods: ['POST'])]
    public function approve(
        Request $request,
        Review $review,
        EntityManagerInterface $entityManager,
    ): Response {
        if ($this->isCsrfTokenValid('approve' . $review->getId(), $request->request->get('_token'))) {
            $review->setStatus(Review::STATUS_APPROVED);
            $entityManager->flush();
            $this->addFlash('success', 'Avis validé.');
        }

        return $this->redirectToRoute('app_admin_review_index', ['status' => $this->resolveStatusParam($request)]);
    }

    #[Route('/{id}/hide', name: 'hide', methods: ['POST'])]
    public function hide(
        Request $request,
        Review $review,
        EntityManagerInterface $entityManager,
    ): Response {
        if ($this->isCsrfTokenValid('hide' . $review->getId(), $request->request->get('_token'))) {
            $review->setStatus(Review::STATUS_HIDDEN);
            $entityManager->flush();
            $this->addFlash('success', 'Avis masqué.');
        }

        return $this->redirectToRoute('app_admin_review_index', ['status' => $this->resolveStatusParam($request)]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Review $review,
        EntityManagerInterface $entityManager,
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $review->getId(), $request->request->get('_token'))) {
            $entityManager->remove($review);
            $entityManager->flush();
            $this->addFlash('success', 'Avis supprimé.');
        }

        return $this->redirectToRoute('app_admin_review_index', ['status' => $this->resolveStatusParam($request)]);
    }

    private function resolveStatusParam(Request $request): string
    {
        $status = $request->request->get('status', Review::STATUS_PENDING);

        return in_array($status, self::ALLOWED_FILTERS, true) ? $status : Review::STATUS_PENDING;
    }
}
