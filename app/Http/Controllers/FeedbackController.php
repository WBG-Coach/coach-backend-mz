<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return Feedback::find($request->id);
        }

        $search = Feedback::select('*');
        if($request->questionnaire_application_id) {
            $search->where("questionnaire_application_id", $request->questionnaire_application_id);
        }
        if($request->answer_id) {
            $search->where("answer_id", $request->answer_id);
        }
        if ($request->competence_id) {
            $search->where("competence_id", $request->competence_id);
        }
        return $search->get();
    }

    public function save(Request $request)
    {
        \DB::beginTransaction();

        try {
            $feedback = new Feedback();
            $feedback->fill($request->all());
            $feedback->save();

            foreach ($request->feedback_answers as $feedbackAnswer) {
                $fa = new FeedbackAnswer();
                $fa->fill($feedbackAnswer);
                $fa->save();
            }

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
}