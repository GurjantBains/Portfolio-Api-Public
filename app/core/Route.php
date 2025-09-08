<?php

class Route
{
    private static $routes = [
        "GET" => [],
        "POST" => [],
        "PUT" => [],
        "DELETE" => [],
        "PATCH" => [],
    ];

    public static function get($uri, $action) {
        self::addRoute('GET', $uri, $action);
    }

    public static function post($uri, $action) {
        self::addRoute('POST', $uri, $action);
    }

    public static function put($uri, $action) {
        self::addRoute('PUT', $uri, $action);
    }

    public static function delete($uri, $action) {
        self::addRoute('DELETE', $uri, $action);
    }

    public static function patch($uri, $action) {
        self::addRoute('PATCH', $uri, $action);
    }

    private static function addRoute($method, $uri, $action)
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^\/]+)', $uri);
        $pattern = "#^" . $pattern . "$#";

        self::$routes[$method][$pattern] = $action;
    }
    public static function getRoutes(): array
    {
        return self::$routes;
    }



}
