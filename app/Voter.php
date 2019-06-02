<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voter extends Model
{
    protected $guarded = ['id'];

    public function app()
    {
        return $this->belongsTo('App\App', 'app_id');
    }
}
