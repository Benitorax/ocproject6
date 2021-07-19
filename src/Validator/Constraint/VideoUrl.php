<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint for video url field.
 */
class VideoUrl extends Constraint
{
    public string $message = 'Valid url: Youtube, Dailymotion and Vimeo.';
}
