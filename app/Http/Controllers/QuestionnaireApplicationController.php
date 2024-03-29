<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\QuestionnaireApplication;
use App\Models\Project;

class QuestionnaireApplicationController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return QuestionnaireApplication::with('questionnaire', 'feedbackQuestionnaire', 'docQuestionnaire', 'teacher', 'notes', 'coach', 'school')->find($request->id);
        }

        $search = QuestionnaireApplication::with('questionnaire', 'feedbackQuestionnaire', 'docQuestionnaire', 'teacher', 'notes', 'coach', 'school')->select('*');
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
        if($request->teacher_project_id) {
            $search->whereRaw("teacher_id in (select u.id from users u where u.project_id = ".$request->teacher_project_id.")");
        }
        if($request->coach_project_id) {
            $search->whereRaw("coach_id in (select u.id from users u where u.project_id = ".$request->coach_project_id.")");
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
            if ($request->project_id) {
                $project = Project::find($request->project_id);
                $application->questionnaire_id = $project->observation_questionnaire_id;
                $application->feedback_questionnaire_id = $project->feedback_questionnaire_id;
                $application->doc_questionnaire_id = $project->doc_questionnaire_id;
            }
            $application->order = QuestionnaireApplication::where('teacher_id', $request->teacher_id)->count()+1;
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
