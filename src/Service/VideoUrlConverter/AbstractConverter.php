<?php

namespace App\Service\VideoUrlConverter;

abstract class AbstractConverter
{
    /**
     * Platform source of the url.
     */
    public const SOURCE = '';

    /**
     * Prefix of url (HTTP protocol and subdomain).
     */
    public const URL_PREFIX_REGEX = '((https?:\/\/)?(www\.)?)';

    /**
     * List of url regex.
     */
    public const URL_REGEX = [];

    /**
     * embed url format.
     */
    public const EMBED_URL_FORMAT = '';

    /**
     * Return whether this class supports the given string.
     */
    public function supports(string $tagOrUrl): bool
    {
        $regex = implode('|', static::URL_REGEX);
        if (preg_match('#(' . $regex . ')#', $tagOrUrl, $matches)) {
            return true;
        }

        return false;
    }

    /**
     * Process the given string.
     */
    public function process(string $tagOrUrl): array
    {
        $embedUrl = '';
        foreach (static::URL_REGEX as $regex) {
            // fetch the url
            if (preg_match('#(' . $regex . ')#', $tagOrUrl, $matches)) {
                // change the format of the fetched url
                $embedUrl = preg_replace('#' . $regex . '#', static::EMBED_URL_FORMAT, $matches[0]);
                break;
            }
        }

        return [static::SOURCE, $embedUrl];
    }
}
