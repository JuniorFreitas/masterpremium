@extends('layouts.sistema')
@section('title', 'Logo Cliente')
@section('content_header')
    <h4 class="text-default">Logo Clientes</h4>
    <hr class="bg-warning" style="margin-top: -5px;">@stop
@section('content')

    <fieldset>
        <legend>Logotipos</legend>
        <upload label="Selecionar Imagens" :model="form.fotos"
                :model-delete="form.fotosDel"
                url="{{ route('g.site.cliente.upload-fotos') }}"
                :ordenar="true"
                :apenas-imagens="true"
                @onprogresso="fotoUploadAndamento=true"
                @onfinalizado="fotoUploadAndamento=false"></upload>
    </fieldset>

    <button class="btn btn-success" :disabled="fotoUploadAndamento" @click="alterar"><i class="fa fa-save"></i> Salvar
    </button>

@stop
@push('js')
    <script src="{{mix('js/g/site/clientes/app.js')}}"></script>
@endpush
