<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use ResponseHelper;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $ex) {
            //
        });
    }

    public function render ($req, Throwable $ex)
    {
        if ($ex instanceof \Illuminate\Auth\AuthenticationException) {
            return ResponseHelper::sendResponse([], 401, null, true);
        } elseif ($ex instanceof \Illuminate\Validation\ValidationException) {
            return ResponseHelper::sendResponse([], 200, 'Validation Errors', true, $ex->validator->messages()->get('*'));
        } else {
            return ResponseHelper::sendResponse([], 500, $ex->getMessage(), true);
        }
        return parent::render($req, $ex);
    }
}
