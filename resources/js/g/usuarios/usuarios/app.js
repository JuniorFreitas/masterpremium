const app = new Vue({
    el: '#app',
    data: {
        tituloJanela: 'Cadastrando usuário',
        preloadAjax: false,
        editando: false,
        id: 0,

        cadastrado: false,
        atualizado: false,
        urlAjax: '',
        apagado: false,
        alterarSenha: false,

        lista: [],
        dados: {},
        controle: {
            carregando: false,
            dados: {},
        }
    },
    methods: {
        formNovo: function () {
            $('#form')[0].reset();
            this.cadastrado = false;
            this.atualizado = false;
            this.editando = false;
            this.tituloJanela = "Cadastrando usuário";
            formReset();
        },
        cadastrar: function () {

            $('#janelaCadastrar :input:visible:enabled').trigger('blur');
            if ($('#janelaCadastrar :input:visible:enabled.is-invalid').length) {
                alert('Verificar os erros');
                return false;
            }
            var dados = {};
            dados.nome = $('#nome').val();
            dados.login = $('#login').val();
            dados.password = $('#password').val();
            dados.password_confirmation = $('#password_confirmation').val();
            dados.grupo_id = $('#grupo_id').val();
            dados.ativo = $('#ativo').val();

            this.preloadAjax = true;

            $.post(URL_ADMIN + '/usuarios', dados)
                .done((data) => {
                    app.preloadAjax = false;
                    app.cadastrado = true;
                    $('#controle button:eq(0)').click();
                })
                .fail((data) => {
                    app.preloadAjax = false;
                });

        },
        formAlterar: function (id) {
            app.id = id;

            this.cadastrado = false;
            this.atualizado = false;
            this.editando = false;
            this.tituloJanela = "Alterando usuário";

            this.erros = [];
            this.preloadAjax = true;
            formReset();

            $.get(URL_ADMIN + '/usuarios/' + id + "/editar")
                .done((data) => {

                    $('#nome').val(data.nome);
                    $('#login').val(data.login);
                    $('#grupo_id').val(data.grupo_id);
                    $('#tipo_form').val(data.tipo);
                    $('#ativo').val(data.ativo.toString());
                    app.editando = true;
                    app.preloadAjax = false;
                })
                .fail((data) => {
                    app.preloadAjax = false;
                });


        },
        alterar: function () {

            $('#janelaCadastrar :input:visible:enabled').trigger('blur');
            if ($('#janelaCadastrar :input:visible:enabled.is-invalid').length) {
                alert('Verificar os erros');
                return false;
            }

            var dados = {};
            dados.nome = $('#nome').val();
            dados.login = $('#login').val();
            if (this.alterarSenha) {
                dados.alterarSenha = true;
                dados.password = $('#password').val();
                dados.password_confirmation = $('#password_confirmation').val();
            }
            dados.grupo_id = $('#grupo_id').val();
            dados.ativo = $('#ativo').val();
            dados._method = 'PUT';
            this.preloadAjax = true;

            $.post(URL_ADMIN + '/usuarios/' + this.id, dados)
                .done((data) => {
                    app.preloadAjax = false;
                    app.atualizado = true;
                    $('#controle button:eq(0)').click();
                })
                .fail((data) => {
                    app.preloadAjax = false;

                });
        },
        janelaConfirmar: function (id) {
            app.id = id;
            this.apagado = false;
            this.preloadAjax = false;
        },
        apagar: function () {
            var dados = {};
            dados._method = 'DELETE';
            this.preloadAjax = true;

            $.post(URL_ADMIN + '/usuarios/' + this.id, dados)
                .done((data) => {
                    app.preloadAjax = false;
                    app.apagado = true;
                    $('#controle button:eq(0)').click();
                })
                .fail((data) => {
                    app.preloadAjax = false;
                });
        },
        carregou: function (dados) {

            this.lista = _.map(dados, (obj) => {
                obj.preload = false;
                return obj;
            });
            this.controle.carregando = false;

        },
        carregando: function () {
            this.controle.carregando = true;
        }

    }
});


$().ready(function () {

    $('#janelaCadastrar').on('shown.bs.modal', function () {
        $('#nome').focus(); // ja foca no nome quando a janela abrir
    });
    $('#btnAtualizar').on('click', atualizar);
    atualizar();

    $('#formBusca').on('submit', function (e) {
        e.preventDefault();
        app.controle.dados.campoBusca = $('#campoBusca').val();
        atualizar();
    });


});

function atualizar() {
    app.$refs.componente.atual = 1;
    app.$refs.componente.buscar();

}
