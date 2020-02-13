<template>
    <div>
        <modal id="janelaVisualizadora" :size="90" :titulo="titulojanelavisualizar">
            <template slot="conteudo">
                <div class="col-12" v-if="exibindo && exibindo.imagem">
                    <img :src="exibindo.url" class="img-fluid d-flex mx-auto">
                </div>
                <div class="row" v-if="exibindo && !exibindo.imagem">
                    <div id="quadrado"
                         style=" color: rgb(255, 255, 255); visibility: visible; position: absolute; z-index: 8; top: 28px; left: auto; padding: 9px; text-align: center; width: 41px; right: 13px; background: rgb(0, 0, 0); opacity: 1;">
                        <i class="fa fa-eye"></i></div>
                    <iframe :src="`https://docs.google.com/viewer?url=${exibindo.url}?file=fdg46fgd&embedded=true`"
                            v-show="!exibindo.imagem" frameborder="0" style="height: 70vh; width: 100%"></iframe>
                </div>
            </template>
            <template slot="rodape">
                <!--                <a :href="urlBase+srcDownload" download class="btn btn-outline-default"><i class="fa fa-download"></i> Download</a>-->
            </template>
        </modal>

        <modal id="janelaDetalhes" titulo="Detalhes">
            <template slot="conteudo">
                <fieldset v-if="detalhes">
                    <legend>Expecificações</legend>
                    <p style="font-size: 11pt;">
                        Nome: {{ detalhes.label }}{{ detalhes.arquivo.extensao }} <br>
                        Tamanho: {{ detalhes.arquivo.bytes | formatBytes }} <br>
                        Data Criação: {{detalhes.created_at}} <br>
                        Lançado por: {{detalhes.criou.nome}} <br>
                        <span v-if="detalhes.editou">Atualizado por: {{detalhes.editou.nome}} <br>
                       Data da Atualização: {{detalhes.updated_at}}
                       </span>
                    </p>
                </fieldset>
            </template>
        </modal>


        <modal id="janelaCadastrarPasta" :titulo="tituloNovaPasta" size="g" :fechar="!preload_pasta">
            <template slot="conteudo">
                <div class="alert alert-success text-center" v-show="cadastrado">
                    <h4><i class="icon fa fa-check"></i> Pasta criada com sucesso!</h4>
                </div>

                <div class="alert alert-success text-center" v-show="atualizado">
                    <h4><i class="icon fa fa-check"></i> Alteração realizada com sucesso!</h4>
                </div>
                <p class=" mt-2 text-center" v-show="preload_pasta"><i class="fa fa-spinner fa-pulse"></i>Carregando...
                </p>
                <div v-if="!preload_pasta && (!cadastrado && !atualizado)">
                    <div v-if="!preload_pasta">
                        <div class="row">
                            <div class="col-12">
                                <fieldset>
                                    <legend>NOME</legend>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" v-model="form.label"
                                                   placeholder="Nome"
                                                   onblur="valida_campo_vazio(this,1)">
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-12">
                                <fieldset>
                                    <legend>GRUPOS PERMITIDOS</legend>

                                    <div class="table-responsive">
                                        <table class="table table-bordered bg-white">
                                            <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Descrição</th>
                                                <th class="text-center">
                                                    <a class="btn btn-success" href="javascript://"
                                                       @click.prevent="selecionarTodos" v-if="!form.todosGrupos">
                                                        <span class="fa fa-ok" aria-hidden="true"></span> Todos
                                                    </a>
                                                    <a class="btn btn-danger" href="javascript://"
                                                       @click.prevent="selecionarTodos" v-if="form.todosGrupos">
                                                        <span class="fa fa-remove" aria-hidden="true"></span> Nenhum
                                                    </a>
                                                </th>
                                            </tr>
                                            </thead>

                                            <tbody>

                                            <tr v-for="papel in papeis">
                                                <td>{{papel.nome}}</td>
                                                <td>{{papel.descricao}}</td>
                                                <td class="text-center">
                                                    <a class="btn btn-success" href="#"
                                                       @click.prevent="papel.permitido = !papel.permitido; removePermissao(papel)"
                                                       v-if="papel.permitido">
                                                        <span class="fa fa-ok" aria-hidden="true"></span> Permitido
                                                    </a>
                                                    <a class="btn btn-danger" href="#"
                                                       @click.prevent="papel.permitido = !papel.permitido; adicionaPermissao(papel)"
                                                       v-if="!papel.permitido">
                                                        <span class="fa fa-remove" aria-hidden="true"></span> Negado
                                                    </a>
                                                </td>

                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template slot="rodape">
                <button type="button" class="btn btn-primary" v-show="editando && !atualizado && (!preload_pasta)"
                        @click="alterarPasta()">
                    Alterar
                </button>
                <button type="button" class="btn btn-primary" v-show="!editando && !cadastrado && (!preload_pasta)"
                        @click="criaPasta">
                    <i class="far fa-save"></i> Salvar
                </button>
            </template>
        </modal>

        <!-- Modal confirmar -->
        <modal id="janelaConfirmar" :titulo="tituloApagar">
            <template slot="conteudo">
            <span v-show="preloadDel">
                <i class="fa fa-spinner fa-pulse"></i> Carregando...
            </span>

                <div class="alert alert-success alert-dismissible" v-show="apagado">

                    <h4>
                        <i class="icon fa fa-check"></i>
                        Registro apagado com sucesso!
                    </h4>

                </div>

                <h4 v-show="!apagado && !preloadDel" class="text-center">
                    Tem certeza que deseja apagar {{ form.tipo === 'pasta' ? 'esta pasta' : 'este arquivo' }} - <strong>{{
                    form.label }}</strong>?
                    <br>
                    <br>
                    <p class="text-danger" v-show="form.tipo === 'pasta'">
                        ATENÇÃO: Todas os arquivos e pastas internas serão removidos
                    </p>
                </h4>
            </template>
            <template slot="rodape">
                <div v-show="!preloadDel">
                    <button type="button" class="btn btn-danger" @click="apagar()" v-show="!apagado">Apagar</button>
                </div>
            </template>
        </modal>

        <button class="btn btn-outline-default"
                :disabled="preload"
                @click.prevent="formNovaPasta"
                data-toggle="modal"
                data-target="#janelaCadastrarPasta">
            <i class="fas fa-folder-plus"></i> Nova Pasta
        </button>

        <button class="btn btn-outline-default" :disabled="preload" @click="atualizar">
            <i class="fas fa-sync"></i> Atualizar
        </button>

        <!--<button class="btn btn-outline-default" :disabled="preload" @click="atualizar">
            <i class="fas fa-upload"></i> Upload
        </button>-->

        <upload v-show="itemBusca !== '' && !preload" label="Upload" :model="arquivosUpload" :url="urlArquivoUpload"
                @onprogresso="(arquivo) => {arquivoUploadAtual = arquivo}"
                @onprogressogeral="(info)=>{pctGeral=info.pct}"
                @onfinalizado="uploadFinalizado" :simples="true"
                :dados-ajax="{cloud_id: cloud, pertence_id: itemBusca}"
        ></upload>

        <div class="progress" style="height: 15px;" v-if="arquivosUpload.length > 0">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                 :style="{'width': pctGeral + '%'}" :aria-valuenow="pctGeral" aria-valuemin="0" aria-valuemax="100">
                {{ pctGeral }}%
            </div>
        </div>

        <div class="mb-3" v-if="arquivosUpload.length > 0 && arquivoUploadAtual!=null">
            <span>{{ arquivoUploadAtual.nome }}</span>
            <br>
            <div class="progress" style="height: 1px;">
                <div class="progress-bar" role="progressbar" :style="{'width': arquivoUploadAtual.pctProgresso + '%'}"
                     :aria-valuenow="arquivoUploadAtual.pctProgresso" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>

        <!--Caminho de Rato-->
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb"
                        style="margin-top: 10px; margin-bottom: 0px; padding: 0.2rem 1rem; font-size: 13.5px;">
                        <li class="breadcrumb-item" :class="index === Number.MAX_VALUE ? 'active' : ''"
                            v-for="(folder,index) in caminho">
                            <button class="btn btn-outline-default border-0" :disabled="preload"
                                    @click.prevent="abriPasta(folder.id); caminho.splice(index+1)">{{folder.label}}
                            </button>

                        </li>
                        <!--                        <li class="breadcrumb-item active" aria-current="page">Biblioteca</li>-->

                    </ol>
                </nav>

                <!--<a href="javascript://" v-for="(folder,index) in caminho"
                   @click.prevent="abriPasta(folder.id); caminho.splice(index+1,Number.MAX_VALUE)">{{folder.label}}</a>-->
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr class="bg-default">
                            <th>Nome</th>
                            <!--                            <th class="text-center">Tamanho</th>-->
                            <th class="text-center">Data de Criação</th>
                            <th class="text-center">Criado por</th>
                            <th class="text-center">Última Atualização</th>
                            <th class="text-center">Atualizado por</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-if="preload">
                            <td colspan="4">
                                <i class="fas fa-circle-notch fa-spin mr-1"></i> Carregando...
                            </td>
                        </tr>
                        <tr v-if="!preload && itemBusca ">
                            <td>
                                <button class="btn btn-outline-default border-0" :disabled="preload"
                                        @click="abriPasta(anterior);caminho.pop()">
                                    <i class="fa-fw mr-1 fas fa-folder-open" style="color: #EECD6D"></i> ..
                                </button>
                            </td>
                        </tr>
                        <tr v-for="(item, index) in lista" v-if="!preload && lista.length > 0 && item.TemPermissao">
                            <td>
                                <span v-if="item.tipo === 'pasta'">
                                    <button class="btn btn-outline-default border-0"
                                            @click="abriPasta(item.id);adicionaCaminho(item)">
                                        <i class="fas fa-folder mr-1" style="color: #EECD6D"></i> {{item.label}}
                                    </button>
                                    <br/>
                                    <!--                                    <button class="btn btn-outline-default btn-sm mt-1 mb-1">Editar</button>-->
                                    <!--                                    <button class="btn btn-outline-default btn-sm mt-1 mb-1">Remover</button>-->
                                    <!--                                    <i class="far fa-folder mr-1"></i> {{item.label}}-->
                                </span>
                                <div v-if="item.tipo === 'arquivo'">
                                    <span style="font-size: 11pt;">{{item.label}}{{item.arquivo.extensao}}
                                    </span>

                                    <!--<span v-show="
                                    item.arquivo.extensao !== '.jpg' &&
                                    item.arquivo.extensao !== '.jpeg' &&
                                    item.arquivo.extensao !== '.gif' &&
                                    item.arquivo.extensao !== '.png' &&
                                    item.arquivo.extensao !== '.bmp' &&
                                    item.arquivo.extensao !== '.pdf'
                                    ">
                                        <i class="far fa-file-alt fa-2x ml-1 mr-1"
                                           v-show="
                                        item.arquivo.extensao !== '.ppt' &&
                                        item.arquivo.extensao !== '.pptx' &&
                                        item.arquivo.extensao !== '.doc' &&
                                        item.arquivo.extensao !== '.docx' &&
                                        item.arquivo.extensao !== '.xls' &&
                                        item.arquivo.extensao !== '.xlsx'"
                                        ></i>

                                        <i class="far fa-file-word fa-2x ml-1 mr-1"
                                           v-show="
                                           item.arquivo.extensao === '.doc' ||
                                           item.arquivo.extensao === '.docx'">
                                        </i>

                                        <i class="far fa-file-powerpoint fa-2x ml-1 mr-1"
                                           v-show="
                                           item.arquivo.extensao === '.ppt' ||
                                           item.arquivo.extensao === '.pptx'">
                                        </i>

                                        <i class="far fa-file-excel fa-2x ml-1 mr-1"
                                           v-show="
                                           item.arquivo.extensao === '.xls' &&
                                           item.arquivo.extensao === '.xlsx'">
                                        </i>
                                        &lt;!&ndash;                                        <a :href="item.arquivo.urlDownload" download="">{{item.label}}{{item.arquivo.extensao}}</a>&ndash;&gt;
                                        &lt;!&ndash;                                        <a :href="item.arquivo.url"&ndash;&gt;
                                        &lt;!&ndash;                                           download="">{{item.label}}{{item.arquivo.extensao}}</a>&ndash;&gt;
                                        <a href="javascript://" data-toggle="modal"
                                           @click.prevent="visualizar(item.arquivo)"
                                           data-target="#janelaVisualizadora">{{item.label}}{{item.arquivo.extensao}}
                                        </a>
                                    </span>

                                    <span v-show="
                                    item.arquivo.extensao === '.jpg' ||
                                    item.arquivo.extensao === '.jpeg' ||
                                    item.arquivo.extensao === '.gif' ||
                                    item.arquivo.extensao === '.png' ||
                                    item.arquivo.extensao === '.bmp'
                                    ">
                                        <img :src="item.arquivo.urlThumb" width="30px">
                                        <a href="javascript://" data-toggle="modal"
                                           @click.prevent="visualizar(item.arquivo)"
                                           data-target="#janelaVisualizadora">{{item.label}}{{item.arquivo.extensao}}
                                        </a>
                                    </span>

                                     <span v-show="item.arquivo.extensao === '.pdf'">
                                         <i class="far fa-file-pdf fa-2x ml-1 mr-1"></i>
                                         <a href="javascript://" data-toggle="modal"
                                            @click.prevent="visualizar(item.arquivo)"
                                            data-target="#janelaVisualizadora">{{item.label}}{{item.arquivo.extensao}}
                                        </a>
                                         &lt;!&ndash;                                         <a :href="item.arquivo.url" target="_blank">{{item.label}}{{item.arquivo.extensao}}</a>&ndash;&gt;
                                    </span>-->

                                    <br/>
                                    <a :href="`${url_publico}/anexoDownload/${item.arquivo.file}`" download
                                       class="btn btn-outline-default btn-sm mt-2 mb-2">
                                        Download
                                    </a>
                                    <button class="btn btn-outline-default btn-sm mt-2 mb-2"
                                            data-toggle="modal"
                                            @click.prevent="visualizar(item.arquivo)"
                                            data-target="#janelaVisualizadora">Visualizar
                                    </button>
                                    <button class="btn btn-outline-default btn-sm mt-2 mb-2"
                                            data-toggle="modal"
                                            @click.prevent="exibirDetalhes(item)"
                                            data-target="#janelaDetalhes">Detalhes
                                    </button>
                                    <button class="btn btn-outline-default btn-sm mt-2 mb-2">Editar</button>
                                    <button class="btn btn-outline-default btn-sm mt-2 mb-2">Mover</button>
                                    <button class="btn btn-outline-default btn-sm mt-2 mb-2"
                                            @click.prevent="janelaConfirmar(item)"
                                            data-toggle="modal"
                                            data-target="#janelaConfirmar"
                                    >Deletar
                                    </button>
                                    <button class="btn btn-outline-default btn-sm mt-2 mb-2">Atualizar</button>
                                    <button class="btn btn-outline-default btn-sm mt-2 mb-2">Revisar</button>
                                    <button class="btn btn-outline-default btn-sm mt-2 mb-2">Aprovar</button>
                                </div>

                            </td>
                            <!--                            <td class="text-center">-->
                            <!--                                <span v-if="item.tipo === 'arquivo'">{{item.arquivo.bytes | formatBytes}}</span>-->
                            <!--                            </td>-->
                            <td class="text-center" v-if="item.tipo === 'pasta'">{{item.created_at}}</td>
                            <td class="text-center" v-if="item.tipo === 'pasta'">{{item.criou ? item.criou.nome : ""}}
                            </td>
                            <td class="text-center" v-if="item.tipo === 'pasta'">{{item.updated_at}}</td>
                            <td class="text-center" v-if="item.tipo === 'pasta'">{{item.editou ? item.editou.nome :
                                ""}}
                            </td>
                            <td class="text-right" :colspan="item.tipo === 'arquivo' ? 5 : 0">
                                <!--                                <a href="javascript://" class="btn btn-success btnFormAlterar"-->
                                <!--                                   @click.prevent="formAlterar(bairro.id)"-->
                                <!--                                   data-toggle="modal"-->
                                <!--                                   data-target="#janelaCadastrar">-->
                                <!--                                    <i class="fa fa-edit" aria-hidden="true"></i> Alterar-->
                                <!--                                </a>-->


                                <button class="btn btn-outline-dark" v-if="item.tipo === 'pasta'"
                                        @click.prevent="formAlterarPasta(item.id)"
                                        data-toggle="modal"
                                        data-target="#janelaCadastrarPasta"
                                >
                                    <i class="fa fa-edit"></i>
                                </button>

                                <button class="btn btn-outline-danger" v-if="item.tipo === 'pasta'"
                                        @click.prevent="janelaConfirmar(item)"
                                        data-toggle="modal"
                                        data-target="#janelaConfirmar">
                                    <!-- Excluir-->
                                    <i class="fa fa-trash" aria-hidden="true"></i>

                                </button>

                                <!--<template v-if="item.TemPermissao || item.tipo === 'arquivo'">

                                    <button class="btn btn-outline-dark"
                                            @click.prevent="janelaConfirmar(item.id)"
                                            data-toggle="modal"
                                            data-target="#janelaConfirmar"
                                    >
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <button class="btn btn-outline-dark"
                                            @click.prevent="janelaConfirmar(item.id)"
                                            data-toggle="modal"
                                            data-target="#janelaConfirmar"
                                            v-if="item.tipo == 'pasta'"
                                    >
                                        &lt;!&ndash; Permissoes Grupo&ndash;&gt;
                                        <i class="fas fa-user-lock"></i>
                                    </button>

                                    <button class="btn btn-outline-dark"
                                            @click.prevent="janelaConfirmar(item.id)"
                                            data-toggle="modal"
                                            data-target="#janelaConfirmar"
                                            v-if="item.tipo == 'arquivo'"
                                    >
                                        &lt;!&ndash;                                    Download&ndash;&gt;
                                        <i class="fa fa-download" aria-hidden="true"></i>

                                    </button>


                                </template>

                                <template v-if="!item.TemPermissao && item.tipo == 'pasta'">
                                    <i class="fas fa-lock"></i>
                                </template>-->
                            </td>

                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import upload from '../components/Upload';
    import modal from '../components/Modal';

    export default {
        components: {
            upload,
            modal,
        },
        props: {
            cloud: {
                type: Number,
                required: true,
                default: () => '',
            },

            itemBusca: {
                type: Number | String,
                required: false,
                default: () => '',
            },
        },
        data() {
            return {
                url_site: '',
                crsf: '',
                tituloNovaPasta: '',
                tituloApagar: '',
                titulojanelavisualizar: '',
                cadastrado: false,
                editando: false,
                atualizado: false,
                apagado: false,

                preload: false,
                preload_pasta: false,
                preloadDel: false,

                caminho: [],

                src: '',
                srcDownload: '',
                imagem: true,

                exibindo: null,
                detalhes: null,

                form: {
                    _method: '',
                    id: 0,
                    cloud_id: 0,
                    label: '',
                    tipo: 'pasta',
                    pertence: null,
                    todosGrupos: false,
                    permissoes: [],

                },

                arquivosUpload: [],
                urlArquivoUpload: `${URL_ADMIN}/cloud/uploadAnexos`,
                pctGeral: 0,
                arquivoUploadAtual: null,

                formDefault: null,

                anterior: '',
                anteriorListaVazia: '',

                lista: [],
                papeis: []
            }
        },
        computed: {
            urlBase() {
                return `${URL_ADMIN}/cloud/anexoDownload/`;
            }
        },
        mounted() {
            this.form.cloud_id = this.cloud; // incluindo o id do Cloud
            this.atualizar();
            this.formDefault = _.cloneDeep(this.form) //copia

            this.csrf = CSRF_token;
            this.url_publico = `${URL_PUBLICO}/cloud`;

            let raiz = {
                label: "Inicio",
                id: ""
            };

            this.caminho.push(raiz);
            // document.oncontextmenu = document.body.oncontextmenu = function() {return false;}

        },
        filters: {
            formatBytes(bytes, decimals, kib) {
                kib = kib || false;
                if (bytes === 0) return '0 Bytes';
                if (isNaN(parseFloat(bytes)) && !isFinite(bytes)) return 'Not an number';
                const k = kib ? 1024 : 1000;
                const dm = decimals || 2;
                const sizes = kib ? ['Bytes', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB', 'BiB'] : ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB', 'BB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));

                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            }
        },

        methods: {
            uploadFinalizado() {
                this.arquivosUpload = [];
                setTimeout(() => {
                    this.atualizar();
                }, 100)

            },
            adicionaPermissao(id) {
                this.form.permissoes.push(id);
            },
            removePermissao(id) {
                let index = _.indexOf(this.form.permissoes, id);
                this.form.permissoes.splice(index, 1);
            },
            //
            selecionarTodos: function () {
                this.form.todosGrupos = !this.form.todosGrupos;
                // var valor = this.todasHabilidades;
                _.forEach(this.papeis, (papel) => {
                    papel.permitido = this.form.todosGrupos;
                    if (this.form.todosGrupos) {
                        this.adicionaPermissao(papel);
                    } else {
                        this.form.permissoes = []
                    }
                });
            },
            visualizar(obj) {
                this.exibindo = null;
                this.titulojanelavisualizar = `Visualizando`;
                this.exibindo = _.cloneDeep(obj);
            },
            exibirDetalhes(obj) {
                this.detalhes = null;
                this.detalhes = _.cloneDeep(obj);
            },
            formNovaPasta() {
                this.cadastrado = false;
                this.atualizado = false;
                this.editando = false;

                this.tituloNovaPasta = "NOVA PASTA";

                formReset();
                setupCampo();
                this.form = _.cloneDeep(this.formDefault) //copia
                this.form.pertence = this.itemBusca;

                // desmarco todos
                _.forEach(this.papeis, function (papel) {
                    papel.permitido = false;
                });

            },
            criaPasta() {
                $('#janelaCadastrarPasta :input:visible').trigger('blur');
                if ($('#janelaCadastrarPasta :input:visible.is-invalid').length) {
                    alert('Verificar os erros');
                    return false;
                }
                this.preload_pasta = true;
                axios.post(`${URL_ADMIN}/itenscloud/`, this.form)
                    .then(response => {
                        this.preload_pasta = false;
                        this.cadastrado = true;
                        this.atualizar();
                    }).catch(error => (this.preload_pasta = false));
            },
            formAlterarPasta(id) {
                this.form.id = id;

                this.cadastrado = false;
                this.atualizado = false;
                this.editando = true;
                this.tituloNovaPasta = "Alterando";

                this.preload_pasta = true;

                // desmarco todos
                _.forEach(this.papeis, function (papel) {
                    papel.permitido = false;
                });

                Object.assign(this.form, this.formDefault);
                formReset();
                $.get(`${URL_ADMIN}/itenscloud/${id}/editar`)
                    .done((data) => {
                        Object.assign(this.form, data);
                        this.tituloNovaPasta = `Alterando - ${data.label}`;
                        setupCampo();

                        // muda para todos se for igual a qnt de papel
                        this.form.todosGrupos = data.permissoes.length - 2 === this.papeis.length;

                        //ligando os botoes
                        _.forEach(this.papeis, function (papel) {
                            let achou = _.find(data.permissoes, {'id': papel.id});
                            if (achou) {
                                papel.permitido = true;
                            }
                        });

                        this.preload_pasta = false;
                    })
                    .fail((data) => {
                        this.preload_pasta = false;
                    });
            },
            alterarPasta() {
                $('#janelaCadastrarPasta :input:visible').trigger('blur');
                if ($('#janelaCadastrarPasta :input:visible.is-invalid').length) {
                    alert('Verificar os erros');
                    return false;
                }
                this.form._method = 'PUT';
                this.preload_pasta = true;
                axios.put(`${URL_ADMIN}/itenscloud/${this.form.id}`, this.form)
                    .then(response => {
                        this.preload_pasta = false;
                        this.atualizado = true;
                        this.atualizar();
                    }).catch(error => (this.preload_pasta = false));
            },
            adicionaCaminho(item) {
                this.caminho.push(item);
            },
            abriPasta(id) {
                let itemBusca = this.itemBusca;
                this.anteriorListaVazia = itemBusca;
                // this.anterior = itemBusca;
                itemBusca = id;
                this.$emit("abri-pasta", itemBusca);

                // this.preload = true;
                setTimeout(() => {
                    this.atualizar();
                }, 50);

            },
            janelaConfirmar(obj) {
                this.form = obj;
                this.tituloApagar = `Apagar ${this.form.tipo}`;
                this.apagado = false;
                this.preloadDel = false;
            },
            apagar() {
                this.erros = [];
                this.form._method = 'DELETE';
                this.preloadDel = true;
                axios.delete(`${URL_ADMIN}/itenscloud/${this.form.id}`, this.form)
                    .then(response => {
                        this.preloadDel = false;
                        this.apagado = true;
                        this.atualizar();
                    });
            },
            atualizar() {
                this.preload = true;
                this.form.pertence = this.itemBusca; // incluindo a pasta
                axios.get(`${URL_ADMIN}/cloud/atualizar/${this.cloud}/${this.itemBusca}`)
                    .then(response => {
                        this.lista = response.data.lista;
                        //Nivel de Pastas
                        if (this.lista.length >= 1) {
                            if (!this.lista[0].pertence) {
                                this.anteriorListaVazia = this.anterior;
                            } else {
                                this.anterior = this.anterior != null ? this.lista[0].pertence.pertence : "";
                                if (this.anterior == null) {
                                    this.anterior = "";
                                }
                            }
                        } else {
                            this.anterior = this.anteriorListaVazia;
                        }
                        this.papeis = response.data.papeis;
                        this.preload = false;
                    }).catch(error => (this.preload = false));
            }
        }
    }
</script>

<style scoped>

</style>
