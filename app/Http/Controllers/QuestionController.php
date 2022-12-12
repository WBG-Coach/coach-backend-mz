<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Question;
use App\Models\QuestionnaireQuestion;

class QuestionController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return Question::find($request->id);
        }

        $search = Question::select('*');
        if($request->competency_id) {
            $search->where("competency_id", $request->competency_id);
        }
        if($request->text) {
            $search->whereRaw("text like '%".$request->text."%'");
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
            $question = new Question();
            $question->fill($request->all());
            $question->save();

            if ($request->questionnaire_id) {
                $questionnaireQuestion = new QuestionnaireQuestion();
                $questionnaireQuestion->question_id = $question->id;
                $questionnaireQuestion->questionnaire_id = $request->questionnaire_id;
                $questionnaireQuestion->save();
            }

            \DB::commit();
            return $question->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function update(Request $request)
    {
        \DB::beginTransaction();

        try {

            $question = Question::find($request->id);
            $question->fill($request->all());
            $question->update();

            \DB::commit();
            return $request->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function delete($id)
    {
        \DB::beginTransaction();

        try {
            
            Question::destroy($id);

            \DB::commit();
            return $id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
