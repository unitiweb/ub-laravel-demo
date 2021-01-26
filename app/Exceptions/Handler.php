<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
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
//        $this->reportable(function (Throwable $exception, Request $request) {
//            if ($this->shouldReport($exception) && app()->bound('sentry')) {
//                app('sentry')->captureException($exception);
//            }
//
//            parent::report($exception);
//        });

        $this->renderable(function (Throwable $exception, Request $request) {
            if ($request->is('api/*') || $request->is('webhook/*')) {
                return $this->handleApiException($exception);
            }

            return $request;
        });
    }

    /**
     * Handle api json exceptions
     *
     * @param Throwable $exception
     *
     * @return JsonResponse
     */
    protected function handleApiException(Throwable $exception): JsonResponse
    {
        $exception = $this->prepareException($exception);

        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = $exception->getCode();
        }

        if ($statusCode <= 0) {
            $statusCode = 500;
        }

        $error = [
            'status' => $statusCode,
            'description' => JsonResponse::$statusTexts[$statusCode] ?? 'Unknown',
            'exception' => $this->getExceptionName($exception),
            'message' => $exception->getMessage(),
        ];

        if ($exception instanceof ValidationException) {
            $error['status'] = 422;
            $error['data'] = $this->formatValidationErrors($exception);
        }

        if (config('app.debug') === true && $statusCode >= 500) {
            $error['trace'] = $exception->getTrace();
        }

        return response()->json(['error' => $error], $error['status']);
    }

    /**
     * Format the errors for the validation exception
     *
     * @param ValidationException $exception
     *
     * @return array
     */
    protected function formatValidationErrors(ValidationException $exception): array
    {
        $errors = [];
        foreach ($exception->validator->errors()->getMessages() as $key => $value) {
            if (is_array($value) && count($value) >= 1) {
                $errors[$key] = $value[0];
            }
        }

        return $errors;
    }

    /**
     * Get the name of the exception class
     *
     * @param Throwable $exception
     *
     * @return string
     */
    protected function getExceptionName(Throwable $exception): string
    {
        $path = explode('\\', get_class($exception));

        return array_pop($path);
    }
}
