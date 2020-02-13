@extends('layouts.sistema')
@section('title', 'DASHBOARD')
@section('content')

    {{--   <div class="row">
           <div class="col-xl-3 col-lg-6">
               <div class="card card-stats mb-4 mb-xl-0 bg-default">
                   <div class="card-body">
                       <div class="row">
                           <div class="col">
                               <h5 class="card-title text-uppercase  mb-0">Currículos Cadastrados</h5>
                               <span class="h2 font-weight-bold mb-0">{{$curriculos}}</span>
                           </div>
                           <div class="col-auto">
                               <div class="icon icon-shape bg-default text-white rounded-circle shadow">
                                   <i class="fas fa-user"></i>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>--}}
    <div class="row">

        <div class="col-xl-5 col-lg-6 mt-3">
            <div class="card card-stats mb-4 mb-xl-0 bg-default">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase  mb-0">Missão</h5>
                            <p class="font-weight-bold">Nós queremos ser os MELHORES. Por isso, desenvolvemos e
                                executamos estratégias para
                                atuar como protagonista de transformação no negócio, gerando uma troca benéfica,
                                encantadora, rentável, duradoura e exclusiva entre as partes envolvidas.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5 col-lg-6 mt-3">
            <div class="card card-stats mb-4 mb-xl-0 bg-default">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase  mb-0">Visão</h5>
                            <p class="font-weight-bold">Fazer da BPSE a mais EFICIENTE, ATRATIVA e COBIÇADA marca de
                                serviços e soluções em Gestão de RH e QSSMA no Maranhão até 2022.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5 col-lg-6 mt-3">
            <div class="card card-stats mb-4 mb-xl-0 bg-default">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase  mb-0">Valores</h5>
                            <p class="font-weight-bold">
                                <i class="fa fa-check"></i> Respeito é o princípio <br>
                                <i class="fa fa-check"></i> Engajamento e entusiasmo <br>
                                <i class="fa fa-check"></i> Agilidade com qualidade <br>
                                <i class="fa fa-check"></i> Sentimento de dono <br>
                                <i class="fa fa-check"></i> Amor em cada detalhe <br>
                                <i class="fa fa-check"></i> Foco em resultado sustentável <br>
                                <i class="fa fa-check"></i> Ética e transparência <br>
                                <i class="fa fa-check"></i> Desenvolvimento de pessoas
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <img src="{{ asset('imagens/bepinha.png') }}" class="img-fluid" alt="">
        </div>
    </div>



@stop
@push('css')
    <style type="text/css">
        .icon {
            width: 3rem;
            height: 3rem;
        }

        .icon i {
            font-size: 2.25rem;
        }

        .icon-shape {
            display: inline-flex;
            padding: 12px;
            text-align: center;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
        }

        .icon-shape i {
            font-size: 1.25rem;
        }
    </style>
@endpush
@push('js')
    <script src="{{mix('js/g/curriculo/app.js')}}"></script>
@endpush
