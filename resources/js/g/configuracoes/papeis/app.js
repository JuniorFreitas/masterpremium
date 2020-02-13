const app = new Vue({
    el: '#app',
    data: {
        tituloJanela: 'Cadastrando papeis',
        preloadAjax: false,
        editando: false,
        id: 0,//id_curso

        cadastrado: false,
        atualizado: false,
        urlAjax: '',
        apagado: false,

        lista: [],
        listaDeHabilidades: [],
        todasHabilidades: true,

        dados: {},
        controle: {
            carregando: false,
            dados: {},
        }
    },
    methods: {
        selecionarTodas: function () {
            this.todasHabilidades = !this.todasHabilidades;
            var valor = this.todasHabilidades;
            _.forEach(this.listaDeHabilidades, function (habilidade) {
                habilidade.acesso = valor;
            });
        },
        formNovo: function () {
            $('#janelaTitulo').html('Cadastrando papeis');
            $('#form')[0].reset();

            this.preloadAjax = true;
            this.cadastrado = false;
            this.atualizado = false;
            this.editando = false;
            formReset();

            $.get(`${URL_ADMIN}/papeis/novo`)
                .done((data) => {
                    this.listaDeHabilidades = data;
                    this.preloadAjax = false;
                });
        },
        cadastrar: function () {

            $('#janelaCadastrar :input:visible:enabled').trigger('blur');
            if ($('#janelaCadastrar :input:visible:enabled.is-invalid').length) {
                alert('Verificar os erros');
                return false;
            }

            var dados = {};
            dados.nome = $('#nome').val();
            dados.descricao = $('#descricao').val();
            dados.email = $('#email').val();
            dados.ativo = $('#ativo').val();
            dados.listaDeHabilidades = this.listaDeHabilidades;
            this.preloadAjax = true;

            $.post(`${URL_ADMIN}/papeis`, dados)
                .done((data) => {
                    app.preloadAjax = false;
                    app.cadastrado = true;
                    $('#controle button:eq(0)').click();
                });
        },
        formAlterar: function (id) {
            app.id = id;

            $('#janelaTitulo').html('Alterando papeis');
            this.cadastrado = false;
            this.atualizado = false;
            this.editando = false;

            this.preloadAjax = true;

            $.get(`${URL_ADMIN}/papeis/${id}/editar`)
                .done((data) => {

                    app.preloadAjax = false;
                    this.listaDeHabilidades = data.listaDeHabilidade;

                    $('#nome').val(data.papel.nome);
                    $('#descricao').val(data.papel.descricao);
                    $('#email').val(data.papel.email);
                    $('#ativo').val(data.papel.ativo.toString());

                    //ligando os botoes
                    var habilidades_papel = data.papel.habilidades;
                    _.forEach(app.listaDeHabilidades, function (habilidade) {
                        var achou = _.find(habilidades_papel, {'id': habilidade.id});
                        if (achou) {
                            habilidade.acesso = true;
                        }
                    });

                    app.editando = true;

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
            dados.descricao = $('#descricao').val();
            dados.email = $('#email').val();
            dados.ativo = $('#ativo').val();
            dados.listaDeHabilidades = this.listaDeHabilidades;
            dados._method = 'PUT';
            this.preloadAjax = true;

            $.post(`${URL_ADMIN}/papeis/${this.id}`, dados)
                .done((data) => {
                    this.preloadAjax = false;
                    this.atualizado = true;
                    $('#controle button:eq(0)').click();

                });
        },
        janelaConfirmar: function (id) {
            app.id = id;
            this.apagado = false;
            this.preloadAjax = false;
        },
        apagar: function () {
            this.erros = [];
            var dados = {};
            dados._method = 'DELETE';
            this.preloadAjax = true;

            $.post(`${URL_ADMIN}/papeis/${this.id}`, dados)
                .done((data) => {

                    app.preloadAjax = false;
                    app.apagado = true;
                    $('#controle button:eq(0)').click();

                });
        },

        carregou: function (dados) {

            this.lista = dados;
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
