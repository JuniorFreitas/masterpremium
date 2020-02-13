<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AlterarSenhaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('g.usuarios.alterar-senha.index');
    }


    public function update(Request $request)
    {
        $this->authorize('alterar-senha');

        $dadosValidados = \Validator::make($request->only('password', 'password_confirmation'), [
            'password' => 'confirmed|min:3',
        ]);
        if ($dadosValidados->fails()) { // se o array de erros contem 1 ou mais erros..
            return response()->json([
                'msg' => 'Erro ao atualizar a senha',
                'erros' => $dadosValidados->errors()
            ], 400);
        } else {

            $usuario = auth()->user();
            $usuario->password = bcrypt($request->password);
            $usuario->save();
            return response()->json([], 201);
        }
    }
}
