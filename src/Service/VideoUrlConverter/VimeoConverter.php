<?php

namespace App\Service\VideoUrlConverter;

class VimeoConverter extends AbstractConverter
{
    /**
     * {@inheritdoc}
     */
    public const SOURCE = 'vimeo';

    /**
     * {@inheritdoc}
     */
    public const URL_REGEX = [
        self::URL_PREFIX_REGEX . 'player.vimeo.com\/video\/([\d]+)',
        self::URL_PREFIX_REGEX . 'vimeo.com\/([\d]+)',
    ];

    /**
     * {@inheritdoc}
     */
    public const EMBED_URL_FORMAT = 'https://player.vimeo.com/video/${4}';
}
