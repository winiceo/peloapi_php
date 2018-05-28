<?php

use App\Exception\AccessDeniedException;
use Slim\Handlers\NotAllowed;
use Slim\Handlers\NotFound;
use Slim\Handlers\PhpError;
use Slim\Handlers\Strategies\RequestResponse;
use Slim\Http\Request;
use Slim\Http\Response;
use Interop\Container\ContainerInterface;

/**
 * Controller functions signature must be like:
 *
 * public function controllerAction($request, $response, $arg1, $arg2, $arg3, ...)
 *
 * https://www.slimframework.com/docs/objects/router.html#route-strategies
 */


$container = [];
//$container['foundHandler'] = function (ContainerInterface $c) {
//    /** @var Request $request */
//    $request = $c->get('request');
//    $c->get('monolog')->info(sprintf('Matched route "%s /%s"', $request->getMethod(), ltrim($request->getUri()->getPath(), '/')));
//
//     return new RequestResponse();
//};

$container['csrfFailureHandler'] = function (ContainerInterface $c) {
    return function (Request $request, Response $response) use ($c) {
        $c->get('monolog')->error(sprintf('Failed CSRF check on "%s /%s"', $request->getMethod(), ltrim($request->getUri()->getPath(), '/')));

        $c->get('flash')->addMessage('error', 'Failed CSRF check');

        if ('prod' === $c->get('environment')['APP_ENV']) {
            return $response->withRedirect($request->getUri()->getPath());
        } else {
            return $response->write('Failed CSRF check!');
        }
    };
};


$container['notFoundHandler'] = function (ContainerInterface $c) {
    return function (Request $request, Response $response) use ($c) {
        $c->get('monolog')->error(sprintf('No route found for "%s /%s"', $request->getMethod(), ltrim($request->getUri()->getPath(), '/')));

        if ($request->getContentType() == 'application/json') {
            return $response->withJson(["status" => 404, "message" => 'ÇëÇóµØÖ·´íÎó'], 404);
        }

        if ('prod' === $c->get('environment')['APP_ENV']) {

            return $response->withStatus(404)->write($c->get('twig')->fetch('error/404.twig'));

        } else {

            return (new NotFound())($request, $response);

        }
    };
};


$container['notAllowedHandler'] = function (ContainerInterface $c) {
    return function (Request $request, Response $response, array $methods) use ($c) {
        $c->get('monolog')->error(sprintf(
            'No route found for "%s /%s": Method not allowed (Allow: %s)',
            $request->getMethod(),
            ltrim($request->getUri()->getPath(), '/'),
            implode(', ', $methods)
        ));

        if ('prod' === $c->get('environment')['APP_ENV']) {
            return $response->withStatus(405)->write($c->get('twig')->fetch('error/4xx.twig'));
        } else {
            return (new NotAllowed())($request, $response, $methods);
        }
    };
};

$container['accessDeniedHandler'] = function (ContainerInterface $c) {
    return function (Request $request, Response $response, AccessDeniedException $exception) use ($c) {
        $c->get('monolog')->debug('Access denied, the user does not have access to this section', [
            'exception' => $exception
        ]);

        return $response->withStatus(403)->write($c->get('twig')->fetch('error/403.twig'));
    };
};

$container['errorHandler'] = function (ContainerInterface $c) {
    return function (Request $request, Response $response, Exception $exception) use ($c) {
        if ($exception instanceof AccessDeniedException) {
            return $c->get('accessDeniedHandler')($request, $response, $exception);
        }

        $c->get('monolog')->error('Uncaught PHP Exception ' . get_class($exception), [
            'exception' => $exception
        ]);

        if ($request->getContentType() == 'application/json') {
            $message='prod' === $c->get('environment')['APP_ENV']?'´íÎó':$exception->getMessage();

            return $response->withJson(["status" => 500, "message" => $message], 500);
        }

        if ('prod' === $c->get('environment')['APP_ENV']) {
            return $response->withStatus(500)->write($c->get('twig')->fetch('error/500.twig'));
        } else {
            return (new Slim\Handlers\Error(true))($request, $response, $exception);
        }
    };
};

$container['phpErrorHandler'] = function (ContainerInterface $c) {
    return function (Request $request, Response $response, Throwable $error) use ($c) {
        $c->get('monolog')->critical('Uncaught PHP Exception ' . get_class($error), [
            'exception' => $error
        ]);

        if ($request->getContentType() == 'application/json') {
            $message='prod' === $c->get('environment')['APP_ENV']?'Î´Öª´íÎó':$error->getMessage();
            return $response->withJson(["status" => 500, "message" => $message], 500);
        }
        if ('prod' === $c->get('environment')['APP_ENV']) {
            return $response->withStatus(500)->write($c->get('twig')->fetch('error/500.twig'));
        } else {
            return (new PhpError(true))($request, $response, $error);
        }
    };
};

return $container;
