<?php
namespace Test\AppBundle\Services;

use AppBundle\Services\UrlValidator;
use PHPUnit\Framework\TestCase;

class UrlValidatorTest extends TestCase
{
    public function testisUrlValid(){
        $urlValidator = new UrlValidator();

        $isValid = $urlValidator->isUrlValid('http://google.rs');
        $this->assertTrue($isValid);

//        $isValid = $urlValidator->isUrlValid('google.rs');
//        $this->assertTrue($isValid);
//
//        $isValid = $urlValidator->isUrlValid('www.google.rs');
//        $this->assertTrue($isValid); //TODO uncomment this

        $isValid = $urlValidator->isUrlValid('http://notvalidurl_1123.rs');
        $this->assertFalse($isValid);
    }
}