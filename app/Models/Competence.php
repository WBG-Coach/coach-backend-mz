<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'competencies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'matrix_id'
    ];

    public function matrix()
    {
        return $this->belongsTo('App\Models\Matrix', 'matrix_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany('App\Models\Question', 'competency_id', 'id');
    }
}
