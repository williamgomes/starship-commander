<?php

use PHPUnit\Framework\TestCase;
use William\SevencooksTestTask\Sanitizers\StringSanitizer;

class StringSanitizerTest extends TestCase
{
    private StringSanitizer $numberSanitizer;
    protected function setUp(): void
    {
        $this->numberSanitizer = new StringSanitizer();
    }

    public function testSanitizeMethodReturnsTrimmedNumber()
    {
        $string = " Hello World!  ";
        $returnResult = $this->numberSanitizer->sanitize($string);
        $this->assertIsString($returnResult);
        $this->assertEquals('Hello World!', $returnResult);
    }
}