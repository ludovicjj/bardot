<?php

namespace App\Service;

use App\Enum\VideoProvider;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class VideoThumbnailResolver
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface     $logger,
    ) {
    }

    public function resolve(VideoProvider $provider, string $externalId): ?string
    {
        if ($provider === VideoProvider::VIMEO) {
            return $this->resolveVimeo($externalId);
        }

        // delegate to the enum for YouTube and Dailymotion (deterministic URL)
        return $provider->thumbnailUrl($externalId);
    }

    private function resolveVimeo(string $externalId): ?string
    {
        $oembedUrl = 'https://vimeo.com/api/oembed.json?url=' . urlencode('https://vimeo.com/' . $externalId) . '&width=1280';

        try {
            $response = $this->httpClient->request('GET', $oembedUrl, [
                'timeout' => 5,
            ]);

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            $data = $response->toArray();

            return is_string($data['thumbnail_url'] ?? null) ? $data['thumbnail_url'] : null;
        } catch (ExceptionInterface $e) {
            $this->logger->warning('Vimeo oEmbed thumbnail fetch failed', [
                'externalId' => $externalId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
