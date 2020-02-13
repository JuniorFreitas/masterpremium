<template>
    <div>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-append">
                            <div class="input-group-text">Tipo da conta
                            </div>
                        </div>
                        <select class="custom-select" :disabled="preload" v-model="model.tipo_conta">
                            <option value="corrente">Corrente</option>
                            <option value="poupanca">Poupança</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-append">
                            <div class="input-group-text">Banco
                            </div>
                        </div>
                        <select class="custom-select" :disabled="preload" v-model="model.banco_id">
                            <!--<option value="0">Selecione...</option>-->
                            <option :value="banco.id" v-for="banco in listaDeBancos">{{banco.nome}}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-append">
                            <div class="input-group-text">Nome do Titular
                            </div>
                        </div>
                        <input type="text" :disabled="preload" class="form-control"
                               onblur="valida_campo_vazio(this,3)"
                               placeholder="Informe o Nome do Titular"
                               v-model="model.nome_conta">
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-3 col-md-3 col-lg-3">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-append">
                            <div class="input-group-text">Agência
                            </div>
                        </div>
                        <input type="text" :disabled="preload" class="form-control"
                               onblur="valida_campo_vazio(this,3)" v-model="model.agencia"
                               placeholder="Informe a Agência"
                               autocomplete="off">
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-3 col-md-3 col-lg-3">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-append">
                            <div class="input-group-text">Conta
                            </div>
                        </div>
                        <input type="text" :disabled="preload" class="form-control"
                               onblur="valida_campo_vazio(this,3)" v-model="model.numero"
                               placeholder="Informe a conta"
                               autocomplete="off">
                    </div>
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
                        tipo_conta: 'fisica',
                        banco_id: '',
                        nome_conta: '',
                        agencia: '',
                        numero: ''
                    }
                }
            }
        },
        computed: {},
        mounted() {
            this.preload = true;
            $.getJSON(`${URL_PUBLICO}/lista-bancos/`)
                .done((data) => {

                    this.preload = false;
                    this.listaDeBancos = data;
                    let banco = this.listaDeBancos[0];
                    this.banco_id = banco.id;

                    if (data.erro) {
                        this.preload = false;
                    }
                })
                .fail((data) => {
                    this.preload = false;
                });
        },
        data() {
            return {
                preload: false,
                listaDeBancos: []
            }
        },

        methods: {}
    }
</script>

<style scoped>

</style>
