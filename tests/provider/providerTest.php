<?php namespace tests\provider;

use bulksms\exception\BulkSmsException;
use bulksms\provider\Providers;
use bulksms\provider\wrapper\africatalking\AfricaTalking;
use PHPUnit\Framework\TestCase;

final class ProviderTest extends TestCase
{
    public function testAddInvalidProvider() {
        Providers::addProvider((object)array("Invalid"));

        $this->expectException(BulkSmsException::class);
        $this->expectExceptionMessage('invalid provider implementation');
    }

    public function testAddValidProvider() {
        $at = new AfricaTalking();
        Providers::addProvider($at);

        $this->assertEquals(Providers::getProvider($at->name()), $at);
    }
}