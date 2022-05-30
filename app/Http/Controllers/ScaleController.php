<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Scale;

class ScaleController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return Scale::find($request->id);
        }

        $search = Scale::select('*');
        if ($request->name) {
            $search->whereRaw("name like '%".$request->name."%'");
        }
        if ($request->type) {
            $search->whereRaw("type like '%".$request->type."%'");
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
            $scale = new Scale();
            $scale->fill($request->all());
            $scale->save();

            \DB::commit();
            return $scale->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function update(Request $request)
    {
        \DB::beginTransaction();

        try {

            $scale = Scale::find($request->id);
            $scale->fill($request->all());
            $scale->update();

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
            Scale::destroy($id);

            \DB::commit();
            return $id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
