@inject('Sistema', 'App\Models\Sistema')
<ul class="navbar-nav mr-auto">

    @if($Sistema::permitirLinks('clientes'))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                ADMINISTRAÇÃO
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <span class="dropdown-menu-arrow"></span>
                @can('clientes')
                    <a href="{{route('g.administracao.clientes.index')}}" class="dropdown-item text-default">
                        Clientes
                    </a>
                @endcan
            </div>
        </li>
    @endif


    @if($Sistema::permitirLinks('galeria_site'))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                SITE
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <span class="dropdown-menu-arrow"></span>
                @can('galeria_site')
                    <a href="{{route('g.site.galeria.index')}}" class="dropdown-item text-default">
                        Galeria de Fotos do sistema
                    </a>
                @endcan

                @can('cartela_cliente_site')
                    <a href="{{route('g.site.cliente.cliente-logo.index')}}" class="dropdown-item text-default">
                        Cartela Cliente
                    </a>
                @endcan

                @can('depoimento_site')
                    <a href="{{route('g.site.testemunhal.testemunhal.index')}}" class="dropdown-item text-default">
                        Depoimento
                    </a>
                @endcan
            </div>
        </li>
    @endif

    @if($Sistema::permitirLinks('usuarios','alterar-senha'))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                USUÁRIOS
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <span class="dropdown-menu-arrow"></span>
                @can('usuarios')<a href="{{route('g.usuarios.usuarios.index')}}" class="dropdown-item text-default">Usuários
                    do sistema</a>@endcan
                @can('alterar-senha')
                    <a href="{{route('g.usuarios.alterar-senha.index')}}" class="dropdown-item text-default">Alterar
                        senha de acesso</a>@endcan
            </div>
        </li>
    @endif


    @if($Sistema::permitirLinks('papel','habilidades'))
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                CONFIGURAÇÕES
            </a>

            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <span class="dropdown-menu-arrow"></span>
                @can('papel')<a href="{{route('g.configuracoes.papeis.index')}}" class="dropdown-item text-default">Grupos
                    de usuários</a>@endcan
                @can('habilidades')<a href="{{route('g.configuracoes.habilidades.index')}}"
                                      class="dropdown-item text-default">Módulos do sistema</a>@endcan
            </div>

        </li>
    @endif

</ul>
