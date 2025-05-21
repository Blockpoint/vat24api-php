# API wrapper for Vat24API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/blockpoint/vat24api-php.svg?style=flat-square)](https://packagist.org/packages/blockpoint/vat24api-php)
[![Tests](https://img.shields.io/github/actions/workflow/status/blockpoint/vat24api-php/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/blockpoint/vat24api-php/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/blockpoint/vat24api-php.svg?style=flat-square)](https://packagist.org/packages/blockpoint/vat24api-php)

A PHP wrapper for the Vat24API service, which provides VAT and EORI number validation for EU and UK businesses. This package makes it easy to integrate VAT and EORI validation into your PHP applications.

https://vat24api.com/

## Installation

You can install the package via composer:

```bash
composer require blockpoint/vat24api-php
```

## Usage

```php
// Create a new Vat24Api instance with your API key
$vat24api = new Blockpoint\Vat24Api\Vat24Api('your-api-key');

// Validate a VAT number
try {
    $response = $vat24api->validateVat('NL836176320B01');

    if ($response->isValid()) {
        echo "VAT number is valid!\n";
        echo "Company name: " . $response->getCompanyName() . "\n";
        echo "Company address: " . $response->getCompanyAddress() . "\n";
    } else {
        echo "VAT number is not valid!\n";
        echo "Reason: " . $response->getFaultString() . "\n";
    }
} catch (\Blockpoint\Vat24Api\Exceptions\Vat24ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Validate an EORI number
try {
    $response = $vat24api->validateEori('GB123456789000');

    if ($response->isValid()) {
        echo "EORI number is valid!\n";
        echo "Company name: " . $response->getCompanyName() . "\n";
    } else {
        echo "EORI number is not valid!\n";
        echo "Reason: " . $response->getFaultString() . "\n";
    }
} catch (\Blockpoint\Vat24Api\Exceptions\Vat24ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Available Methods

#### Validate VAT Number

```php
$response = $vat24api->validateVat('NL836176320B01');
```

You can also perform a mutual validation by providing a requester VAT number:

```php
$response = $vat24api->validateVat('NL836176320B01', 'GB123456789');
```

#### Validate EORI Number

```php
$response = $vat24api->validateEori('GB123456789000');
```

### Response Methods

The `ValidationResponse` object provides the following methods:

- `isValid()`: Returns whether the VAT/EORI number is valid
- `getStatusCode()`: Returns the HTTP status code
- `getMessage()`: Returns the status message
- `getCountryCode()`: Returns the country code
- `getVatNumber()`: Returns the VAT number
- `getEoriNumber()`: Returns the EORI number
- `getValidationStatus()`: Returns the validation status
- `getFaultString()`: Returns the fault string if validation failed
- `getRequestDate()`: Returns the request date
- `getConsultationNumber()`: Returns the consultation number
- `getConsultationAuthority()`: Returns the consultation authority
- `getCompanyName()`: Returns the company name
- `getCompanyAddress()`: Returns the company address
- `toArray()`: Returns the raw response data as an array

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
