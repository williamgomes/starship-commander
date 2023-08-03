<?php

namespace William\SevencooksTestTask\Sanitizers;

class NumberSanitizer implements SanitizerInterface
{

    public function sanitize(mixed $value): int
    {
        return (int) preg_replace('/[^0-9]/', '', $value);
    }
}