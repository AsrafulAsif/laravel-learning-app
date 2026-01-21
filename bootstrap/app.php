<?php

use App\Exceptions\RecordNotFoundException;
use App\Http\Middleware\CheckApiPermission;
use App\Http\Middleware\ForceJsonResponse;
use App\Traits\ApiResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->prepend(ForceJsonResponse::class);
        $middleware->alias([
            'permission' => CheckApiPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function (Request $request) {
            if ($request->is('api/*')) {
                return true;
            }
            return $request->expectsJson();
        });

        $responseHelper = new class {
            use ApiResponseTrait;
            public function error(string $m, int $s, ?array $errors = null) { return $this->errorResponse($m, $s, $errors); }
        };

        /// 1. Handle Method Not Allowed (405)
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) use ($responseHelper) {
            return $responseHelper->error(
                "The {$request->method()} method is not supported for this route.",
                405
            );
        });

        // 2. Handle Custom Record Not Found (Service Layer 404)
        $exceptions->render(function (RecordNotFoundException $e) use ($responseHelper) {
            return $responseHelper->error($e->getMessage(), 404);
        });

        // 3. Handle Endpoint Not Found (Invalid URL 404)
        $exceptions->render(function (NotFoundHttpException $e, Request $request) use ($responseHelper) {
            return $responseHelper->error("The path '{$request->path()}' was not found.", 404);
        });
        // 4. Handle ValidationException for request
        $exceptions->render(function (ValidationException $e) use ($responseHelper) {
            return $responseHelper->error("Validation failed", 422, $e->errors());
        });
        // 5. Handle UnauthorizedException for user login
        $exceptions->render(function (UnauthorizedException $e) use ($responseHelper) {
            return $responseHelper->error($e->getMessage(), 401);
        });
        // 6. Handle QueryException
        $exceptions->render(function (QueryException $e) use ($responseHelper) {
            return $responseHelper->error($e->getMessage(), 500);
        });
        // 7. Handle AuthenticationException
        $exceptions->render(function (AuthenticationException  $e) use ($responseHelper) {
            return $responseHelper->error($e->getMessage(), 401);
        });

    })->create();
