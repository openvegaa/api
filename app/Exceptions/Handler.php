<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param ValidationException $e
     * @param $request
     * @return JsonResponse|void
     */
    public function convertValidationExceptionToResponse(ValidationException $e, $request) {
        if($request->expectsJson()){
            return response()->json([
                "success" => false,
                "message" =>  $e->getMessage(),
                "error_code" => 1103,
                "errors" => $e->errors()
            ]);
        } else {
            parent::convertValidationExceptionToResponse($e, $request);
        }
    }

    public function render($request, Throwable $e)
    {
        if ($request->expectsJson() && $e instanceof AuthenticationException) {
            return response()->json([
                "success" => false,
                "message" =>  $e->getMessage(),
                "error_code" => 1101
            ], 401);
        }

        return parent::render($request, $e);
    }
}
