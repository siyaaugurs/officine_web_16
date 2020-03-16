<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof MethodNotAllowedHttpException) {
            if($request->wantsJson())
                return response()->json([
                    'data' => 'Resource not found'
                ], 404);

            return response()->view('errors.405', [], 405);
        }

        if (in_array(env('APP_ENV'), ['production'])) {
            if (!$exception instanceof \Illuminate\Auth\AuthenticationException) {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Oops, something wrong with the system, pls try again !']);
                } else {
                    return redirect()->intended('home')->withErrors('Oops, something wrong with the system, pls try again !');
                }
            }
        }
        
        return parent::render($request, $exception);
    }
}
