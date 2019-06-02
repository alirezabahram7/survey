<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function poll()
    {
        return $this->belongsTo('App\Poll', 'poll_id');
    }

    public function questions()
    {
        return $this->hasMany('App\Question', 'category_id');
    }
}
