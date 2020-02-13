const app = new Vue({
    el: '#app',
    data: {
        tituloJanela: 'Cadastrando habilidade',
        preloadAjax: false,
        editando: false,
        id: 0,//id_curso

        cadastrado: false,
        atualizado: false,
        urlAjax: '',
        apagado: false,

        erros: [],

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
            this.tituloJanela = "Cadastrando habilidade";
            formReset();
        },
        cadastrar: function () {
            //var erro=false;
            /*$('#janelaCadastrar :input:text').each(function(index){
                $(this).trigger('blur');
                if( $(this).hasClass('is-invalid') ){
                    erro=true;
                }
            });*!/
            /!*if(erro){
                alert('Verificar os erros');
                return false;
            }*/
            $('#janelaCadastrar :input:visible').trigger('blur');
            if ($('#janelaCadastrar :input:visible.is-invalid').length) {
                alert('Verificar os erros');
                return false;
            }
            this.erros = [];
            var dados = {};
            dados.nome = $('#nome').val();
            dados.descricao = $('#descricao').val();

            this.preloadAjax = true;
            $.post(URL_ADMIN + '/habilidades', dados, function (data) {
                app.preloadAjax = false;
                if (data.erro == 's') {
                    app.erros = data.erros;
                    alert(data.msg);
                } else {
                    app.cadastrado = true;
                    $('#controle button:eq(0)').click();
                }
            });
        },
        formAlterar: function (id) {
            app.id = id;

            this.cadastrado = false;
            this.atualizado = false;
            this.editando = false;
            this.tituloJanela = "Alterando habilidade";

            this.erros = [];
            this.preloadAjax = true;
            formReset();

            $.get(URL_ADMIN + '/habilidades/' + id + "/editar", null, function (data) {

                app.preloadAjax = false;
                if (data.erro == 's') {
                    app.erros = data.erros;
                    alert(data.msg);
                } else {
                    $('#nome').val(data.nome);
                    $('#descricao').val(data.descricao);
                    app.editando = true;
                }
            });


        },
        alterar: function () {

            $('#janelaCadastrar :input:visible').trigger('blur');
            if ($('#janelaCadastrar :input:visible.is-invalid').length) {
                alert('Verificar os erros');
                return false;
            }

            this.erros = [];
            var dados = {};
            dados.nome = $('#nome').val();
            dados.descricao = $('#descricao').val();
            dados._method = 'PUT';
            this.preloadAjax = true;

            $.post(URL_ADMIN + '/habilidades/' + this.id, dados, function (data) {

                app.preloadAjax = false;
                if (data.erro == 's') {
                    app.erros = data.erros;
                    alert(data.msg);
                } else {
                    app.atualizado = true;
                    $('#controle button:eq(0)').click();
                }
            });
        },
        janelaConfirmar: function (id) {
            app.id = id;
            this.apagado = false;

            this.erros = [];
            this.preloadAjax = false;
        },
        apagar: function () {
            this.erros = [];
            var dados = {};
            dados._method = 'DELETE';
            this.preloadAjax = true;

            $.post(URL_ADMIN + '/habilidades/' + this.id, dados, function (data) {

                app.preloadAjax = false;
                if (data.erro == 's') {
                    app.erros = data.erros;
                    alert(data.msg);
                } else {
                    app.apagado = true;
                    $('#controle button:eq(0)').click();
                }
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
