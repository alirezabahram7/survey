<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['poll_id','parent_id', 'description','dependant_option_id', 'category_id','answer_type_id', 'text', 'is_active', 'is_required', 'position'];

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
   public function optionsText()
    {
        return $this->hasMany('App\Option', 'question_id')->select('text');
    }

    public function answerType()
    {
        return $this->belongsTo('App\AnswerType','answer_type_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Poll','parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Question','parent_id');
    }

    public function dependantOption()
    {
        return $this->belongsTo('App\Option','dependant_option_id');
    }

    public function Category(){
        return $this->belongsTo('App\Category','category_id');
    }

    public function answers(){
        return $this->hasMany('App\Answer');
    }

    /**
     * @param $query
     * @param $filters
     * @return mixed
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);

    }

    public function scopeAdjectives($query) {
        return $query->whereIn('answer_type_id', [AnswerType::ADJECTIVE, AnswerType::MIXED,AnswerType::SCORING]);
    }
}
