<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Drivers;

use GuzzleHttp\Client;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Visitor\Factory\JsonDeserializationVisitorFactory;
use JMS\Serializer\Visitor\Factory\JsonSerializationVisitorFactory;
use Lamoda\AtolClient\Converter\ObjectConverter;
use Lamoda\AtolClient\Serializer\Handler\EnumHandler;
use Lamoda\AtolClient\Serializer\Handler\ExtendedDateHandler;
use Lamoda\AtolClient\V4\AtolApi;
use Symfony\Component\Validator\Validation;

final class AtolApiFactory
{
    /** @var array<string, AtolApi> */
    private array $instances = [];

    /** @var ObjectConverter|null */
    private ?ObjectConverter $converter;

    public function make(string $baseUri = null): AtolApi
    {
        $baseUri = isset($baseUri) ? rtrim($baseUri, '/') : 'https://online.atol.ru/possystem';

        return $this->instances[$baseUri] ??= new AtolApi($this->getConverter(), new Client, [], $baseUri);
    }

    public function getConverter(): ObjectConverter
    {
        return $this->converter ??= new ObjectConverter(

            SerializerBuilder::create()
                ->setSerializationVisitor('atol_client', new JsonSerializationVisitorFactory)
                ->setDeserializationVisitor('atol_client', new JsonDeserializationVisitorFactory)
                ->configureHandlers(function (HandlerRegistry $handlerRegistry) {
                    $handlerRegistry->registerSubscribingHandler(new EnumHandler);
                    $handlerRegistry->registerSubscribingHandler(new ExtendedDateHandler);
                })->build(),

            Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator()

        );
    }
}
