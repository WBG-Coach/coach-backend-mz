<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function pesquisar(Request $request)
    {
        if ($request->id) {
            return Usuario::find($request->id);
        }

        $busca = Usuario::select('*');
        if ($request->ativos) {
            $busca->where('deletado', 'N');
        }
        if ($request->deletados) {
            $busca->where('deletado', 'S');
        }
        if ($request->nome) {
            $busca->whereRaw("nome like '%".$request->nome."%'");
        }
        if ($request->username) {
            $busca->whereRaw("username like '%".$request->username."%'");
        }
        return $busca->get();
    }

    public function salvar(Request $request)
    {
        \DB::beginTransaction();

        try {

            $usuario = new Usuario();
            $usuario->fill($request->all());
            $usuario->password = bcrypt($request->password);
            $usuario->save();

            \DB::commit();
            return $usuario->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function atualizar(Request $request)
    {
        \DB::beginTransaction();

        try {

            $usuario = Usuario::find($request->id);
            $usuario->fill($request->all());
            if ($request->password) {
                $usuario->password = bcrypt($request->password);
            }
            $usuario->update();

            \DB::commit();
            return "true";
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function deletar($id)
    {
        \DB::beginTransaction();

        try {
            Usuario::find($id)->update(['deletado' => 'S']);

            \DB::commit();
            return 'true';
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
