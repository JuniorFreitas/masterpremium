<!--<autocomplete  :items="[ 'Apple', 'Banana', 'Orange', 'Mango', 'Pear', 'Peach', 'Grape', 'Tangerine', 'Pineapple']"></autocomplete>-->
<!--<autocomplete id="descricao" is-async caminho="feriados/search" autocomplete="off" onblur="valida_campo_vazio(this,3)"></autocomplete>-->
<template>
    <div class="autocomplete">
        <input
            autocomplete="mastertag"
            v-bind:value="value"
            v-on:input="$emit('input', $event.target.value)"
            :class="[ leitura ? 'form-control-plaintext': 'form-control', valido ? 'is-valid':'' ]" :readonly="leitura"
            :disabled="disabled"
            type="text"
            :id="id"
            :placeholder="placeholder"
            @input="onChange"
            @keydown.down="onArrowDown"
            @keydown.up="onArrowUp"
            @keydown.prevent.enter="onEnter"
            @blur="onBlur"
        />
        <ul
            id="autocomplete-results"
            v-show="isOpen"
            class="autocomplete-results"
        >
            <li class="loading" v-if="isLoading">
                <i class="fas fa-spinner fa-pulse"></i> Buscando...
            </li>
            <li
                v-else
                v-for="(result, i) in results"
                :key="i"
                @mouseup="setResult(result)"
                class="autocomplete-result"
                :class="{ 'is-active': i === arrowCounter }"
            >
                {{ result.label }}
            </li>
        </ul>
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
            items: {
                type: Array | Object,
                required: false,
                default: () => []
            },

            id: {
                type: String,
                required: false,
            },

            placeholder: {
                type: String,
                // required: true,
            },

            caminho: {
                type: String,
                required: false,
            },

            delay: {
                type: Number,
                required: false,
                default: 500
            },

            rows: {
                type: Number,
                required: false,
                default: 20
            },

            isAsync: {
                type: Boolean,
                required: false,
                default: true
            },
            leitura: {
                type: Boolean,
                required: false,
                default: false
            },
            disabled: {
                type: Boolean,
                required: false,
                default: false
            },
            valido: {
                type: Boolean,
                required: false,
                default: false
            },

        },

        data() {
            return {
                isOpen: false,
                results: [],
                search: "",
                isLoading: false,
                arrowCounter: -1,
                intervalo: null,
                el: null,
                event: null
            };
        },
        methods: {
            onBlur($event) {
                this.event = $event;
                setTimeout(() => {
                    this.$emit("onblur", $event);
                }, 100)
                //console.log('onBlur');

            },
            onChange() {
                // Let's warn the parent that a change was made
                this.$emit("input", this.el.value);
                //this.$vnode.data.model
                //console.log('onChange');

                // Is the data given by an outside ajax request?
                if (this.isAsync) {
                    clearInterval(this.intervalo);
                    this.isLoading = true;
                    this.isOpen = true;
                    this.arrowCounter = 0;
                    this.intervalo = setTimeout(() => {
                        /*this.isLoading = true;
                        this.isOpen = true;
                        this.arrowCounter=0;*/
                        $.get(`${URL_ADMIN}/${this.caminho}?busca=${this.el.value}&rows=${this.rows}`)
                            .done((data) => {
                                this.isLoading = false;

                                this.results = data;
                                if (data.length > 0) {
                                    this.isOpen = true;
                                    this.arrowCounter = 0;
                                }
                            })
                            .fail((data) => {
                                this.isLoading = false;
                                this.isOpen = false;
                                this.arrowCounter = -1;
                            });
                    }, this.delay);
                } else {
                    // Let's search our flat array
                    this.isOpen = true;
                }
            },
            setResult(result) {
                this.isOpen = false;

                this.el.value = result.value ? result.value : result.label;
                this.$emit("onblur", this.event);
                this.$emit("input", this.el.value);
                this.$emit('onselect', result);

                //this.el.blur();
                //$(this.el).trigger('blur');

            },
            onArrowDown(evt) {
                if (this.isOpen) {
                    if (this.arrowCounter < this.results.length) {
                        this.arrowCounter = this.arrowCounter + 1;
                    }
                }

            },
            onArrowUp() {
                if (this.isOpen) {
                    if (this.arrowCounter > 0) {
                        this.arrowCounter = this.arrowCounter - 1;
                    }
                }

            },
            onEnter() {
                if (this.arrowCounter === -1) {
                    return false;
                }
                //console.log('onEnter');
                let selecionado = this.results[this.arrowCounter];
                if (selecionado) {
                    this.isOpen = false;
                    this.arrowCounter = -1;
                    //this.$emit('onselect', selecionado);
                    this.setResult(selecionado);

                    //this.$emit('onselect', selecionado);
                    //this.el.value = selecionado.value ? selecionado.value: selecionado.label;
                }
                //$(this.el).trigger('blur');

            },
            handleClickOutside(evt) {

                if (this.isOpen) {

                    if (!this.$el.contains(evt.target)) {
                        this.isOpen = false;
                        this.arrowCounter = -1;
                    }
                }

            }
        },
        mounted() {
            this.result = this.items;
            document.addEventListener("click", this.handleClickOutside);
            this.el = $(this.$el).find(':input:text:eq(0)')[0];
        },
        destroyed() {
            document.removeEventListener("click", this.handleClickOutside);
        }
    };
</script>
<style>
    .autocomplete {
        position: relative;
        font-size: 0.75rem;
    }

    .autocomplete-results {
        padding: 0;
        margin: 0;
        border: 1px solid #d4d4d4;
        /*max-height: 120px;*/
        /*overflow: auto;*/
        width: 100%;
        position: absolute;
        /*top: 39px;*/
        z-index: 999999999;
        background: #fff;
    }

    .autocomplete-result {
        list-style: none;
        text-align: left;
        padding: 4px 2px;
        cursor: pointer;
    }

    .loading {
        list-style: none;
    }

    .autocomplete-result.is-active,
    .autocomplete-result:hover {
        /*background-color: #4AAE9B;*/
        /*color: white;*/
    }
</style>
