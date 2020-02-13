@extends('layouts.sistema')
@section('title', 'Alterar senha de acesso')
@section('content_header')
    <h4 class="text-default">ALTERAR SENHA DE ACESSO</h4>
    <hr class="bg-warning" style="margin-top: -5px;">
@stop
@section('content')

    <form>
        <div class="row ">
            <div class="col-sm-6">

                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" class="form-control" id="password" placeholder="Senha" autocomplete="off"
                           :disabled="preloadAjax" onblur="valida_campo_vazio(this,3)">
                </div>

                <div class="form-group">
                    <label>Redigitar senha</label>
                    <input type="password" class="form-control" id="password_confirmation" placeholder="Redigitar senha"
                           autocomplete="off" :disabled="preloadAjax" onblur="valida_campo_vazio(this,3)">
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-primary" :disabled="preloadAjax" @click="alterar()">
                        Alterar senha
                    </button>
                </div>

            </div>


        </div>

    </form>
@stop

@push('js')
    <script src="{{mix('js/g/alterar-senha/app.js')}}"></script>
@endpush
