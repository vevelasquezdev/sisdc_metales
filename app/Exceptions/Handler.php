<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    // public function render($request, Exception $e){

        
    //     if($e instanceof NotFoundHttpException){
    //         abort(404);
    //     }
    //     return parent::render($request, $e);
    // }

    // public function render($request, Exception $e)
    // {
    //     if($this->isHttpException($e)){
    //         $code = $e->getStatusCode();
    //         if($code==404){
    //             return response()->view('errors.404');
    //         }
    //         if($code==500){
    //             return response()->view('errors.404');
    //         }
    //     }
    //     return parent::render($request, $e);
    // } 
}
