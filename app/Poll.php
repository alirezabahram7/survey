<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        'app_id',
        'parent_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'first_text',
        'final_text',
        'is_active'
    ];

    public static $rules = array(
        'title' => 'required',
        'app_id' => 'required'
    );

    public function questions()
    {
        return $this->hasMany('App\Question', 'poll_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Poll', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Poll', 'parent_id');
    }

    public function categories()
    {
        return $this->hasMany('App\Category', 'poll_id');
    }

}
