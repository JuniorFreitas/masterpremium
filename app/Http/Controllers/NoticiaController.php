<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\CategoriasNoticia;
use App\Models\Noticia;
use Illuminate\Http\Request;
use MasterTag\Html2Text;

class NoticiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('g.site.noticia.index');
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
            'titulo' => 'required',
            'categoria_id' => 'required',
            'conteudo' => 'required',
        ]);
        if ($dadosValidados->fails()) { // se o array de erros contem 1 ou mais erros..
            return response()->json([
                'msg' => 'Erro ao cadastrar Artigo',
                'erros' => $dadosValidados->errors()
            ], 400);

        } else {
            if (!isset($dados['anexo'])) {
                return response()->json([
                    'msg' => 'Faça o upload de uma imagem',
                    'erros' => $dadosValidados->errors()
                ], 400);
            }

            if ($request->filled('descricao')) {
                $dados['descricao'] = (new Html2Text($dados['descricao']))->getText();
            }

            $dados['conteudo'] = str_replace('../responsive_filemanager/', asset('') . "responsive_filemanager/", $dados['conteudo']);
            $dados['conteudo'] = str_replace('<img src="' . asset(''), '<img class="img-fluid d-block mx-auto" src="' . asset(''), $dados['conteudo']);


            $noticia = Noticia::create($dados);
            foreach ($dados['anexo'] as $index => $anexo) {
                $arquivo = Arquivo::whereChave($anexo['chave'])->whereId($anexo['id'])->first();
                if ($arquivo) {
                    $arquivo->temporario = false;
                    $arquivo->chave = '';
                    $arquivo->save();
                    $noticia->Anexo()->attach($arquivo->id);
                }
            }

            return response()->json([], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Noticia $noticium
     * @return \Illuminate\Http\Response
     */
    public function show(Noticia $noticium)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Noticia $noticium
     * @return \Illuminate\Http\Response
     */
    public function edit(Noticia $noticium)
    {
        return $noticium->load('Anexo', 'Categoria');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Noticia $noticium
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Noticia $noticium)
    {
        $dados = $request->input();
        $dados['ativo'] = $dados['ativo'] == 'true' ? true : false;

        $dadosValidados = \Validator::make($dados, [
            'titulo' => 'required',
            'categoria_id' => 'required',
            'conteudo' => 'required',
        ]);

        if ($dadosValidados->fails()) { // se o array de erros contem 1 ou mais erros..
            return response()->json([
                'msg' => 'Erro ao atualizar artigo',
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
                            $noticium->Anexo()->attach($arquivo->id);
                        }
                    }

                }
            }

            $dados['conteudo'] = str_replace('../responsive_filemanager/', asset('') . "responsive_filemanager/", $dados['conteudo']);
            $dados['conteudo'] = str_replace('<img src="' . asset(''), '<img class="img-fluid d-block mx-auto" src="' . asset(''), $dados['conteudo']);

            $noticium->update($dados);
            return response()->json([], 201);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Noticia $noticium
     * @return \Illuminate\Http\Response
     */
    public function destroy(Noticia $noticium)
    {
        if ($noticium->Anexo->count() > 0) {
            foreach ($noticium->Anexo as $anexo) {
                $anexo->excluir($anexo->id);
            }
        }
        $noticium->delete();
    }

    public function atualizar(Request $request)
    {
        $resultado = Noticia::with('Anexo', 'Categoria');

        if ($request->filled('campoBusca')) {
            $resultado->where('titulo', 'like', '%' . $request->campoBusca . '%');
        }

        $resultado = $resultado->paginate($request->pages);
        $categorias = CategoriasNoticia::get();

        return response()->json([
            'atual' => $resultado->currentPage(),
            'ultima' => $resultado->lastPage(),
            'total' => $resultado->total(),
            'dados' => ['itens' => $resultado->items(), 'categorias' => $categorias]
        ]);

    }


    // Anexos-------------------------------------------------
    public function uploadAnexos(Request $request)
    {
        if ($request->file('arquivo')->isValid()) {
            $mimeType = $request->file('arquivo')->getMimeType();
            $permitidos = [
                Arquivo::$MIME_JPEG,
                Arquivo::$MIME_JPG,
                Arquivo::$MIME_PNG,
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
