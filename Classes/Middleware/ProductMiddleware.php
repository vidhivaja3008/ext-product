<?php
namespace Nitsan\NitsanProduct\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Frontend\Controller\ErrorController;
// use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;

class ProductMiddleware implements MiddlewareInterface{
     public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        

        $response = $handler->handle($request);
        $path = $request->getUri()->getPath();

        if (str_starts_with($path, '/product')) {
            
            $content = $response->getBody()->getContents();

            $message = 'Welcome to Product Page';

            $content = $message . $content;

            $response->getBody()->rewind();
            $response->getBody()->write($content);
        }

        return $response;


        // if ($request->getRequestTarget() === 'product') {
        //     return GeneralUtility::makeInstance(ErrorController::class)
        //         ->unavailableAction(
        //             $request,
        //             'This page is temporarily unavailable.',
        //         );
        // }

        // return $handler->handle($request);
    }

}

