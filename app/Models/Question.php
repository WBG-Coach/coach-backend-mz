<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'questions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'competency_id',
        'text',
        'type'
    ];

    public function competence()
    {
        return $this->belongsTo('App\Models\Competence', 'competency_id', 'id');
    }

    public function options()
    {
        return $this->hasMany('App\Models\Option', 'question_id', 'id');
    }

}
