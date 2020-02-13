<template>
    <div class="form-group">
        <div class="input-group" v-if="append">
            <div class="input-group-append">
                <div class="input-group-text">{{label}}</div>
            </div>

            <input
                autocomplete="off"
                v-bind:value="value"
                v-on:input="$emit('input', $event.target.value)"
                :id="id"
                :disabled="disabled"
                :placeholder="placeholder"
                @input="onChange"
                @blur="onBlur"
                :readonly="leitura"
                type="text" class="form-control" :class="disabled ? 'disabled':'readonly'"
                v-if="!range">

            <input
                autocomplete="off"
                v-bind:value="value"
                v-on:input="$emit('input', $event.target.value)"
                :value="value"
                :id="id"
                :disabled="disabled"
                :placeholder="placeholder"
                @input="onChange"
                @blur="onBlur"
                :readonly="leitura"
                type="text" class="form-control" :class="disabled ? 'disabled':'readonly'" v-if="range">
        </div>

        <div v-if="!append">
            <label>{{label}}</label>
            <input
                autocomplete="off"
                v-bind:value="value"
                v-on:input="$emit('input', $event.target.value)"
                :id="id"
                :disabled="disabled"
                :placeholder="placeholder"
                @input="onChange"
                @blur="onBlur"
                :readonly="leitura"
                type="text" class="form-control" :class="disabled ? 'disabled':'readonly'"
                v-if="!range">

            <input
                autocomplete="off"
                v-bind:value="value"
                v-on:input="$emit('input', $event.target.value)"
                :value="value"
                :id="id"
                :disabled="disabled"
                :placeholder="placeholder"
                @input="onChange"
                @blur="onBlur"
                :readonly="leitura"
                type="text" class="form-control" :class="disabled ? 'disabled':'readonly'"
                v-if="range">
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: String,
                required: false,
                default: ''
            },
            id: {
                type: String,
                required: false,
            },
            leitura: {
                type: Boolean,
                default: true,
                required: false,
            },
            disabled: {
                type: Boolean,
                required: false,
            },

            placeholder: {
                type: String,
                required: false,
            },

            append: {
                type: Boolean,
                required: false,
                default: true
            },

            label: {
                type: String,
                required: false,
                default: 'Data'
            },

            min: { //Data hora minima, no formato configurado no campo
                type: String,
                required: false,
                default: null
            },
            max: { //Data hora maxima, no formato configurado no campo
                type: String,
                required: false,
                default: null
            },
            hora: {
                type: Boolean,
                required: false,
                default: false
            },
            range: {
                type: Boolean,
                required: false,
                default: false
            },
            separador: {
                type: String,
                required: false,
                default: " até "
            },
            aplicarLabel: {
                type: String,
                required: false,
                default: "Aplicar"
            },
            cancelarLabel: {
                type: String,
                required: false,
                default: "Cancelar"
            },
            deLabel: {
                type: String,
                required: false,
                default: "De"
            },
            paraLabel: {
                type: String,
                required: false,
                default: "Para"
            },
            posicao: {
                type: String,
                required: false,
                default: 'down'
            }
        },

        data() {
            return {
                isOpen: false,
                isLoading: false,
                el: null,
                event: null
            };
        },
        methods: {
            onBlur($event) {
                this.event = $event;
                setTimeout(() => {
                    this.$emit("onblur", $event);
                }, 100);

            },
            onChange() {
                // Let's warn the parent that a change was made
                //this.$emit("input", this.el.value);
            }
        },

        updated() {

            this.$emit("input", this.el.value);
            if (this.range) {
                let texto = this.el.value.trim();
                let datas = texto.replace(this.separador + ' ', '#');
                datas = datas.split('#');
                if (datas.length > 1) {
                    $(this.el).data('daterangepicker').setStartDate(datas[0]);
                    $(this.el).data('daterangepicker').setEndDate(datas[1]);
                } else {
                    let datas = texto.replace(this.separador, '#');
                    datas = datas.split('#');
                    $(this.el).data('daterangepicker').setStartDate(datas[0]);
                    $(this.el).data('daterangepicker').setEndDate(datas[1]);
                }


            } else {
                let texto = this.el.value.trim();
                $(this.el).data('daterangepicker').setStartDate(texto);
                $(this.el).data('daterangepicker').setEndDate(texto);
            }


        },
        mounted() {
            // this.label = this.label ? this.label : 'Data';

            this.el = $(this.$el).find(':input:text:eq(0)')[0];
            let ref = this;
            let valueInicial = $(this.el).attr('value');

            if (valueInicial) {
                this.el.value = valueInicial;
            }

            if (this.range) {

                $(this.el).daterangepicker({
                    singleDatePicker: false,
                    showDropdowns: true,
                    timePicker: this.hora,
                    timePicker24Hour: this.hora,
                    minDate: this.min,
                    maxDate: this.max,
                    //autoUpdateInput:false,
                    drops: this.posicao,
                    opens: 'center',
                    locale: {
                        separator: this.separador,
                        applyLabel: this.aplicarLabel,
                        cancelLabel: this.cancelarLabel,
                        fromLabel: this.deLabel,
                        toLabel: this.paraLabel,
                        format: this.hora ? `L [às] HH:mm` : `DD/MM/YYYY`
                    },
                });

                $(this.el).on('apply.daterangepicker', function (ev, picker) {
                    /*if(ref.hora){
                        $(this).val(picker.startDate.format('L [às] HH:mm') + ref.separador + picker.endDate.format('L [às] HH:mm'));
                        ref.$emit("input", ref.el.value);
                        ref.$emit('onselect', {
                            data_inicio:picker.startDate.format('DD/MM/YYYY'),
                            hora_inicio:picker.startDate.format('HH:mm'),

                            data_fim:picker.endDate.format('DD/MM/YYYY'),
                            hora_fim:picker.endDate.format('HH:mm'),
                            value:ref.el.value
                        });
                    }else{
                        $(this).val(picker.startDate.format('DD/MM/YYYY') + ref.separador + picker.endDate.format('DD/MM/YYYY'));
                        ref.$emit("input", ref.el.value);
                        ref.$emit('onselect', {
                            inicio:picker.startDate.format('DD/MM/YYYY'),
                            fim:picker.endDate.format('DD/MM/YYYY'),
                            value:ref.el.value
                        });
                    }*/

                });

                $(this.el).on('hide.daterangepicker', function (ev, picker) {
                    if (ref.hora) {
                        $(this).val(picker.startDate.format('L [às] HH:mm') + ref.separador + picker.endDate.format('L [às] HH:mm'));
                        ref.$emit("input", ref.el.value);
                        ref.$emit('onselect', {
                            data_inicio: picker.startDate.format('DD/MM/YYYY'),
                            hora_inicio: picker.startDate.format('HH:mm'),

                            data_fim: picker.endDate.format('DD/MM/YYYY'),
                            hora_fim: picker.endDate.format('HH:mm'),
                            value: ref.el.value
                        });
                    } else {
                        $(this).val(picker.startDate.format('DD/MM/YYYY') + ref.separador + picker.endDate.format('DD/MM/YYYY'));
                        ref.$emit("input", ref.el.value);
                        ref.$emit('onselect', {
                            inicio: picker.startDate.format('DD/MM/YYYY'),
                            fim: picker.endDate.format('DD/MM/YYYY'),
                            value: ref.el.value
                        });
                    }
                });


            } else {
                $(this.el).daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    timePicker: this.hora,
                    timePicker24Hour: this.hora,
                    minDate: this.min,
                    maxDate: this.max,
                    //autoUpdateInput: false,
                    drops: this.posicao,
                    opens: 'center',
                    locale: {
                        applyLabel: this.aplicarLabel,
                        cancelLabel: this.cancelarLabel,
                        fromLabel: this.deLabel,
                        toLabel: this.paraLabel,
                        format: this.hora ? "L [às] HH:mm" : 'DD/MM/YYYY'
                    },
                });

                $(this.el).on('apply.daterangepicker', function (ev, picker) {
                    /*if(ref.hora){
                        $(this).val(picker.startDate.format('L [às] HH:mm'));
                    }else{
                        $(this).val(picker.startDate.format('DD/MM/YYYY'));
                    }

                    ref.$emit("input", ref.el.value);
                    ref.$emit('onselect', ref.el.value);*/

                });

                $(this.el).on('hide.daterangepicker', function (ev, picker) {
                    if (ref.hora) {
                        $(this).val(picker.startDate.format('L [às] HH:mm'));
                    } else {
                        $(this).val(picker.startDate.format('DD/MM/YYYY'));
                    }

                    ref.$emit("input", ref.el.value);
                    ref.$emit('onselect', ref.el.value);
                });

            }

            this.$emit("input", this.el.value);


        }
    };
</script>
<style>
    .daterangepicker .drp-selected {
        display: inline-block;
        font-size: 17px;
        color: #184056;
        padding-right: 8px;
        padding-bottom: 8px;
    }

    .readonly {
        background: #ffffff !important;
    }

    .disabled {
        background-color: #e9ecef !important;
    }
</style>
