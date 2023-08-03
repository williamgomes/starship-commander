<?php

use PHPUnit\Framework\TestCase;
use William\SevencooksTestTask\Sanitizers\NumberSanitizer;

class NumberSanitizerTest extends TestCase
{
    private NumberSanitizer $numberSanitizer;
    protected function setUp(): void
    {
        $this->numberSanitizer = new NumberSanitizer();
    }

    public function testSanitizeMethodReturnsSanitizedNumber()
    {
        $numberOne = "12-3$4%5^6&7";
        $returnNumberOne = $this->numberSanitizer->sanitize($numberOne);
        $this->assertIsInt($returnNumberOne);
        $this->assertEquals(1234567, $returnNumberOne);

        $numberTwo = "7654321";
        $returnNumberTwo = $this->numberSanitizer->sanitize($numberTwo);
        $this->assertIsInt($returnNumberTwo);
        $this->assertEquals(7654321, $returnNumberTwo);
    }
}