<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Contracts;

use TTBooking\FiscalRegistrar\DTO;
use TTBooking\FiscalRegistrar\Enums\Operation;

interface Receipt extends SelfResolvable, StatefulFiscalRegistrar
{
    /**
     * @param  DTO\Receipt  $data
     * @return $this
     */
    public function make(DTO\Receipt $data);

    /**
     * @param  string  $connection
     * @return $this
     */
    public function for(string $connection);

    /**
     * @param  Operation  $operation
     * @return $this
     */
    public function do(Operation $operation);

    /**
     * @param  string  $id
     * @return $this
     */
    public function as(string $id);

    /**
     * @return bool
     */
    public function save();

    /**
     * @return static
     */
    public function clone();

    /**
     * @return bool|null
     */
    public function delete();
}
