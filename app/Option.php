<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $guarded = ['id'];

    public function question()
    {
        return $this->belongsTo('App\Question', 'question_id');
    }

}
