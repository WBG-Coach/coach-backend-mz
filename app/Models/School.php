<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'schools';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'image_url',
        'address',
        'country',
        'state',
        'city',
        'town',
        'village',
        'district',
        'external_group_id'
    ];

    public function users()
    {
        return $this->hasMany('App\Models\UserSchool', 'school_id', 'id')->whereRaw("user_schools.user_id in (select u.id from users u where u.profile_id in (select p.id from profiles p where p.name = 'TEACHER'))");
    }
    
}
