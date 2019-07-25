<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnswerType extends Model
{
    //Answer Types
    const ADJECTIVE = 1;
    const RADIOBUTTON =2;
    const CHECKBOX =3;
    const MIXED = 4;
    const SCORING =5;
}
