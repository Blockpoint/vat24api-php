<?php

namespace Blockpoint\Vat24Api;

use Blockpoint\Vat24Api\Exceptions\Vat24ApiException;
use Blockpoint\Vat24Api\Responses\ValidationResponse;

class Vat24Api
{
    private Vat24ApiClass $client;

    /**
     * Create a new Vat24Api instance.
     */
    public function __construct(string $apiKey, array $options = [])
    {
        $this->client = new Vat24ApiClass($apiKey, $options);
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
        return $this->client->validateVat($countryCode, $vatNumber, $requesterCountryCode, $requesterVatNumber);
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
        return $this->client->validateEori($countryCode, $eoriNumber);
    }
}
