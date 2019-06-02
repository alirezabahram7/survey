<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
        $rows = App::get();
        return response()->json([
            'data' => $rows,
        ], 200);

    }

    public function show($id)
    {
        $rows = App::find($id);
        return response()->json([
            'data' => $rows,
        ], 200);

    }
}
