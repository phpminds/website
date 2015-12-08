<?php
/**
 * Created by PhpStorm.
 * User: shaunhare
 * Date: 08/12/15
 * Time: 21:36
 */

namespace App\Action;


use Monolog\Logger;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class CommunityAction
{
    /**
     * @var Monolog logger
     */
    private $logger;

    /**
     * @var Twig view
     */
    private $view;


    public function __construct(Logger $logger, Twig $view)
    {
        $this->logger = $logger;

        $this->view = $view;
    }

    public function dispatch(Request $request, Response $response)
    {
        $this->view->render($response, "community.twig");
        return $response;
    }
}