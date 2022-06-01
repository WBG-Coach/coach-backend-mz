<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Schools\StoreRequest;
use App\Http\Requests\Schools\UpdateRequest;

use App\Models\School;

class SchoolController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return School::find($request->id);
        }

        $search = School::select('*');
        if ($request->name) {
            $search->whereRaw("name like '%".$request->name."%'");
        }
        if ($request->pagination) {
            return $search->paginate($request->pagination);
        }
        return $search->get();
    }

    public function save(StoreRequest $request)
    {
        \DB::beginTransaction();

        try {
            $school = new School();
            $school->fill($request->all());
            $school->save();

            \DB::commit();
            return $school->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function update(UpdateRequest $request)
    {
        \DB::beginTransaction();

        try {

            $school = School::find($request->id);
            $school->fill($request->all());
            $school->update();

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
            if(UserSchool::where('school_id', $id)->exists()) {
                abort(500, 'This school has linked users');
            }
            
            School::destroy($id);

            \DB::commit();
            return $id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
