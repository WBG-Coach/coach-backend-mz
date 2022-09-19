<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'image_url',
        'primary_color',
        'country',
        'is_active',
        'observation_questionnaire_id', 
        'feedback_questionnaire_id'
    ];

    public function users()
    {
        return $this->hasMany('App\Models\User', 'project_id', 'id');
    }

    public function observationQuestionnaire()
    {
        return $this->belongsTo('App\Models\Questionnaire', 'observation_questionnaire_id', 'id');
    }

    public function feedbackQuestionnaire()
    {
        return $this->belongsTo('App\Models\Questionnaire', 'feedback_questionnaire_id', 'id');
    }
}
