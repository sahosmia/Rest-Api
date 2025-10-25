<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                if ($e instanceof ValidationException) {
                    return $this->errorResponse($e->validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                if ($e instanceof AuthenticationException) {
                    return $this->errorResponse('Unauthenticated.', Response::HTTP_UNAUTHORIZED);
                }

                if ($e instanceof NotFoundHttpException) {
                    return $this->errorResponse('Not Found.', Response::HTTP_NOT_FOUND);
                }

                // Default to 500 Server Error
                return $this->errorResponse('Something went wrong.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    }
}
