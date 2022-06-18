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
        'application_date',
        'status',
        'feedback_questionnaire_id',
        'review_questionnaire_id'
    ];

    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id', 'id');
    }

    public function questionnaire()
    {
        return $this->belongsTo('App\Models\Questionnaire', 'questionnaire_id', 'id');
    }

    public function review()
    {
        return $this->belongsTo('App\Models\Questionnaire', 'review_questionnaire_id', 'id');
    }

    public function feedback()
    {
        return $this->belongsTo('App\Models\Questionnaire', 'feedback_questionnaire_id', 'id');
    }

    public function coach()
    {
        return $this->belongsTo('App\Models\User', 'coach_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo('App\Models\User', 'teacher_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany('App\Models\Note', 'questionnaire_application_id', 'id');
    }
}
