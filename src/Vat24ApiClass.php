<?php

namespace Blockpoint\Vat24Api;

use Blockpoint\Vat24Api\Exceptions\Vat24ApiException;
use Blockpoint\Vat24Api\Responses\ValidationResponse;

class Vat24ApiClass
{
    private string $apiKey;

    private string $baseUrl = 'https://vat24api.com/api';

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
     * @param  string  $countryCode  The country code (e.g. 'NL', 'DE', 'GB')
     * @param  string  $vatNumber  The VAT number to validate (without country code)
     * @param  string|null  $requesterCountryCode  Optional requester country code
     * @param  string|null  $requesterVatNumber  Optional requester VAT number for mutual validation
     *
     * @throws Vat24ApiException
     */
    public function validateVat(string $countryCode, string $vatNumber, ?string $requesterCountryCode = null, ?string $requesterVatNumber = null): ValidationResponse
    {
        $params = [
            'country_code' => $countryCode,
            'vat_number' => $vatNumber,
        ];

        if ($requesterVatNumber && $requesterCountryCode) {
            $params['requester_country_code'] = $requesterCountryCode;
            $params['requester_vat_number'] = $requesterVatNumber;
        }

        $response = $this->makeRequest('POST', '/vat/validate', $params);

        return new ValidationResponse($response);
    }

    /**
     * Validate an EORI number.
     *
     * @param  string  $countryCode  The country code (e.g. 'GB')
     * @param  string  $eoriNumber  The EORI number to validate (without country code)
     *
     * @throws Vat24ApiException
     */
    public function validateEori(string $countryCode, string $eoriNumber): ValidationResponse
    {
        $params = [
            'country_code' => $countryCode,
            'eori_number' => $eoriNumber,
        ];

        $response = $this->makeRequest('POST', '/eori/validate', $params);

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
                'ignore_errors' => true, // This allows us to get the error response body
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

        // Get HTTP status code from the headers
        $statusCode = 0;
        if (isset($http_response_header[0])) {
            preg_match('/\d{3}/', $http_response_header[0], $matches);
            $statusCode = intval($matches[0] ?? 0);
        }

        // Only throw exceptions for server errors (5xx) or connection issues
        // For 4xx errors, we'll return the response so the client can handle it
        if ($statusCode >= 500) {
            throw new Vat24ApiException(
                $responseData['message'] ?? 'Server error',
                $statusCode
            );
        }

        // Make sure the response has a status field
        if (! isset($responseData['status'])) {
            $responseData['status'] = $statusCode;
        }

        // Make sure the response has a message field
        if (! isset($responseData['message']) && isset($responseData['error'])) {
            $responseData['message'] = $responseData['error'];
        }

        return $responseData;
    }
}
