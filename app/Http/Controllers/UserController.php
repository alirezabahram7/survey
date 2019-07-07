<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use function Sodium\crypto_pwhash_scryptsalsa208sha256;

class UserController extends Controller {
	protected function login( Request $request ) {
		$clientApp = User::where( 'username', $request->input( 'username' ) )
		                 ->where( 'password', $request->input( 'password' ) )->first();
		if ( ! $clientApp ) {
			return response( 'UnAuthorized ', 401 );
		}

		//return sha1(microtime());
		return [ 'apiKey' => $clientApp->api_key ];
	}
}
