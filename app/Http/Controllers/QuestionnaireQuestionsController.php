<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Questionnaire;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireApplication;

use App\Models\Answer;

class QuestionnaireQuestionsController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return QuestionnaireQuestion::find($request->id);
        }

        $search = QuestionnaireQuestion::with('question.competence')->select('*');
        if($request->questionnaire_application_id || $request->questionnaire_id) {

            if ($request->questionnaire_id) {
                $questionnaireQuestions = QuestionnaireQuestion::with('question.competence', 'question.options')->select('*')->where('questionnaire_id', $request->questionnaire_id)->orderBy('order')->get();
                $questionnaire = Questionnaire::find($request->questionnaire_id);
            } else {
                $questionnaireApplication = QuestionnaireApplication::find($request->questionnaire_application_id);
                $questionnaire = Questionnaire::find($questionnaireApplication->questionnaire_id);
                $questionnaireQuestions = QuestionnaireQuestion::with('question.competence', 'question.options')->select('*')->where('questionnaire_id', $questionnaireApplication->questionnaire_id)->orderBy('order')->get();
            }

            if ($request->feedback) {
                $questionnaireQuestions = QuestionnaireQuestion::with('question.competence', 'question.options')->select('*')->where('questionnaire_id', $questionnaireApplication->feedback_questionnaire_id)->orderBy('order')->get();
            }

            foreach ($questionnaireQuestions as $questionnaireQuestion) {
                if ($request->teacher_id) {
                    $answers = Answer::with('option.question.competence', 'questionnaireApplication')->where('questionnaire_question_id', $questionnaireQuestion['id'])->whereRaw("questionnaire_application_id in (select qa.id from questionnaire_applications qa where qa.teacher_id = ".$request->teacher_id.")")->get();
                } else {
                    $answers = [];
                }
                $questionnaireQuestion['question']['last_answers'] = $answers;
            }

            return [
                'questions' => $questionnaireQuestions,
                'questionnaire' => $questionnaire
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
