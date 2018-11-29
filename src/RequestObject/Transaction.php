<?php

namespace App\RequestObject;

use Symfony\Component\HttpFoundation\Request;

class Transaction
{
    /** @var ConsumerIdentifier */
    public $consumerIdentifier;

    public function __construct(ConsumerIdentifier $consumerIdentifier)
    {
        $this->consumerIdentifier = $consumerIdentifier;
    }

    public static function make(ConsumerIdentifier $consumerIdentifier): self
    {
        return new self($consumerIdentifier);
    }

    /**
     * @param Request $request
     * @return Transaction
     * @throws \Exception
     */
    public static function makeFromRequest(Request $request): self
    {
        $consumerIdentifier = ConsumerIdentifier::makeFromRequest($request);
        return self::make($consumerIdentifier);
    }
}
