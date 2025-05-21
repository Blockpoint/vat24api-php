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
     * @param  string  $vatNumber  The VAT number to validate (with or without country code)
     * @param  string|null  $requesterVatNumber  Optional requester VAT number for mutual validation
     *
     * @throws Vat24ApiException
     */
    public function validateVat(string $vatNumber, ?string $requesterVatNumber = null): ValidationResponse
    {
        return $this->client->validateVat($vatNumber, $requesterVatNumber);
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
        return $this->client->validateEori($eoriNumber);
    }
}
