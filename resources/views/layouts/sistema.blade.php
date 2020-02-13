<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="msapplication-TileColor" content="#072433">
    <meta name="msapplication-TileImage" content="{{asset('/')}}ms-icon-144x144.png">
    <meta name="theme-color" content="#072433">
    <title>@yield('title')</title>
    <link rel="preload" href="{{asset('js/app.js')}}" as="script">
    <link rel="preload" href="{{asset('js/funcoes.js')}}" as="script">
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('/')}}apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{asset('/')}}apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('/')}}apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('/')}}apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('/')}}apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('/')}}apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('/')}}apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('/')}}apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/')}}apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="{{asset('/')}}android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/')}}favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('/')}}favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/')}}favicon-16x16.png">
    <link rel="manifest" href="{{asset('/')}}manifest.json">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css"
          integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    @stack('css')
</head>
<body>
<div id="app" v-cloak>
    <modal id="janelaConfirmarSair" titulo="Sair" :centralizada="true" label-fechar="Não">
        <template slot="conteudo">
            <div class="text-center text-default">
                <i class="fa fa-exclamation-triangle" style="font-size: 67px;"></i>
                <h6 class="text-center mt-2">Você realmente deseja Sair do Sistema?</h6>
            </div>
        </template>
        <template slot="rodape">
            <button type="button" class="btn btn-danger" onclick="window.location.href=`${URL_ADMIN}/sair`">
                SIM
            </button>
        </template>
    </modal>

    <div class="container-fluid bg-dark text-white">
        <div class="row" style="font-size: 0.92em;">
            <div class="col-12 col-sm col-md text-left">
                <span id="dataHora">{{\App\Models\Sistema::hoje()}}<span id="relogio"></span></span>
            </div>
            <div class="col-12 col-sm col-md text-right">
                <span
                    style="font-size: .8rem;">Usuário: {{Auth::user()->nome}} | Grupo: {{ucfirst(Auth::user()->Papel->nome)}}</span>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <nav class="navbar navbar-expand-lg navbar-light bg-default navtopo">
        <a class="navbar-brand" href="{{ route('g.dashboard') }}"><img src="{{asset('imagens/logo_oficial.png')}}"
                                                                       style="height: 80px"
                                                                       class="img-fluid" alt="Logo"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            @include('layouts.menu')
            <div class="form-inline my-2 my-lg-0">
                <a class="btn btn-outline-light my-2 my-sm-0"
                   href="javascript://"
                   data-toggle="modal"
                   data-target="#janelaConfirmarSair">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        @yield('content_header')
        @yield('content')
    </div>
    <footer class="footer bg-default">
        <div class="container-fluid text-center">

            <div class="col">
                <img src="{{asset('imagens/mastertag_w.svg')}}" height="30px"
                     alt="">
            </div>
        </div>
    </footer>
    <div class="bg-dark" style="height:3px"></div>
</div>

<script src="{{mix('js/app.js')}}"></script>
<script src="{{mix('js/funcoes.js')}}"></script>
<script type="text/javascript">
    var agora = new Date({{\App\Models\Sistema::horaJs()}});
    setInterval(pegaHora, 1000);

    function pegaHora() {
        var segundos = agora.getSeconds();
        segundos++;
        agora.setSeconds(segundos);
        var segundos = agora.getSeconds();
        var hora = agora.getHours();
        var minuto = agora.getMinutes();
        if (hora < 10) {
            hora = "0" + hora;
        }
        if (minuto < 10) {
            minuto = "0" + minuto;
        }
        if (segundos < 10) {
            segundos = "0" + segundos;
        }
        $('#relogio').html(hora + ":" + minuto + ":" + segundos);
    }
</script>
@stack('js')
</body>
</html>
