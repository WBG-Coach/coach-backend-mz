<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\School;


class CoachController extends Controller
{
    // TODO: the coach_id column is the KeyToken
    
    public function questionnaireSchools(Request $request)
    {
        return School::whereRaw("id in (select qa.school_id from questionnaire_applications qa where qa.coach_id = ".$request->coach_id.")")->get();
    }

    public function questionnaireTeachers(Request $request)
    {
        $teachers = User::with('lastAnswers')->whereRaw("id in (
                select qa.teacher_id 
                  from questionnaire_applications qa 
                 where qa.coach_id = ".$request->coach_id." 
                   and qa.school_id = ".$request->school_id."
                )")
        ->get();

        return $teachers;
    }
}
