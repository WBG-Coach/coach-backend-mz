<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feedbacks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'questionnaire_application_id',
        'answer_id',
        'competence_id'
    ];

    public function feedbackAnswers()
    {
        return $this->hasMany('App\Models\FeedbackAnswer', 'feedback_id', 'id');
    }

    public function questionnaireApplication()
    {
        return $this->belongsTo('App\Models\QuestionnaireApplication', 'questionnaire_application_id', 'id');
    }

    public function answer()
    {
        return $this->belongsTo('App\Models\Answer', 'answer_id', 'id');
    }

    public function competence()
    {
        return $this->belongsTo('App\Models\Competence', 'competence_id', 'id');
    }

}
