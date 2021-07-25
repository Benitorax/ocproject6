<?php

namespace App\Tests\Service\VideoUrlConverter;

use App\Service\VideoUrlConverter\VimeoConverter;
use App\Service\VideoUrlConverter\YoutubeConverter;
use App\Service\VideoUrlConverter\VideoUrlConverter;
use App\Service\VideoUrlConverter\DailymotionConverter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VideoUrlConverterTest extends KernelTestCase
{
    private VideoUrlConverter $videoUrlConverter;

    public function setUp(): void
    {
        $this->videoUrlConverter = static::getContainer()->get(VideoUrlConverter::class);
    }

    public function testSupports(): void
    {
        $value = $this->videoUrlConverter->supports('https://www.youtube.com/watch?v=azerty');
        $this->assertTrue($value);

        $value = $this->videoUrlConverter->supports('https://vimeo.com/123456');
        $this->assertTrue($value);

        $value = $this->videoUrlConverter->supports('https://vimeo.com/azerty');
        $this->assertFalse($value);

        $value = $this->videoUrlConverter->supports('https://www.dailymotion.com/video/azerty');
        $this->assertTrue($value);

        $value = $this->videoUrlConverter->supports('https://www.google.com/search?q=azerty');
        $this->assertFalse($value);
    }

    public function testConvert(): void
    {
        $value = $this->videoUrlConverter->convert('https://www.youtube.com/watch?v=azerty');
        $this->assertSame('youtube', $value['source']);

        $value = $this->videoUrlConverter->convert('https://vimeo.com/123456');
        $this->assertSame('vimeo', $value['source']);

        $value = $this->videoUrlConverter->convert('https://vimeo.com/azerty');
        $this->assertNull($value);

        $value = $this->videoUrlConverter->convert('https://www.dailymotion.com/video/azerty');
        $this->assertSame('dailymotion', $value['source']);

        $value = $this->videoUrlConverter->convert('https://www.google.com/search?q=azerty');
        $this->assertNull($value);
    }
}
