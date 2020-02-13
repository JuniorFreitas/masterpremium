@extends('layouts.sistema')
@section('title', 'Horário de acesso')
@section('content_header')
    <h4 class="text-default">HORÁRIO DE ACESSO</h4>
    <h6>Defina os horários de acesso ao sistema</h6>
    <hr class="bg-warning" style="margin-top: -5px;">
@stop
@section('content')

    <form>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <input type="text" readonly class="form-control-plaintext" value="Sistema liberado de: ">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 col-md-2">
                <div class="form-group">
                    <input type="time" class="form-control" id="abertura" value="{{$acesso->abertura}}"
                           onblur="valida_campo_vazio(this,5)" v-mascara:hora>
                </div>
                <div class="form-group">
                    <input type="time" class="form-control" id="fechamento" value="{{$acesso->fechamento}}"
                           onblur="valida_campo_vazio(this,5)" v-mascara:hora>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary" :disabled="preloadAjax" @click="alterarHorario()">
                        Alterar hoárario
                    </button>
                </div>
            </div>

            <div class="col-sm-6 col-md-10">
                <div class="form-group">
                    <button type="button" class="btn btn-success" @click.prevent="ativaDesativa()" v-if="ativo"
                            :disabled="preloadAjax">
                        <span class="fas fa-check" aria-hidden="true" v-if="!preloadAjax"></span>
                        <span class="fas fa-redo fa-spin" aria-hidden="true" v-if="preloadAjax"></span>
                        Regra ativa
                    </button>
                    <button type="button" class="btn btn-danger" @click.prevent="ativaDesativa()" v-if="!ativo"
                            :disabled="preloadAjax">
                        <span class="fas fa-times" aria-hidden="true" v-if="!preloadAjax"></span>
                        <span class="fas fa-redo fa-spin" aria-hidden="true" v-if="preloadAjax"></span>
                        Regra inativa
                    </button>
                </div>
            </div>


        </div>

    </form>
@stop

@push('js')
    <script src="{{mix('js/g/horario-acesso/app.js')}}"></script>
@endpush
