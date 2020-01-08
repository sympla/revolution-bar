<?php


use RDStation\Configuration\Http;
use RDStation\Configuration\Routes;

class BuidUrl
{
    /**
     * @param string $route
     * @return string
     * @throws \RDStation\Exception\InvalidRouteException
     * @throws ReflectionException
     */
    public static function getUrlByRoute(string $route)
    {
        if (! static::routeExists($route)) {
            throw new \RDStation\Exception\InvalidRouteException("Invalid route: " . $route);
        }


        return sprintf("%s/%s", Http::BASE_URL, $route);
    }

    /**
     * @param string $route
     * @return bool
     * @throws ReflectionException
     */
    private static function routeExists(string $route)
    {
        $reflection = new ReflectionClass(Routes::class);
        foreach ($reflection->getConstants() as $constant) {
            if ($route == $constant) {
                return true;
            }
        }

        return false;
    }
}