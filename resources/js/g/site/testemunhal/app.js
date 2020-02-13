import Upload from '../../../components/Upload';
import configTinyMCE from '../../../components/configTinyMCE';
import Editor from '@tinymce/tinymce-vue';

const app = new Vue({
    el: '#app',
    components: {
        Upload,
        Editor
    },
    data: {
        config: configTinyMCE,
        tituloJanela: 'Cadastrando Depoimento',
        preloadAjax: false,
        editando: false,
        apagado: false,
        cadastrado: false,
        atualizado: false,

        urlAnexoUpload: `${URL_ADMIN}/testemunhal/uploadAnexos`,
        anexoUploadAndamento: false,

        form: {
            _method: '',
            nome: '',
            subtitulo: '',
            texto: '',
            site: true,
            ativo: true,

            anexo: [],
            anexoDel: []
        },

        formDefault: null,

        lista: [],

        controle: {
            carregando: false,
            dados: {
                pages: 20,
                campoBusca: '',
            },
        }
    },
    mounted() {
        this.formDefault = _.cloneDeep(this.form)//copia
        this.atualizar();
    },

    methods: {

        formNovo() {
            this.cadastrado = false;
            this.atualizado = false;
            this.editando = false;

            this.tituloJanela = "Cadastrando Depoimento";

            formReset();
            setupCampo();
            this.form = _.cloneDeep(this.formDefault) //copia
        },

        cadastrar() {
            $('#janelaCadastrar :input:visible').trigger('blur');
            if ($('#janelaCadastrar :input:visible.is-invalid').length) {
                alert('Verificar os erros');
                return false;
            }

            if (this.form.anexo.length === 0) {
                alert('faça um anexo de uma imagem');
                return false;
            }

            this.preloadAjax = true;
            $.post(`${URL_ADMIN}/testemunhal`, this.form)
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
            this.form.id = id;

            this.cadastrado = false;
            this.atualizado = false;
            this.editando = false;
            this.tituloJanela = "Alterando Depoimento";

            this.preloadAjax = true;

            formReset();
            $.get(`${URL_ADMIN}/testemunhal/${id}/editar`)
                .done((data) => {
                    Object.assign(this.form, data);
                    this.form.anexoDel = [];
                    this.editando = true;
                    this.preloadAjax = false;
                    setupCampo();
                })
                .fail((data) => {
                    this.preloadAjax = false;
                });
        },

        alterar() {
            $('#janelaCadastrar :input:visible').trigger('blur');
            if ($('#janelaCadastrar :input:visible.is-invalid').length) {
                alert('Verificar os erros');
                return false;
            }

            if (this.form.anexo.length === 0) {
                alert('faça um anexo de uma imagem');
                return false;
            }

            this.form._method = 'PUT';
            this.preloadAjax = true;

            $.post(`${URL_ADMIN}/testemunhal/${this.form.id}`, this.form)
                .done((data) => {
                    this.preloadAjax = false;
                    this.atualizado = true;
                    this.atualizar();
                })
                .fail((data) => {
                    this.preloadAjax = false;
                });

        },

        janelaConfirmar(id) {
            this.form.id = id;
            this.apagado = false;

            this.preloadAjax = false;
        },

        apagar() {
            this.erros = [];
            this.form._method = 'DELETE';
            this.preloadAjax = true;

            $.post(`${URL_ADMIN}/testemunhal/${this.form.id}`, this.form)
                .done((data) => {
                    this.preloadAjax = false;
                    this.apagado = true;
                    this.atualizar();
                })
                .fail((data) => {
                    this.preloadAjax = false;
                });
        },


        carregou(dados) {
            this.lista = dados;
            this.controle.carregando = false;
        },
        carregando() {
            this.controle.carregando = true;
        },
        atualizar() {
            this.$refs.componente.atual = 1;
            this.$refs.componente.buscar();
        },
    }
});
