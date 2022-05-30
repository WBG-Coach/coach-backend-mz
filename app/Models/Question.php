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
        'scale_id'
    ];

    public function competence()
    {
        return $this->belongsTo('App\Models\Competence', 'competency_id', 'id');
    }

    public function scale()
    {
        return $this->belongsTo('App\Models\Scale', 'scale_id', 'id');
    }
}
