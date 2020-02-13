<header class="top-header" id="top" style="background:#333333;">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-6 col-xl-6 hidden-xsd-none d-sm-block">
                <div class="list-inline" style="color: #d8d8d8"><span
                        style="text-decoration: none;margin-right: 15px; font-size: 11px;"><i class="fa fa-phone"></i> (98) 3236-9959</span>
                    <span
                        style="text-decoration: none;margin-right: 15px; font-size: 11px;"> <i
                            class="fa fa-envelope"></i> contato@alunodofuturo.com.br</span>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6 col-xl-6">
                <ul class="top-social-media float-right">@if(!Auth::check())
                        <li><a href="" class="sign-in"><i class="fa fa-sign-in"></i> Entrar</a>
                        </li>
                        <li><a href="" class="sign-in"><i class="fa fa-user"></i>

                                Registrar</a>
                        </li>@else
                        <li>{{Auth::user()-&gt;name}}</li>
                        <li><a href="{{route(&apos;logout&apos;)}}" class="sign-in"><i class="fa fa-sign-out"></i>

                                Sair</a>
                        </li>@endif</ul>
            </div>
        </div>
    </div>
</header>
<header class="main-header" style="background: rgba(255,255,255,0.9);">
    <div class="container">
        <nav class="navbar navbar-light bg-light navbar-expand-md">
            <button type="button" class="navbar-toggler collapsed" data-toggle="collapse"
                    data-target="#app-navigation" aria-expanded="false"><span class="sr-only">Toggle navigation</span>
                &#x2630;
            </button>
            <a href="{{assets(&apos;/&apos;)}}"
               class="logo">

                <img src="{{assets(&apos;img/logos/logo.svg&apos;)}}" alt="Logo Aluno do Futuro" title="Logo">

            </a>
            <div class="navbar-collapse collapse" role="navigation"
                 aria-expanded="true" id="app-navigation">
                <ul class="nav navbar-nav">@if(!Auth::check()) @include(&apos;menu.site&apos;) @else @foreach(menus()
                    as $bt) {{-- @if(getIsRouter($bt[&apos;route&apos;]))--}}
                    <li class="dropdown {{(url()-&gt;current()==route($bt[&apos;rota&apos;]) ?  nav-item"
                    active "=" " :=" " " ")}}"=""> <a
                        href="{{(empty($bt[&apos;rota&apos;]) ? &apos;&apos; : route($bt[&apos;rota&apos;]))}}"
                    @if(isset($bt[ 'dropdown'])="" ||="" !empty($bt[ 'dropdown']))="" tabindex="0"
                    data-toggle="dropdown" data-submenu="" aria-expanded="false" @endif=""
                    class="nav-link">

                    <i class="{!!$bt[&apos;icon&apos;]!!} d-none d-md-block"></i> <br class="d-none d-md-block">

                    <span>{{mb_strtoupper($bt[&apos;titulo&apos;])}}</span>

                    </a>
                    @if(isset($bt[&apos;dropdown&apos;])
                                            || !empty($bt[&apos;dropdown&apos;]))
                        <ul class="dropdown-menu">@for($i=1; $i
                            <=count($bt[ 'dropdown']) 2;="" $i++)=""
                            <li="" class="dropdown-submenu"> <a
                                href="{{route($bt[&apos;dropdown&apos;][&apos;rota&apos;.$i])}}"
                                tabindex="0">{{mb_strtoupper($bt[&apos;dropdown&apos;][&apos;titulo&apos;.$i])}}</a>
                        </=count($bt[
                        'dropdown'])>
                </ul>
                </li>@endfor</ul>@endif {{--@endif--}} @endforeach @endif {{--{{getMenu()}}--}}</div>
        </nav>
    </div>
</header>
