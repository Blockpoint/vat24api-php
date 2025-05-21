<?php

namespace Blockpoint\Vat24Api\Responses;

class ValidationResponse
{
    private array $data;

    /**
     * Create a new ValidationResponse instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the status code.
     */
    public function getStatusCode(): int
    {
        return $this->data['status'] ?? 0;
    }

    /**
     * Get the status message.
     */
    public function getMessage(): string
    {
        return $this->data['message'] ?? '';
    }

    /**
     * Get the country code.
     */
    public function getCountryCode(): ?string
    {
        return $this->data['country_code'] ?? null;
    }

    /**
     * Get the VAT number.
     */
    public function getVatNumber(): ?string
    {
        return $this->data['vat_number'] ?? null;
    }

    /**
     * Get the EORI number.
     */
    public function getEoriNumber(): ?string
    {
        return $this->data['eori_number'] ?? null;
    }

    /**
     * Get the requester country code.
     */
    public function getRequesterCountryCode(): ?string
    {
        return $this->data['requester_country_code'] ?? null;
    }

    /**
     * Get the requester VAT number.
     */
    public function getRequesterVatNumber(): ?string
    {
        return $this->data['requester_vat_number'] ?? null;
    }

    /**
     * Check if the validation was successful.
     */
    public function isValid(): bool
    {
        return isset($this->data['validation']['valid']) && $this->data['validation']['valid'] === true;
    }

    /**
     * Get the validation status.
     */
    public function getValidationStatus(): ?string
    {
        return $this->data['validation']['status'] ?? null;
    }

    /**
     * Get the fault string.
     */
    public function getFaultString(): ?string
    {
        return $this->data['validation']['fault_string'] ?? null;
    }

    /**
     * Get the request date.
     */
    public function getRequestDate(): ?string
    {
        return $this->data['validation']['request_date'] ?? null;
    }

    /**
     * Get the consultation number.
     */
    public function getConsultationNumber(): ?string
    {
        return $this->data['validation']['consultation_number'] ?? null;
    }

    /**
     * Get the consultation authority.
     */
    public function getConsultationAuthority(): ?string
    {
        return $this->data['validation']['consultation_authority'] ?? null;
    }

    /**
     * Get the company name.
     */
    public function getCompanyName(): ?string
    {
        return $this->data['validation']['company_name'] ?? null;
    }

    /**
     * Get the company address.
     */
    public function getCompanyAddress(): ?string
    {
        return $this->data['validation']['company_address'] ?? null;
    }

    /**
     * Get the raw response data.
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
