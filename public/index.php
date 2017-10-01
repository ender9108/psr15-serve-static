<?php
use EnderLab\Application\App;
use EnderLab\Application\AppFactory;
use EnderLab\ServeStaticMiddleware;
use GuzzleHttp\Psr7\Response;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;

chdir(dirname(__DIR__));

// autoload
include('vendor/autoload.php');

$app = AppFactory::create();
$app->pipe(new ServeStaticMiddleware('public'));

$app->get('/test', function(ServerRequestInterface $request, DelegateInterface $delegate) {
    $response = $delegate->process($request);
    $response->getBody()->write('<center><h1 style="color:green">200 page found !</h1></center>');

    return $response;
});

$app->enableRouterHandler();
$app->enableDispatcherHandler();

$app->pipe(function(ServerRequestInterface $request, DelegateInterface $delegate) {
    return new Response(404, [], '404 not found !!');
});

$app->run();
