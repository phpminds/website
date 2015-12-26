<?php

namespace PHPMinds\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthCheck
{
    private $isAuth = false;
    private $authRoutes = [];

    public function __construct(array $session, $authKey = 'auth', array $authRoutes = [])
    {
        if (array_key_exists($authKey, $session)) {
            $this->isAuth = true;
        }

        $this->authRoutes = $authRoutes;
    }

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
        if (!$this->isAuth) {
            if (in_array($request->getUri()->getPath(), $this->authRoutes)) {
                return $response->withStatus(302)->withHeader('Location', '/');
            }
        }

        return $next($request, $response);
    }
}
