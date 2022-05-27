<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Matrix;

class MatrixController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return Matrix::find($request->id);
        }

        $search = Matrix::select('*');
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
            $matrix = new Matrix();
            $matrix->fill($request->all());
            $matrix->save();

            \DB::commit();
            return $matrix->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function update(Request $request)
    {
        \DB::beginTransaction();

        try {

            $matrix = Matrix::find($request->id);
            $matrix->fill($request->all());
            $matrix->update();

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
            
            Matrix::destroy($id);

            \DB::commit();
            return $id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
