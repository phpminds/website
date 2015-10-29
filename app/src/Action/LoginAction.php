<?php

namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use App\Model\Auth;
use Slim\Http\Request;
use Slim\Http\Response;

final class LoginAction
{
    private $view;
    private $logger;
    private $auth;
    private $csrf;

    public function __construct(Twig $view, LoggerInterface $logger, Auth $auth, $csrf)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->auth = $auth;
        $this->csrf = $csrf;
    }

    public function dispatch(Request $request, Response $response, $args)
    {

        // CSRF token name and value
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();

        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);


        $email = '';
        $msg = '';
        if($request->isPost()) {
            $email = $request->getParam('email');
            $password = $request->getParam('password');
            if ($this->auth->isValid($email, $password)) {
                $this->auth->store();
                return $response->withStatus(302)->withHeader('Location', '/');
            } else {
                $msg = 'Incorrect email or password.';
            }
        }

        $this->view->render(
            $response,
            'login.twig',
            [
                'nameKey' => $nameKey, 'valueKey' => $valueKey,
                'name' => $name, 'value' => $value,
                'email' => $email, 'msg' => $msg
            ]
        );

        return $response;
    }
}