<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Questionnaire;
use App\Models\QuestionnaireQuestion;

class QuestionnaireQuestionsController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return QuestionnaireQuestion::find($request->id);
        }

        $search = QuestionnaireQuestion::with('question.competence')->select('*');
        if($request->questionnaire_id) {
            return [
                'questions' => QuestionnaireQuestion::with('question.competence', 'question.options')->select('*')->where('questionnaire_id', $request->questionnaire_id)->orderBy('order')->get(),
                'questionnaire' => Questionnaire::find($request->questionnaire_id)
            ];
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
            $model = new QuestionnaireQuestion();
            $model->fill($request->all());
            $model->save();

            \DB::commit();
            return $model->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function update(Request $request)
    {
        \DB::beginTransaction();

        try {

            $model = QuestionnaireQuestion::find($request->id);
            $model->fill($request->all());
            $model->update();

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
            
            QuestionnaireQuestion::destroy($id);

            \DB::commit();
            return $id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
