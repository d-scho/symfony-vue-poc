<?php

declare(strict_types=1);

namespace App;

final  class ManifestError extends \Exception
{
    private function __construct(string $message, int $code, ?\Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public static function fileMissing(): self
    {
        return new self('Manifest file missing', 1745057425);
    }

    public static function unexpectedContent(\Throwable $previous): self
    {
        return new self('Could not deduct app.js name from manifest.json', 1745057488, $previous);
    }
}