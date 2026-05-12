<?php

namespace App\Service\Video;

use App\Entity\Video;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class VideoService
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    /**
     * Generate the public URL for a video.
     * If the video is private (visibility = false), the token is included as a query parameter.
     */
    public function generatePublicUrl(Video $video): string
    {
        $params = ['id' => $video->getId()];

        if (!$video->isVisibility()) {
            $params['token'] = $video->getToken();
        }

        return $this->urlGenerator->generate(
            'app_front_video_show',
            $params,
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    public function canAccessVideo(Video $video, ?string $token): bool
    {
        if ($video->isVisibility()) {
            return true;
        }

        $videoToken = $video->getToken();
        if ($videoToken === null || $token === null) {
            return false;
        }

        return hash_equals($videoToken, $token);
    }
}
