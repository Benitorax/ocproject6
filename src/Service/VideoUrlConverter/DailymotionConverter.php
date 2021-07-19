<?php

namespace App\Service\VideoUrlConverter;

class DailymotionConverter extends AbstractConverter
{
    /**
     * {@inheritdoc}
     */
    public const SOURCE = 'dailymotion';

    /**
     * {@inheritdoc}
     */
    public const URL_REGEX = [
        self::URL_PREFIX_REGEX . 'dailymotion.com\/embed\/video\/([\w]+)',
        self::URL_PREFIX_REGEX . 'dailymotion.com\/video\/([\w]+)',
        self::URL_PREFIX_REGEX . 'dai.ly\/([\w]+)',
    ];

    /**
     * {@inheritdoc}
     */
    public const EMBED_URL_FORMAT = 'https://www.dailymotion.com/embed/video/${4}';
}
