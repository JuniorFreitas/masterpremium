<template>
    <span>
        <button v-if="!leitura" @click="selecionar()" class="btn btn-outline-primary"
                :disabled="emAndamento || quantidadeMaxima"><i class="fas fa-upload"></i> {{label}}</button>
        <input type="file" style="display:none;" ref="file" @change="upload()" :disabled="emAndamento" v-bind="multiple"
               :accept="arquivosPermitidos" v-show="false">
        <div class="table-responsive" v-show="lista.length" v-if="!simples">
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                <tr class="bg-default">
                    <th class="text-center"></th>
                    <th class="text-center">Descrição</th>
                    <th class="text-center">Ações</th>
                    <th class="text-center" v-if="!leitura"></th>
                </tr>
                </thead>

                <draggable v-model="lista" element="tbody" :options="{draggable:'.linha',handle:'.mover'}">
                    <tr class="linha" :class="{ 'bg-warning': arquivo.falhou }" :key="index"
                        v-for="(arquivo, index) in lista">
                        <td class="text-center">
                            <!-- o !arquivo.chave é para os casos de editar, pois nao tem chave, ja veio do servidor  -->
                            <span v-show="!arquivo.imagem && ( !arquivo.chave || (arquivo.enviado && !arquivo.falhou))">
                                <i class="fas fa-file-word fa-4x" v-show="arquivo.extensao=='.doc'"></i>
                                <i class="fas fa-file-word fa-4x" v-show="arquivo.extensao=='.docx'"></i>
                                <i class="fas fa-file-pdf fa-4x" v-show="arquivo.extensao=='.pdf'"></i>
                            </span>

                            <span v-show="arquivo.imagem &&  ( !arquivo.chave || (arquivo.enviado && !arquivo.falhou))">
                                <img :src="arquivo.urlThumb" width="100">
                            </span>

                            <i class="fas fa-exclamation-triangle fa-4x" v-show="arquivo.falhou"></i>

                        </td>
                        <td class="text-center">
                            <div class="form-group">
                                <input type="text" :class="[ leitura ? 'form-control-plaintext': 'form-control' ]"
                                       :readonly="leitura"
                                       onblur="valida_campo_vazio(this,1)"
                                       v-model="arquivo.nome"
                                       autocomplete="off"
                                       :disabled="arquivo.enviando || arquivo.aguardando"
                                >
                            </div>
                        </td>
                        <td class="text-center">
                            <a :href="arquivo.url" target="_blank" class="btn btn-secondary"
                               v-show="!arquivo.chave || (arquivo.enviado && !arquivo.falhou)">
                                <i class="fas fa-search"></i> Visualizar
                            </a>
                            <a :href="arquivo.urlDownload" class="btn btn-secondary"
                               v-show="!arquivo.chave || (arquivo.enviado && !arquivo.falhou)">
                                <i class="fas fa-download"></i> Download
                            </a>
                            <button class="btn btn-secondary mover"
                                    v-show="(!arquivo.chave && ordenar) || ((arquivo.enviado && !arquivo.falhou) && ordenar)"
                                    v-if="!leitura">
                                    <span>
                                        <i class="fas fa-arrows-alt-v"></i> Mover
                                    </span>
                            </button>

                            <div class="progress" v-show="arquivo.enviando || arquivo.aguardando">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                     :style="{width:`${arquivo.pctProgresso}%`}" :aria-valuenow="arquivo.pctProgresso"
                                     aria-valuemin="0" aria-valuemax="100">
                                    <span v-if="arquivo.enviando">{{arquivo.pctProgresso}}%</span>
                                </div>
                            </div>
                            <span v-if="arquivo.aguardando"> Preparado para envio </span>


                        </td>
                        <td class="text-center" v-if="!leitura">
                            <button class="btn btn-secondary" @click="cancelar()" v-show="arquivo.enviando">
                                <i class="fas fa-window-close"></i> Cancelar
                            </button>
                            <button class="btn btn-danger" @click="remover(index)" v-show="!arquivo.enviando">
                                <i class="fas fa-trash-alt"></i> Apagar
                            </button>
                        </td>
                    </tr>

                </draggable>

            </table>

        </div>
    </span>
</template>

<script>
    let KB = 1024;
    let MB = 1024 * KB;

    import draggable from 'vuedraggable'

    export default {
        components: {
            'draggable': draggable,
        },
        props: {
            label: {
                type: String,
                required: false,
                default: () => 'Selecionar anexo(s)'
            },
            model: {
                type: Array,
                required: true,
                default: () => []
            },
            modelDelete: {
                type: Array,
                required: false,
                default: () => []
            },
            url: {
                type: String,
                required: false,
                default: () => ''
            },
            /*chave: {
                type: String,
                required: false,
                default: () => ''
                /!*
                   coloquei false pq geralmente esse componente esta dentro de outro. Entao isso evita de ser obrigado
                   a passar uma chave quando o componente mais externo é apenas para leitura, nao sera usado upload de fato
               *!/
            },*/
            size: { // quantidade de MB (5GB)
                type: Number,
                required: false,
                default: 5120
            },
            quantidade: { // quantidade de arquivos maximo para enviar
                type: Number,
                required: false,
                default: null
            },
            dadosAjax: {
                type: Object,
                required: false,
                default: () => {
                    return {}
                }
            },
            tipos: {
                type: Array,
                required: false,
                default: () => []
            },
            ordenar: {
                type: Boolean,
                required: false,
                default: false
            },
            multi: {
                type: Boolean,
                required: false,
                default: true
            },
            nomePost: {
                type: String,
                required: false,
                default: () => 'arquivo'// nome que vai para o backend
            },
            leitura: { //se o componente é apenas de leitura...
                type: Boolean,
                required: false,
                default: false
            },
            apenasImagens: { // se aceita apenas imagens
                type: Boolean,
                required: false,
                default: false
            },
            simples: { // interface simples se tabela
                type: Boolean,
                required: false,
                default: false
            },


        },
        computed: {
            btn: function () {
                return $(this.$refs.file);
            },
            atual: function () {

                if (this.total === 0) {
                    return 1;
                }

                let index = null;

                // o primeiro da lista que esta enviando...
                index = _.findIndex(this.lista, {enviando: true});
                if (index > -1) {
                    /*console.log('achou o primeiro index que esta enviando',index);*/
                    return index + 1; // o primeiro que estiver enviando é o atual...
                }

                // o primeiro da lista que esta aguardando
                index = _.findIndex(this.lista, {aguardando: true});
                if (index > -1) {
                    /*console.log('achou o primeiro index que esta aguardando',index);*/
                    return index + 1; // ou o primeiro que estiver aguardando é o atual...
                }

                /*index = _.findLastIndex(this.lista, { enviado: true,enviando: false,aguardando: false });
                if(index > -1){
                    console.log('achou o ultimo index que já enviou',index);
                    return index +1; // ou o primeiro que estiver enviado é o atual...
                }*/


                return this.total; // se nao achar nada retorna 1 ou o ultimo item da lista
            },
            total: function () {
                return this.lista.length;
            },
            arquivo: function () {
                return this.lista[this.atual - 1];
            },
            lista: {
                get: function () {
                    return this.model;
                },
                set: function (newValue) {
                    newValue.forEach((obj, index) => {
                        this.$set(this.model, index, obj);
                    });
                }
            },
            bytesLimite: function () {
                return this.size * MB;
            },
            arquivosPermitidos: function () {
                return this.mimeTypes.join(',');
            },
            multiple: function () {
                return this.multi === true ? {multiple: ''} : '';
            },
            quantidadeMaxima: function () {
                if (this.quantidade == null) {
                    return false;
                }
                return this.total >= this.quantidade;
            },
            pctGeral() {
                let bytesCarregados = _.sumBy(this.model, 'bytesCarregados');
                let bytesTotal = _.sumBy(this.model, 'bytesTotal');
                let pctProgresso = Math.round((bytesCarregados / bytesTotal) * 100);
                return {
                    'carregados': bytesCarregados,
                    'total': bytesTotal,
                    'pct': pctProgresso,
                };
            }

        },
        mounted() {
            if (this.apenasImagens) {
                this.mimeTypes = [
                    "image/jpeg",
                    "image/jpg",
                    "image/png",
                ];
            } else {

                this.mimeTypes = this.mimeTypes.concat(this.tipos); // junta com a lista padrão

            }
            //criando a chave de upload para essa pagina
            this.chave = String(Math.random()).substr(2);
        },
        data() {
            return {
                ajax: null,
                pediuCancelar: false,
                prefixo_id: "upload",
                emAndamento: false, // se esta enviando arquivos ou não
                mimeTypes: [
                    "image/gif",
                    "image/jpg",
                    "image/jpeg",
                    "image/png",
                    "application/pdf",
                    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                    "application/msword",
                    "application/vnd.ms-excel",
                    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                    "application/vnd.ms-powerpoint",
                    "application/vnd.openxmlformats-officedocument.presentationml.presentation",
                    "application/vnd.ms-powerpoint",
                    "application/vnd.openxmlformats-officedocument.presentationml.slideshow",
                    "text/plain",
                    "application/x-rar-compressed",
                    "application/zip"
                ],
                chave: null // para identificar o upload
            }
        },

        methods: {
            remover: function (index) {
                this.$emit("ondelete", this.lista[index]);
                //if(this.lista[index].id && this.lista[index].enviado && !this.lista[index].falhou){
                if (this.lista[index].id && this.lista[index].temporario == false) {
                    this.modelDelete.push(parseInt(this.lista[index].id));
                }
                if (this.lista[index].id && this.lista[index].temporario == true) {
                    var dados = {};
                    dados._method = 'DELETE';
                    $.post(this.lista[index].urlDelete, dados).done((data) => {
                        //console.log('apagou')
                    });
                }
                this.lista.splice(index, 1);
            },
            cancelar: function () {
                this.pediuCancelar = true;
                mostraErro('', 'O envio foi cancelado');
                this.ajax.abort();
            },
            proximo: function () {

                if (this.atual === this.total) {
                    if (this.arquivo.enviado) {
                        this.$emit('onfinalizado');
                        this.emAndamento = false;
                    } else {
                        /*console.log('***** PROXIMO ***** ');
                        console.log(this.atual,this.total);*/
                        this.enviarArquivo();
                    }

                } else {
                    /*console.log('***** PROXIMO ***** ');
                    console.log(this.atual,this.total);*/
                    this.enviarArquivo(); // proximo arquivo
                }
            },
            selecionar: function () {
                this.btn.val('');
                this.btn.trigger('click');
            },
            upload: function () {

                let totalDeArquivos = this.btn[0].files.length;
                this.emAndamento = true;
                this.ajax = null;
                //this.model.splice(0); // reset no array forçado

                if (totalDeArquivos > 0) {
                    //primeiro avaliar todos os arquivos enviados

                    let lista = this.btn[0].files;
                    let novosArquivos = 0;
                    Array.from(lista).forEach((file, index) => {
                        let arquivo = {};
                        arquivo.lastModified = file.lastModified;
                        arquivo.lastModifiedDate = file.lastModifiedDate;
                        arquivo.nome = file.name;
                        arquivo.bytes = file.size;
                        arquivo.bytesCarregados = 0;
                        arquivo.bytesTotal = file.size;
                        arquivo.type = file.type !== "" ? file.type : 'application/octet-stream';
                        arquivo.webkitRelativePath = file.webkitRelativePath;
                        arquivo.hashId = this.prefixo_id + parseInt((Math.random() * 999999));
                        arquivo.chave = this.chave;

                        arquivo.falhou = false; // se falhou no upload
                        arquivo.aguardando = true;
                        arquivo.enviando = false;
                        arquivo.enviado = false;

                        arquivo.file = file;

                        if (this.quantidade) {
                            if (this.total >= this.quantidade) {
                                mostraErro({}, "Limitado apenas a " + this.quantidade + " arquivo(s)");
                                return true;//proximo loop
                            }
                        }

                        if (arquivo.bytes > this.bytesLimite) {
                            mostraErro({}, "O arquivo \"" + arquivo.nome + "\" (" + arquivo.bytes + " bytes) deve ter um tamanho menor que " + this.bytesLimite + " bytes");
                            return true;//proximo loop
                        }

                        if (this.mimeTypes.indexOf(arquivo.type) === -1) {
                            mostraErro({}, "O formato do arquivo \"" + arquivo.nome + "\" não é permitido para envio.");
                            return true;//proximo loop
                        }

                        this.lista.push(arquivo);
                        novosArquivos++;
                        this.$emit('onInit', arquivo); // o arquivo será enviado, aguardando o envio

                    });

                    //if (this.lista.length > 0){
                    if (novosArquivos > 0) {
                        this.emAndamento = true;
                        /*console.log(this.atual);
                        console.log("aguardando",this.arquivo.aguardando,"enviando",this.arquivo.enviando);
                        return false;*/
                        this.enviarArquivo();


                    } else {
                        //this.totalDeArquivos=0;
                        this.emAndamento = false;
                    }
                }

            },

            enviarArquivo() {

                let dados = new FormData();
                dados.append(this.nomePost, this.arquivo.file);
                dados.append('chave', this.chave);

                // completar com outros dados se for necessario
                Object.keys(this.dadosAjax).forEach((key, index) => {
                    let value = this.dadosAjax[key];
                    dados.append(key, value);
                });

                let refVue = this;

                Object.assign(this.arquivo, {enviando: true, aguardando: false});

                /*console.log(this.atual);
                console.log("aguardando",this.arquivo.aguardando,"enviando",this.arquivo.enviando,"enviado",this.arquivo.enviado);
                return false;*/


                this.$emit('onStart', this.arquivo); // o arquivo iniciou o envio
                this.ajax = $.ajax({
                    url: this.url, // Url do lado server que vai receber o arquivo
                    data: dados,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    xhr: () => {  // Custom XMLHttpRequests
                        let ajax = $.ajaxSettings.xhr();
                        if (ajax.upload) { // Check if upload property exists
                            ajax.upload.addEventListener('progress', (e) => {

                                refVue.arquivo.pctProgresso = Math.round((e.loaded / e.total) * 100);
                                refVue.arquivo.bytesCarregados = e.loaded;
                                refVue.arquivo.bytesTotal = e.total;

                                refVue.$set(this.lista, refVue.atual - 1, refVue.arquivo); //forçar atualizacao do array

                                refVue.$emit('onprogresso', refVue.arquivo);
                                refVue.$emit("onprogressogeral", refVue.pctGeral);

                            }, false);
                        }
                        return ajax;
                    }
                })
                    .done(function (data) {

                        Object.assign(refVue.arquivo, data); //mesclar e sobrescrever com resposta do servidor

                        Object.assign(refVue.arquivo, {enviando: false, enviado: true});

                        refVue.$emit('onComplete', data); //dados do servidor

                        refVue.proximo();
                    })
                    .fail(function (data) {

                        if (refVue.pediuCancelar) {
                            refVue.pediuCancelar = false;

                            if (refVue.atual < refVue.total) {
                                //console.log("vai cancelar ",refVue.atual);
                                refVue.lista.splice(refVue.atual - 1, 1);
                                //console.log('quem é o atual agora é:'+refVue.atual+" e o total é: "+refVue.total);
                                refVue.proximo();
                                return false;
                            }

                            if (refVue.atual === refVue.total) {
                                refVue.lista.splice(refVue.atual - 1, 1);
                                refVue.$emit('onfinalizado');
                                refVue.emAndamento = false;
                                //console.log('finalizado');
                                return false;
                            }
                            //console.log('quem é o atual agora é:'+refVue.atual+" e o total é: "+refVue.total);
                        } else {
                            Object.assign(refVue.arquivo, {enviando: false, enviado: true, falhou: true});
                        }

                        refVue.proximo();


                    });
            }
        }
    }
</script>

<style scoped>

</style>
