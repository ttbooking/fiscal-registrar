<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AggregateRule implements Rule
{
    protected array $rules = [];

    protected array $customMessages;

    protected array $customAttributes;

    /** @var string[] */
    protected array $messages = [];

    /**
     * Aggregate Rule constructor.
     *
     * @param  Closure|array  $rules
     * @param  array  $customMessages
     * @param  array  $customAttributes
     * @return void
     */
    public function __construct($rules = [], array $customMessages = [], array $customAttributes = [])
    {
        $this->rules += value($rules);
        $this->customMessages = $customMessages;
        $this->customAttributes = $customAttributes;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $validator = Validator::make($value, $this->getRules(), $this->customMessages, $this->customAttributes);

        $this->messages = $validator->errors()->getMessages();

        return ! $validator->fails();
    }

    /**
     * Get the validation error messages.
     *
     * @return string[]
     */
    public function message(): array
    {
        return $this->messages;
    }

    /**
     * Get the validation rules.
     *
     * @return array
     */
    protected function getRules(): array
    {
        return $this->rules;
    }
}
