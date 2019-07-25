<?php

namespace App;

use App\Filters\AnswerFilter;
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function option()
    {
        return $this->belongsTo('App\Option', 'option_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function optionText()
    {
        return $this->belongsTo('App\Option', 'option_id')->select('text');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function voter()
    {
        return $this->belongsTo('App\Option', 'voter_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo('App\Question', 'question_id');
    }

    public function scopeBetweenDates($query,$dateArray){
        return $query->whereBetween('created_at', $dateArray);
    }
}
