<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use HttpResponses;
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
            if($e instanceof MissingAbilityException) {
                Log::debug('missing ability exception');
            }
        });
    }
}
