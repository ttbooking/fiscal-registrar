<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Drivers;

use GuzzleHttp\Client;
use JMS\Serializer\SerializerBuilder;
use Lamoda\AtolClient\Converter\ObjectConverter;
use Lamoda\AtolClient\V4\AtolApi;
use Symfony\Component\Validator\Validation;

final class AtolApiFactory
{
    /** @var array<string, AtolApi> */
    private array $instances = [];

    private ?ObjectConverter $converter;

    public function make(?string $baseUri = null): AtolApi
    {
        $baseUri = isset($baseUri) ? rtrim($baseUri, '/') : 'https://online.atol.ru/possystem';

        return $this->instances[$baseUri] ??= new AtolApi($this->getConverter(), new Client, [], $baseUri);
    }

    public function getConverter(): ObjectConverter
    {
        return $this->converter ??= new ObjectConverter(
            SerializerBuilder::create()->enableEnumSupport()->build(),
            Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator()
        );
    }
}
