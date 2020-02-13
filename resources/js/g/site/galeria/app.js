import upload from '../../../components/Upload';

const app = new Vue({
    el: '#app',
    components: {
        upload
    },

    data: {
        tituloJanela: 'Cadastrando Galeria',
        preloadAjax: false,
        cadastrado: false,
        atualizado: false,
        apagado: false,
        editando: false,

        fotoUploadAndamento: false,

        form: {
            _method: '',
            titulo: '',
            descricao: '',
            ordem: null,
            ativo: true,
            fotos: [],
            fotosDel: []
        },
        formDefault: null,

        lista: [],
        dados: {},
        controle: {
            carregando: false,
            dados: {},
        }
    },
    mounted() {
        this.formDefault = _.cloneDeep(this.form) //copia
        this.atualizar();
    },
    computed: {
        uploadAndamento() {
            if (this.fotoUploadAndamento == true) {
                return true;
            }
            return false;
        }
    },
    methods: {
        formNovo() {
            this.cadastrado = false;
            this.atualizado = false;
            this.editando = false;
            this.tituloJanela = "Cadastrando Galeria";
            formReset();
            setupCampo();
            this.form = _.cloneDeep(this.formDefault) //copia
        },

        cadastrar() {
            $('#janelaCadastrar :input:visible').trigger('blur');
            if ($('#janelaCadastrar :input:visible.is-invalid').length) {
                mostraErro('Verificar os erros');
                return false;
            }

            this.preloadAjax = true;
            $.post(`${URL_ADMIN}/galeria`, this.form)
                .done((data) => {
                    this.preloadAjax = false;
                    this.cadastrado = true;
                    this.atualizar();
                })
                .fail((data) => {
                    this.preloadAjax = false;
                });
        },

        formAlterar(id) {
            this.formDefault = _.cloneDeep(this.form) //copia

            this.cadastrado = false;
            this.atualizado = false;
            this.editando = false;
            this.tituloJanela = "Alterando Galeria";

            this.preloadAjax = true;
            formReset();
            $.get(`${URL_ADMIN}/galeria/${id}/editar`)
                .done((data) => {
                    Object.assign(this.form, data);
                    this.editando = true;
                    this.preloadAjax = false;
                    setupCampo();
                })
                .fail((data) => {
                    this.preloadAjax = false;
                });
        },
        alterar: function () {
            $('#janelaCadastrar :input:visible').trigger('blur');
            if ($('#janelaCadastrar :input:visible.is-invalid').length) {
                mostraErro('Verificar os erros');
                return false;
            }

            this.form._method = 'PUT';
            this.preloadAjax = true;

            $.post(`${URL_ADMIN}/galeria/${this.form.id}`, this.form)
                .done((data) => {
                    this.preloadAjax = false;
                    this.atualizado = true;
                    this.atualizar();
                })
                .fail((data) => {
                    this.preloadAjax = false;
                });
        },
        apagar: function () {
            this.erros = [];
            this.form._method = 'DELETE';
            this.preloadAjax = true;

            $.post(`${URL_ADMIN}/galeria/${this.form.id}`, this.form)
                .done((data) => {
                    this.preloadAjax = false;
                    this.apagado = true;
                    this.atualizar();
                })
                .fail((data) => {
                    this.preloadAjax = false;
                });
        },
        janelaConfirmar: function (id) {
            this.form.id = id;
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
        atualizar: function () {
            this.$refs.componente.atual = 1;
            this.$refs.componente.buscar();
        },
    }

});
