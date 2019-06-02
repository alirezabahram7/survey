<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{

    public function polls()
    {
        return $this->belongsToMany('App\Poll', 'app_poll');
    }

    public function voters()
    {
        return $this->hasMany('App\Voter', 'app_id');
    }
}
