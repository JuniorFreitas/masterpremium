<template>
    <div>
        <!--<div class="row">-->
        <!--<div class="col-12 col-sm-4 col-md-6 col-lg-4" v-if="!select">-->
        <!--<div class="form-group">-->
        <!--<label>CEP</label>-->
        <!--<div class="input-group">-->
        <!--<input type="text" :disabled="preload" v-mascara:cep class="form-control"-->
        <!--placeholder="Informe o cep"-->
        <!--v-model="model.cep"-->
        <!--onblur="valida_cep_vazio(this)">-->
        <!--<div class="input-group-append">-->
        <!--<button class="btn btn-secondary" @click.stop="onClick"><i class="fa fa-search"></i></button>-->
        <!--</div>-->
        <!--</div>-->
        <!--</div>-->
        <!--</div>-->

        <!--<div class="col-12 col-sm-4 col-md-6 col-lg-4" v-if="select">-->
        <!--<div class="form-group">-->
        <!--<label>CEP</label>-->
        <!--<input type="text" :disabled="preload" v-mascara:cep class="form-control"-->
        <!--placeholder="Informe o cep"-->
        <!--v-model="model.cep"-->
        <!--onblur="valida_cep_vazio(this)">-->
        <!--</div>-->
        <!--</div>-->
        <!--</div>-->

        <!--<div class="row">-->
        <!--<div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-5" v-if="!select">-->
        <!--<div class="form-group">-->
        <!--<label>Logradouro</label>-->
        <!--<input type="text" :disabled="preload" class="form-control" placeholder="Informe o Logradouro"-->
        <!--v-model="model.logradouro"-->
        <!--onblur="valida_campo_vazio(this,3)">-->
        <!--</div>-->
        <!--</div>-->

        <!--<div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-5" v-if="!select">-->
        <!--<div class="form-group">-->
        <!--<label>Bairro</label>-->
        <!--<input type="text" :disabled="preload" class="form-control" placeholder="Informe o Bairro"-->
        <!--v-model="model.bairro"-->
        <!--onblur="valida_campo_vazio(this,3)">-->
        <!--</div>-->
        <!--</div>-->

        <!--<div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-5" v-if="select">-->
        <!--<div class="form-group">-->
        <!--<label>Bairro</label>-->
        <!--<select class="form-control" :disabled="preload" v-model="model.bairro_id">-->
        <!--&lt;!&ndash;<option value="0"> </option>&ndash;&gt;-->
        <!--<option :value="bairro.id"-->
        <!--v-for="(bairro, index) in listaDeBairros">{{bairro.nome}}-->
        <!--</option>-->
        <!--</select>-->
        <!--</div>-->
        <!--</div>-->
        <!--</div>-->

        <!--<div class="row">-->
        <!--<div class="col-12 col-sm-3 col-md-6 col-lg-5" v-if="!select">-->
        <!--<div class="form-group">-->
        <!--<label>Município</label>-->
        <!--<input type="text" :disabled="preload" class="form-control" placeholder="Informe a Cidade"-->
        <!--v-model="model.municipio"-->
        <!--onblur="valida_campo_vazio(this,3)">-->
        <!--</div>-->
        <!--</div>-->

        <!--<div class="col-12 col-sm-3 col-md-6 col-lg-5" v-if="select">-->
        <!--<div class="form-group">-->
        <!--<label>Município</label>-->
        <!--<select class="form-control" :disabled="preload"-->
        <!--v-model="model.municipio_id" @change="trocaMunicipio()">-->
        <!--<option :value="municipio.id"-->
        <!--v-for="(municipio, index) in listaDeMunicipios">-->
        <!--{{municipio.nome}}-->
        <!--</option>-->
        <!--</select>-->
        <!--</div>-->
        <!--</div>-->

        <!--<div class="col-4 col-sm-2 col-md-2 col-lg-2 col-xl-2" v-if="!select">-->
        <!--<div class="form-group">-->
        <!--<label>UF</label>-->
        <!--<input type="text" :disabled="preload" maxlength="2" class="form-control" placeholder="UF"-->
        <!--v-model="model.uf"-->
        <!--onblur="valida_campo_vazio(this,1)">-->
        <!--</div>-->
        <!--</div>-->

        <!--<div class="col-4 col-sm-2 col-md-2 col-lg-2 col-xl-2" v-if="select && model.municipio">-->
        <!--<div class="form-group">-->
        <!--<label>UF</label>-->
        <!--<select class="form-control" :disabled="preload" v-model="model.municipio.uf"-->
        <!--@change="trocaUF()">-->
        <!--<option value="MA">MA</option>-->
        <!--<option value="AC">AC</option>-->
        <!--<option value="AL">AL</option>-->
        <!--<option value="AM">AM</option>-->
        <!--<option value="AP">AP</option>-->
        <!--<option value="BA">BA</option>-->
        <!--<option value="CE">CE</option>-->
        <!--<option value="DF">DF</option>-->
        <!--<option value="ES">ES</option>-->
        <!--<option value="GO">GO</option>-->
        <!--<option value="MG">MG</option>-->
        <!--<option value="MS">MS</option>-->
        <!--<option value="MT">MT</option>-->
        <!--<option value="PA">PA</option>-->
        <!--<option value="PB">PB</option>-->
        <!--<option value="PE">PE</option>-->
        <!--<option value="PI">PI</option>-->
        <!--<option value="PR">PR</option>-->
        <!--<option value="RJ">RJ</option>-->
        <!--<option value="RN">RN</option>-->
        <!--<option value="RO">RO</option>-->
        <!--<option value="RR">RR</option>-->
        <!--<option value="RS">RS</option>-->
        <!--<option value="SC">SC</option>-->
        <!--<option value="SE">SE</option>-->
        <!--<option value="SP">SP</option>-->
        <!--<option value="TO">TO</option>-->
        <!--</select>-->
        <!--</div>-->
        <!--</div>-->
        <!--</div>-->

        <div class="col-12 col-sm-6 col-md-6 col-lg-6">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-append">
                        <div class="input-group-text">Nome Completo
                        </div>
                    </div>
                    <input type="text" class="form-control"
                           onblur="valida_campo_vazio(this,3)"
                           placeholder="Informe o cnpj" value=""
                           autocomplete="off" v-model="model.cnpj">
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: {
            model: {
                type: Object,
                required: true,
                default: () => {
                    return {
                        cnpj: '',
                        razaosocial: '',
                        nomefantasia: '',
                    }
                }
            }

        },
        computed: {},
        mounted() {

        },
        data() {
            return {
                preload: false,
                load: 'buscando ...',
                listaDeBairros: [],
                listaDeMunicipios: [{nome: 'São Luís', id: 0}],
            }
        },

        methods: {
            // trocaUF() {
            //     if(!this.select){
            //         return false;
            //     }
            //     this.preload = true;
            //     $.post(`${URL_PUBLICO}/lista-municipios`, {UF: this.model.municipio.uf})
            //         .done((data) => {
            //
            //             this.preload = false;
            //             this.listaDeBairros = data.bairros;
            //             this.listaDeMunicipios = data.municipios;
            //             let encontrou = _.find(this.listaDeMunicipios, { 'id': this.model.municipio_id});
            //             if(!encontrou){
            //                 this.model.municipio_id = this.listaDeMunicipios[0].id;
            //             }
            //
            //
            //
            //         })
            //         .fail((data) => {
            //             this.preload = false;
            //         });
            // },
            // trocaMunicipio() {
            //     if(!this.select){
            //         return false;
            //     }
            //     this.preload = true;
            //
            //     $.post(`${URL_PUBLICO}/lista-bairros`, {id_municipio: this.model.municipio_id})
            //         .done((data) => {
            //
            //             this.preload = false;
            //             this.listaDeBairros = data.bairros;
            //             let encontrou = _.find(this.listaDeBairros, { 'id': this.model.bairro_id});
            //             if(!encontrou){
            //                 this.model.bairro_id = this.listaDeBairros[0].id;
            //             }
            //
            //
            //         })
            //         .fail((data) => {
            //             this.preload = false;
            //         });
            // },
            onClick: function () {
                if (this.model.cep.length >= 9) {
                    this.model.logradouro = this.load;
                    this.model.bairro = this.load;
                    this.model.municipio = this.load;
                    this.model.uf = this.load;

                    this.preload = true;
                    $.getJSON(`https://viacep.com.br/ws/${this.model.cep}/json/?callback=?`)
                        .done((data) => {
                            this.model.logradouro = data.logradouro;
                            this.model.bairro = data.bairro;
                            this.model.municipio = data.localidade;
                            this.model.uf = data.uf;

                            this.preload = false;
                            if (data.erro) {
                                this.preload = false;
                                // alert('Cep Inválido')
                                this.model.cep = '';
                                this.model.logradouro = '';
                                this.model.bairro = '';
                                this.model.municipio = '';
                                this.model.uf = '';
                            }
                        })
                        .fail((data) => {
                            this.preload = false;
                            this.model.cep = '';
                            this.model.logradouro = '';
                            this.model.bairro = '';
                            this.model.municipio = '';
                            this.model.uf = '';
                        });
                }
            }
        }
    }
</script>

<style scoped>

</style>
