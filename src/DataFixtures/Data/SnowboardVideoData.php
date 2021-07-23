<?php

namespace App\DataFixtures\Data;

class SnowboardVideoData
{
    public static array $data = [
        ['youtube', 'https://www.youtube.com/embed/AnI7qGQs0Ic'],
        ['youtube', 'https://www.youtube.com/embed/LPxoK1ej7r0'],
        ['youtube', 'https://www.youtube.com/embed/aAzP3wNT220'],
        ['youtube', 'https://www.youtube.com/embed/38hMZ_Rreo'],
        ['youtube', 'https://www.youtube.com/embed/VL9pPKsVB_4'],
        ['youtube', 'https://www.youtube.com/embed/8AjS0rIqzJw'],
        ['youtube', 'https://www.youtube.com/embed/llNHSb_prZs'],
        ['youtube', 'https://www.youtube.com/embed/7Ux66bkH3zg'],
        ['dailymotion', 'https://www.dailymotion.com/embed/video/x7s1x5d'],
        ['dailymotion', 'https://www.dailymotion.com/embed/video/x5tvprm'],
        ['dailymotion', 'https://www.dailymotion.com/embed/video/x63ites'],
        ['dailymotion', 'https://www.dailymotion.com/embed/video/x1z5s2s'],
        ['vimeo', 'https://player.vimeo.com/video/46550353'],
    ];

    /**
     * Return a random element from $data property.
     */
    public static function getRandomData(): array
    {
        return self::$data[array_rand(self::$data)];
    }
}
