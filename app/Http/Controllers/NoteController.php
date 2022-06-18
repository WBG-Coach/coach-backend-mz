<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Note;

class NoteController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return Note::find($request->id);
        }

        $search = Note::select('*');
        if($request->answer_id) {
            $search->where('answer_id', $request->answer_id);
        }
        if($request->text) {
            $search->whereRaw("text like '%".$request->text."%'");
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
            $note = new Note();
            $note->fill($request->all());
            $note->save();

            \DB::commit();
            return $note->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function update(Request $request)
    {
        \DB::beginTransaction();

        try {

            $note = Note::find($request->id);
            $note->fill($request->all());
            $note->update();

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
            
            Note::destroy($id);

            \DB::commit();
            return $id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
