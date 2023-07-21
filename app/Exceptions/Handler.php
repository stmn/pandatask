<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
//        $this->reportable(function (Throwable $e, Request $request) {
//
//        });
    }

    /** @noinspection PhpMissingReturnTypeInspection */
    public function render($request, Throwable $e)
    {
        if ($request->ajax()) {
            if ($e instanceof AuthorizationException) {
                $request->session()->flash('message', [
                    'type' => 'error',
                    'message' => $e->getMessage(),
                ]);
                return back();
            }
        }

        return parent::render($request, $e);
    }
}
