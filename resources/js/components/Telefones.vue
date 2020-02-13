<template>
    <div :model="model" :modelDelete="modelDelete">
        <div class="mb-3">
            <!--<label>Telefone</label><br>-->
            <button class="btn btn-secondary" @click="add()">
                <span class="fas fa-plus" aria-hidden="true"></span>
                Adicionar
            </button>
        </div>
        <div>
            <div class="row mb-2" v-for="(tel, index) in lista" :key="tel.id">
                <div class="col-12">
                    <div class="form-inline tels pb-2">
                        <select class="form-control mb-2 mr-sm-2" v-model="tel.tipo">
                            <option value="residencial">Residencial</option>
                            <option value="celular">Celular</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="comercial">Comercial</option>
                        </select>

                        <div class="input-group mb-2 mr-sm-2" v-if="pais">
                            <div class="input-group-prepend">
                                <div class="input-group-text">País +</div>
                            </div>
                            <input type="text" class="form-control pais" v-model="tel.pais">
                        </div>

                        <div class="input-group mb-2 mr-sm-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-phone"></i></div>
                            </div>
                            <input type="text" class="form-control telefone" v-mascara:telefone
                                   onblur="valida_telefone_vazio(this)" v-model="tel.numero">
                        </div>

                        <div class="input-group mb-2 mr-sm-2" v-if="ramal">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Ramal</div>
                            </div>
                            <input type="text" class="form-control ramal" v-model="tel.ramal">
                        </div>

                        <div class="input-group mb-2 mr-sm-2" v-if="detalhe">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Obs</div>
                            </div>
                            <input type="text" class="form-control" v-model="tel.detalhe">
                        </div>

                        <button class="btn btn-danger mb-2" @click="remove(index)">
                            <span class="fas fa-times" aria-hidden="true"></span>

                        </button>
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
                type: Array,
                required: true,
                default: () => []
            },
            modelDelete: {
                type: Array,
                required: false,
                default: () => []
            },
            ramal: {
                type: Boolean,
                default: () => true

            },
            pais: {
                type: Boolean,
                default: () => true

            },
            detalhe: {
                type: Boolean,
                default: () => true

            },

        },
        data: function () {
            return {}
        },
        computed: {
            lista: function () {
                return this.model;
            },
            listaDelete: function () {
                return this.modelDelete;
            },
        },
        methods: {
            add: function () {
                let op = {};
                op.tipo = 'residencial';
                op.pais = '55';
                op.numero = '';
                op.ramal = '';
                op.detalhe = '';
                op.novo = true;

                //this.lista.push(op);
                this.model.push(op);
            },
            remove: function (index) {
                this.$emit("ondelete", this.lista[index]);
                if (this.lista[index].id) {
                    this.listaDelete.push(this.lista[index].id);
                }
                this.lista.splice(index, 1);

            },
        }

    }
</script>

<style scoped>
    .bt {
        margin-bottom: 10px;
    }

    .tels {
        border-bottom: 1px dashed #999999;
        margin-bottom: 10px;
    }

    .pais {
        width: 46px;
    }

    .ramal {
        width: 75px;
    }

    .telefone {
        width: 134px;
    }

</style>
