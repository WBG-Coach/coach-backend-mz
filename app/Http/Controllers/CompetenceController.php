<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Competence;

class CompetenceController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return Competence::find($request->id);
        }

        $search = Competence::select('*');
        if($request->matrix_id) {
            $search->where("matrix_id", $request->matrix_id);
        }
        if($request->project_id) {
            $search->where("project_id", $request->project_id);
        }
        if($request->title) {
            $search->whereRaw("title like '%".$request->title."%'");
        }
        if($request->subtitle) {
            $search->whereRaw("subtitle like '%".$request->subtitle."%'");
        }
        if($request->description) {
            $search->whereRaw("description like '%".$request->description."%'");
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
            $competence = new Competence();
            $competence->fill($request->all());
            $competence->save();

            \DB::commit();
            return $competence->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function update(Request $request)
    {
        \DB::beginTransaction();

        try {

            $competence = Competence::find($request->id);
            $competence->fill($request->all());
            $competence->update();

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
            
            Competence::destroy($id);

            \DB::commit();
            return $id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
