<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireApplication extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'questionnaire_applications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'questionnaire_id',
        'coach_id',
        'teacher_id',
        'school_id',
        'status' // TODO
    ];

    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id', 'id');
    }

    public function questionnaire()
    {
        return $this->belongsTo('App\Models\Questionnaire', 'questionnaire_id', 'id');
    }

    public function coach()
    {
        return $this->belongsTo('App\Models\User', 'coach_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Models\User', 'teacher_id', 'id');
    }
}
