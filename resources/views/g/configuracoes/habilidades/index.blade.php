@extends('layouts.sistema')
@section('title', 'Habilidades')
@section('content_header')
    <h4 class="text-default">HABILIDADES</h4>
    <hr class="bg-warning" style="margin-top: -5px;">
@stop
@section('content')

    <modal id="janelaCadastrar" :titulo="tituloJanela" size="g">
        <template slot="conteudo">

            <span v-show="preloadAjax"><i class="fa fa-spinner fa-pulse"></i> Aguarde...</span>
            <div class="alert alert-success alert-dismissible" v-show="cadastrado">
                <h4><i class="icon fa fa-check"></i>Habilidade cadastrada com sucesso!</h4>
            </div>
            <div class="alert alert-success alert-dismissible" v-show="atualizado">
                <h4><i class="icon fa fa-check"></i>Habilidade alterada com sucesso!</h4>
            </div>
            <form v-show="!preloadAjax && (!cadastrado && !atualizado)" id="form">
                <div class="form-group" :class="{'is-invalid': erros.nome}">
                    <label>Nome</label>
                    <input type="text" class="form-control" id="nome" placeholder="Nome da habilidade"
                           autocomplete="off" onblur="valida_campo_vazio(this,1)" v-popover>
                    <span class="help-block" v-if="erros.nome">@{{ erros.nome[0] }}</span>
                </div>
                <div class="form-group">
                    <label>Descrição</label>
                    <input type="text" class="form-control" id="descricao" placeholder="Descrição da habilidade"
                           autocomplete="off" onblur="valida_campo_vazio(this,1)" v-popover>
                </div>
            </form>
        </template>
        <template slot="rodape">
            <button type="button" class="btn btn-primary" v-show="editando && !atualizado" @click="alterar()">
                Alterar
            </button>
            <button type="button" class="btn btn-primary" v-show="!editando && !cadastrado" @click="cadastrar()">
                Cadastrar
            </button>
        </template>
    </modal>

    <modal id="janelaConfirmar" titulo="Apagar habilidades">
        <template slot="conteudo">
            <span v-show="preloadAjax"><i class="fa fa-spinner fa-pulse"></i>Aguarde...</span>
            <div class="alert alert-success alert-dismissible" v-show="apagado">
                <h4><i class="icon fa fa-check"></i>Habilidade apagada com sucesso!</h4>
            </div>
            <h4 v-show="!apagado">Tem certeza que deseja apagar esta habilidade?</h4>
        </template>
        <template slot="rodape">
            <button type="button" class="btn btn-danger" @click="apagar()" v-show="!apagado">Apagar</button>
        </template>
    </modal>

    <div class="row">
        <div class="col-md-4 column">
            <form id="formBusca">
                <div class="form-group">
                    <label>Buscar:</label>
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <i class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></i>
                        </span>
                        <input type="text" id="campoBusca" placeholder="Nome da habilidade" autocomplete="off"
                               class="form-control">
                    </div>
                </div>
            </form>
        </div>
    </div>


    <button type="button" class="btn btn-success" id="btnAtualizar">Atualizar</button>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#janelaCadastrar"
            @click="formNovo()">
        Cadastrar
    </button>

    <p class="text-center" v-if="controle.carregando">
        <i class="fa fa-spinner fa-pulse"></i> Carregando...
    </p>

    <div id="conteudo">
        <h4 v-show="!controle.carregando && lista.length==0"></h4>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-condensed"
                   v-if="!controle.carregando && lista.length > 0">
                <thead>
                <tr class="bg-default">
                    <th class="text-center">Cód.</th>
                    <th class="text-center">Nome</th>
                    <th class="text-center">Descrição</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="ab in lista">
                    <td class="text-center">@{{ab.id}}</td>
                    <td class="text-center">@{{ab.nome}}</td>
                    <td class="text-center">@{{ab.descricao}}</td>
                    <td class="text-center">
                        <a href="javascript://" class="btn btn-success btnFormAlterar"
                           @click.prevent="formAlterar(ab.id)"
                           data-toggle="modal"
                           data-target="#janelaCadastrar">
                            <i class="fas fa-edit"></i> Alterar
                        </a>
                        <a href="javascript://" class="btn btn-danger btnFormExcluir"
                           @click.prevent="janelaConfirmar(ab.id)"
                           data-toggle="modal"
                           data-target="#janelaConfirmar">
                            <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>
        <controle-paginacao class="d-flex justify-content-center" id="controle" ref="componente"
                            url="{{route('g.configuracoes.habilidades.atualizar')}}" por-pagina="10"
                            :dados="controle.dados"
                            v-on:carregou="carregou" v-on:carregando="carregando"></controle-paginacao>
    </div>

@stop

@push('js')
    <script src="{{mix('js/g/habilidades/app.js')}}"></script>
@endpush
