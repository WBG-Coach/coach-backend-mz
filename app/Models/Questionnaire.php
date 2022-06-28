<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'questionnaires';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'type'
    ];

    public function applications()
    {
        return $this->hasMany('App\Models\QuestionnaireApplication', 'questionnaire_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany('App\Models\QuestionnaireQuestion', 'questionnaire_id', 'id');
    }
}
