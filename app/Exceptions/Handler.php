<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Psr\Log\LogLevel;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Plain-English text for JSON error responses (AJAX). The error pages in
     * resources/views/errors/ carry the same wording.
     */
    public const PUBLIC_MESSAGES = [
        401 => 'Your session has ended. Please sign in again.',
        403 => "You don't have permission to do that.",
        404 => "We couldn't find what you were looking for.",
        419 => 'Your session expired. Reload the page and try again.',
        429 => 'Too many requests. Wait a minute and try again.',
        503 => 'FleetFreak is down for maintenance. Please try again in a few minutes.',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // HTTP errors (404, 419, 403, other 4xx, 503, abort(500)) are never "reported", so without
        // this they leave no log entry for the reference ID on the error page. One short line each,
        // no trace: debug for the expected ones (404, 419, other 4xx, 503 maintenance), warning for
        // 403, error for any other 5xx raised in code.
        $this->renderable(function (Throwable $e, $request) {
            if ($e instanceof HttpExceptionInterface && ! $this->shouldReport($e)) {
                $status = $e->getStatusCode();
                $level = match (true) {
                    $status === 403 => LogLevel::WARNING,
                    $status >= 500 && $status !== 503 => LogLevel::ERROR,
                    default => LogLevel::DEBUG,
                };
                Log::log($level, sprintf('HTTP %d %s /%s', $status, $request->method(), ltrim($request->path(), '/')), [
                    'error_ref' => static::errorRef(),
                    'userId' => auth()->id(),
                ]);
            }

            return null; // render as usual
        });
    }

    /**
     * The reference ID for the current request's error: shown on the error page or in the
     * AJAX toast, and written into the matching log entry. Created once per request.
     */
    public static function errorRef(): string
    {
        $request = request();
        if (! $ref = $request->attributes->get('error_ref')) {
            $ref = 'FF-' . strtoupper(substr((string) Str::ulid(), -10));
            $request->attributes->set('error_ref', $ref);
        }

        return $ref;
    }

    /**
     * Context added to every reported (logged) exception.
     */
    protected function context()
    {
        $context = ['error_ref' => static::errorRef()];
        if (request()->route()) { // a real routed request, not a queue job or console command
            $context['url'] = request()->method() . ' ' . request()->fullUrl();
        }

        return array_merge(parent::context(), $context);
    }

    /**
     * Error pages get the reference ID as $errorRef.
     */
    protected function renderHttpException(HttpExceptionInterface $e)
    {
        view()->share('errorRef', static::errorRef());

        return parent::renderHttpException($e);
    }

    /**
     * JSON errors (AJAX): with debug off, a plain message and the reference ID only; never the
     * exception message, file or trace. With debug on, Laravel's details plus the reference ID.
     */
    protected function convertExceptionToArray(Throwable $e)
    {
        if (config('app.debug')) {
            return parent::convertExceptionToArray($e) + ['ref' => static::errorRef()];
        }

        $status = $this->isHttpException($e) ? $e->getStatusCode() : 500;

        return [
            'message' => self::PUBLIC_MESSAGES[$status]
                ?? ($status >= 500 ? 'Something went wrong on our side. Please try again.' : "That request couldn't be completed."),
            'ref' => static::errorRef(),
        ];
    }
}
