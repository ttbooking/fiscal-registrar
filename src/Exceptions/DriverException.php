<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class DriverException extends FiscalRegistrarException implements HttpExceptionInterface
{
    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getHeaders(): array
    {
        return [];
    }
}
