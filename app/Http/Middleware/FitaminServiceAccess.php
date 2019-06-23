<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class FitaminServiceAccess
{

    private $_apiKey;

    public function __construct()
    {
        $this->_apiKey = md5('fitamin-survey');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('x-api-key') != $this->_apiKey) {
            throw  new UnauthorizedHttpException('', 'access denied');
        }
        return $next($request);
    }
}
