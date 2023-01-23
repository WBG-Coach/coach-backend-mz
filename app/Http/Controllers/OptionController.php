<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Option;

class OptionController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return Option::find($request->id);
        }

        $search = Option::select('*');
        if ($request->text) {
            $search->whereRaw("text like '%".$request->text."%'");
        }
        if($request->question_id) {
            $search->where("question_id", $request->question_id);
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
            $option = new Option();
            $option->fill($request->all());
            $option->save();

            \DB::commit();
            return $option->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function update(Request $request)
    {
        \DB::beginTransaction();

        try {

            $option = Option::find($request->id);
            $option->fill($request->all());
            $option->update();

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
            
            Option::destroy($id);

            \DB::commit();
            return $id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
