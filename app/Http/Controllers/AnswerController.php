<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Answers\StoreRequest;

use App\Models\Answer;
use App\Models\AnswerFile;
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
            if ($request->questionnaire_application_id && $request->answers) {
                $application = QuestionnaireApplication::find($request->questionnaire_application_id);
                if ($application->status == 'PENDING_RESPONSE') {
                    foreach ($request->answers as $answer) {
                        $model = new Answer();
                        $model->fill($answer);
                        $model->questionnaire_application_id = $request->questionnaire_application_id;
                        $model->save();

                        if (isset($answer['files'])) {

                            foreach ($answer['files'] as $answerFile) {
                                $file = new AnswerFile();
                                $file->answer_id = $model->id;
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
            return 'true';
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

}
