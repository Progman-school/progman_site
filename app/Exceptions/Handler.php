<?php

namespace App\Exceptions;

use App\Helpers\ApiHelper;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
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

        $this->renderable(function (UserAlert $e, Request $request) {
            if ($request->is('api/*')) {
                return response(ApiHelper::createFrontAnswer(
                    $e->getMessage(),
                    ApiHelper::ERROR_STATUS
                ));
            }
            return null;
        });
    }
}
