<?php

namespace App\Service\Video;

use App\Enum\VideoProvider;

class VideoUrlParser
{
    /**
     * @return array{provider: VideoProvider, externalId: string}|null
     */
    public function parse(string $url): ?array
    {
        $url = trim($url);

        if ($url === '') {
            return null;
        }

        $parsed = parse_url($url);
        if ($parsed === false || !isset($parsed['host'])) {
            return null;
        }

        $host = strtolower($parsed['host']);
        $path = $parsed['path'] ?? '';
        parse_str($parsed['query'] ?? '', $query);

        if ($this->matchesHost($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'music.youtube.com'])) {
            $id = null;

            if ($path === '/watch' && !empty($query['v'])) {
                $id = $query['v'];
            } elseif (preg_match('#^/(embed|shorts|v|live)/([A-Za-z0-9_-]{6,})#', $path, $m)) {
                $id = $m[2];
            }

            if ($id !== null && preg_match('/^[A-Za-z0-9_-]{6,}$/', $id) === 1) {
                return ['provider' => VideoProvider::YOUTUBE, 'externalId' => $id];
            }
        }

        if ($host === 'youtu.be' && preg_match('#^/([A-Za-z0-9_-]{6,})#', $path, $m)) {
            return ['provider' => VideoProvider::YOUTUBE, 'externalId' => $m[1]];
        }

        if ($this->matchesHost($host, ['vimeo.com', 'www.vimeo.com', 'player.vimeo.com'])) {
            if (preg_match('#/(?:video/)?(\d{6,})#', $path, $m)) {
                return ['provider' => VideoProvider::VIMEO, 'externalId' => $m[1]];
            }
        }

        if ($this->matchesHost($host, ['dailymotion.com', 'www.dailymotion.com'])) {
            if (preg_match('#^/(?:embed/)?video/([A-Za-z0-9]+)#', $path, $m)) {
                return ['provider' => VideoProvider::DAILYMOTION, 'externalId' => $m[1]];
            }
        }

        if ($host === 'dai.ly' && preg_match('#^/([A-Za-z0-9]+)#', $path, $m)) {
            return ['provider' => VideoProvider::DAILYMOTION, 'externalId' => $m[1]];
        }

        return null;
    }

    /** @param string[] $hosts */
    private function matchesHost(string $host, array $hosts): bool
    {
        return in_array($host, $hosts, true);
    }
}
