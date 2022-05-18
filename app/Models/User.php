<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use Notifiable;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'profile_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile', 'profile_id', 'id');
    }

    public function userSchools()
    {
        return $this->hasMany('App\Models\UserSchool', 'user_id', 'id');
    }

    public function appliedQuestionnaires()
    {
        return $this->hasMany('App\Models\QuestionnaireApplication', 'coach_id', 'id');
    }

    public function receivedQuestionnaires()
    {
        return $this->hasMany('App\Models\QuestionnaireApplication', 'teacher_id', 'id');
    }
}
