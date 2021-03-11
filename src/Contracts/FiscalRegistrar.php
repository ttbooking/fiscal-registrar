<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\DTO\Register\Request;
use TTBooking\FiscalRegistrar\DTO\Register\Response;
use TTBooking\FiscalRegistrar\Exceptions\FiscalRegistrarException;

interface FiscalRegistrar
{
    /**
     * @param  string  $externalId
     * @param  Request\Receipt  $receipt
     * @return Response
     *
     * @throws FiscalRegistrarException
     */
    public function sell(string $externalId, Request\Receipt $receipt): Response;

    /**
     * @param  string  $externalId
     * @param  Request\Receipt  $receipt
     * @return Response
     *
     * @throws FiscalRegistrarException
     */
    public function sellRefund(string $externalId, Request\Receipt $receipt): Response;

    /**
     * @param  string  $externalId
     * @param  Request\Receipt  $receipt
     * @return Response
     *
     * @throws FiscalRegistrarException
     */
    public function buy(string $externalId, Request\Receipt $receipt): Response;

    /**
     * @param  string  $externalId
     * @param  Request\Receipt  $receipt
     * @return Response
     *
     * @throws FiscalRegistrarException
     */
    public function buyRefund(string $externalId, Request\Receipt $receipt): Response;
}
