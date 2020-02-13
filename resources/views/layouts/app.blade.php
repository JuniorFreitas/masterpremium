<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{env('APP_NAME')}}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="my-login-page" style="background: url({{assets('imagens/bg-business.jpg')}});">
<section class="h-100">
    <div class="container h-100">
        <div class="row justify-content-md-center align-items-center h-100">
            <div class="card-wrapper">
                {{--<div class="brand">--}}
                {{--<img src="{{assets('imagens/logo_topo.png')}}" style="height: 100px; width: 100px;" class="img-fluid" alt="logo">--}}
                {{--</div>--}}
                <div class="card fat">
                    <div class="card-body">
                        <div style="margin-bottom: 20px" class="text-center">
                            <img src="{{assets('imagens/logo.png')}}" class="img-fluid" alt="logo">
                        </div>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="login">Usuário</label>
                                <input id="login" type="text"
                                       class="form-control{{ $errors->has('login') ? ' is-invalid' : '' }}" name="login"
                                       value="" required autofocus>
                                @if ($errors->has('login'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('login') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="password">Senha
                                    <a href="" class="float-right text-default">
                                        Esqueceu a Senha?
                                    </a>
                                </label>
                                <input id="password" type="password" class="form-control"
                                       name="password{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group" style="display: none">
                                <div class="custom-checkbox custom-control">
                                    <input type="checkbox" checked name="remember" id="remember"
                                           class="custom-control-input tbtn-default">
                                    <label for="remember" class="custom-control-label">Lembrar-me</label>
                                </div>
                            </div>

                            <div class="form-group m-0">
                                <button type="submit" class="btn btn-default btn-block">
                                    Login
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="footer">
                    <img src="{{assets('imagens/mastertag.svg')}}" height="30px" alt="MasterTag desenvolvimento">
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>
