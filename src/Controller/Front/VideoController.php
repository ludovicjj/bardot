<?php

namespace App\Controller\Front;

use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/videos', name: 'app_front_video_')]
class VideoController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(VideoRepository $videoRepository): Response
    {
        return $this->render('front/video/index.html.twig', [
            'videos' => $videoRepository->findActive(),
        ]);
    }
}
