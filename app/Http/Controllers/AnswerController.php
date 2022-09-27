<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Answers\StoreRequest;

use App\Models\Answer;
use App\Models\AnswerFile;
use App\Models\Project;
use App\Models\QuestionnaireApplication;

class AnswerController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return Answer::with('option.question.competence', 'files')->find($request->id);
        }

        $search = Answer::with('option.question.competence', 'files')->select('*');
        if($request->questionnaire_application_id) {
            $search->where('questionnaire_application_id', $request->questionnaire_application_id);
        }
        if($request->questionnaire_question_id) {
            $search->where('questionnaire_question_id', $request->questionnaire_question_id);
        }
        if($request->notes) {
            $search->whereRaw("notes like '%".$request->notes."%'");
        }
        if ($request->pagination) {
            return $search->paginate($request->pagination);
        }
        return $search->get();
    }

    public function save(StoreRequest $request)
    {
        \DB::beginTransaction();

        try {
            if (($request->questionnaire_application_id || $request->questionnaire_application) && $request->answers) {
                if ($request->questionnaire_application_id) {
                    $application = QuestionnaireApplication::find($request->questionnaire_application_id);
                } else {
                    $application = new QuestionnaireApplication();
                    $application->coach_id = $request->questionnaire_application['coach_id'];
                    $application->teacher_id = $request->questionnaire_application['teacher_id'];
                    $application->school_id = $request->questionnaire_application['school_id'];
                    $application->application_date = $request->questionnaire_application['application_date'];
                    $application->status = 'PENDING_RESPONSE';
                    if ($request->project_id) {
                        $project = Project::find($request->project_id);
                        $application->questionnaire_id = $project->observation_questionnaire_id;
                        $application->feedback_questionnaire_id = $project->feedback_questionnaire_id;
                        $application->doc_questionnaire_id = $project->doc_questionnaire_id;
                    }
                    $application->order = QuestionnaireApplication::where('teacher_id', $application->teacher_id)->count()+1;
                    $application->save();
                }

                if ($application->status == 'PENDING_RESPONSE') {
                    foreach ($request->answers as $answer) {
                        $model = new Answer();
                        $model->fill($answer);
                        $model->questionnaire_application_id = $application->id;
                        $model->save();

                        if (isset($answer['files'])) {

                            foreach ($answer['files'] as $answerFile) {
                                $file = new AnswerFile();
                                $file->answer_id = $model->id;
                                $file->name = $answerFile['name'];
                                $file->url = $answerFile['url'];
                                $file->save();
                            }

                        }
                    }

                    $application->status = 'PENDING_FEEDBACK';
                    $application->update();
                }
                
            }


            \DB::commit();
            return $application->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

}
