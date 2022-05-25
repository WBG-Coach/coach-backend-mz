<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Permission;

class PermissionController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return Permission::find($request->id);
        }

        $search = Permission::select('*');
        if ($request->profile_id) {
            $search->where("profile_id", $request->profile_id);
        }
        if ($request->funcion_code) {
            $search->whereRaw("funcion_code like '%".$request->funcion_code."%'");
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
            $permission = new Permission();
            $permission->fill($request->all());
            $permission->save();

            \DB::commit();
            return $permission->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function update(Request $request)
    {
        \DB::beginTransaction();

        try {

            $permission = Permission::find($request->id);
            $permission->fill($request->all());
            $permission->update();

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
            
            Permission::destroy($id);

            \DB::commit();
            return $id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
