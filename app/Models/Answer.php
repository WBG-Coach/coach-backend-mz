<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'answers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'questionnaire_application_id',
        'questionnaire_question_id',
        'notes',
        'option_id'
    ];

    public function question()
    {
        return $this->belongsTo('App\Models\QuestionnaireQuestion', 'questionnaire_question_id', 'id');
    }

    public function option()
    {
        return $this->belongsTo('App\Models\Option', 'option_id', 'id');
    }

}
