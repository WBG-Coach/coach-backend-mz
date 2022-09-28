<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\QuestionnaireApplication;
use App\Models\Answer;
use App\Models\AnswerFile;

class DocumentQuestionnaireController extends Controller
{

    public function save(Request $request)
    {
        \DB::beginTransaction();

        try {
            if ($request->questionnaire_application_id && $request->answers) {
                $application = QuestionnaireApplication::find($request->questionnaire_application_id);

                foreach ($request->answers as $answer) {
                    $model = new Answer();
                    $model->fill($answer);
                    $model->questionnaire_id = $application->doc_questionnaire_id;
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

                $application->status = 'DONE';
                $application->update();
                
            }


            \DB::commit();
            return $application;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
