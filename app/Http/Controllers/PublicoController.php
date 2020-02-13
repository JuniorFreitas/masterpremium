<?php

namespace App\Http\Controllers;

use App\Models\Bairro;
use App\Models\Banco;
use App\Models\Municipio;
use App\Models\Vaga;
use Illuminate\Http\Request;

class PublicoController extends Controller
{

    public function download(Request $request, string $nome)
    {

        return Storage::disk('fotos_imovel')->url($nome);
        //return Storage::disk('fotos_imovel')->download($nome,'foto.jpg');
    }


    public function listaBancos()
    {
        return Banco::get();
    }

    //Utilizado pelo componente de LocalizaImovel.vue
    public function listaMunicipios(Request $request)
    {
        $listaDeMunicipios = Municipio::whereUf($request->UF)->orderByRaw("capital=1 DESC ,nome ASC")->get();
        $listaDeBairros = Bairro::whereMunicipioId($listaDeMunicipios[0]->id)->orderBy('nome')->get();
        return response()->json(['municipios' => $listaDeMunicipios, 'bairros' => $listaDeBairros], 201);
    }

    public function listaMBairros(Request $request)
    {
        $listaDeBairros = Bairro::whereMunicipioId($request->id_municipio)->orderBy('nome')->get();
        return response()->json(['bairros' => $listaDeBairros], 201);
    }

    //------------

    public function upload(Request $request)
    {

        //dd($request->all());
        if ($request->arquivo->getClientOriginalName() == "Edital da UFMA.pdf") {
            return response()->json([
                'msg' => "O upload do arquivo \"{$request->arquivo->getClientOriginalName()}\" falhou",
                'erros' => []
            ], 400);
        } else {
            return response()->json([
                'id' => '300',
                'nome' => $request->arquivo->getClientOriginalName(),
                'imagem' => true,
                'extensao' => '.jpg',
                'thumb' => 'https://osegredo.com.br/wp-content/uploads/2017/09/O-que-as-pessoas-felizes-t%C3%AAm-em-comum-site-830x450.jpg',
            ], 201);
        }

    }

    public function listaVagas()
    {
        return response()->json(['vagas' => Vaga::whereAtivo(true)->get()], 200);
    }
}
