<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\School;


class CoachController extends Controller
{
    public function questionnaireSchools(Request $request)
    {
        // TODO: change when access API Token is done
        return School::whereRaw("id in (select qa.school_id from questionnaire_applications qa where qa.coach_id = ".$request->coach_id.")")->get();
    }

    public function questionnaireTeachers(Request $request)
    {
        return User::whereRaw("id in (select qa.teacher_id from questionnaire_applications qa where qa.coach_id = ".$request->teacher_id.")")->get();
    }
}
