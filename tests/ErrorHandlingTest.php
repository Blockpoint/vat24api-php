<?php

namespace Blockpoint\Vat24Api\Tests;

use Blockpoint\Vat24Api\Responses\ValidationResponse;
use Blockpoint\Vat24Api\Vat24Api;
use PHPUnit\Framework\TestCase;

class ErrorHandlingTest extends TestCase
{
    /** @test */
    public function it_handles_api_errors()
    {
        // Create a mock of the Vat24Api class
        $api = $this->getMockBuilder(Vat24Api::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validateVat'])
            ->getMock();

        // Set up the mock to return an error response
        $mockResponse = $this->createMockResponse([
            'status' => 400,
            'message' => 'Invalid VAT number format',
        ]);

        $api->expects($this->once())
            ->method('validateVat')
            ->with('XX', 'INVALID')
            ->willReturn($mockResponse);

        // Call the method
        $response = $api->validateVat('XX', 'INVALID');

        // Assert the response
        $this->assertTrue($response->hasError());
        $this->assertFalse($response->isValid());
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('Invalid VAT number format', $response->getErrorMessage());
    }

    /** @test */
    public function it_handles_validation_failures()
    {
        // Create a mock of the Vat24Api class
        $api = $this->getMockBuilder(Vat24Api::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validateVat'])
            ->getMock();

        // Set up the mock to return a validation failure response
        $mockResponse = $this->createMockResponse([
            'status' => 200,
            'message' => 'Success',
            'country_code' => 'NL',
            'vat_number' => 'NL123456789B01',
            'validation' => [
                'status' => 'INVALID',
                'valid' => false,
                'fault_string' => 'VAT number does not exist',
            ],
        ]);

        $api->expects($this->once())
            ->method('validateVat')
            ->with('NL', '123456789B01')
            ->willReturn($mockResponse);

        // Call the method
        $response = $api->validateVat('NL', '123456789B01');

        // Assert the response
        $this->assertFalse($response->hasError()); // Not an API error
        $this->assertFalse($response->isValid()); // But validation failed
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('VAT number does not exist', $response->getFaultString());
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
