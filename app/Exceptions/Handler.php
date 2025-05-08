<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
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
    // public function render($request, Throwable $exception)
    // {
    //     // check api guard for unauthorized responses
    //     if(auth()->guard('api')->check() && method_exists($exception, 'getStatusCode')) {

    //         if ($exception->getStatusCode() == 404) {
    //             return response(['error' => '404 Page not found']);
    //         }

    //         if ($exception->getStatusCode() == 401) {
    //             return response(['error' => 'Sorry! This action is unauthorized']);
    //         }
    //     }

    //     if(auth()->guard('web')->check() && method_exists($exception, 'getStatusCode')) {

    //         if ($exception->getStatusCode() == 419) {
    //             return redirect('/login')->with('error', "Session has expired. Please login again");
    //         }
    //     }

    //     // return redirect()->back()->with('error', $exception->getMessage());
        
    //     return parent::render($request, $exception);
    // }

     public function render($request, Throwable $exception)
    {   
       //dd($exception->message == 'Unauthenticated');
       // check api guard for unauthorized responses
        // if($exception->getMessage() == 'Unauthenticated.') {    
        //         return redirect('/login')->with('error', "Session has expired. Please login again");
        // }

        //return redirect()->back()->with('error', $exception->getMessage());
       // return redirect()->back()->with('error', 'An Error has Occurred while Processing request. Please Check Logs.');
         return parent::render($request, $exception);
    }
}
