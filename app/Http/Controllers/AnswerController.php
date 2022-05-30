<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Answer;

class AnswerController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return Answer::find($request->id);
        }

        $search = Answer::select('*');
        if($request->questionnaire_application_id) {
            $search->where('questionnaire_application_id', $request->questionnaire_application_id);
        }
        if($request->questionnaire_question_id) {
            $search->where('questionnaire_question_id', $request->questionnaire_question_id);
        }
        if($request->scale_id) {
            $search->where('scale_id', $request->scale_id);
        }
        if($request->notes) {
            $search->whereRaw("notes like '%".$request->notes."%'")
        }
        if ($request->pagination) {
            return $search->paginate($request->pagination);
        }
        return $search->get();
    }

    public function save(Request $request)
    {
        \DB::beginTransaction();

        try {
            if ($request->questionnaire_application_id && $request->answers) {
                foreach ($request->answers as $answer) {
                    $model = new Answer();
                    $model->fill($answer);
                    $model->questionnaire_application_id = $request->questionnaire_application_id;
                    $model->save();
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
