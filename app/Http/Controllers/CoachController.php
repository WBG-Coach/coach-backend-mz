<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\School;


class CoachController extends Controller
{
    public function questionnaireSchools(Request $request)
    {
        return School::whereRaw("id in (select qa.school_id from questionnaire_applications qa where qa.coach_id = ".$request->coach_id.")")->get();
    }

}
