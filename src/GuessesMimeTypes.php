<?php

declare (strict_types = 1);

namespace App;

use Symfony\Component\Mime\MimeTypes;

trait GuessesMimeTypes
{
    public function guessMimeType(string $filePath): string
    {
        return (new MimeTypes())->guessMimeType($filePath) ?: 'application/octet-stream';
    }
}