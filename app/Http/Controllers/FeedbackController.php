<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Feedback;
use App\Models\FeedbackAnswer;
use App\Models\Answer;
use App\Models\QuestionnaireApplication;

class FeedbackController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return Feedback::with('questionnaireApplication.teacher', 'questionnaireApplication.answers.option', 'competence', 'feedbackAnswers.questionnaireQuestion.question')->find($request->id);;
        }

        $search = Feedback::with('competence')->select('*');
        if($request->questionnaire_application_id) {
            $search->where("questionnaire_application_id", $request->questionnaire_application_id);
        }
        if($request->answer_id) {
            $search->where("answer_id", $request->answer_id);
        }
        if($request->teacher_id) {
            $search->whereRaw("questionnaire_application_id in (select qa.id from questionnaire_applications qa where qa.teacher_id = ".$request->teacher_id.")");
        }
        if ($request->competence_id) {
            $search->where("competence_id", $request->competence_id);
        }

        if ($request->count) {
            return ['quantity' => $search->count()];
        }

        return $search->get();
    }

    public function save(Request $request)
    {
        \DB::beginTransaction();

        try {
            $answer = Answer::with('question.question')->find($request->answer_id);

            $feedback = new Feedback();
            $feedback->questionnaire_application_id = $answer->questionnaire_application_id;
            $feedback->answer_id = $request->answer_id;
            $feedback->competence_id = $answer->question->question->competency_id;
            $feedback->save();

            foreach ($request->feedback_answers as $feedbackAnswer) {
                $fa = new FeedbackAnswer();
                $fa->fill($feedbackAnswer);
                $fa->feedback_id = $feedback->id;
                $fa->save();
            }

            $application = QuestionnaireApplication::find($answer->questionnaire_application_id);
            $application->status = 'PENDING_MEET';
            $application->update();

            \DB::commit();
            return $feedback->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function update(Request $request)
    {
        \DB::beginTransaction();

        try {

            $feedback = Feedback::find($request->id);
            $feedback->fill($request->all());
            $feedback->update();

            FeedbackAnswer::where('feedback_id', $request->id)->delete();

            foreach ($request->feedback_answers as $feedbackAnswer) {
                $fa = new FeedbackAnswer();
                $fa->fill($feedbackAnswer);
                $fa->save();
            }

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

            FeedbackAnswer::where('feedback_id', $id)->delete();
            Feedback::destroy($id);

            \DB::commit();
            return $id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function countByCompetence(Request $request)
    {
        # code...
    }
}
