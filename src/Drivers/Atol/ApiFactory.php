<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Drivers\Atol;

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

final class ApiFactory
{
    public function make(string $baseUri): AtolApi
    {
        return new AtolApi(
            new ObjectConverter(

                SerializerBuilder::create()
                    ->setSerializationVisitor('atol_client', new JsonSerializationVisitorFactory)
                    ->setDeserializationVisitor('atol_client', new JsonDeserializationVisitorFactory)
                    ->configureHandlers(function (HandlerRegistry $handlerRegistry) {
                        $handlerRegistry->registerSubscribingHandler(new EnumHandler);
                        $handlerRegistry->registerSubscribingHandler(new ExtendedDateHandler);
                    })->build(),

                Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator()

            ),
            new Client, [], $baseUri
        );
    }
}
