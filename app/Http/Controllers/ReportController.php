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

    public function competences(Request $request)
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

    public function sessionsBySchool(Request $request)
    {
        $response = [];

        foreach (School::where('project_id', $request->project_id)->get() as $school) {
            $sessions = QuestionnaireApplication::with('answers.option')->whereRaw("questionnaire_id in (select id from questionnaires q where q.project_id = ".$request->project_id.")")
            ->whereRaw("application_date >= '".$request->start_date."' and application_date <= '".$request->end_date."'")
            ->where('school_id', $school['id']);

            $yesCounter = 0;
            $noCounter = 0;
            $feedbackQty = 0;
            foreach ($sessions->get() as $session) {
                foreach ($session['answers'] as $answer) {
                    if (mb_strtoupper($answer['option']['text']) == 'SIM') {
                        $yesCounter++;
                    }
                    if (mb_strtoupper($answer['option']['text']) == 'NÃO') {
                        $noCounter++;
                    }
                }

                $feedbackQty += Feedback::where('questionnaire_application_id', $session['id'])->count();
            }

            array_push($response, [
                'school' => $school,
                'sessions_qty' => $sessions->count(),
                'yes_qty' => $yesCounter,
                'no_qty' => $noCounter,
                'feedback_qty' => $feedbackQty
            ]);
        }

        return $response;
    }

    public function sessionsByCoach(Request $request)
    {
        $response = [];

        $coachProfileId = Profile::where('name', 'COACH')->first()->id;

        foreach (User::where('profile_id', $coachProfileId)->where('project_id', $request->project_id)->get() as $coach) {
            $sessions = QuestionnaireApplication::with('answers.option')->whereRaw("questionnaire_id in (select id from questionnaires q where q.project_id = ".$request->project_id.")")
            ->whereRaw("application_date >= '".$request->start_date."' and application_date <= '".$request->end_date."'")
            ->where('coach_id', $coach['id']);

            $yesCounter = 0;
            $noCounter = 0;
            $feedbackQty = 0;
            foreach ($sessions->get() as $session) {
                foreach ($session['answers'] as $answer) {
                    if (mb_strtoupper($answer['option']['text']) == 'SIM') {
                        $yesCounter++;
                    }
                    if (mb_strtoupper($answer['option']['text']) == 'NÃO') {
                        $noCounter++;
                    }
                }

                $feedbackQty += Feedback::where('questionnaire_application_id', $session['id'])->count();
            }

            array_push($response, [
                'coach' => $coach,
                'sessions_qty' => $sessions->count(),
                'yes_qty' => $yesCounter,
                'no_qty' => $noCounter,
                'feedback_qty' => $feedbackQty
            ]);
        }

        return $response;
    }

    public function sessionsByTeacher(Request $request)
    {
        $response = [];

        $teacherProfileId = Profile::where('name', 'TEACHER')->first()->id;

        foreach (User::where('profile_id', $teacherProfileId)->where('project_id', $request->project_id)->get() as $teacher) {
            $sessions = QuestionnaireApplication::with('answers.option')->whereRaw("questionnaire_id in (select id from questionnaires q where q.project_id = ".$request->project_id.")")
            ->whereRaw("application_date >= '".$request->start_date."' and application_date <= '".$request->end_date."'")
            ->where('teacher_id', $teacher['id']);

            $yesCounter = 0;
            $noCounter = 0;
            $feedbackQty = 0;
            foreach ($sessions->get() as $session) {
                foreach ($session['answers'] as $answer) {
                    if (mb_strtoupper($answer['option']['text']) == 'SIM') {
                        $yesCounter++;
                    }
                    if (mb_strtoupper($answer['option']['text']) == 'NÃO') {
                        $noCounter++;
                    }
                }

                $feedbackQty += Feedback::where('questionnaire_application_id', $session['id'])->count();
            }

            array_push($response, [
                'teacher' => $teacher,
                'sessions_qty' => $sessions->count(),
                'yes_qty' => $yesCounter,
                'no_qty' => $noCounter,
                'feedback_qty' => $feedbackQty
            ]);
        }

        return $response;
    }

    public function competencesBySchool(Request $request)
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
                where c.id = q.competency_id
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
                'total' => $total,
                'feedbacks_quantity' => Feedback::whereRaw("competence_id in (select c.id from competencies c where c.title = '".$competence."')")
                                        ->whereRaw("questionnaire_application_id in (select qa.id from questionnaire_applications qa where qa.school_id = ".$request->school_id.")")
                                        ->count()
            ]);
        }

        return $result;
    }

    public function sessionsByYear(CompetenceEvolutionRequest $request)
    {

        return [
            [
                'sessions_qty' => QuestionnaireApplication::whereRaw("questionnaire_id in (select q.id from questionnaires q where q.project_id = ".$request->project_id.")")
                                                            ->whereRaw("YEAR(application_date) = '".$request->year."'")
                                                            ->whereRaw("MONTH(application_date) = '1'")
                                                            ->count(),
                'feedback_qty' => Feedback::whereRaw("questionnaire_application_id in (select qa.id 
                                                                                            from questionnaire_applications qa 
                                                                                            where qa.questionnaire_id in (select q.id 
                                                                                                                            from questionnaires q 
                                                                                                                            where q.project_id = ".$request->project_id."
                                                                                                                        )
                                                                                            and YEAR(qa.application_date) = '".$request->year."'
                                                                                            and MONTH(qa.application_date) = '1'
                                                                                        )")->count()
            ],
            [
                'sessions_qty' => QuestionnaireApplication::whereRaw("questionnaire_id in (select q.id from questionnaires q where q.project_id = ".$request->project_id.")")
                                                            ->whereRaw("YEAR(application_date) = '".$request->year."'")
                                                            ->whereRaw("MONTH(application_date) = '2'")
                                                            ->count(),
                'feedback_qty' => Feedback::whereRaw("questionnaire_application_id in (select qa.id 
                                                                                            from questionnaire_applications qa 
                                                                                            where qa.questionnaire_id in (select q.id 
                                                                                                                            from questionnaires q 
                                                                                                                            where q.project_id = ".$request->project_id."
                                                                                                                        )
                                                                                            and YEAR(qa.application_date) = '".$request->year."'
                                                                                            and MONTH(qa.application_date) = '2'
                                                                                        )")->count()
            ],
            [
                'sessions_qty' => QuestionnaireApplication::whereRaw("questionnaire_id in (select q.id from questionnaires q where q.project_id = ".$request->project_id.")")
                                                            ->whereRaw("YEAR(application_date) = '".$request->year."'")
                                                            ->whereRaw("MONTH(application_date) = '3'")
                                                            ->count(),
                'feedback_qty' => Feedback::whereRaw("questionnaire_application_id in (select qa.id 
                                                                                            from questionnaire_applications qa 
                                                                                            where qa.questionnaire_id in (select q.id 
                                                                                                                            from questionnaires q 
                                                                                                                            where q.project_id = ".$request->project_id."
                                                                                                                        )
                                                                                            and YEAR(qa.application_date) = '".$request->year."'
                                                                                            and MONTH(qa.application_date) = '3'
                                                                                        )")->count()
            ],
            [
                'sessions_qty' => QuestionnaireApplication::whereRaw("questionnaire_id in (select q.id from questionnaires q where q.project_id = ".$request->project_id.")")
                                                            ->whereRaw("YEAR(application_date) = '".$request->year."'")
                                                            ->whereRaw("MONTH(application_date) = '4'")
                                                            ->count(),
                'feedback_qty' => Feedback::whereRaw("questionnaire_application_id in (select qa.id 
                                                                                            from questionnaire_applications qa 
                                                                                            where qa.questionnaire_id in (select q.id 
                                                                                                                            from questionnaires q 
                                                                                                                            where q.project_id = ".$request->project_id."
                                                                                                                        )
                                                                                            and YEAR(qa.application_date) = '".$request->year."'
                                                                                            and MONTH(qa.application_date) = '4'
                                                                                        )")->count()
            ],
            [
                'sessions_qty' => QuestionnaireApplication::whereRaw("questionnaire_id in (select q.id from questionnaires q where q.project_id = ".$request->project_id.")")
                                                            ->whereRaw("YEAR(application_date) = '".$request->year."'")
                                                            ->whereRaw("MONTH(application_date) = '5'")
                                                            ->count(),
                'feedback_qty' => Feedback::whereRaw("questionnaire_application_id in (select qa.id 
                                                                                            from questionnaire_applications qa 
                                                                                            where qa.questionnaire_id in (select q.id 
                                                                                                                            from questionnaires q 
                                                                                                                            where q.project_id = ".$request->project_id."
                                                                                                                        )
                                                                                            and YEAR(qa.application_date) = '".$request->year."'
                                                                                            and MONTH(qa.application_date) = '5'
                                                                                        )")->count()
            ],
            [
                'sessions_qty' => QuestionnaireApplication::whereRaw("questionnaire_id in (select q.id from questionnaires q where q.project_id = ".$request->project_id.")")
                                                            ->whereRaw("YEAR(application_date) = '".$request->year."'")
                                                            ->whereRaw("MONTH(application_date) = '6'")
                                                            ->count(),
                'feedback_qty' => Feedback::whereRaw("questionnaire_application_id in (select qa.id 
                                                                                            from questionnaire_applications qa 
                                                                                            where qa.questionnaire_id in (select q.id 
                                                                                                                            from questionnaires q 
                                                                                                                            where q.project_id = ".$request->project_id."
                                                                                                                        )
                                                                                            and YEAR(qa.application_date) = '".$request->year."'
                                                                                            and MONTH(qa.application_date) = '6'
                                                                                        )")->count()
            ],
            [
                'sessions_qty' => QuestionnaireApplication::whereRaw("questionnaire_id in (select q.id from questionnaires q where q.project_id = ".$request->project_id.")")
                                                            ->whereRaw("YEAR(application_date) = '".$request->year."'")
                                                            ->whereRaw("MONTH(application_date) = '7'")
                                                            ->count(),
                'feedback_qty' => Feedback::whereRaw("questionnaire_application_id in (select qa.id 
                                                                                            from questionnaire_applications qa 
                                                                                            where qa.questionnaire_id in (select q.id 
                                                                                                                            from questionnaires q 
                                                                                                                            where q.project_id = ".$request->project_id."
                                                                                                                        )
                                                                                            and YEAR(qa.application_date) = '".$request->year."'
                                                                                            and MONTH(qa.application_date) = '7'
                                                                                        )")->count()
            ],
            [
                'sessions_qty' => QuestionnaireApplication::whereRaw("questionnaire_id in (select q.id from questionnaires q where q.project_id = ".$request->project_id.")")
                                                            ->whereRaw("YEAR(application_date) = '".$request->year."'")
                                                            ->whereRaw("MONTH(application_date) = '8'")
                                                            ->count(),
                'feedback_qty' => Feedback::whereRaw("questionnaire_application_id in (select qa.id 
                                                                                            from questionnaire_applications qa 
                                                                                            where qa.questionnaire_id in (select q.id 
                                                                                                                            from questionnaires q 
                                                                                                                            where q.project_id = ".$request->project_id."
                                                                                                                        )
                                                                                            and YEAR(qa.application_date) = '".$request->year."'
                                                                                            and MONTH(qa.application_date) = '8'
                                                                                        )")->count()
            ],
            [
                'sessions_qty' => QuestionnaireApplication::whereRaw("questionnaire_id in (select q.id from questionnaires q where q.project_id = ".$request->project_id.")")
                                                            ->whereRaw("YEAR(application_date) = '".$request->year."'")
                                                            ->whereRaw("MONTH(application_date) = '9'")
                                                            ->count(),
                'feedback_qty' => Feedback::whereRaw("questionnaire_application_id in (select qa.id 
                                                                                            from questionnaire_applications qa 
                                                                                            where qa.questionnaire_id in (select q.id 
                                                                                                                            from questionnaires q 
                                                                                                                            where q.project_id = ".$request->project_id."
                                                                                                                        )
                                                                                            and YEAR(qa.application_date) = '".$request->year."'
                                                                                            and MONTH(qa.application_date) = '9'
                                                                                        )")->count()
            ],
            [
                'sessions_qty' => QuestionnaireApplication::whereRaw("questionnaire_id in (select q.id from questionnaires q where q.project_id = ".$request->project_id.")")
                                                            ->whereRaw("YEAR(application_date) = '".$request->year."'")
                                                            ->whereRaw("MONTH(application_date) = '10'")
                                                            ->count(),
                'feedback_qty' => Feedback::whereRaw("questionnaire_application_id in (select qa.id 
                                                                                            from questionnaire_applications qa 
                                                                                            where qa.questionnaire_id in (select q.id 
                                                                                                                            from questionnaires q 
                                                                                                                            where q.project_id = ".$request->project_id."
                                                                                                                        )
                                                                                            and YEAR(qa.application_date) = '".$request->year."'
                                                                                            and MONTH(qa.application_date) = '10'
                                                                                        )")->count()
            ],
            [
                'sessions_qty' => QuestionnaireApplication::whereRaw("questionnaire_id in (select q.id from questionnaires q where q.project_id = ".$request->project_id.")")
                                                            ->whereRaw("YEAR(application_date) = '".$request->year."'")
                                                            ->whereRaw("MONTH(application_date) = '11'")
                                                            ->count(),
                'feedback_qty' => Feedback::whereRaw("questionnaire_application_id in (select qa.id 
                                                                                            from questionnaire_applications qa 
                                                                                            where qa.questionnaire_id in (select q.id 
                                                                                                                            from questionnaires q 
                                                                                                                            where q.project_id = ".$request->project_id."
                                                                                                                        )
                                                                                            and YEAR(qa.application_date) = '".$request->year."'
                                                                                            and MONTH(qa.application_date) = '11'
                                                                                        )")->count()
            ],
            [
                'sessions_qty' => QuestionnaireApplication::whereRaw("questionnaire_id in (select q.id from questionnaires q where q.project_id = ".$request->project_id.")")
                                                            ->whereRaw("YEAR(application_date) = '".$request->year."'")
                                                            ->whereRaw("MONTH(application_date) = '12'")
                                                            ->count(),
                'feedback_qty' => Feedback::whereRaw("questionnaire_application_id in (select qa.id 
                                                                                            from questionnaire_applications qa 
                                                                                            where qa.questionnaire_id in (select q.id 
                                                                                                                            from questionnaires q 
                                                                                                                            where q.project_id = ".$request->project_id."
                                                                                                                        )
                                                                                            and YEAR(qa.application_date) = '".$request->year."'
                                                                                            and MONTH(qa.application_date) = '12'
                                                                                        )")->count()
            ]
        ];
        
    }

    public function sessionsQtyByProject(Request $request)
    {

        return [
            'sessions_qty' => QuestionnaireApplication::whereRaw("questionnaire_id in (select q.id from questionnaires q where q.project_id = ".$request->project_id.")")
                                                    -> whereRaw("(application_date >= '".$request->start_date."' and application_date <= '".$request->end_date."')")
                                                    -> count(),
            'pending_feedback_sessions_qty' => QuestionnaireApplication::whereRaw("questionnaire_id in (select q.id from questionnaires q where q.project_id = ".$request->project_id.")")
                                                    -> whereRaw("(application_date >= '".$request->start_date."' and application_date <= '".$request->end_date."')")
                                                    -> where('status', 'PENDING_FEEDBACK')
                                                    -> count()
        ];
    }

}