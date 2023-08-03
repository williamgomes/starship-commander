<?php

namespace William\SevencooksTestTask\Sanitizers;

class StringSanitizer implements SanitizerInterface
{

    public function sanitize(mixed $value): string
    {
        return trim($value);
    }
}