<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
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
        'competency_id'
    ];

    public function competence()
    {
        return $this->belongsTo('App\Models\Competence', 'competency_id', 'id');
    }
}
