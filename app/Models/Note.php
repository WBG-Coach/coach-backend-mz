<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'questionnaire_question_id'
    ];

    public function questionnaireQuestion()
    {
        return $this->belongsTo('App\Models\QuestionnaireQuestion', 'questionnaire_question_id', 'id');
    }
}
