<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\LogoCliente;
use DB;
use Illuminate\Http\Request;

class ClienteLogoSiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('g.site.clientes.index');
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
//        $this->authorize('galeria_site_insert');
        $dados = $request->input();
        $cliente = LogoCliente::find(1);
        try {
            DB::beginTransaction();

            if (isset($dados['fotos'])) {
                foreach ($dados['fotos'] as $index => $foto) {
                    $arquivo = Arquivo::whereChave($foto['chave'])->whereId($foto['id'])->first();
                    if ($arquivo) {
                        $arquivo->temporario = false;
                        $arquivo->chave = '';
                        $arquivo->save();
                        $cliente->Fotos()->attach($arquivo->id, ['ordem' => $index]);
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
     * @param \App\Models\LogoCliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(LogoCliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\LogoCliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(LogoCliente $cliente)
    {
        $cliente = LogoCliente::find(1);
        return $cliente->load('Fotos');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\LogoCliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LogoCliente $cliente)
    {
        $cliente2 = LogoCliente::find(1);

//        $this->authorize('galeria_site_update');
        $dados = $request->input();
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
                        $cliente2->Fotos()->updateExistingPivot($foto['id'], ['ordem' => $index]);
                    } else {
                        $arquivo = Arquivo::whereChave($foto['chave'])->whereId($foto['id'])->first();
                        if ($arquivo) {
                            $arquivo->temporario = false;
                            $arquivo->chave = '';
                            $arquivo->save();
                            $cliente2->Fotos()->attach($arquivo->id, ['ordem' => $index]);
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
     * @param \App\Models\LogoCliente $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(LogoCliente $cliente)
    {
        //
    }

    public function fotoUpload(Request $request)
    {
        if ($request->file('arquivo')->isValid()) {
            $mimeType = $request->file('arquivo')->getMimeType();
            $permitidos = [
                Arquivo::MIME_JPEG,
                Arquivo::MIME_JPG,
                Arquivo::MIME_PNG,
            ];
            if (in_array($mimeType, $permitidos)) {
                $arquivo = Arquivo::gravaArquivoCliente($request, 'arquivo', Arquivo::DISCO_PUBLICO);
                return response()->json($arquivo, 201);
            } else {
                return response()->json([
                    'msg' => "O upload do arquivo \"{$request->file('arquivo')->getClientOriginalName()}\" falhou. Permitidos apenas PNG, JPG, JPEG",
                    'erros' => []
                ], 400);
            }

        } else {
            return response()->json([
                'msg' => "O upload da imagem falhou",
                'erros' => []
            ], 400);
        }

    }

    public function fotoShow(Request $request, $arquivo)
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

    public function fotoDelete(Request $request, $arquivo)
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
            return response("Não foi possível apagar a foto", 400);
        }

    }

    //anexo ou foto
    public function fotoDownload(Request $request, $arquivo)
    {
        //Fazer a validacao (middleware) de download para resumo-cliente , resumo-ocorrencias, aqui se nescessario...
        $disco = Arquivo::nomeDisco($arquivo);
        $permitidos = [
            Arquivo::DISCO_CLOUD
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

    public function atualizar()
    {
        return $logo = LogoCliente::get();
//        return response()->json($logo, 200);
    }
}
