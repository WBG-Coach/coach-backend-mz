<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Reports\CompetenceEvolutionRequest;

use App\Models\Competence;

class ReportController extends Controller
{
    public function competenceEvolutions(CompetenceEvolutionRequest $request)
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
                    year(a.created_at) = '".$request->year."'
                    and c.id = q.competency_id
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

        $result = [];
        foreach ($competencies as $competence => $data) {
            $processedData = [];

            foreach ($data as $dataValues) {
                if (count($dataValues) == 0) {
                    array_push($processedData, [
                        'percentYes' => 0,
                        'percentNo' => 0,
                        'yes' => 0,
                        'no' => 0,
                        'total' => 0
                    ]);
                } else {
                    $total = count($dataValues);
                    $yesCounter = 0;
                    
                    foreach ($dataValues as $dataValue) {
                        if (strtoupper($dataValue) == 'SIM') {
                            $yesCounter++;
                        }
                    }

                    array_push($processedData, [
                        'percentYes' => $yesCounter/$total,
                        'percentNo' => ($total-$yesCounter)/$total,
                        'yes' => $yesCounter,
                        'no' => $total-$yesCounter,
                        'total' => $total
                    ]);
                }
            }

            array_push($result, [
                'name' => $competence,
                'data' => $processedData
            ]);
        }

        return $result;
        
    }

}
