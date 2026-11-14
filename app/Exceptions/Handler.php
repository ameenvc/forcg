<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use App\Errorlog;
use Carbon\Carbon;
use Exception;

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
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        // Invalid hosts and central setup errors must never query a company's error table.
        if (config('tenancy.enabled')) {
            parent::report($exception);
            return;
        }

        $dir = substr(__DIR__, 0, -14);
        $backtrace =  $exception->getTraceAsString();
        $backtrace = str_replace([$dir], "", $backtrace);
        $backtrace = preg_replace('^(.*vendor.*)\n^', '', $backtrace);
        $today = Carbon::now()->toDateTimeString();
        $today = Carbon::parse($today);
        // This will logs the errors occured during the run time in in table errors
        $error = '**Error:** ' . $exception->getMessage() . PHP_EOL . '**Line:** ' . $exception->getLine() . PHP_EOL . '**File:** ' . $exception->getFile() . PHP_EOL . '**Trace:**' . PHP_EOL . $backtrace;
        $errors = Errorlog::where('error', $error)->First();
        if ($errors === null)
            Errorlog::insert(['error' => $error, 'logged_date' => $today, 'solved' => 0, 'occurence' => 1]);
        else {
            $id = $errors->id;
            $errors->occurence += 1;
            Errorlog::where('id', $id)->update(['logged_date' => $today, 'occurence' => $errors->occurence]);
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Check out Error Handling #render for more information
        // render method is responsible for converting a given exception into an HTTP response
        // Catch AthenticationException and redirect back to somewhere else....
        if ($exception instanceof AuthenticationException) {
            $guard = $exception->guards()[0] ?? null;
            if ($guard) {
                return redirect(route('login'));
            }
        }
        return parent::render($request, $exception);
    }
}
