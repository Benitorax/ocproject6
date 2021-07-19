<?php

namespace App\Service\VideoUrlConverter;

use App\Service\VideoUrlConverter\AbstractConverter;

class VideoUrlConverter
{
    /**
     * List of VideoUrlConverters.
     */
    private iterable $urlConverters;

    public function __construct(iterable $urlConverters)
    {
        $this->urlConverters = $urlConverters;
    }

    /**
     * Check whether a converter supports the given string.
     */
    public function supports(string $tagOrUrl): bool
    {
        if ($this->getConverter($tagOrUrl)) {
            return true;
        }

        return false;
    }

    /**
     * Convert url to array: [$source, $embedUrl].
     */
    public function convert(string $tagOrUrl): array
    {
        if ($converter = $this->getConverter($tagOrUrl)) {
            return $converter->process($tagOrUrl);
        }

        return [];
    }

    /**
     * @return null|AbstractConverter
     */
    public function getConverter(string $tagOrUrl)
    {
        foreach ($this->urlConverters as $converter) {
            if ($converter->supports($tagOrUrl)) {
                return $converter;
            }
        }

        return null;
    }
}
