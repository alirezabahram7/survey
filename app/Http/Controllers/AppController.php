<?php

namespace App\Http\Controllers;

use App\Http\Resources\BasicCollectionResource;
use App\Http\Resources\BasicResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\App;

class AppController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        $apps = App::all();
        return response(new BasicCollectionResource($apps), 200);
    }


    /**
     * @param App $app
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show(App $app)
    {
        if (!$app) {
            throw new ModelNotFoundException('Entry doesnt Found');
        }
        return response(new BasicResource($app), 200);
    }
}
