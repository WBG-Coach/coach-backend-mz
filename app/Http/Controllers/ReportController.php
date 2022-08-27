<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Reports\CompetenceEvolutionRequest;
use App\Http\Requests\Reports\DashboardRequest;

use App\Models\Competence;
use App\Models\QuestionnaireApplication;
use App\Models\School;
use App\Models\User;
use App\Models\Profile;
use App\Models\Feedback;

class ReportController extends Controller
{
    public function competenceEvolutions(CompetenceEvolutionRequest $request)
    {
        $sql = "select
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
                    and q.id = qq.question_id
                    and qq.id = a.questionnaire_question_id
                    and c.project_id = ".$request->project_id."
                    and a.option_id = o.id";

        $competencies = [];

        foreach (Competence::where('project_id', $request->project_id)->get() as $competence) {
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
    public function competenceWithFeedbacks(Request $request)
    {
        $sql = "select
                    c.title competence_title,
                    o.text selected_option
                from
                    questions q,
                    questionnaire_questions qq,
                    answers a,
                    options o,
                    competencies c
                where 
                    (
                        a.created_at >=  '".$request->start_date."'
                        and a.created_at <=  '".$request->end_date."'
                    )
                    and c.id = q.competency_id
                    and q.id = qq.question_id
                    and qq.id = a.questionnaire_question_id
                    and c.project_id = ".$request->project_id."
                    and a.option_id = o.id";

        $competencies = [];

        foreach (Competence::where('project_id', $request->project_id)->get() as $competence) {
            $competencies[$competence->title] = [];
        }

        foreach (\DB::select($sql) as $sqlResponse) {
            array_push($competencies[$sqlResponse->competence_title], $sqlResponse->selected_option);
        }

        $result = [];
        foreach ($competencies as $competence => $competenceData) {

            $total = count($competenceData);
            $yesCounter = 0;

            foreach ($competenceData as $dataValue) {
                if (strtoupper($dataValue) == 'SIM') {
                    $yesCounter++;
                }
            }

            array_push($result, [
                'name' => $competence,
                'yes' => $yesCounter,
                'no' => $total-$yesCounter,
                'total' => $total
            ]);
        }

        return $result;
    }

    public function dashboard(DashboardRequest $request)
    {
        $coachProfileId = Profile::where('name', 'COACH')->first()->id;
        $teacherProfileId = Profile::where('name', 'TEACHER')->first()->id;

        $teacherInMostSessions = QuestionnaireApplication::select('teacher_id', \DB::raw("count(teacher_id) quantity"))
        ->whereRaw("application_date >= '".$request->start_date."' and application_date <= '".$request->end_date."'")
        ->whereRaw("questionnaire_id in (select id from questionnaires q where q.project_id = ".$request->project_id.")")
        ->groupBy('teacher_id')
        ->orderBy('quantity', 'desc')
        ->first();

        $coachInMostSessions = QuestionnaireApplication::select('coach_id', \DB::raw("count(coach_id) quantity"))
        ->whereRaw("application_date >= '".$request->start_date."' and application_date <= '".$request->end_date."'")
        ->whereRaw("questionnaire_id in (select id from questionnaires q where q.project_id = ".$request->project_id.")")
        ->groupBy('coach_id')
        ->orderBy('quantity', 'desc')
        ->first();

        $competencies = [];
        foreach(Competence::where('project_id', $request->project_id)->get() as $competence) {
            array_push($competencies, [
                'name' => $competence->title,
                'quantity' => Feedback::whereRaw("created_at >= '".$request->start_date." 00:00:00' and created_at <= '".$request->end_date." 23:59:59'")->where('competence_id', $competence->id)->count()
            ]);
        }

        return [
            "questionnaire_applications_qty" => QuestionnaireApplication::whereRaw("questionnaire_id in (select id from questionnaires q where q.project_id = ".$request->project_id.")")->whereRaw("application_date >= '".$request->start_date."' and application_date <= '".$request->end_date."'")->count(),
            "schools_qty" => School::where('project_id', $request->project_id)->whereRaw("created_at >= '".$request->start_date." 00:00:00' and created_at <= '".$request->end_date." 23:59:59'")->count(),
            "coaches_qty" => User::where('project_id', $request->project_id)->where('profile_id', $coachProfileId)->whereRaw("created_at >= '".$request->start_date." 00:00:00' and created_at <= '".$request->end_date." 23:59:59'")->count(),
            "teachers_qty" => User::where('project_id', $request->project_id)->where('profile_id', $teacherProfileId)->whereRaw("created_at >= '".$request->start_date." 00:00:00' and created_at <= '".$request->end_date." 23:59:59'")->count(),
            "teacher_most_sessions" => [
                'user' => $teacherInMostSessions?User::find($teacherInMostSessions->teacher_id):null,
                'quantity' => $teacherInMostSessions?$teacherInMostSessions->quantity:0
            ],
            "coach_most_sessions" => [
               'user' => $coachInMostSessions?User::find($coachInMostSessions->coach_id):null,
               'quantity' => $coachInMostSessions?$coachInMostSessions->quantity:0
            ],
            "competencies" => $competencies
        ];
    }

}
