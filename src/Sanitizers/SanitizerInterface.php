<?php

namespace William\SevencooksTestTask\Sanitizers;

interface SanitizerInterface
{
    public function sanitize(string $value): string|int;
}