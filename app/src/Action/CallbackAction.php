<?php

namespace PHPMinds\Action;


use PHPMinds\Model\Auth;
use PHPMinds\Repository\FileRepository;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

final class CallbackAction
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var FileRepository
     */
    private $fileRepository;

    public function __construct(LoggerInterface $logger, Auth $auth, FileRepository $fileRepository)
    {
        $this->logger           = $logger;
        $this->auth             = $auth;
        $this->fileRepository   = $fileRepository;
    }

    public function dispatch(Request $request, Response $response, $args)
    {
        if (isset($args['callback']) && $args['callback'] === 'joindin') {

            // if logged in
            if ($this->auth->isLoggedIn() && strlen($request->getParam('access_token', '')) > 0) {
                // store token
                $this->fileRepository->save(
                    $this->auth->getUserId() . '_joindin',
                    $request->getParam('access_token')
                );
            }
        }

        return $response->withStatus(302)->withHeader('Location', '/');
    }
}