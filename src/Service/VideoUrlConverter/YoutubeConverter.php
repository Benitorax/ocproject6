<?php

namespace App\Service\VideoUrlConverter;

class YoutubeConverter extends AbstractConverter
{
    /**
     * {@inheritdoc}
     */
    public const SOURCE = 'youtube';

    /**
     * {@inheritdoc}
     */
    public const URL_REGEX = [
        self::URL_PREFIX_REGEX . 'youtube.com\/embed\/([\w]+)',
        self::URL_PREFIX_REGEX . 'youtube.com\/watch\?v=([\w]+)',
        self::URL_PREFIX_REGEX . 'youtube.com\/([\w]+)',
        self::URL_PREFIX_REGEX . 'youtu.be\/([\w]+)',
    ];

    /**
     * {@inheritdoc}
     */
    public const EMBED_URL_FORMAT = 'https://www.youtube.com/embed/${4}';
}
