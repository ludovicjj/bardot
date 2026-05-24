<?php

namespace App\Controller\Front;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactoryInterface;
use Symfony\Component\Routing\Attribute\Route;

class ReviewController extends AbstractController
{
    #[Route('/avis', name: 'app_front_review_index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        ReviewRepository $reviewRepository,
        EntityManagerInterface $entityManager,
        RateLimiterFactoryInterface $reviewSubmitLimiter,
    ): Response {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Honeypot: bots fill all inputs; silently fake success so they don't retry
            if (!empty($form->get('website')->getData())) {
                $this->addFlash('success', 'reviews.flash_success');

                return $this->redirectToRoute('app_front_review_index');
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $clientIp = $request->getClientIp() ?? 'unknown';
            $limit = $reviewSubmitLimiter->create($clientIp)->consume();

            if (!$limit->isAccepted()) {
                $this->addFlash('error', 'reviews.flash_rate_limited');

                return $this->redirectToRoute('app_front_review_index');
            }

            $review->setIp($clientIp);
            $entityManager->persist($review);
            $entityManager->flush();

            $this->addFlash('success', 'reviews.flash_success');

            return $this->redirectToRoute('app_front_review_index');
        }

        return $this->render('front/review/index.html.twig', [
            'reviews' => $reviewRepository->findApproved(),
            'form' => $form,
        ]);
    }
}
