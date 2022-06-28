<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackAnswer extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feedback_answers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'feedback_id',
        'notes',
        'questionnaire_question_id'
    ];

    public function questionnaireQuestion()
    {
        return $this->belongsTo('App\Models\QuestionnaireQuestion', 'questionnaire_question_id', 'id');
    }

    public function feedback()
    {
        return $this->belongsTo('App\Models\Feedback', 'feedback_id', 'id');
    }

}
