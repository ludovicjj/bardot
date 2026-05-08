<?php

namespace App\Enum;

enum VideoProvider: string
{
    case YOUTUBE = 'youtube';
    case VIMEO = 'vimeo';
    case DAILYMOTION = 'dailymotion';

    public function label(): string
    {
        return match ($this) {
            self::YOUTUBE => 'YouTube',
            self::VIMEO => 'Vimeo',
            self::DAILYMOTION => 'Dailymotion',
        };
    }

    public function embedUrl(string $externalId): string
    {
        return match ($this) {
            self::YOUTUBE => 'https://www.youtube.com/embed/' . $externalId,
            self::VIMEO => 'https://player.vimeo.com/video/' . $externalId,
            self::DAILYMOTION => 'https://www.dailymotion.com/embed/video/' . $externalId,
        };
    }

    public function thumbnailUrl(string $externalId): ?string
    {
        return match ($this) {
            self::YOUTUBE => 'https://i.ytimg.com/vi/' . $externalId . '/hqdefault.jpg',
            self::DAILYMOTION => 'https://www.dailymotion.com/thumbnail/video/' . $externalId,
            self::VIMEO => null,
        };
    }
}
