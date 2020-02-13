@extends('layouts.sistema')
@section('title', 'Usuários')
@section('content_header')
    <h4 class="text-default">USUÁRIOS DO SISTEMA</h4>
    <hr class="bg-warning" style="margin-top: -5px;">@stop
@section('content')


    <modal id="janelaCadastrar" :titulo="tituloJanela" size="g">
        <template slot="conteudo">

            <span v-show="preloadAjax"><i class="fa fa-spinner fa-pulse"></i> Aguarde...</span>
            <div class="alert alert-success alert-dismissible" v-show="cadastrado">
                <h4><i class="icon fa fa-check"></i>Usuário cadastrado com sucesso!</h4>
            </div>
            <div class="alert alert-success alert-dismissible" v-show="atualizado">
                <h4><i class="icon fa fa-check"></i>Usuário alterado com sucesso!</h4>
            </div>

            <form v-show="!preloadAjax && (!cadastrado && !atualizado)" id="form">
                <div class="form-group">
                    <label>Nome do usuário</label>
                    <input type="text" class="form-control" id="nome" placeholder="Nome do usuário" autocomplete="off"
                           onblur="valida_campo_vazio(this,3)">
                </div>
                <div class="form-group">
                    <label>Login</label>
                    <input type="text" class="form-control" id="login" placeholder="Login" autocomplete="off"
                           onblur="valida_campo_vazio(this,3)">
                </div>

                <div class="form-check" v-if="editando">

                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" v-model="alterarSenha">
                        Redefinir senha
                    </label>
                </div>

                <div class="form-group" v-if="editando && alterarSenha || !editando">
                    <label>Senha</label>
                    <input type="password" class="form-control" id="password" placeholder="Senha" autocomplete="off"
                           onblur="valida_campo_vazio(this,3)">
                </div>

                <div class="form-group" v-if="editando && alterarSenha || !editando">
                    <label>Redigitar senha</label>
                    <input type="password" class="form-control" id="password_confirmation" placeholder="Redigitar senha"
                           autocomplete="off" onblur="valida_campo_vazio(this,3)">
                </div>

                <div class="form-group">
                    <label>Tipo do formuário</label>
                    <select class="form-control" id="grupo_id">
                        @foreach ($listaDePapeis as $papel)
                            <option value="{{$papel->id}}">{{$papel->nome}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Ativo</label>
                    <select class="form-control" id="ativo">
                        <option value="true">Sim</option>
                        <option value="false">Não</option>
                    </select>
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

    <modal id="janelaConfirmar" titulo="Apagar Usuário">
        <template slot="conteudo">
            <span v-show="preloadAjax"><i class="fa fa-spinner fa-pulse"></i>Aguarde...</span>
            <div class="alert alert-success alert-dismissible" v-show="apagado">
                <h4><i class="icon fa fa-check"></i>Usuário apagado com sucesso!</h4>
            </div>
            <h4 v-show="!apagado">Tem certeza que deseja apagar este usuário?</h4>
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
                        <input type="text" id="campoBusca" placeholder="Nome do usuário" autocomplete="off"
                               class="form-control">
                    </div>
                </div>
            </form>
        </div>
    </div>


    <button type="button" class="btn btn-success" id="btnAtualizar">Atualizar</button>
    @can('usuarios_insert')
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#janelaCadastrar"
                @click="formNovo()">
            Criar novo usuário
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
                    <th class="text-center">Nome do usuário</th>
                    <th class="text-center">Papel</th>
                    <th class="text-center">Alterar</th>
                    <th class="text-center">Excluir</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="usuario in lista">
                    <td class="text-center">@{{usuario.nome}}</td>
                    <td class="text-center">@{{usuario.papel.nome}}</td>
                    <td align="center">
                        @can('usuarios_update')
                            <a href="javascript://" class="btn btn-success btnFormAlterar"
                               @click.prevent="formAlterar(usuario.id)"
                               data-toggle="modal"
                               data-target="#janelaCadastrar">
                                <i class="fa fa-edit" aria-hidden="true"></i> Alterar
                            </a>
                        @endcan
                    </td>
                    <td align="center">
                        @can('usuarios_delete')
                            <a href="javascript://" class="btn btn-danger btnFormAlterar"
                               @click.prevent="janelaConfirmar(usuario.id)"
                               data-toggle="modal"
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
                            url="{{route('g.usuarios.usuarios.atualizar')}}" por-pagina="10" :dados="controle.dados"
                            v-on:carregou="carregou" v-on:carregando="carregando"></controle-paginacao>
    </div>
@stop

@push('js')
    <script src="{{mix('js/g/usuarios/app.js')}}"></script>
@endpush
