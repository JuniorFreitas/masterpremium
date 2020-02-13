@extends('layouts.sistema')
@section('title', 'Galeria de Fotos')
@section('content_header')
    <h4 class="text-default">GALERIAS DE FOTOS DO SITE</h4>
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
                    Galeria cadastrada com sucesso!
                </h4>
            </div>
            <div class="alert alert-success alert-dismissible" v-show="atualizado">
                <h4>
                    <i class="icon fa fa-check"></i>
                    Galeria alterada com sucesso!
                </h4>
            </div>
            <form v-if="!preloadAjax && (!cadastrado && !atualizado)" onsubmit="return false" id="form">
                <fieldset>
                    <legend>INFORMAÇÕES</legend>
                    <div class="form-group">
                        <label>Titulo</label>
                        <input type="text" class="form-control" placeholder="Titulo" onblur="valida_campo_vazio(this,1)"
                               v-model="form.titulo">
                    </div>

                    <div class="form-group">
                        <label>Ativo</label>
                        <select class="form-control">
                            <option :value="true">Sim</option>
                            <option :value="false">não</option>
                        </select>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>FOTOS</legend>
                    <p class="text-muted">A Primeira foto será de capa</p>
                    <upload label="Selecionar foto(s)" :model="form.fotos"
                            :model-delete="form.fotosDel"
                            url="{{ route('g.site.galeria.upload-fotos') }}"
                            :ordenar="true"
                            :apenas-imagens="true"
                            @onprogresso="fotoUploadAndamento=true"
                            @onfinalizado="fotoUploadAndamento=false"></upload>
                </fieldset>

            </form>
        </template>
        <template slot="rodape">
            <div v-show="!preloadAjax">
                <button type="button" class="btn btn-primary" v-if="editando && !atualizado"
                        @click="alterar()">Alterar
                </button>
                <button type="button" class="btn btn-primary" v-if="!editando && !cadastrado"
                        @click="cadastrar()">Cadastrar
                </button>
            </div>
        </template>
    </modal>

    <!-- Modal confirmar -->
    <modal id="janelaConfirmar" titulo="Apagar galeria">
        <template slot="conteudo">
            <span v-show="preloadAjax">
                <i class="fa fa-spinner fa-pulse"></i> Carregando...
            </span>
            <div class="alert alert-success alert-dismissible" v-show="apagado">
                <h4>
                    <i class="icon fa fa-check"></i>
                    Galeria apagada com sucesso!
                </h4>
            </div>
            <h4 v-show="!apagado && !preloadAjax">
                Tem certeza que deseja apagar esta galeria?
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
    @can('galeria_site_insert')
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
                    <th>Cód.</th>
                    <th class="text-center">Nome</th>
                    <th class="text-center">Quantidade Fotos</th>
                    <th class="text-center">Ativo</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <tr v-for="galeria in lista">
                    <td>@{{galeria.id}}</td>
                    <td class="text-center">@{{galeria.titulo}}</td>
                    <td class="text-center">@{{galeria.fotos_count}}</td>
                    <td class="text-center">
                        <span class="badge badge-success" v-if="galeria.ativo">Ativo</span>
                        <span class="badge badge-danger" v-if="!galeria.ativo">Inativo</span>
                    </td>
                    <td class="text-center">
                        @can('galeria_site_update')
                            <a class="btn btn-success btnFormAlterar" href="javascript://"
                               @click.prevent="formAlterar(galeria.id)" data-toggle="modal"
                               data-target="#janelaCadastrar">
                                <i class="fa fa-edit"></i> Alterar
                            </a>
                        @endcan
                        @can('galeria_site_delete')
                            <a class="btn btn-danger btnFormExcluir" href="javascript://"
                               @click.prevent="janelaConfirmar(galeria.id)" data-toggle="modal"
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
                            url="{{route('g.site.galeria.atualizar')}}" por-pagina="10"
                            :dados="controle.dados" v-on:carregou="carregou" v-on:carregando="carregando">

        </controle-paginacao>
    </div>
@stop
@push('js')
    <script src="{{mix('js/g/site/galeria/app.js')}}"></script>
@endpush
