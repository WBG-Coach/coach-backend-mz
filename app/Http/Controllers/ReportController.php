<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            $competencies[$competence->title]['1'] = [];
            $competencies[$competence->title]['2'] = [];
            $competencies[$competence->title]['3'] = [];
            $competencies[$competence->title]['4'] = [];
            $competencies[$competence->title]['5'] = [];
            $competencies[$competence->title]['6'] = [];
            $competencies[$competence->title]['7'] = [];
            $competencies[$competence->title]['8'] = [];
            $competencies[$competence->title]['9'] = [];
            $competencies[$competence->title]['10'] = [];
            $competencies[$competence->title]['11'] = [];
            $competencies[$competence->title]['12'] = [];
        }

        foreach (\DB::select($sql) as $sqlResponse) {
            array_push($competencies[$sqlResponse['competence_title']][$sqlResponse['month']], $sqlResponse['selected_option']);
        }

        return $competencies;
    }

}
