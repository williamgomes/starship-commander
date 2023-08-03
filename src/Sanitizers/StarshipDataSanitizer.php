<?php

namespace William\SevencooksTestTask\Sanitizers;

use Psr\Log\LoggerInterface;

class StarshipDataSanitizer
{
    public function __construct(
        private readonly array           $configSanitizer,
        private readonly LoggerInterface $logger
    )
    {
    }

    public function sanitizeData($starship = array()): array
    {
        foreach ($starship as $attribute => $value) {
            if (isset($this->configSanitizer[$attribute])) {
                $sanitizerClassName = $this->configSanitizer[$attribute]['class'];
                $interfaces = class_implements($sanitizerClassName);
                if (isset($interfaces['William\SevencooksTestTask\Sanitizers\SanitizerInterface'])) {
                    $sanitizer = new $sanitizerClassName();
                    $sanitizedValue = $sanitizer->sanitize($value);
                    $this->logger->info(
                        sprintf(
                            'For %s field the value %s has been sanitized to %s.',
                            $value, $attribute, $sanitizedValue
                        ));
                    $starship[$attribute] = $sanitizedValue;
                }
            }
        }

        return $starship;
    }
}