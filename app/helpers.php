<?php declare(strict_types = 1);

if (!function_exists('make_name_readable')) {
    /**
     * @param string $methodName name of view function
     *
     * @return string
     * @throws \App\Exceptions\WrongMethodNameException
     */
    function make_name_readable(string $methodName): String
    {
        if (ends_with($methodName, 'View') && !starts_with($methodName, 'show')) {
            throw new \App\Exceptions\WrongMethodNameException($methodName.' is not a valid name for a View-Function!');
        } else {
            $readableName = preg_replace(
                '/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]|[0-9]{1,}/',
                ' $0',
                $methodName
            );

            $methodName = ucfirst(strtolower($readableName));
        }

        return $methodName;
    }
}

if (!function_exists('get_cache_key_for_current_request')) {
    /**
     * From https://laravel-news.com/cache-query-params
     * Generates a Hash based on the current URL, used by Cache
     *
     * @return string
     */
    function get_cache_key_for_current_request()
    {
        $url = request()->url();
        $queryParams = request()->query();

        ksort($queryParams);

        $queryString = http_build_query($queryParams);

        $fullUrl = "{$url}?{$queryString}";

        $rememberKey = sha1($fullUrl);

        return $rememberKey;
    }
}
