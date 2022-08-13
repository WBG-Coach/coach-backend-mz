<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Competence;

class ReportController extends Controller
{
    public function competenceEvolutions(Request $request)
    {
        $sql = "select
                    c.id competence_id,
                    c.title competence_title,
                    o.text selected_option,
                    month(a.created_at) month
                from
                    questions q,
                    questionnaire_questions qq,
                    answers a,
                    options o,
                    competencies c
                where
                    c.id = q.competency_id
                    and a.option_id = o.id
                    and qq.id = a.questionnaire_question_id
                    and q.id = qq.question_id";

        $competencies = [];

        foreach (Competence::all() as $competence) {
            $competencies[$competence->title] = [[],[],[],[],[],[],[],[],[],[],[],[]];
        }

        foreach (\DB::select($sql) as $sqlResponse) {
            array_push($competencies[$sqlResponse->competence_title][$sqlResponse->month - 1], $sqlResponse->selected_option);
        }

        return $competencies;
        
    }

}
