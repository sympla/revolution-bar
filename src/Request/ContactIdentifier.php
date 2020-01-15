<?php


namespace RDStation\Request;


use RDStation\Configuration\Routes;
use RDStation\Exception\IncorrectTypeException;
use RDStation\Exception\InvalidRouteException;
use RDStation\Helpers\BuildUrl;

class ContactIdentifier
{
    const EMAIL = 'email';
    const UUID  = 'uuid';

    public static function getIdentifiersValid() : array
    {
        return [
            self::EMAIL,
            self::UUID
        ];
    }

    /**
     * @param string $identifierName
     * @param string $identifierValue
     * @return string
     * @throws IncorrectTypeException
     * @throws InvalidRouteException
     */
    public static function getContactUrl(string $identifierName, string $identifierValue) : string
    {
        if (!in_array($identifierName, static::getIdentifiersValid())) {

            throw new IncorrectTypeException(
                "The identifier name must be: " .
                implode(", ", self::getIdentifiersValid())
            );
        }

        return BuildUrl::getUrlByRoute(Routes::CONTACT) . '/' . $identifierName . ':' . $identifierValue;
    }
}