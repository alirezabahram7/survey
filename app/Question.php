<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['poll_id', 'answer_type_id', 'text', 'is_active', 'is_required', 'position'];

    public static $rules = array(
        'text' => 'required',
        'poll_id' => 'required',
        'answer_type_id' => 'required',
    );

    public function poll()
    {
        return $this->belongsTo('App\Poll', 'poll_id');
    }

    public function options()
    {
        return $this->hasMany('App\Option', 'question_id');
    }
}
