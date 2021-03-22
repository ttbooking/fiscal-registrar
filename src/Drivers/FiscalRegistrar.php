<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Drivers;

use Illuminate\Contracts\Routing\UrlGenerator;
use TTBooking\FiscalRegistrar\Concerns;
use TTBooking\FiscalRegistrar\Contracts;
use TTBooking\FiscalRegistrar\DTO\Receipt;
use TTBooking\FiscalRegistrar\DTO\Result;
use TTBooking\FiscalRegistrar\Exceptions;

abstract class FiscalRegistrar implements Contracts\FiscalRegistrar, Contracts\DispatchesEvents
{
    use Concerns\HasEvents;

    protected UrlGenerator $urlGenerator;

    protected array $config;

    protected string $connection;

    public function __construct(UrlGenerator $urlGenerator, array $config = [], string $connection = 'default')
    {
        $this->urlGenerator = $urlGenerator;
        $this->config = $config;
        $this->connection = $connection;
    }

    public function sell(string $externalId, Receipt $receipt): Result
    {
        return $this->register(__FUNCTION__, $externalId, $receipt);
    }

    public function sellRefund(string $externalId, Receipt $receipt): Result
    {
        return $this->register(__FUNCTION__, $externalId, $receipt);
    }

    public function buy(string $externalId, Receipt $receipt): Result
    {
        return $this->register(__FUNCTION__, $externalId, $receipt);
    }

    public function buyRefund(string $externalId, Receipt $receipt): Result
    {
        return $this->register(__FUNCTION__, $externalId, $receipt);
    }

    /**
     * @param  string  $operation
     * @param  string  $externalId
     * @param  Receipt  $receipt
     * @return Result
     *
     * @throws Exceptions\FiscalRegistrarException
     */
    protected function register(string $operation, string $externalId, Receipt $receipt): Result
    {
        $this->event($this->eventName($operation).'.registering', [
            'connection' => $this->connection,
            'external_id' => $externalId,
            'internal_id' => null,
            'receipt' => $receipt,
            'result' => null,
        ]);

        $result = $this->doRegister(...func_get_args());

        $this->event($this->eventName($operation).'.registered', [
            'connection' => $this->connection,
            'external_id' => $externalId,
            'internal_id' => $result->internalId,
            'receipt' => $receipt,
            'result' => $result,
        ]);

        return $result;
    }

    /**
     * @param  string  $operation
     * @param  string  $externalId
     * @param  Receipt  $receipt
     * @return Result
     *
     * @throws Exceptions\FiscalRegistrarException
     */
    abstract protected function doRegister(string $operation, string $externalId, Receipt $receipt): Result;

    public function getCallbackUrl(): ?string
    {
        return ! is_string($callback = $this->config['callback'] ?? true) && $callback
            ? $this->urlGenerator->route('fiscal-registrar.callback', ['connection' => $this->connection])
            : ($callback ?: null);
    }

    protected function eventName(string $operation): string
    {
        return "fiscal-registrar.{$this->connection}.{$operation}";
    }
}
