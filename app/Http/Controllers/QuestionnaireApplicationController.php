<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\QuestionnaireApplication;

class QuestionnaireApplicationController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return QuestionnaireApplication::find($request->id);
        }

        $search = QuestionnaireApplication::with('questionnaire', 'feedback', 'review')->select('*');
        if($request->questionnaire_id) {
            $search->where('questionnaire_id', $request->questionnaire_id);
        }
        if($request->coach_id) {
            $search->where('coach_id', $request->coach_id);
        }
        if($request->teacher_id) {
            $search->where('teacher_id', $request->teacher_id);
        }
        if($request->school_id) {
            $search->where('school_id', $request->school_id);
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
            $application = new QuestionnaireApplication();
            $application->fill($request->all());
            $application->save();

            \DB::commit();
            return $application->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function update(Request $request)
    {
        \DB::beginTransaction();

        try {

            $application = QuestionnaireApplication::find($request->id);
            $application->fill($request->all());
            $application->update();

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
            
            QuestionnaireApplication::destroy($id);

            \DB::commit();
            return $id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
