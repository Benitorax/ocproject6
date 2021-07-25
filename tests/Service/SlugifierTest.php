<?php

namespace App\Tests\Service;

use App\Service\Slugifier;
use PHPUnit\Framework\TestCase;

class SlugifierTest extends TestCase
{
    private Slugifier $slugifier;
    private const ACCENTS = [
        'à' => 'a',
        'é' => 'e',
        'è' => 'e',
        'ù' => 'u',
        'ã' => 'a',
        'û' => 'u',
        'ô' => 'o',
    ];

    private const SENTENCES = [
        'This is a test' => 'this-is-a-test',
        'We\'re used to PHPUnit' => 'we-re-used-to-phpunit',
        'Symfony is a good framework!' => 'symfony-is-a-good-framework',
        'can_you_read_it?' => 'canyoureadit',
        'it\'s ok' => 'it-s-ok',
        'Un après-midi oublié' => 'un-apres-midi-oublie'
    ];

    public function setUp(): void
    {
        $this->slugifier = new Slugifier();
    }

    public function testRemoveAccent(): void
    {
        foreach (self::ACCENTS as $key => $value) {
            $converted = $this->slugifier->removeAccent($key);
            $this->assertEquals($value, $converted);
        }
    }

    public function testSlugify(): void
    {
        foreach (self::SENTENCES as $key => $value) {
            $converted = $this->slugifier->slugify($key);
            $this->assertEquals($value, $converted);
        }
    }
}
