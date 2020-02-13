<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Testemunhal;
use Illuminate\Http\Request;

class TestemunhalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('g.site.testemunhal.index');
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
        $dados = $request->input();
        $dados['ativo'] = $dados['ativo'] == 'true' ? true : false;

        $dadosValidados = \Validator::make($dados, [
            'nome' => 'required',
            'texto' => 'required',
        ]);

        if ($dadosValidados->fails()) { // se o array de erros contem 1 ou mais erros..
            return response()->json([
                'msg' => 'Erro ao cadastrar Depoimento',
                'erros' => $dadosValidados->errors()
            ], 400);

        } else {
            if (!isset($dados['anexo'])) {
                return response()->json([
                    'msg' => 'Faça o upload de uma imagem',
                    'erros' => $dadosValidados->errors()
                ], 400);
            }

            $dados['texto'] = html_entity_decode($dados['texto']);
            $dados['texto'] = strip_tags($dados['texto'], "<p><a><strong><i><ul><li><ol><table><tbody><tr><td>"); // permitir apenas essas tags

            $testemunhal = Testemunhal::create($dados);
            foreach ($dados['anexo'] as $index => $anexo) {
                $arquivo = Arquivo::whereChave($anexo['chave'])->whereId($anexo['id'])->first();
                if ($arquivo) {
                    $arquivo->temporario = false;
                    $arquivo->chave = '';
                    $arquivo->save();
                    $testemunhal->Anexo()->attach($arquivo->id);
                }
            }

            return response()->json([], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Testemunhal $testemunhal
     * @return \Illuminate\Http\Response
     */
    public function show(Testemunhal $testemunhal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Testemunhal $testemunhal
     * @return \Illuminate\Http\Response
     */
    public function edit(Testemunhal $testemunhal)
    {
        return $testemunhal->load('Anexo');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Testemunhal $testemunhal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Testemunhal $testemunhal)
    {
        $dados = $request->input();
        $dados['ativo'] = $dados['ativo'] == 'true' ? true : false;

        $dadosValidados = \Validator::make($dados, [
            'nome' => 'required',
            'texto' => 'required',
        ]);

        if ($dadosValidados->fails()) { // se o array de erros contem 1 ou mais erros..
            return response()->json([
                'msg' => 'Erro ao atualizar Depoimento',
                'erros' => $dadosValidados->errors()
            ], 400);

        } else {

            if (isset($dados['anexoDel'])) {
                foreach ($dados['anexoDel'] as $id_anexo) {
                    $arquivo = Arquivo::find($id_anexo);
                    $arquivo->excluir();
                }
            }

            if (isset($dados['anexo'])) {
                foreach ($dados['anexo'] as $index => $anexo) {
                    //Se nao tem chave, entao é uma anexo que já estava cadastrada no banco
                    if ($anexo['chave'] == null) {
                        Arquivo::whereId($anexo['id'])->update([
                            'nome' => $anexo['nome'],
                        ]);
                    } else {
                        $arquivo = Arquivo::whereChave($anexo['chave'])->whereId($anexo['id'])->first();
                        if ($arquivo) {
                            $arquivo->temporario = false;
                            $arquivo->chave = '';
                            $arquivo->save();
                            $testemunhal->Anexo()->attach($arquivo->id);
                        }
                    }

                }
            }

            $testemunhal->update($dados);
            return response()->json([], 201);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Testemunhal $testemunhal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Testemunhal $testemunhal)
    {
        if ($testemunhal->Anexo->count() > 0) {
            foreach ($testemunhal->Anexo as $anexo) {
                $anexo->excluir($anexo->id);
            }
        }
        $testemunhal->delete();
    }

    public function atualizar(Request $request)
    {
        $resultado = Testemunhal::with('Anexo');

        if ($request->filled('campoBusca')) {
            $resultado->where('titulo', 'like', '%' . $request->campoBusca . '%');
        }

        $resultado = $resultado->paginate($request->pages);

        return response()->json([
            'atual' => $resultado->currentPage(),
            'ultima' => $resultado->lastPage(),
            'total' => $resultado->total(),
            'dados' => $resultado->items()
        ]);

    }


    // Anexos-------------------------------------------------
    public function uploadAnexos(Request $request)
    {
        if ($request->file('arquivo')->isValid()) {
            $mimeType = $request->file('arquivo')->getMimeType();
            $permitidos = [
                Arquivo::MIME_JPG,
                Arquivo::MIME_JPEG,
                Arquivo::MIME_PNG,
            ];
            if (in_array($mimeType, $permitidos)) {
                $arquivo = Arquivo::gravaArquivo($request, 'arquivo', Arquivo::DISCO_PUBLICO);
                return response()->json($arquivo, 201);
            } else {
                return response()->json([
                    'msg' => "O upload do arquivo \"{$request->file('arquivo')->getClientOriginalName()}\" falhou. Permitidos apenas JPEG/JPG ou PNG.",
                    'erros' => []
                ], 400);
            }

        } else {
            return response()->json([
                'msg' => "O upload do arquivo falhou",
                'erros' => []
            ], 400);
        }

    }

    public function anexoShow(Request $request, $arquivo)
    {

        $path = Arquivo::buscaPath($arquivo);
        if ($path == false) {
            return response("", 404);
        } else {
            $conteudo = Arquivo::buscaConteudo($arquivo);
            header("Content-type: " . Arquivo::getMimeType($path));
            header('Content-Length: ' . filesize($path));
            echo $conteudo;
        }
    }

    public function anexoDelete(Request $request, $arquivo)
    {
        $disco = Arquivo::nomeDisco($arquivo);
        $permitidos = [
            Arquivo::DISCO_PUBLICO
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
            return response("Não foi possível apagar o arquivo", 400);
        }

    }

    //anexo ou foto
    public function download(Request $request, $arquivo)
    {
        //Fazer a validacao (middleware) de download para resumo-cliente , resumo-ocorrencias, aqui se nescessario...
        $disco = Arquivo::nomeDisco($arquivo);
        $permitidos = [
            Arquivo::DISCO_PUBLICO
        ];
        if (in_array($disco, $permitidos) == false) {
            return response("", 404);
        }

        $url = Arquivo::buscaPath($arquivo);
        if ($url) {
            $model = Arquivo::findByArquivo($arquivo);
            return response()->download($url, $model->nome);
        } else {
            return response("", 404);
        }
    }
}
