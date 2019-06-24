<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $guarded = ['id'];

    public static $rules = array(
        'text' => 'required',
        'question_id' => 'required',
    );

    public function question()
    {
        return $this->belongsTo('App\Question', 'question_id');
    }

    public function answers(){
        return$this->hasMany('App\Answer');
    }

}
