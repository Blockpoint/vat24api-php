<?php

namespace Blockpoint\Vat24Api;

use Blockpoint\Vat24Api\Exceptions\Vat24ApiException;
use Blockpoint\Vat24Api\Responses\ValidationResponse;

class Vat24ApiClass
{
    private string $apiKey;

    private string $baseUrl = 'https://api.vat24api.com/v1';

    private array $options = [];

    /**
     * Create a new Vat24Api instance.
     */
    public function __construct(string $apiKey, array $options = [])
    {
        $this->apiKey = $apiKey;
        $this->options = $options;

        if (isset($options['baseUrl'])) {
            $this->baseUrl = $options['baseUrl'];
        }
    }

    /**
     * Validate a VAT number.
     *
     * @param  string  $vatNumber  The VAT number to validate (with or without country code)
     * @param  string|null  $requesterVatNumber  Optional requester VAT number for mutual validation
     *
     * @throws Vat24ApiException
     */
    public function validateVat(string $vatNumber, ?string $requesterVatNumber = null): ValidationResponse
    {
        $params = [
            'vat_number' => $vatNumber,
        ];

        if ($requesterVatNumber) {
            $params['requester_vat_number'] = $requesterVatNumber;
        }

        $response = $this->makeRequest('GET', '/validate/vat', $params);

        return new ValidationResponse($response);
    }

    /**
     * Validate an EORI number.
     *
     * @param  string  $eoriNumber  The EORI number to validate (with or without country code)
     *
     * @throws Vat24ApiException
     */
    public function validateEori(string $eoriNumber): ValidationResponse
    {
        $params = [
            'eori_number' => $eoriNumber,
        ];

        $response = $this->makeRequest('GET', '/validate/eori', $params);

        return new ValidationResponse($response);
    }

    /**
     * Make an API request.
     *
     * @throws Vat24ApiException
     */
    private function makeRequest(string $method, string $endpoint, array $params = []): array
    {
        $url = $this->baseUrl.$endpoint;

        $options = [
            'http' => [
                'method' => $method,
                'header' => [
                    'Authorization: Bearer '.$this->apiKey,
                    'Content-Type: application/json',
                    'Accept: application/json',
                ],
            ],
        ];

        if ($method === 'GET' && ! empty($params)) {
            $url .= '?'.http_build_query($params);
        } elseif (! empty($params)) {
            $options['http']['content'] = json_encode($params);
        }

        $context = stream_context_create($options);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            throw new Vat24ApiException('Failed to connect to Vat24Api');
        }

        $responseData = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Vat24ApiException('Invalid JSON response from Vat24Api');
        }

        if (isset($responseData['status']) && $responseData['status'] >= 400) {
            throw new Vat24ApiException(
                $responseData['message'] ?? 'Unknown error',
                $responseData['status'] ?? 500
            );
        }

        return $responseData;
    }
}
