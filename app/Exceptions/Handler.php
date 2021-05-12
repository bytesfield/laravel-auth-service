<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;
use App\Traits\ApiResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Handles all throwable exceptions
     *
     * @param $request
     * @param \Throwable $e
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->methodNotAllowed(__('errors.method_not_allowed'));
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->notFound(__('errors.not_found'));
        }

        if ($e instanceof ModelNotFoundException) {
            return $this->notFound(__('errors.not_found'));
        }

        if ($e instanceof ValidationException) {
            return $this->failedValidation(__('errors.validation_failed'), $e->errors());
        }

        if ($e instanceof AuthenticationException) {
            return $this->unauthorized($e->getMessage());
        }

        return $this->buildResponse($e->getMessage(), 'failed', config('errors.codes.server_error'));
    }
}
