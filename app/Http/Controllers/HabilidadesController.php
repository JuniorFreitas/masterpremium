<?php

namespace App\Http\Controllers;

use App\Models\Habilidade;
use Illuminate\Http\Request;

class HabilidadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('g.configuracoes.habilidades.index');
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
        $this->authorize('habilidades_insert');
        // Validacao para PHP
        /*$dadosValidados = $request->validate([
            'nome' => 'required|min:3',
        ]);*/

        // Validacao para ajax sem dar erro de HTTP (402)
        $dadosValidados = \Validator::make($request->all(), [
            'nome' => 'required|min:3',
            'descricao' => 'required|min:3'
        ]);
        if ($dadosValidados->fails()) { // se o array de erros contem 1 ou mais erros..
            return response()->json([
                'erro' => 's',
                'msg' => 'Erro ao cadastrar habilidade',
                'erros' => $dadosValidados->errors()
            ]);
        } else {

            Habilidade::create($request->all());
            return response()->json([
                'erro' => 'n'
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Habilidade $habilidade
     * @return \Illuminate\Http\Response
     */
    public function show(Habilidade $habilidade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Habilidade $habilidade
     * @return \Illuminate\Http\Response
     */
    public function edit(Habilidade $habilidade)
    {
        $this->authorize('habilidades_update');
        return $habilidade;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Habilidade $habilidade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Habilidade $habilidade)
    {
        $this->authorize('habilidades_update');

        // Validacao para ajax sem dar erro de HTTP (402)
        $dadosValidados = \Validator::make($request->all(), [
            'nome' => 'required|min:3',
            'descricao' => 'required|min:3'
        ]);
        if ($dadosValidados->fails()) { // se o array de erros contem 1 ou mais erros..
            return response()->json([
                'erro' => 's',
                'msg' => 'Erro ao atualizar a habilidade',
                'erros' => $dadosValidados->errors()
            ]);
        } else {
            $habilidade->update($request->all());
            return response()->json([
                'erro' => 'n'
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Habilidade $habilidade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Habilidade $habilidade)
    {
        $this->authorize('habilidades_delete');
        $habilidade->delete();
    }

    public function atualizar(Request $request)
    {
        //$this->authorize('habilidades');
        $porPagina = $request->get('porPagina');
        $busca = false;

        if ($request->has('campoBusca')) {

            $busca = $request->get('campoBusca');

            $resultado = Habilidade::where('nome', 'like', '%' . $busca . '%')
                ->orWhere('descricao', 'like', '%' . $busca . '%')
                ->orderBy('nome');

        } else {
            $resultado = Habilidade::orderBy('nome'); // senao busca tudo
        }

        $resultado = $resultado->paginate($porPagina);
        return response()->json([
            'atual' => $resultado->currentPage(),
            'ultima' => $resultado->lastPage(),
            'total' => $resultado->total(),
            'dados' => $resultado->items()
        ]);

    }

}
