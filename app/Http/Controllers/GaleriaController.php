<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Galeria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GaleriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('g.site.galeria.index');
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
        $this->authorize('galeria_site_insert');

        $dados = $request->input();
        $dados['ativo'] = $dados['ativo'] == 'true' ? true : false;
        $dados['descricao'] = '';
        // Fotos

        $dadosValidados = \Validator::make($dados, [
            'titulo' => 'required|min:1|unique:galerias,titulo',
            'ativo' => 'required',
        ]);

        if ($dadosValidados->fails()) { // se o array de erros contem 1 ou mais erros..
            return response()->json([
                'msg' => 'Erro ao cadastrar Galeria',
                'erros' => $dadosValidados->errors()
            ], 400);
        }
        try {

            DB::beginTransaction();

            $galeria = Galeria::create($dados);

            if (isset($dados['fotos'])) {
                foreach ($dados['fotos'] as $index => $foto) {
                    $arquivo = Arquivo::whereChave($foto['chave'])->whereId($foto['id'])->first();
                    if ($arquivo) {
                        $arquivo->temporario = false;
                        $arquivo->chave = '';
                        $arquivo->save();
                        $galeria->Fotos()->attach($arquivo->id, ['ordem' => $index]);
                    }
                }
            }

            DB::commit();
            return response()->json([], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'msg' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Galeria $galeria
     * @return \Illuminate\Http\Response
     */
    public function show(Galeria $galeria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Galeria $galeria
     * @return \Illuminate\Http\Response
     */
    public function edit(Galeria $galeria)
    {
        return $galeria->load('Fotos');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Galeria $galeria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Galeria $galeria)
    {
        $this->authorize('galeria_site_update');

        $dados = $request->input();
        $dados['ativo'] = $dados['ativo'] == 'true' ? true : false;
        // Fotos

        $dadosValidados = \Validator::make($dados, [
            'titulo' => 'required|min:1|unique:galerias,titulo,' . $galeria->id,
            'ativo' => 'required',
        ]);

        if ($dadosValidados->fails()) { // se o array de erros contem 1 ou mais erros..
            return response()->json([
                'msg' => 'Erro ao atualizar Galeria',
                'erros' => $dadosValidados->errors()
            ], 400);
        }
        try {

            DB::beginTransaction();

            // Fotos
            if (isset($dados['fotosDel'])) {
                foreach ($dados['fotosDel'] as $id_foto) {
                    $arquivo = Arquivo::find($id_foto);
                    $arquivo->excluir();
                }
            }

            if (isset($dados['fotos'])) {
                foreach ($dados['fotos'] as $index => $foto) {
//                    dd($foto);
                    //Se nao tem chave, entao é uma foto que já estava cadastrada no banco
                    if ($foto['chave'] == null) {
                        Arquivo::whereId($foto['id'])->update([
                            'nome' => $foto['nome'],
                        ]);
                        $galeria->Fotos()->updateExistingPivot($foto['id'], ['ordem' => $index]);
                    } else {
                        $arquivo = Arquivo::whereChave($foto['chave'])->whereId($foto['id'])->first();
                        if ($arquivo) {
                            $arquivo->temporario = false;
                            $arquivo->chave = '';
                            $arquivo->save();
                            $galeria->Fotos()->attach($arquivo->id, ['ordem' => $index]);
                        }
                    }

                }
            }

            DB::commit();
            return response()->json([], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'msg' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Galeria $galeria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Galeria $galeria)
    {
        if ($galeria->Fotos->count() > 0) {
            foreach ($galeria->Fotos as $anexo) {
                $anexo->excluir($anexo->id);
            }
        }
        $galeria->delete();
        return response()->json([], 201);
    }

    public function atualizar(Request $request)
    {
        $this->authorize('galeria_site');
        $resultado = Galeria::withCount('Fotos');

        if ($request->filled('campoBusca')) {
            $resultado->where('nome', 'like', '%' . $request->campoBusca . '%');
        }

        $resultado = $resultado->orderByDesc('created_at')->paginate(50);

        return response()->json([
            'atual' => $resultado->currentPage(),
            'ultima' => $resultado->lastPage(),
            'total' => $resultado->total(),
            'dados' => $resultado->items()
        ]);
    }

    // Fotos -------------------------------------------------
    public function uploadFotos(Request $request)
    {
        $this->authorize('galeria_site_insert');
        if ($request->file('arquivo')->isValid()) {
            $arquivo = Arquivo::gravaArquivo($request, 'arquivo', Arquivo::DISCO_PUBLICO);
            return response()->json($arquivo, 201);
        } else {
            return response()->json([
                'msg' => "O upload da foto falhou",
                'erros' => []
            ], 400);
        }

    }

    public function fotoDelete(Request $request, $arquivo)
    {
        $this->authorize('galeria_site_delete');
        //Se esta apagando realmente um foto_imovel
        $disco = Arquivo::nomeDisco($arquivo);
        $permitidos = [
            Arquivo::DISCO_PUBLICO,
        ];
        if (in_array($disco, $permitidos) == false) {
            return response("", 404);
        }

        //Apagar
        $model = Arquivo::findByArquivo($arquivo);
        if ($model && $model->temporario) {
            Arquivo::apagar($arquivo);
            return response("", 200);

        } else {
            return response("Não foi possível apagar a foto", 400);
        }
    }

}
