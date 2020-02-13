<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\Vaga;
use Illuminate\Http\Request;

class AutoCompletesController extends Controller
{
    public function vagasAtivas(Request $request)
    {
        $busca = $request->query('busca');
        if ($busca == '') {
            return response()->json([], 201);
        }
        $quantidade = $request->query('rows');
        $busca = $request->query('busca');
        if ($busca === '*') {
            return Vaga::whereAtivo(true)
                ->get()
                ->map(function ($item) {
                    $item->label = $item->nome;
                    return $item;
                });
        } else {
            return Vaga::whereAtivo(true)
                ->where('nome', 'like', '%' . $busca . '%')
                ->take($quantidade)
                ->get()
                ->map(function ($item) {
                    $item->label = $item->nome;
                    return $item;
                });
        }
    }

    public function clientesAtivos(Request $request)
    {
        $busca = $request->query('busca');
        if ($busca == '') {
            return response()->json([], 201);
        }
        $quantidade = $request->query('rows');

        $busca = $request->query('busca');
        if ($busca === '*') {
            return Clientes::whereAtivo(true)
                ->get()
                ->map(function ($item) {
                    if ($item->tipo == Clientes::TIPO_PESSOA_JURIDICA) {
                        $label = $item->nome_fantasia . ' | ' . $item->cnpj;
                    } else {
                        $label = $item->nome . ' | ' . $item->cnpj;
                    }
                    $item->label = $label;
                    return $item;
                });
        } else {
            return Clientes::whereAtivo(true)
                ->where('nome_fantasia', 'like', '%' . $busca . '%')
                ->orWhere('nome', 'like', '%' . $busca . '%')
                ->take($quantidade)
                ->get()
                ->map(function ($item) {
                    if ($item->tipo == Clientes::TIPO_PESSOA_JURIDICA) {
                        $label = $item->nome_fantasia . ' | ' . $item->cnpj;
                    } else {
                        $label = $item->nome . ' | ' . $item->cnpj;
                    }
                    $item->label = $label;
                    return $item;
                });
        }

    }
}
