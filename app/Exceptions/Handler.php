<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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


    protected function errorResponse($message, $code){
        return response()->json(['error'=>$message, 'code'=>$code], $code);
    }


    public function render($request, Exception $exception)
    {
        //we don't want to give user all details so we give him exceptions for just some errors
        if($exception instanceof ValidationException){
            return $this->convertValidationExceptionToResponse($exception,$request);
        }

        //if model doesn't exist, if request is for table row with non-existing id
        if($exception instanceof ModelNotFoundException){
            $modelName=strtolower(class_basename($exception->getModel()));

            return $this->errorResponse('There is no '.$modelName.' with this name', 404);
        }
        //user is not authenticated
        if($exception instanceof AuthenticationException){
            return $this->unauthenticated($request, $exception);
        }

        //user is not authorized for this action
        if($exception instanceof AuthorizationException){
            return $this->errorResponse($exception->getMessage(), 403);
        }

        //page not found 404
        if($exception instanceof NotFoundHttpException){
            return $this->errorResponse('Url you have requested does not exist', 404);
        }
        //wrong method (post, get, put...) but correct url
        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse('Method not allowed for this request', 405);
        }
        //for any other exception
        if($exception instanceof HttpException){
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        //token mismatch. no message since this is most likely hacker attack
        if($exception instanceof TokenMismatchException){
            return redirect()->back()->withInput($request->input());
        }

        //if it is none of the above, we return this friendly message
        return $this->errorResponse('Error on the server. Please, try again later!', 500);

    }
    /***********************************************************************************************/

    //if user is not logged in
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if($this->isFrontend($request)){
            return redirect()->guest('home');
        }
        return $this->errorResponse('Unauthenticated', 401);
    }
    /*******************************************************************************************/

    protected function convertValidationExceptionToResponse(ValidationException $e, $request){
        $errors=$e->validator->errors()->getMessages();
        if($this->isFrontend($request)){
            //if request is ajax, return json, else return html page
            return $request->ajax() ? response()->json($errors, 422) : redirect()
                ->back()->withInput($request->input())->withErrors($errors);
        }

        return $this->errorResponse($errors, 422);
    }
    /***********************************************************************************************/

    //method that checks if request is from frontend
    protected function isFrontend($request){
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
