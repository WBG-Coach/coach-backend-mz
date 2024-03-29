<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Reports\CompetenceEvolutionRequest;
use App\Http\Requests\Reports\DashboardRequest;

use App\Models\Answer;
use App\Models\Competence;
use App\Models\QuestionnaireApplication;
use App\Models\School;
use App\Models\User;
use App\Models\Profile;
use App\Models\Feedback;
use App\Models\UserSchool;

class ReportController extends Controller
{
    public function competenceEvolutions(CompetenceEvolutionRequest $request)
    {
        $competencies = [];

        foreach (Competence::where('project_id', $request->project_id)->get() as $competence) {
            $monthlyData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            $months = 12;
            for ($i=0; $i < $months; $i++) {

                $startDate = date('Y-m-01', strtotime('-'.($months - $i).' month'));
                $finalDate = date('Y-m-01', strtotime('-'.($months - $i - 1).' month'));

                $feedbackQty = Feedback::where('competence_id', $competence->id)
                        ->whereRaw("(created_at >= '" .$startDate." 00:00:00' and created_at < '".$finalDate." 00:00:00')")
                        ->count();
                
                $monthlyData[$i] = ['month' => date('m/Y', strtotime('-'.($months - $i).' month')), 'qty' => $feedbackQty];
            }

            array_push($competencies, [
                'name' => $competence->subtitle,
                'data' => $monthlyData
            ]);

        }

        return $competencies;
        
    }

    public function competences(Request $request)
    {
        $sql = "select
                    c.id competence_id,
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
            $competencies[$competence->id] = [];
        }

        foreach (\DB::select($sql) as $sqlResponse) {
            array_push($competencies[$sqlResponse->competence_id], $sqlResponse->selected_option);
        }

        $result = [];
        foreach ($competencies as $competenceId => $competenceData) {

            $total = count($competenceData);
            $yesCounter = 0;

            foreach ($competenceData as $dataValue) {
                if (strtoupper($dataValue) == 'SIM') {
                    $yesCounter++;
                }
            }

            $competence = Competence::find($competenceId);

            array_push($result, [
                'name' => $competence->subtitle,
                'yes' => $yesCounter,
                'no' => $total-$yesCounter,
                'feedback_qty' => Feedback::where('competence_id', $competenceId)->whereRaw("created_at >= '".$request->start_date." 00:00:00' and created_at <= '".$request->end_date." 23:59:59'")->count(),
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
                'name' => $competence->subtitle,
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
                    if (isset($answer['option'])) {
                        if (mb_strtoupper($answer['option']['text']) == 'SIM') {
                            $yesCounter++;
                        }
                        if (mb_strtoupper($answer['option']['text']) == 'NÃO') {
                            $noCounter++;
                        }
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

	    usort($response, function ($a, $b) {return $a['sessions_qty'] < $b['sessions_qty'];});
        
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
                    if (isset($answer['option'])) {
                        if (mb_strtoupper($answer['option']['text']) == 'SIM') {
                            $yesCounter++;
                        }
                        if (mb_strtoupper($answer['option']['text']) == 'NÃO') {
                            $noCounter++;
                        }
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

	    usort($response, function ($a, $b) {return $a['sessions_qty'] < $b['sessions_qty'];});

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
                    if (isset($answer['option'])) {
                        if (mb_strtoupper($answer['option']['text']) == 'SIM') {
                            $yesCounter++;
                        }
                        if (mb_strtoupper($answer['option']['text']) == 'NÃO') {
                            $noCounter++;
                        }
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

	    usort($response, function ($a, $b) {return $a['sessions_qty'] < $b['sessions_qty'];});

        return $response;
    }

    public function competencesBySchool(Request $request)
    {

        $sql = "select
                    c.id competence_id,
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
            $competencies[$competence->id] = [];
        }

        foreach (\DB::select($sql) as $sqlResponse) {
            array_push($competencies[$sqlResponse->competence_id], $sqlResponse->selected_option);
        }

        $result = [];
        foreach ($competencies as $competenceId => $competenceData) {
            $total = count($competenceData);
            $yesCounter = 0;

            foreach ($competenceData as $dataValue) {
                if (strtoupper($dataValue) == 'SIM') {
                    $yesCounter++;
                }
            }

            $competence = Competence::find($competenceId);
            array_push($result, [
                'title' => $competence->title,
                'subtitle' => $competence->subtitle,
                'yes' => $yesCounter,
                'no' => $total-$yesCounter,
                'total' => $total,
                'feedbacks_quantity' => Feedback::where("competence_id", $competenceId)
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

    public function teacherCompetences(Request $request)
    {
        $response = ['headers' => [], 'data' => []];

        if ($request->teacher_id) {
            $teacher = User::find($request->teacher_id);
            $sessions = QuestionnaireApplication::where('teacher_id', $teacher->id)->get();

            // headers definition
            array_push($response['headers'], null);
            array_push($response['headers'], ...$sessions);

            // data definition
            foreach (Competence::where('project_id', $teacher->project_id)->get() as $competence) {
                $row = [];
                
                array_push($row, $competence);

                foreach($sessions as $session) {
                    
                    $answer = Answer::with('option')->where('questionnaire_application_id', $session->id)
                    ->whereRaw("option_id in (select o.id from options o where o.question_id in (select q.id from questions q where q.competency_id = ".$competence->id."))")
                    ->first();
                    
                    if (isset($answer->option)) {
                        // check answer response
                        if (strtoupper($answer->option->text) == 'SIM') {
                            $type = 'Y';
                        } else {
                            $type = 'N';
                        }

                        // check if answer has a feedback
                        $feedbackQty = Feedback::where('answer_id', $answer->id)->count();
                        if ($feedbackQty > 0) {
                            $type .= '_F';
                        }

                        array_push($row, ['type' => $type]);
                    } else {
                        array_push($row, null);
                    }

                }
                array_push($response['data'], $row);
            }
        }

        return $response;
    }

    public function answersByCity(Request $request)
    {
        return Answer::select('city', \DB::raw("count(id) as quantity"))->groupBy('city')->get();
    }

    public function competencesBySchoolFromYear(Request $request)
    {
        $response = [];

        foreach (School::all() as $school) {
            $row = [0,0,0,0,0,0,0,0,0,0,0,0];

            $sql = "SELECT month(a.created_at) month, 
                            year(a.created_at) year, 
                            qa.school_id, 
                            q.competency_id, 
                            count(o.text) quantity 
                        FROM questionnaire_applications qa, 
                            answers as a, 
                            options o, 
                            questions q 
                        where a.questionnaire_application_id = qa.id 
                            and a.option_id = o.id 
                            and q.id = o.question_id 
                            and upper(o.text) = 'SIM' 
                            and qa.school_id = ".$school->id."
                            and year(a.created_at) = '".$request->year."'
                            group by month(a.created_at), 
                                year(a.created_at), 
                                qa.school_id, 
                                q.competency_id";

            foreach (\DB::select($sql) as $sqlResponse) {
                $row[$sqlResponse->month - 1] = $sqlResponse->quantity;
            }

            array_push($response, [
                'school' => $school->name,
                'data' => $row
            ]);
        }

        return $response;
    }

    public function schoolsBySession(Request $request)
    {
        return [
            'schools_quantity' => School::count(),
            'schools_without_sessions' => School::whereRaw('schools.id not in (select qa.school_id from questionnaire_applications qa)')->count()
        ];
    }

    public function improvementByTeacher(Request $request)
    {
        $data = [];
        $sessionsQty = 0;
        
        $teachers = User::where('project_id', $request->project_id)
            ->whereRaw("id in (select qa.teacher_id from questionnaire_applications qa)")
            ->get();

        foreach ($teachers as $teacher) {

            $sessions = QuestionnaireApplication::where('teacher_id', $teacher->id)->get();

            $sessionsQty += count($sessions);

            foreach (Competence::where('project_id', $request->project_id)->get() as $competence) {
                $row = [];
                
                foreach($sessions as $session) {

                    $answer = Answer::with('option')->where('questionnaire_application_id', $session->id)
                    ->whereRaw("option_id in (select o.id from options o where o.question_id in (select q.id from questions q where q.competency_id = ".$competence->id."))")
                    ->first();
                    
                    if (isset($answer->option)) {
                        // check answer response
                        if (strtoupper($answer->option->text) == 'SIM') {
                            $type = 'Y';
                        } else {
                            $type = 'N';
                        }

                        // check if answer has a feedback
                        $feedbackQty = Feedback::where('answer_id', $answer->id)->count();
                        if ($feedbackQty > 0) {
                            $type .= '_F';
                        }

                        array_push($row, ['type' => $type]);
                    } else {
                        array_push($row, null);
                    }

                }
                array_push($data, $row);
            }
        }

        $improvementQty = 0;
        for ($i=0; $i < $sessionsQty; $i++) {
            foreach ($data as $competence) {
                if ((isset($competence[$i]['type']) && $competence[$i]['type'] == 'N_F') && 
                    (isset($competence[$i+1]['type']) && $competence[$i+1]['type'] == 'Y')
                ) { 
                    $improvementQty++;
                }
            }
        }

        return [
            'sessions_qty' => $sessionsQty,
            'improvement_qty' => $improvementQty
        ];
    }

    public function teachersWithPerfectLastSession(Request $request)
    {
        $data = [];
        
        $teachers = User::where('project_id', $request->project_id)
        ->whereRaw("id in (select qa.teacher_id from questionnaire_applications qa)")
        ->get();
        
        $teachersQuantity = count($teachers);
        $perfectLastSessionQty = 0;

        foreach ($teachers as $teacher) {

            $lastSession = QuestionnaireApplication::where('teacher_id', $teacher->id)
            ->orderBy('application_date', 'desc')
            ->first();

            $flagCount = true;

            // Check if any competence is negative
            foreach (Competence::where('project_id', $request->project_id)->get() as $competence) {
                $answer = Answer::with('option')->where('questionnaire_application_id', $lastSession->id)
                ->whereRaw("option_id in (select o.id from options o where o.question_id in (select q.id from questions q where q.competency_id = ".$competence->id."))")
                ->first();
                
                if (isset($answer->option)) {
                    // check answer response
                    if (strtoupper($answer->option->text) != 'SIM') {
                        $flagCount = false;
                    }
                }
            }

            if ($flagCount) {
                $perfectLastSessionQty++;
            }
        }

        return [
            'teachers_qty' => $teachersQuantity,
            'perfect_last_sessions_qty' => $perfectLastSessionQty
        ];
    }

    public function schools(Request $request)
    {
        $schools = School::select("*", 
            \DB::raw("(select count(1) from user_schools us where us.school_id = schools.id) as teachers_qty"), 
            \DB::raw("(select count(1) from questionnaire_applications qa where qa.school_id = schools.id) as sessions_qty"), 
            \DB::raw("(select count(1) from feedbacks f where f.questionnaire_application_id in (select qa.id from questionnaire_applications qa where qa.school_id = schools.id)) as feedbacks_qty"), 
            \DB::raw("(
                        (
                            select count(1) 
                                from answers a 
                                where a.questionnaire_application_id in 
                                (
                                    select qa.id 
                                        from questionnaire_applications qa 
                                        where qa.school_id = schools.id
                                ) and a.option_id in 
                                (
                                    select o.id 
                                        from options o 
                                        where upper(o.text) = 'SIM'
                                )
                        ) / (
                            select count(1) 
                                from answers a 
                                where a.questionnaire_application_id in 
                                (
                                    select qa.id 
                                        from questionnaire_applications qa 
                                        where qa.school_id = schools.id
                                )
                        )
                    ) * 100 as positive_percent")
        )
        ->where('project_id', $request->project_id)
        ->get();

        foreach ($schools as $school) {
            $coaches = \DB::select("SELECT coach_id FROM questionnaire_applications where school_id = ".$school->id." group by coach_id");
            $school->coaches_qty = count($coaches);
        }

        return $schools;
    }

}
