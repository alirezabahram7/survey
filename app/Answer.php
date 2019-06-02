<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'user_id',
        'app_id',
        'question_id',
        'answer',
        'option_id'
    ];

    public function option()
    {
        return $this->belongsTo('App\Option', 'option_id');
    }

    public function voter()
    {
        return $this->belongsTo('App\Option', 'voter_id');
    }

    public function question()
    {
        return $this->belongsTo('App\Question', 'question_id');
    }
}
