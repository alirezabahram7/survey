<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = ['parent_id', 'title', 'description', 'max_voter', 'num_voter',
        'start_date', 'end_date', 'first_text', 'last_text', 'flg_active' ];
}
