<?php

namespace App\Support;

class VideoEmbed
{
    public static function platform(?string $url): ?string
    {
        $host = strtolower((string) parse_url((string) $url, PHP_URL_HOST));
        $host = preg_replace('/^(www\.|m\.|web\.)/', '', $host);

        return match (true) {
            in_array($host, ['youtube.com', 'youtu.be'], true) => 'youtube',
            in_array($host, ['facebook.com', 'fb.watch', 'fb.com'], true) => 'facebook',
            default => null,
        };
    }

    /** URL d'intégration (iframe) construite à partir d'un lien YouTube ou Facebook. */
    public static function embedUrl(?string $url): ?string
    {
        $url = (string) $url;

        switch (self::platform($url)) {
            case 'youtube':
                if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?(?:[^#]*&)?v=|embed/|shorts/|live/|v/))([A-Za-z0-9_-]{11})~', $url, $m)) {
                    return 'https://www.youtube-nocookie.com/embed/' . $m[1];
                }
                // Lien de chaîne (/channel/UC...) : affiche automatiquement le direct en cours
                if (preg_match('~youtube\.com/channel/(UC[A-Za-z0-9_-]{22})~', $url, $m)) {
                    return 'https://www.youtube.com/embed/live_stream?channel=' . $m[1];
                }

                return null;

            case 'facebook':
                return 'https://www.facebook.com/plugins/video.php?show_text=false&href=' . rawurlencode($url);
        }

        return null;
    }
}
