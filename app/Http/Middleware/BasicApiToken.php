<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class BasicApiToken {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 *
	 * @return mixed
	 */
	public function handle( $request, Closure $next ) {
		$apiKey = $request->header( 'x-api-key' );

		if ( $apiKey ) {
			$op = User::whereApiKey( $apiKey )->first();
		}

		if ( ! $apiKey || ! $op ) {
			throw  new UnauthorizedHttpException( '', 'access denied' );
		}
        $request->merge([
            'app_id' => $op->app_id
        ]);
		return $next( $request );
	}
}
