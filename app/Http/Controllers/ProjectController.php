<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;

class ProjectController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return Project::with('users', 'observationQuestionnaire', 'feedbackQuestionnaire')->find($request->id);
        }

        $search = Project::select('id', 'name'
                    ,'image_url'
                    ,'primary_color'
                    ,'country'
                    ,'is_active');

        if($request->name) {
            $search->whereRaw("name like '%".$request->name."%'");
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
            $project = new Project();
            $project->fill($request->all());
            $project->save();

            \DB::commit();
            return $project->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function update(Request $request)
    {
        \DB::beginTransaction();

        try {

            $project = Project::find($request->id);
            $project->fill($request->all());
            $project->update();

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
            
            Project::destroy($id);

            \DB::commit();
            return $id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
