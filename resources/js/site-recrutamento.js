Vue.component('endereco', require('./components/Endereco'));
Vue.component('telefone', require('./components/Telefones'));
const app = new Vue({
    el: '#app',
    data: {
        tituloJanela: 'Recrutamento',
        preloadAjax: false,
        editando: false,
        existe: false,

        form: {
            _method: '',
            id: '',
            cpf: '',
            nome: '',
            cnh: '',
            nascimento: '',
            logradouro: '',
            complemento: '',
            bairro: '',
            municipio: '',
            uf: '',
            cep: '',
            email: '',
            formacao: 7,
            formacao_instituicao: '',
            formacao_curso: '',
            formacao_status: 'Concluido',
            vaga_pretendida: 1,

            qualificacoes: [],
            qualificacoesDelete: [],

            experiencias: [],
            experienciasDelete: [],

            telefones: [],
            telefonesDelete: []

        },
        formDefault: null,
        campoNome: null,

        cadastrado: false,
        atualizado: false,

        lista: [],

        controle: {
            carregando: false,
            dados: {},
        }
    },
    mounted: function () {
        this.formDefault = _.cloneDeep(this.form) //copia
    },
    methods: {
        addLIQualificacao: function () {
            let obj = {};
            obj.nova = true;
            obj.nome = '';
            obj.instituicao = '';
            obj.mes_conclusao = '01';
            obj.ano_conclusao = '2019';
            this.form.qualificacoes.push(obj);
        },
        removerLIQualificacao: function (index) {
            if (this.editando) {
                this.form.qualificacoesDelete.push(this.form.qualificacoes[index].id);
            }
            this.form.qualificacoes.splice(index, 1);
        },
        addLIExperiencia: function () {
            let obj = {};
            obj.nova = true;
            obj.empresa = '';
            obj.cargo = '';
            obj.principais_atv = '';
            obj.data_inicio = '';
            obj.data_fim = '';
            obj.referencia_nome = '';
            obj.referencia_telefone = '';
            this.form.experiencias.push(obj);
        },
        removerLIExperiencia: function (index) {
            if (this.editando) {
                this.form.experienciasDelete.push(this.form.experiencias[index].id);
            }
            this.form.experiencias.splice(index, 1);
        },
        formNovo: function () {
            this.cadastrado = false;
            this.atualizado = false;
            this.editando = false;

            this.tituloJanela = "Cadastrando a  uma Vaga";

            formReset();
            setupCampo();
            this.form = _.cloneDeep(this.formDefault) //copia
        },
        cadastrar: function () {
            $('#janelaCadastrar :input:visible').trigger('blur');
            if ($('#janelaCadastrar :input:visible.is-invalid').length) {
                alert('Verificar os erros');
                return false;
            }
            this.preloadAjax = true;
            $.post(`${URL_SITE}/`, this.form)
                .done((data) => {
                    app.preloadAjax = false;
                    app.cadastrado = true;
                    this.form = _.cloneDeep(this.formDefault)
                })
                .fail((data) => {
                    app.preloadAjax = false;
                });
        },
        formAlterar: function (id) {
            this.form.id = id;

            this.cadastrado = false;
            this.atualizado = false;
            this.editando = false;
            this.tituloJanela = "Alterando Cliente";

            this.preloadAjax = true;

            formReset();
            $.get(`${URL_SITE}/clientes/${id}/editar`)
                .done((data) => {
                    Object.assign(app.form, data);
                    app.editando = true;
                    app.preloadAjax = false;
                    setupCampo();
                })
                .fail((data) => {
                    app.preloadAjax = false;
                });
        },

        alterar: function () {
            $('#janelaCadastrar :input:visible').trigger('blur');
            if ($('#janelaCadastrar :input:visible.is-invalid').length) {
                alert('Verificar os erros');
                return false;
            }
            this.form._method = 'PUT';
            this.preloadAjax = true;

            $.post(`${URL_SITE}/clientes/${this.form.id}`, this.form)
                .done((data) => {
                    app.preloadAjax = false;
                    app.atualizado = true;
                    $('#controle button:eq(0)').click();
                })
                .fail((data) => {
                    app.preloadAjax = false;
                });

        },
        apagar: function () {
            this.erros = [];
            this.form._method = 'DELETE';
            this.preloadAjax = true;

            $.post(`${URL_SITE}/clientes/${this.form.id}`, this.form)
                .done((data) => {
                    console.log(data);
                    app.preloadAjax = false;
                    app.apagado = true;
                    $('#controle button:eq(0)').click();
                })
                .fail((data) => {
                    app.preloadAjax = false;
                    app.erros = data.erros;
                    mostraErro(data.responseJSON);
                });
        },

        janelaConfirmar: function (id) {
            app.form.id = id;
            this.apagado = false;

            this.preloadAjax = false;
        },
        carregou: function (dados) {
            this.lista = dados;
            this.controle.carregando = false;

        },
        carregando: function () {
            this.controle.carregando = true;
        },
        // verificaCpf: function () {
        //     if (!this.editando) {
        //         $.get(`${URL_SITE}/beneficiarios/buscar-cpf?cpf=${this.form.cpf}`)
        //             .done((data) => {
        //             })
        //             .fail((data) => {
        //             });
        //     }
        // },
        // verificaCnpj: function () {
        //     if (!this.editando) {
        //         $.get(`${URL_SITE}/beneficiarios/buscar-cnpj?cnpj=${this.form.cnpj}`)
        //             .done((data) => {
        //             })
        //             .fail((data) => {
        //             });
        //     }
        // }
    }
});

$().ready(function () {

    $('#janelaCadastrar').on('shown.bs.modal', function () {
        $('#cnpj').focus(); // ja foca no descricao quando a janela abrir
    });
    // $('#btnAtualizar').on('click', atualizar);
    // atualizar();


    $('#formBusca').on('submit', function (e) {
        e.preventDefault();
        // app.controle.dados.campoBusca = $('#campoBusca').val();
        // atualizar();
    });

});

// function atualizar() {
//     app.$refs.componente.atual = 1;
//     app.$refs.componente.buscar();
// }

