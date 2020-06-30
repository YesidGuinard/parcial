<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Config\Database;
use Psr\Http\Message\ServerRequestInterface;
use App\Utils\Helper;

// Instanciar Illuminate
new Database();

$app = AppFactory::create();
$app->setBasePath("/apirest/public");
$app->addRoutingMiddleware();

$customErrorHandler = function (
    ServerRequestInterface $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
    $HttpCode = 500;
    $payload= Helper::formatResponse(false,"Exception");
    if (isset($exception)) {
        $code = $exception->getCode();
        if ($code >= 100 && $code <= 599) {
            $HttpCode = $code;
        }
        $payload = Helper::formatResponse(false, $exception->getMessage());

    }
    $response = $app->getResponseFactory()->createResponse($HttpCode);
    $response->getBody()->write($payload);
    return $response;
};

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);


// REGISTRAR RUTAS
(require_once __DIR__ . '/routes.php')($app);

// REGISTRAR MIDDLEWARE
(require_once __DIR__ . '/middlewares.php')($app);

return $app;