<?php

namespace Blockpoint\Vat24Api\Tests;

use Blockpoint\Vat24Api\Responses\ValidationResponse;
use Blockpoint\Vat24Api\Vat24Api;
use PHPUnit\Framework\TestCase;

class Vat24ApiTest extends TestCase
{
    /** @test */
    public function it_can_validate_a_vat_number()
    {
        // Create a mock of the Vat24Api class
        $api = $this->getMockBuilder(Vat24Api::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validateVat'])
            ->getMock();

        // Set up the mock to return a successful response
        $mockResponse = $this->createMockResponse([
            'status' => 200,
            'message' => 'Success',
            'country_code' => 'NL',
            'vat_number' => 'NL836176320B01',
            'validation' => [
                'status' => 'OK',
                'valid' => true,
                'company_name' => 'VAT24API B.V.',
                'company_address' => 'KONINGSTRAAT 00023 2\n6820AD AMSTERDAM',
            ],
        ]);

        $api->expects($this->once())
            ->method('validateVat')
            ->with('NL836176320B01')
            ->willReturn($mockResponse);

        // Call the method
        $response = $api->validateVat('NL836176320B01');

        // Assert the response
        $this->assertTrue($response->isValid());
        $this->assertEquals('NL', $response->getCountryCode());
        $this->assertEquals('NL836176320B01', $response->getVatNumber());
        $this->assertEquals('VAT24API B.V.', $response->getCompanyName());
        $this->assertEquals('KONINGSTRAAT 00023 2\n6820AD AMSTERDAM', $response->getCompanyAddress());
    }

    /** @test */
    public function it_can_validate_an_eori_number()
    {
        // Create a mock of the Vat24Api class
        $api = $this->getMockBuilder(Vat24Api::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validateEori'])
            ->getMock();

        // Set up the mock to return a successful response
        $mockResponse = $this->createMockResponse([
            'status' => 200,
            'message' => 'Success',
            'country_code' => 'GB',
            'eori_number' => 'GB123456789000',
            'validation' => [
                'status' => 'OK',
                'valid' => true,
                'company_name' => 'Test Company Ltd',
            ],
        ]);

        $api->expects($this->once())
            ->method('validateEori')
            ->with('GB123456789000')
            ->willReturn($mockResponse);

        // Call the method
        $response = $api->validateEori('GB123456789000');

        // Assert the response
        $this->assertTrue($response->isValid());
        $this->assertEquals('GB', $response->getCountryCode());
        $this->assertEquals('GB123456789000', $response->getEoriNumber());
        $this->assertEquals('Test Company Ltd', $response->getCompanyName());
    }

    /**
     * Create a mock ValidationResponse object.
     */
    private function createMockResponse(array $data): ValidationResponse
    {
        $response = new ValidationResponse($data);

        return $response;
    }
}
