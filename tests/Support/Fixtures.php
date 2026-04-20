<?php

namespace Test\Lucinda\OAuth2\Support;

class Fixtures
{
    public static function path(string $relativePath): string
    {
        return dirname(__DIR__)."/fixtures/".$relativePath;
    }

    public static function url(string $relativePath): string
    {
        return "file://".self::path($relativePath);
    }

    public static function xml(string $relativePath = "oauth2.xml"): \SimpleXMLElement
    {
        return simplexml_load_file(self::path($relativePath));
    }
}
