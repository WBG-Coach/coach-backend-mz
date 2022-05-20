<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Profile;
use App\Models\Permission;

class ProfileController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return Profile::find($request->id);
        }

        $search = Profile::select('*');
        if ($request->name) {
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
            $profile = new Profile();
            $profile->fill($request->all());
            $profile->save();

            if (!isset($request->permissions)) {
                abort(500, 'Please informe the permissions of this profile.');
            }
            foreach ($request->permissions as $permission) {
                $permission = new Permission();
                $permission->fill($permission);
                $permission->profile_id = $profile->id;
                $permission->save();
            }

            \DB::commit();
            return $profile->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function update(Request $request)
    {
        \DB::beginTransaction();

        try {

            $profile = Profile::find($request->id);
            $profile->fill($request->all());
            $profile->update();

            if (!isset($request->permissions)) {
                abort(500, 'Please informe the permissions of this profile.');
            }
            Permission::where('profile_id', $request->id)->delete();
            foreach ($request->permissions as $permission) {
                $permission = new Permission();
                $permission->fill($permission);
                $permission->profile_id = $profile->id;
                $permission->save();
            }

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
            if(User::where('profile_id', $id)->exists()) {
                abort(500, 'This profile is in use.')
            }
            
            Permission::where('profile_id', $id)->delete();
            Profile::destroy($id);

            \DB::commit();
            return $id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
