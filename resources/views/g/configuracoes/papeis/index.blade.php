@extends('layouts.sistema')
@section('title', 'Papeis')
@section('content_header')
    <h4 class="text-default">PAPÉIS</h4>
    <hr class="bg-warning" style="margin-top: -5px;">@stop
@section('content')

    <!-- Modal formulario -->
    <modal id="janelaCadastrar" :titulo="tituloJanela" size="g">
        <template slot="conteudo">
            <span v-show="preloadAjax">
                <i class="fa fa-spinner fa-pulse"></i> Carregando...
            </span>
            <div class="alert alert-success alert-dismissible" v-show="cadastrado">
                <h4>
                    <i class="icon fa fa-check"></i>
                    Papel cadastrado com sucesso!
                </h4>
            </div>
            <div class="alert alert-success alert-dismissible" v-show="atualizado">
                <h4>
                    <i class="icon fa fa-check"></i>
                    Papel alterado com sucesso!
                </h4>
            </div>
            <form v-show="!preloadAjax && (!cadastrado && !atualizado)" id="form">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="nav-item">
                        <a href="#abaIdentificacao" class="nav-link active" aria-controls="home" role="tab"
                           data-toggle="tab">Identificação</a>
                    </li>
                    <li role="presentation">
                        <a href="#abaHabilidades" class="nav-link" aria-controls="profile" role="tab"
                           data-toggle="tab">Habilidades</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="abaIdentificacao">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" class="form-control" id="nome" placeholder="Nome do papel"
                                   autocomplete="off" onblur="valida_campo_vazio(this,3)">
                        </div>

                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="text" class="form-control" id="email" placeholder="Um e-mail"
                                   autocomplete="off" onblur="validaEmailVazio(this)">
                        </div>

                        <div class="form-group">
                            <label>Descrição</label>
                            <input type="text" class="form-control" id="descricao" placeholder="Descrição do papel"
                                   autocomplete="off" onblur="valida_campo_vazio(this,3)">
                        </div>

                        <div class="form-group">
                            <label>Ativo</label>
                            <select id="ativo" class="form-control">
                                <option value="true">Sim</option>
                                <option value="false">Não</option>
                            </select>
                        </div>

                    </div>

                    <div role="tabpanel" class="tab-pane" id="abaHabilidades">
                        <div class="table-responsive">
                            <table class="table table-hover table-condensed">
                                <thead>
                                <tr>
                                    {{--<th>Cód.</th>--}}
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                    <th>
                                        <a class="btn btn-success" href="javascript://"
                                           @click.prevent="selecionarTodas()" v-if="!todasHabilidades">
                                            <span class="fa fa-ok" aria-hidden="true"></span> Todas
                                        </a>
                                        <a class="btn btn-danger" href="javascript://"
                                           @click.prevent="selecionarTodas()" v-if="todasHabilidades">
                                            <span class="fa fa-remove" aria-hidden="true"></span> Todas
                                        </a>
                                    </th>
                                </tr>
                                </thead>

                                <tbody>

                                <tr v-for="habilidade in listaDeHabilidades">
                                    {{--<td>@{{ab.id}}</td>--}}
                                    <td>@{{habilidade.nome}}</td>
                                    <td>@{{habilidade.descricao}}</td>
                                    <td>
                                        <a class="btn btn-success" href="javascript://"
                                           @click.prevent="habilidade.acesso=!habilidade.acesso"
                                           v-if="habilidade.acesso">
                                            <span class="fa fa-ok" aria-hidden="true"></span> Permitir
                                        </a>
                                        <a class="btn btn-danger" href="javascript://"
                                           @click.prevent="habilidade.acesso=!habilidade.acesso"
                                           v-if="!habilidade.acesso">
                                            <span class="fa fa-remove" aria-hidden="true"></span> Negar
                                        </a>
                                    </td>

                                </tr>

                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </form>
        </template>
        <template slot="rodape">
            <div v-show="!preloadAjax">
                <button type="button" class="btn btn-primary" v-show="editando && !atualizado"
                        @click="alterar()">Alterar
                </button>
                <button type="button" class="btn btn-primary" v-show="!editando && !cadastrado"
                        @click="cadastrar()">Cadastrar
                </button>
            </div>
        </template>

    </modal>

    <!-- Modal confirmar -->
    <modal id="janelaConfirmar" titulo="Apagar papel">
        <template slot="conteudo">
            <span v-show="preloadAjax">
                <i class="fa fa-spinner fa-pulse"></i> Carregando...
            </span>

            <div class="alert alert-success alert-dismissible" v-show="apagado">

                <h4>
                    <i class="icon fa fa-check"></i>
                    Papel apagado com sucesso!
                </h4>

            </div>

            <h4 v-show="!apagado && !preloadAjax">
                Tem certeza que deseja apagar este papel?
            </h4>
        </template>
        <template slot="rodape">
            <div v-show="!preloadAjax">
                <button type="button" class="btn btn-danger" @click="apagar()" v-show="!apagado">Apagar</button>
            </div>
        </template>
    </modal>

    <div class="row">
        <div class="col-md-4 column">
            <form id="formBusca">
                <div class="form-group">
                    <label>Buscar:</label>
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                        </span>
                        <input type="text" id="campoBusca" placeholder="Nome do papel" autocomplete="off"
                               class="form-control">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <button type="button" class="btn btn-success" id="btnAtualizar">Atualizar</button>
    @can('papel_insert')
        <button type="button" class="btn btn-primary" id="btnFormCadastrar" data-toggle="modal"
                data-target="#janelaCadastrar" @click="formNovo()">Cadastrar
        </button>
    @endcan

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
                    {{--<th>Cód.</th>--}}
                    <th class="text-center">Nome</th>
                    <th class="text-center">Descrição</th>
                    <th class="text-center">Ativo</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>

                <tr v-for="papel in lista">
                    {{--<td>@{{ab.id}}</td>--}}
                    <td class="text-center">@{{papel.nome}}</td>
                    <td class="text-center">@{{papel.descricao}}</td>
                    <td class="text-center">
                        <span class="badge badge-success" v-if="papel.ativo">Ativo</span>
                        <span class="badge badge-danger" v-if="!papel.ativo">Inativo</span>
                    </td>
                    <td class="text-center">
                        @can('papel_update')
                            <a class="btn btn-success btnFormAlterar" href="javascript://"
                               @click.prevent="formAlterar(papel.id)" data-toggle="modal"
                               data-target="#janelaCadastrar">
                                <i class="fa fa-edit"></i> Alterar
                            </a>
                        @endcan
                        @can('papel_delete')
                            <a class="btn btn-danger btnFormExcluir" href="javascript://"
                               @click.prevent="janelaConfirmar(papel.id)" data-toggle="modal"
                               data-target="#janelaConfirmar">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </a>
                        @endcan
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
        <controle-paginacao class="d-flex justify-content-center" id="controle" ref="componente"
                            url="{{route('g.configuracoes.papeis.atualizar')}}" por-pagina="10"
                            :dados="controle.dados" v-on:carregou="carregou" v-on:carregando="carregando">

        </controle-paginacao>
    </div>
@stop
@push('js')
    <script src="{{mix('js/g/papeis/app.js')}}"></script>
@endpush
