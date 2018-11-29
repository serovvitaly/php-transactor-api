<?php

namespace App\RequestObject;

use Symfony\Component\HttpFoundation\Request;

class ConsumerIdentifier
{
    const TYPE_EMAIL = 'email';

    /** @var string */
    protected $identifier;

    /** @var string */
    protected $type;

    public function __construct(string $identifier, string $type)
    {
        $this->identifier = trim($identifier);
        $this->type = trim($type);
    }

    public static function make(string $identifier, string $type): self
    {
        return new self($identifier, $type);
    }

    /**
     * @param Request $request
     * @return ConsumerIdentifier
     * @throws \Exception
     */
    public static function makeFromRequest(Request $request): self
    {
        $requestJsonStr = $request->getContent();
        $requestJsonObj = json_decode($requestJsonStr);

        if (is_null($requestJsonObj)) {
            throw new \Exception('Invalid request. JSON_ERROR = ' . json_last_error());
        }

        if (property_exists($requestJsonObj, 'consumer_identifier')) {
            $consumerIdentifierObj = $requestJsonObj->consumer_identifier;
        } else {
            throw new \Exception('No specified consumer identifier');
        }

        if (property_exists($consumerIdentifierObj, 'id')) {
            $identifier = $consumerIdentifierObj->id;
        } else {
            throw new \Exception('Invalid consumer identifier, no field specified `id`');
        }

        if (property_exists($consumerIdentifierObj, 'type')) {
            $type = $consumerIdentifierObj->id;
        } else {
            throw new \Exception('Invalid consumer identifier, no field specified `type`');
        }

        return self::make($identifier, $type);
    }
}
