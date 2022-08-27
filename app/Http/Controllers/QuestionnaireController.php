<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Questionnaire;

class QuestionnaireController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return Questionnaire::find($request->id);
        }

        $search = Questionnaire::select('*');
        if($request->title) {
            $search->whereRaw("title like '%".$request->title."%'");
        }
        if($request->type) {
            $search->whereRaw("type like '%".$request->type."%'");
        }
        if($request->project_id) {
            $search->where("project_id", $request->project_id);
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
            $application = new Questionnaire();
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

            $application = Questionnaire::find($request->id);
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
            
            Questionnaire::destroy($id);

            \DB::commit();
            return $id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
