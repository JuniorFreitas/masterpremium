function setMask(tipo, el, mod) {
    switch (tipo) {
        case 'data':
            $(el).mask('00/00/0000');
            break;

        case 'aniversario':
            $(el).mask('00/00');
            break;

        case 'numero':
            $(el).mask('00000000000000000000');
            break;

        case 'ano':
            $(el).mask('0000');
            break;

        case 'cep':
            $(el).mask('00000-000');
            break;

        case 'cpf':
            $(el).mask('000.000.000-00');
            break;

        case 'cnpj':
            $(el).mask('00.000.000/0000-00');
            break;

        case 'rg':
            $(el).mask('00.000.000-0');
            break;

        case 'placa':
            $(el).mask('AAA-0000');
            break;

        case 'hora':
            $(el).mask('00:00');
            break;

        case 'altura':
            if ($(el).val().length > 3) {
                mascara = '####00,00';
            } else {
                mascara = '####0,0';
            }
            $(el).mask(mascara, {reverse: true});
            break;

        case 'peso':
            $(el).mask("#0.000", {reverse: true});
            break;


        case 'telefone':

            if (Object.keys(mod).length) {
                if (mod.celular) {
                    $(el).mask('(00) 0 0000-0000');
                }
                if (mod.fixo) {
                    $(el).mask('(00) 0000-0000');
                }
            } else {
                //Aplicar as mascaras logo de imediato
                if ($(el).val().length < 14) {
                    $(el).mask('(00) 0000-0000');
                }
                if ($(el).val().length == 14) {
                    let valorAtual = $(el).val();
                    $(el).unmask();
                    $(el).val(valorAtual);
                }
                if ($(el).val().length > 14) {
                    $(el).mask('(00) 0 0000-0000');
                }

                $(el).on('keyup', (e) => {
                    if ($(el).val().length < 14) {
                        $(el).mask('(00) 0000-0000');
                    }
                    if ($(el).val().length == 14) {
                        let valorAtual = $(el).val();
                        $(el).unmask();
                        $(el).val(valorAtual);
                    }
                    if ($(el).val().length > 14) {
                        $(el).mask('(00) 0 0000-0000');
                    }
                });
            }

            break;

        case 'dinheiro':
            // $(el).css('text-align','right').val();
            $(el).maskMoney({
                prefix: '',
                allowNegative: false,
                allowZero: true,
                thousands: '.',
                decimal: ',',
                affixesStay: false
            });
            break;

        case 'dinheiroPN':
            // $(el).css('text-align','right').val();
            $(el).maskMoney({
                prefix: '',
                allowNegative: true,
                allowZero: true,
                thousands: '.',
                decimal: ',',
                affixesStay: false
            });
            break;


    }
}

Vue.directive('mascara', {

    inserted: function (el, binding, vnode) {
        let model = binding.expression ? binding.expression : null;

        let VUE = vnode.context;
        const argumento = binding.arg;
        const mod = binding.modifiers;

        if (el.nodeName != 'INPUT') {
            let campo = $(el).find(':input:text:eq(0)');
            if (campo.length) {
                el = campo[0];
            } else {
                console.log('Não foi possível usar a diretiva');
                return false;
            }
        }

        setMask(argumento, el, mod);

        $(el).on('keypress', (e) => {
            let antes = e.target.value;
            $(el).unmask();
            e.target.value = antes;
        });


        $(el).on('keyup', (e) => {
            setMask(argumento, el, mod);
            let atual = $(el).val();

            $(el).unmask();

            e.target.value = atual;

            let event = new Event('input', {bubbles: true});
            el.dispatchEvent(event);
            setMask(argumento, el, mod);

            if (e.target.type == 'text') {
                el.setSelectionRange(e.target.value.length, e.target.value.length);
            }

        });

    }

});
