<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CliRequest
{
    /**
     * Invoke middleware
     *
     * @param  ServerRequestInterface   $request  PSR7 request object
     * @param  ResponseInterface        $response PSR7 response object
     * @param  callable                 $next     Next middleware callable
     *
     * @return ResponseInterface PSR7 response object
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        // use: php public/index.php /status GET event=true

        global $argv;

        if (isset($argv)) {
            list($call, $path, $method, $params) = $argv;

            if (strtoupper($method) === 'GET') {
                $request = \Slim\Http\Request::createFromEnvironment(\Slim\Http\Environment::mock([
                    'REQUEST_METHOD'    => 'GET',
                    'REQUEST_URI'       => $path . '?' . $params,
                    'QUERY_STRING'      => $params
                ]));
            }

            unset($argv);
        }

        return $next($request, $response);
    }
}