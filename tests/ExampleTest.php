<?php

namespace Blockpoint\Vat24Api\Tests;

use Blockpoint\Vat24Api\Vat24Api;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $api = new Vat24Api('test-api-key');
        $this->assertInstanceOf(Vat24Api::class, $api);
    }
}
