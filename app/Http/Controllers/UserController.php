<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Users\StoreRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Models\User;

class UserController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return User::find($request->id);
        }

        $search = User::select('*');
        if ($request->name) {
            $search->whereRaw("name like '%".$request->name."%'");
        }
        if ($request->email) {
            $search->whereRaw("email like '%".$request->email."%'");
        }
        if ($request->profile_id) {
            $search->where("profile_id", $request->profile_id);
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
            $user = new User();
            $user->fill($request->all());
            $user->password = bcrypt($request->password);
            $user->save();

            \DB::commit();
            return $user->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function update(UpdateRequest $request)
    {
        \DB::beginTransaction();

        try {

            $user = User::find($request->id);
            $user->fill($request->all());
            if ($request->password) {
                $user->password = bcrypt($request->password);
            }
            $user->update();

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
            User::find($id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
            \DB::commit();
            return $id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
