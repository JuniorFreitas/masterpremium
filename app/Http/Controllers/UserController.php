<?php

namespace App\Http\Controllers;

use App\Models\Papel;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listaDePapeis = Papel::all();
        return view('g.usuarios.usuarios.index', compact('listaDePapeis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('usuarios_insert');
        $dados = $request->input();
        $dados['ativo'] = $dados['ativo'] == 'true' ? true : false;


        $dadosValidados = \Validator::make($dados, [
            'nome' => 'required|min:3',
            'login' => 'unique:users,login',
            'password' => 'required|confirmed|min:3',
            'grupo_id' => 'required|numeric',
            'ativo' => 'required|boolean',
        ]);
        if ($dadosValidados->fails()) { // se o array de erros contem 1 ou mais erros..
            return response()->json([
                'msg' => 'Erro ao cadastrar usuário',
                'erros' => $dadosValidados->errors()
            ], 400);
        } else {

            $dados['tipo'] = Papel::find($dados['grupo_id'])->nome;
            $dados['password'] = bcrypt($dados['password']);
            $dados['cadastrou'] = auth()->id();

            User::create($dados);
            return response()->json([], 201);
        }
    }


    public function show(User $user)
    {
        //
    }


    public function edit(User $usuario)
    {
        $this->authorize('usuarios_update');
        $usuario->load('papel:id,nome');
        return $usuario;
    }


    public function update(Request $request, User $usuario)
    {
        $this->authorize('usuarios_update');
        $dados = $request->input();
        $dados['ativo'] = $dados['ativo'] == 'true' ? true : false;

        // Validacao para ajax sem dar erro de HTTP (402)
        $dadosValidados = \Validator::make($dados, [
            'nome' => 'required|min:3',
            'login' => 'unique:users,login,' . $usuario->id,
            'password' => 'required_if:alterarSenha,true|confirmed|min:3',
            'grupo_id' => 'required|numeric',
            'ativo' => 'required|boolean',
        ]);
        if ($dadosValidados->fails()) { // se o array de erros contem 1 ou mais erros..
            return response()->json([
                'msg' => 'Erro ao atualizar os dados do usuário',
                'erros' => $dadosValidados->errors()
            ], 400);
        } else {
            if ($request->has('alterarSenha')) {
                $dados['password'] = bcrypt($dados['password']);
            }
            $usuario->update($dados);
            return response()->json([], 201);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $usuario)
    {
        $this->authorize('usuarios_delete');
        $usuario->delete();
    }


    public function atualizar(Request $request)
    {
        //$this->authorize('encargos');
        $porPagina = $request->get('porPagina');
        $busca = false;

        if ($request->has('campoBusca')) {

            $busca = $request->get('campoBusca');

            if (intval($busca) > 0) { // se encontrar um numero
                $resultado = User::whereTipo(User::$ADMINISTRADOR)->where('id', '=', intval($busca))
                    ->orderBy('nome');

            } else {
                $resultado = User::whereTipo(User::$ADMINISTRADOR)
                    ->where('nome', 'like', '%' . $busca . '%')->orderBy('nome');
            }
        } else {
            $resultado = User::orderBy('nome'); // senao busca tudo
        }

        $resultado = $resultado->with('papel:id,nome')->paginate($porPagina);
        return response()->json([
            'atual' => $resultado->currentPage(),
            'ultima' => $resultado->lastPage(),
            'total' => $resultado->total(),
            'dados' => $resultado->items()
        ]);

    }
}
