<?php

namespace App\Twig;

use App\Entity\Image;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('imageToDataUrl', [$this, 'imageToDataUrl']),
        ];
    }

    // public function getFunctions(): array
    // {
    //     return [
    //         new TwigFunction('function_name', [$this, 'doSomething']),
    //     ];
    // }

    /**
     * Convert an Image instance to data url.
     */
    public function imageToDataUrl(?Image $image): string
    {
        if (!$image instanceof Image) {
            return '/images/default.png';
        }

        $format = preg_replace('/image\//', '', $image->getFormat());

        return 'data:image/' . $format . ';base64,' . base64_encode((string) $image->getData());
    }
}
