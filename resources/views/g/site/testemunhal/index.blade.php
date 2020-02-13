@extends('layouts.sistema')
@section('title', 'DEPOIMENTOS')
@section('content_header')
    <h4 class="text-default">DEPOIMENTOS</h4>
    <hr class="bg-warning" style="margin-top: -5px;">@stop
@section('content')

    <modal id="janelaCadastrar" :titulo="tituloJanela" :fechar="!preloadAjax" size="g">
        <template slot="conteudo">
            <span v-show="preloadAjax"><i class="fa fa-spinner fa-pulse"></i> Aguarde...</span>
            <div class="alert alert-success alert-dismissible" v-show="cadastrado">
                <h4><i class="icon fa fa-check"></i>Depoimento cadastrado com sucesso!</h4>
            </div>
            <div class="alert alert-success alert-dismissible" v-show="atualizado">
                <h4><i class="icon fa fa-check"></i>Depoimento alterado com sucesso!</h4>
            </div>
            <form v-if="!preloadAjax && (!cadastrado && !atualizado)" onsubmit="return false;">
                <fieldset>
                    <legend>INFORMAÇÕES DO TESTEMUNHAL</legend>
                    <div class="row">
                        <div class="col-12">

                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text">Nome
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" v-model="form.nome"
                                           onblur="valida_campo_vazio(this,1)">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text">Subtitulo
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" v-model="form.subtitulo">
                                </div>
                            </div>

                            <div class="form-group">


                                <editor :api-key='config.key' v-model="form.texto" :init="config"></editor>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend style="text-transform: uppercase">Imagem</legend>
                    <upload :model="form.anexo"
                            :model-delete="form.anexoDel"
                            :url="urlAnexoUpload"
                            :apenas-imagens="true"
                            :quantidade="1"
                            label="Selecionar Imagem"
                            @onProgresso="anexoUploadAndamento=true"
                            @onFinalizado="anexoUploadAndamento=false"></upload>
                </fieldset>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <div class="input-group-text">Ativo
                                    </div>
                                </div>
                                <select class="custom-select"
                                        v-model="form.ativo">
                                    <option :value="true">Sim</option>
                                    <option :value="false">Não</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </template>
        <template slot="rodape">
            <button type="button" class="btn btn-primary" v-show="editando && !atualizado" @click="alterar()">
                <i class="fa fa-edit"></i> Alterar
            </button>

            <button type="button" class="btn btn-primary" v-show="!editando && !cadastrado" @click="cadastrar()">
                <i class="fas fa-save"></i> Salvar
            </button>
        </template>
    </modal>

    <modal id="janelaConfirmar" titulo="Apagar Depoimento">
        <template slot="conteudo">
            <span v-show="preloadAjax"><i class="fa fa-spinner fa-pulse"></i>Aguarde...</span>
            <div class="alert alert-success alert-dismissible" v-show="apagado">
                <h4><i class="icon fa fa-check"></i>Depoimento apagado com sucesso!</h4>
            </div>
            <h4 v-show="!apagado">Tem certeza que deseja apagar este Depoimento?</h4>
        </template>
        <template slot="rodape">
            <button type="button" class="btn btn-danger" @click="apagar()" v-show="!apagado">Apagar</button>
        </template>
    </modal>

    <div class="row">
        <div class="col-12">
            <fieldset>
                <legend>FILTRAGEM POR:</legend>
                <form class="row" @submit.prevent="atualizar">
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="form-group">
                            <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </span>
                                <input type="text"
                                       placeholder="Pesquise por titulo" autocomplete="mastertag"
                                       @keydown.prevent.enter="atualizar"
                                       class="form-control" :disabled="controle.carregando"
                                       v-model="controle.dados.campoBusca">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-3 col-md-2">
                        <div class="form-group">
                            <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text">Exibir</span>
                            </span>
                                <select class="custom-select" @change="atualizar" :disabled="controle.carregando"
                                        v-model="controle.dados.pages">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-12">
                        <button type="button" class="btn btn-success" :disabled="controle.carregando"
                                @click="atualizar">
                            <i :class="controle.carregando ? 'fa fa-sync fa-spin' : 'fa fa-sync'"></i>
                            Atualizar
                        </button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#janelaCadastrar"
                                @click="formNovo()">
                            Cadastrar
                        </button>
                    </div>
                </form>
            </fieldset>

            <p class="text-center" v-if="controle.carregando">
                <i class="fa fa-spinner fa-pulse"></i> Carregando...
            </p>

            <h4 v-show="!controle.carregando && lista.length==0"></h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-condensed"
                       v-if="!controle.carregando && lista.length > 0" style="font-size: 0.85em;">
                    <thead>
                    <tr class="bg-default">
                        <th class="text-center">CÓD</th>
                        <th>Nome</th>
                        <th>Texto</th>
                        <th>Img</th>
                        <th colspan="2"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="testemunhal in lista">
                        <td class="text-center">@{{ testemunhal.id }}</td>
                        <td>@{{ testemunhal.nome }} - @{{ testemunhal.subtitulo }}</td>
                        <td><span v-html="testemunhal.texto"></span></td>
                        <td><img :src="testemunhal.anexo[0].urlThumb" alt=""></td>
                        <td class="text-center">
                            <a href="javascript://" class="btn btn-success btnFormAlterar"
                               @click.prevent="formAlterar(testemunhal.id)"
                               data-toggle="modal"
                               data-target="#janelaCadastrar">
                                <i class="fa fa-edit" aria-hidden="true"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="javascript://" class="btn btn-danger btnFormExcluir"
                               @click.prevent="janelaConfirmar(testemunhal.id)"
                               data-toggle="modal"
                               data-target="#janelaConfirmar">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <controle-paginacao class="d-flex justify-content-center" id="controle" ref="componente"
                                url="{{route('g.site.testemunhal.testemunhal.atualizar')}}"
                                :por-pagina="controle.dados.pages"
                                :dados="controle.dados"
                                v-on:carregou="carregou" v-on:carregando="carregando"></controle-paginacao>

        </div>
    </div>

@stop
@push('js')
    <script src="{{mix('js/g/site/testemunhal/app.js')}}"></script>
@endpush
