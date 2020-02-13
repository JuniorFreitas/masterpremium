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
            _method: 'PUT',
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
        this.formAlterar();

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
        formAlterar() {
            this.form.fotosDel = [];
            this.formDefault = _.cloneDeep(this.form) //copia
            this.preloadAjax = true;
            formReset();
            axios.get(`${URL_ADMIN}/cliente-logo/1/editar`)
                .then(response => {
                    Object.assign(this.form, response.data);
                })
                .catch(error => {
                });
        },

        alterar(id) {
            this.form._method = 'PUT';
            axios.put(`${URL_ADMIN}/cliente-logo/1`, this.form)
                .then(response => {
                    mostraSucesso('', 'Registro Salvo com sucesso')
                    this.formAlterar();
                })
                .catch(error => {
                });
        },
    }

});
