// JavaScript Document

//configuração do axios
function errorResponseHandler(error) {
    // check for errorHandle config
    if (error.config.hasOwnProperty('errorHandle') && error.config.errorHandle === false) {
        return Promise.reject(error);
    }

    if (!error.response) {
        return mostraErro('', "Verifique sua conexão com a Internet.");
    }

    // if has response show the error
    if (error.response) {
        if (AMBIENTE === 'dev') {
            console.log(error.response)
        }

        if (error.response.status === 419) {
            return mostraErro('', '419 - Recarregue a página novamente');
        }
        if (error.response.status === 403) {
            return mostraErro('', '403 - Sem Permissão');
        }

        mostraErro(error.response.data);
    }
}

// apply interceptor on response error
axios.interceptors.response.use(
    response => response,
    errorResponseHandler
);

//---------------------------------

$.ajaxSetup({
    beforeSend: function (xhr, type) {
        if (!type.crossDomain) {
            xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        }
    },
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    error: function (jqXHR, textStatus, errorThrown) {
        // mostraErro(jqXHR.responseJSON);
        /* if (jqXHR.status == 403) {
             mostraErro('Atenção!', 'Sem Autorização')
         }
         if (jqXHR.status == 404 || jqXHR.status == 417) {
             return false; // fazer o tratamento por conta propria
         }*/
        switch (jqXHR.status) {
            case 403:
                return mostraErro('Atenção!', 'Sem Autorização');
            case 404:
            case 417:
                return false;
            default:
                return mostraErro(jqXHR.responseJSON);
        }

    }
});


function replaceAll(string, token, newtoken) {
    while (string.indexOf(token) != -1) {
        string = string.replace(token, newtoken);
    }
    return string;
}

// serve para dar o reset nos valores de objetos
function objectReset(form) {

    //boolean
    //object
    //string
    if (typeof form === 'object') {
        //form={};
        //console.log(`rodando o campo ${form}`);
        Object.keys(form).forEach(campo => {

            if (form[campo] instanceof Array) {
                //console.log(`Campo array(*) ${campo}`);
                /*form[campo].forEach(outroObj => {
                    objectReset(outroObj);
                })*/
                //objectReset(form[campo]);
                form[campo] = [];
                return false;
            }

            if (typeof form[campo] === 'object') {
                /*Object.keys(form[campo]).forEach(outroObj => {
                    objectReset(outroObj);
                })*/
                //console.log(`Campo objeto(*) ${campo}`);

                objectReset(form[campo]);
                return false;
                //form[campo]={};
            }


            if (typeof form[campo] === 'boolean') {
                //console.log(`Campo boolean(*) ${form}`);
                form[campo] = true;
                //return false;
            } else {
                if (form[campo] === 'true' || form[campo] === 'false') {
                    //console.log(`Campo boolean forçado(*) ${campo}`);
                    form[campo] = true;
                } else {
                    //console.log(`Campo string(*) ${campo}`);
                    form[campo] = '';
                }

            }
        });
        return false;
    }


    if (form instanceof Array) {
        //console.log(`Campo array ${form}`);
        form = [];
        return false;
    }

    if (typeof form === 'boolean') {
        //console.log(`Campo boolean ${form}`);
        form = true;
        //return false;
    } else {
        //console.log('chegou aqui: '+form);
        if (form === 'true' || form === 'false') {
            form = true;
        } else {
            form = '';
        }
        //console.log(`Campo string ${form}`);

    }


}


function setupCampo() {
    $('.data').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false,
        locale: {
            applyLabel: "Aplicar",
            cancelLabel: "Cancelar",
            fromLabel: "De",
            toLabel: "Para",
        },
    });

    $('.data').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
    });


    $('.dataRange').daterangepicker({
        singleDatePicker: false,
        showDropdowns: true,
        //autoUpdateInput:false,
        locale: {
            separator: " até ",
            applyLabel: "Aplicar",
            cancelLabel: "Cancelar",
            fromLabel: "De",
            toLabel: "Para",
        },
    });

    $('.dataRange').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' até ' + picker.endDate.format('DD/MM/YYYY'));
    });
}


function pctDe(valor, pct) {
    return $resposta = valor * (pct / 100);
    /*if($resposta < 0.00){
        return 0.00;
    }else{
        return $resposta;
    }*/

}

$().ready(function (e) {


    /*$('body').on('focus','.telefone, .telefone9',function(e){
        $(this).setMask('(99) 9999-99999');
    });

    $('body').on('blur','.telefone, .telefone9',function(e){

        var telefone = $(this).val();
        telefone = replaceAll(telefone,'(','');
        telefone = replaceAll(telefone,')','');
        telefone = replaceAll(telefone,' ','');
        telefone = replaceAll(telefone,'-','');

        if(telefone.length==11){
            $(this).setMask('phone9');
        }else{
            $(this).setMask('phone');
        }

    });*/


});


//remove aparencia
function formReset() {
    $('div.invalid-feedback').remove();
    $('.is-invalid').popover('disable');
    $('.is-invalid').removeClass('is-invalid');
}

function mascaraTelefone() {

    $('.telefone, .telefone9').each(function (index, element) {
        var telefone = $(this).val();
        telefone = replaceAll(telefone, '(', '');
        telefone = replaceAll(telefone, ')', '');
        telefone = replaceAll(telefone, ' ', '');
        telefone = replaceAll(telefone, '-', '');

        $(element).removeClass('telefone');
        $(element).removeClass('telefone9');

        if (telefone.length == 11) {
            $(element).setMask('phone9');
            $(element).addClass('telefone9');
        } else {
            $(element).setMask('phone');
            $(element).addClass('telefone');
        }
    });
}

function valida_telefone_vazio(obj) { // funcao base de validar telefone

    $(obj).next('div.invalid-feedback').remove();

    var valor = $(obj).val();

    valor = replaceAll(valor, '(', '');
    valor = replaceAll(valor, ')', '');
    valor = replaceAll(valor, ' ', '');
    valor = replaceAll(valor, '-', '');

    if (valor.length == 0) {

        $(obj).addClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
            $(obj).after(`<div class="invalid-feedback">Campo obrigatório</div>`);
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).attr("data-content", "Campo obrigatório");
            $(obj).popover();
            $(obj).popover('enable');
        }
        return false;
    } else {
        if (valor.length > 0 && valor.length < 10) {

            $(obj).addClass('is-invalid');
            if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
                $(obj).after(`<div class="invalid-feedback">Telefone incompleto! Exemplo: (98) 3235-5010</div>`);
            }
            if ($(obj).attr('data-toggle')) {
                $(obj).attr("data-content", "Telefone incompleto! Exemplo: (98) 3235-5010");
                $(obj).popover();
                $(obj).popover('enable')
            }
            return false;
        } else {
            $(obj).removeClass('is-invalid');
            if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
                //$(obj).next('div.invalid-feedback').remove();
            }
            if ($(obj).attr('data-toggle')) {
                $(obj).popover('disable');
            }
            return true;
        }
    }

}

function valida_telefone(obj) { // funcao base de validar telefone

    $(obj).next('div.invalid-feedback').remove();

    var valor = $(obj).val();
    valor = replaceAll(valor, '(', '');
    valor = replaceAll(valor, ')', '');
    valor = replaceAll(valor, ' ', '');
    valor = replaceAll(valor, '-', '');

    if (valor.length == 0) {
        $(obj).removeClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
            //$(obj).next('div.invalid-feedback').remove();
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).popover('disable');
        }
        return true;
    } else {
        if (valor.length > 0 && valor.length < 10) {

            $(obj).addClass('is-invalid');
            if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
                $(obj).after(`<div class="invalid-feedback">Telefone incompleto! Exemplo: (98) 3235-5010</div>`);
            }
            if ($(obj).attr('data-toggle')) {
                $(obj).attr("data-content", "Telefone incompleto! Exemplo: (98) 3235-5010");
                $(obj).popover();
                $(obj).popover('enable')
            }
            return false;
        } else {
            $(obj).removeClass('is-invalid');
            if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
                //$(obj).next('div.invalid-feedback').remove();
            }
            if ($(obj).attr('data-toggle')) {
                $(obj).popover('disable');
            }
            return true;
        }
    }

}

function valida_campo_vazio(obj, carac_minimo) {

    var valor = $(obj).val();
    var quant = carac_minimo;
    $(obj).siblings('div.invalid-feedback').remove();


    if (valor.length == 0 && quant > 0) {

        $(obj).addClass('is-invalid');
        if ($(obj).siblings('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
            if ($(obj).siblings('div.input-group-append').length) {
                $(obj).siblings('div.input-group-append').after(`<div class="invalid-feedback">Campo obrigatório</div>`);
            } else {
                $(obj).after(`<div class="invalid-feedback">Campo obrigatório</div>`);
            }
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).attr("data-content", "Campo obrigatório");
            $(obj).popover();
            $(obj).popover('enable');
        }

        return false;
    } else {
        if (valor.length > 0 && valor.length < quant) {

            $(obj).addClass('is-invalid');
            if ($(obj).siblings('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
                if ($(obj).siblings('div.input-group-append').length) {
                    $(obj).siblings('div.input-group-append').after(`<div class="invalid-feedback">Campo obrigatório</div>`);
                } else {
                    $(obj).after(`<div class="invalid-feedback">Campo obrigatório</div>`);
                }
            }
            if ($(obj).attr('data-toggle')) {
                $(obj).attr("data-content", "Campo obrigatório");
                $(obj).popover();
                $(obj).popover('enable')
            }
            return false;
        } else {

            $(obj).removeClass('is-invalid');
            if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
                //$(obj).next('div.invalid-feedback').remove();
            }
            if ($(obj).attr('data-toggle')) {
                $(obj).popover('disable');
            }
            return true;
        }
    }

}

function valida_campo(obj, carac_minimo) {


    var valor = $(obj).val();
    var quant = carac_minimo;
    $(obj).siblings('div.invalid-feedback').remove();

    if (valor.length == 0) {
        $(obj).removeClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
            //$(obj).next('div.invalid-feedback').remove();
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).popover('disable');
        }
        return true;
    }

    if (valor.length > 0 && valor.length < quant) {
        $(obj).addClass('is-invalid');
        if ($(obj).siblings('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
            if ($(obj).siblings('div.input-group-append').length) {
                $(obj).siblings('div.input-group-append').after(`<div class="invalid-feedback">Campo obrigatório</div>`);
            } else {
                $(obj).after(`<div class="invalid-feedback">Campo obrigatório</div>`);
            }
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).attr("data-content", "Campo obrigatório");
            $(obj).popover();
            $(obj).popover('enable')
        }
        return false;
    } else {
        $(obj).removeClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
            //$(obj).next('div.invalid-feedback').remove();
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).popover('disable');
        }
        return true;
    }

}


function valida_cpf(obj) {


    var valor = $(obj).val();

    var numeros, digitos, soma, i, resultado, digitos_iguais;
    var cpf;

    $(obj).next('div.invalid-feedback').remove();

    cpf = valor
    cpf = replaceAll(cpf, '.', ''); // tira os pontos
    cpf = replaceAll(cpf, '-', ''); // tira o traço
    digitos_iguais = 1;
    if (cpf.length == 0) {
        $(obj).removeClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
            //$(obj).next('div.invalid-feedback').remove();
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).popover('disable');
        }
        return true;
    }
    if (cpf.length < 11) {

        $(obj).addClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
            $(obj).after(`<div class="invalid-feedback">O CPF está incompleto.</div>`);
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).attr("data-content", "O CPF está incompleto.");
            $(obj).popover();
            $(obj).popover('enable')
        }
        return false;
    }
    for (i = 0; i < cpf.length - 1; i++)
        if (cpf.charAt(i) != cpf.charAt(i + 1)) {
            digitos_iguais = 0;
            break;
        }
    if (!digitos_iguais) {
        numeros = cpf.substring(0, 9);
        digitos = cpf.substring(9);
        soma = 0;
        for (i = 10; i > 1; i--) {
            soma += numeros.charAt(10 - i) * i;
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        }
        if (resultado != digitos.charAt(0)) {

            $(obj).addClass('is-invalid');
            if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
                $(obj).after(`<div class="invalid-feedback">O CPF está inválido!</div>`);
            }
            if ($(obj).attr('data-toggle')) {
                $(obj).attr("data-content", "O CPF está inválido!");
                $(obj).popover();
                $(obj).popover('enable')
            }
            return false;
        }
        numeros = cpf.substring(0, 10);
        soma = 0;
        for (i = 11; i > 1; i--) {
            soma += numeros.charAt(11 - i) * i;
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        }
        if (resultado != digitos.charAt(1)) {
            $(obj).addClass('is-invalid');
            if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
                $(obj).after(`<div class="invalid-feedback">O CPF está inválido!</div>`);
            }
            if ($(obj).attr('data-toggle')) {
                $(obj).attr("data-content", "O CPF está inválido!");
                $(obj).popover();
                $(obj).popover('enable')
            }
            return false;
        }
        $(obj).removeClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
            //$(obj).next('div.invalid-feedback').remove();
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).popover('disable');
        }
        return true;
    } else
        $(obj).addClass('is-invalid');
    if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
        $(obj).after(`<div class="invalid-feedback">O CPF está inválido!</div>`);
    }
    if ($(obj).attr('data-toggle')) {
        $(obj).attr("data-content", "O CPF está inválido!");
        $(obj).popover();
        $(obj).popover('enable')
    }
    return false;

}

function valida_cpf_vazio(obj) {

    var valor = $(obj).val();
    var numeros, digitos, soma, i, resultado, digitos_iguais;
    var cpf;
    $(obj).next('div.invalid-feedback').remove();

    cpf = valor;
    cpf = replaceAll(cpf, '.', ''); // tira os pontos
    cpf = replaceAll(cpf, '-', ''); // tira o traço
    digitos_iguais = 1;
    if (cpf.length == 0) {

        $(obj).addClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
            $(obj).after(`<div class="invalid-feedback">O CPF é obrigatório.</div>`);
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).attr("data-content", "O CPF é obrigatório.");
            $(obj).popover();
            $(obj).popover('enable')
        }
        return false;
    }
    if (cpf.length < 11) {
        $(obj).addClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
            $(obj).after(`<div class="invalid-feedback">O CPF está incompleto.</div>`);
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).attr("data-content", "O CPF está incompleto.");
            $(obj).popover();
            $(obj).popover('enable')
        }
        return false;
    }
    for (i = 0; i < cpf.length - 1; i++)
        if (cpf.charAt(i) != cpf.charAt(i + 1)) {
            digitos_iguais = 0;
            break;
        }
    if (!digitos_iguais) {
        numeros = cpf.substring(0, 9);
        digitos = cpf.substring(9);
        soma = 0;
        for (i = 10; i > 1; i--) {
            soma += numeros.charAt(10 - i) * i;
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        }
        if (resultado != digitos.charAt(0)) {
            $(obj).addClass('is-invalid');
            if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
                $(obj).after(`<div class="invalid-feedback">O CPF está inválido!</div>`);
            }
            if ($(obj).attr('data-toggle')) {
                $(obj).attr("data-content", "O CPF está inválido!");
                $(obj).popover();
                $(obj).popover('enable')
            }
            return false;
        }
        numeros = cpf.substring(0, 10);
        soma = 0;
        for (i = 11; i > 1; i--) {
            soma += numeros.charAt(11 - i) * i;
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        }
        if (resultado != digitos.charAt(1)) {
            $(obj).addClass('is-invalid');
            if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
                $(obj).after(`<div class="invalid-feedback">O CPF está inválido!</div>`);
            }
            if ($(obj).attr('data-toggle')) {
                $(obj).attr("data-content", "O CPF está inválido!");
                $(obj).popover();
                $(obj).popover('enable')
            }
            return false;
        }
        $(obj).removeClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
            //$(obj).next('div.invalid-feedback').remove();
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).popover('disable');
        }
        return true;
    } else
        $(obj).addClass('is-invalid');
    if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
        $(obj).after(`<div class="invalid-feedback">O CPF está inválido!</div>`);
    }
    if ($(obj).attr('data-toggle')) {
        $(obj).attr("data-content", "O CPF está inválido!");
        $(obj).popover();
        $(obj).popover('enable')
    }
    return false;

}


function valida_cnpj_vazio(obj) {

    $(obj).next('div.invalid-feedback').remove();

    var cnpj = $(obj).val();
    cnpj = replaceAll(cnpj, '.', ''); // tira os pontos
    cnpj = replaceAll(cnpj, '-', ''); // tira os pontos
    cnpj = replaceAll(cnpj, '/', ''); // tira os pontos
    var soma1, soma2, resto, digito1, digito2;
    if (cnpj.length != 14) {

        $(obj).addClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
            $(obj).after(`<div class="invalid-feedback">O CNPJ está incompleto!</div>`);
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).attr("data-content", "O CNPJ está incompleto!");
            $(obj).popover();
            $(obj).popover('enable')
        }
        return false;
    }
    soma1 = (cnpj[0] * 5) +
        (cnpj[1] * 4) +
        (cnpj[2] * 3) +
        (cnpj[3] * 2) +
        (cnpj[4] * 9) +
        (cnpj[5] * 8) +
        (cnpj[6] * 7) +
        (cnpj[7] * 6) +
        (cnpj[8] * 5) +
        (cnpj[9] * 4) +
        (cnpj[10] * 3) +
        (cnpj[11] * 2);
    resto = soma1 % 11;
    digito1 = (resto < 2) ? 0 : 11 - resto;

    soma2 = (cnpj[0] * 6) +
        (cnpj[1] * 5) +
        (cnpj[2] * 4) +
        (cnpj[3] * 3) +
        (cnpj[4] * 2) +
        (cnpj[5] * 9) +
        (cnpj[6] * 8) +
        (cnpj[7] * 7) +
        (cnpj[8] * 6) +
        (cnpj[9] * 5) +
        (cnpj[10] * 4) +
        (cnpj[11] * 3) +
        (cnpj[12] * 2);
    resto = soma2 % 11;
    digito2 = (resto < 2) ? 0 : 11 - resto;
    if ((cnpj[12] == digito1) && (cnpj[13] == digito2)) {
        $(obj).removeClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
            //$(obj).next('div.invalid-feedback').remove();
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).popover('disable');
        }
        return true;

    } else {


        $(obj).addClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
            $(obj).after(`<div class="invalid-feedback">CNPJ inválido!</div>`);
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).attr("data-content", "CNPJ inválido!");
            $(obj).popover();
            $(obj).popover('enable')
        }
        return false;
    }
}


function valida_data_vazio(obj) {

    $(obj).next('div.invalid-feedback').remove();

    var valor = $(obj).val();

    if (valor.length == 0) {

        $(obj).addClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
            $(obj).after(`<div class="invalid-feedback">Campo obrigatório</div>`);
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).attr("data-content", "Campo obrigatório");
            $(obj).popover();
            $(obj).popover('enable')
        }
        return false;

    } else {
        if (valor.length > 0 && valor.length < 10) {

            $(obj).addClass('is-invalid');
            if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
                $(obj).after(`<div class="invalid-feedback">Data incompleta! Exemplo: dd/mm/aaaa</div>`);
            }
            if ($(obj).attr('data-toggle')) {
                $(obj).attr("data-content", "Data incompleta! Exemplo: dd/mm/aaaa");
                $(obj).popover();
                $(obj).popover('enable')
            }
            return false;
        } else {
            $(obj).removeClass('is-invalid');
            if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
                //$(obj).next('div.invalid-feedback').remove();
            }
            if ($(obj).attr('data-toggle')) {
                $(obj).popover('disable');
            }
            return true;
        }
    }

}

function valida_data(obj) {

    $(obj).next('div.invalid-feedback').remove();

    var valor = $(obj).val();
    if (valor.length == 0) {
        $(obj).removeClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
            //$(obj).next('div.invalid-feedback').remove();
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).popover('disable');
        }
        return true;
    } else {
        if (valor.length > 0 && valor.length < 10) {

            $(obj).addClass('is-invalid');
            if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
                $(obj).after(`<div class="invalid-feedback">Data incompleta! Exemplo: dd/mm/aaaa</div>`);
            }
            if ($(obj).attr('data-toggle')) {
                $(obj).attr("data-content", "Data incompleta! Exemplo: dd/mm/aaaa");
                $(obj).popover();
                $(obj).popover('enable')
            }
            return false;
        } else {
            $(obj).removeClass('is-invalid');
            if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
                //$(obj).next('div.invalid-feedback').remove();
            }
            if ($(obj).attr('data-toggle')) {
                $(obj).popover('disable');
            }
            return true;
        }
    }

}

function valida_cep_vazio(obj) {

    $(obj).siblings('div.invalid-feedback').remove();

    var valor = $(obj).val();
    if (valor.length == 0) {
        $(obj).addClass('is-invalid');

        if (($(obj).siblings('div.invalid-feedback').length == 0 || $(obj).siblings('div.input-group-append').length == 0) && !$(obj).attr('data-toggle')) {
            if ($(obj).siblings('div.input-group-append').length) {
                $(obj).next('div.input-group-append').eq(0).after(`<div class="invalid-feedback">Campo obrigatório</div>`);
            } else {
                $(obj).after(`<div class="invalid-feedback">Campo obrigatório</div>`);
            }

        }
        if ($(obj).attr('data-toggle')) {
            $(obj).attr("data-content", "Campo obrigatório");
            $(obj).popover();
            $(obj).popover('enable')
        }
        return false;
    } else {
        if (valor.length > 0 && valor.length < 9) {

            $(obj).addClass('is-invalid');
            if (($(obj).siblings('div.invalid-feedback').length == 0 || $(obj).siblings('div.input-group-append').length == 0) && !$(obj).attr('data-toggle')) {
                if ($(obj).siblings('div.input-group-append').length) {
                    $(obj).next('div.input-group-append').eq(0).after(`<div class="invalid-feedback">Exemplo: 65000-000</div>`);
                } else {
                    $(obj).after(`<div class="invalid-feedback">Exemplo: 65000-000</div>`);
                }

            }
            if ($(obj).attr('data-toggle')) {
                $(obj).attr("data-content", "Exemplo: 65000-000");
                $(obj).popover();
                $(obj).popover('enable')
            }
            return false;
        } else {
            $(obj).removeClass('is-invalid');
            if (($(obj).siblings('div.invalid-feedback').length > 0 || $(obj).siblings('div.input-group-append').length > 0) && !$(obj).attr('data-toggle')) {
                //$(obj).next('div.invalid-feedback').remove();
            }
            if ($(obj).attr('data-toggle')) {
                $(obj).popover('disable');
            }
            return true;
        }
    }

}

function valida_dinheiro(obj) {

    $(obj).siblings('div.invalid-feedback').remove();

    var valor = convertFloat($(obj).val());
    if (valor == 0.00) {

        $(obj).addClass('is-invalid');
        if ($(obj).siblings('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
            if ($(obj).siblings('div.input-group-append').length) {
                $(obj).siblings('div.input-group-append').after(`<div class="invalid-feedback">O valor não pode ser 0,00</div>`);
            } else {
                $(obj).after(`<div class="invalid-feedback">O valor não pode ser 0,00</div>`);
            }
        }


        if ($(obj).attr('data-toggle')) {
            $(obj).attr("data-content", "O valor não pode ser 0,00");
            $(obj).popover();
            $(obj).popover('enable')
        }
        return false;
    } else {
        $(obj).removeClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
            //$(obj).next('div.invalid-feedback').remove();
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).popover('disable');
        }
        return true;
    }
}

function testaEmail(email) {
    //var regex=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (regex.test(email)) {
        return true;
    } else {
        return false;
    }
}

function validaEmailVazio(obj) {

    $(obj).next('div.invalid-feedback').remove();

    //var regex=/^[\w.-_\+]+@[\w-]+(\.\w{2,4})+$/;
    var regex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    var valor = $(obj).val();

    if (regex.test(valor)) {
        $(obj).removeClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
            //$(obj).next('div.invalid-feedback').remove();
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).popover('disable');
        }
        return true;
    } else {

        $(obj).addClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
            $(obj).after(`<div class="invalid-feedback">E-mail inválido</div>`);
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).attr("data-content", "E-mail inválido");
            $(obj).popover();
            $(obj).popover('enable')
        }
        return false;
    }
}


function validaEmail(obj) {

    $(obj).next('div.invalid-feedback').remove();

    //var regex=/^[\w.-_\+]+@[\w-]+(\.\w{2,4})+$/;
    var regex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    var valor = $(obj).val();

    if (regex.test(valor)) {
        $(obj).removeClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
            //$(obj).next('div.invalid-feedback').remove();
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).popover('disable');
        }
        return true;
    } else {
        if (valor.length == 0) {
            $(obj).removeClass('is-invalid');
            if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
                //$(obj).next('div.invalid-feedback').remove();
            }
            if ($(obj).attr('data-toggle')) {
                $(obj).popover('disable');
            }
            return true;
        } else {
            $(obj).addClass('is-invalid');
            if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
                $(obj).after(`<div class="invalid-feedback">E-mail inválido</div>`);
            }
            if ($(obj).attr('data-toggle')) {
                $(obj).attr("data-content", "E-mail inválido");
                $(obj).popover();
                $(obj).popover('enable')
            }
            return false;
        }

    }
}

function convertFloat(string) {
    if (string === "") {
        return 0;
    }
    while (string.indexOf(".") != -1) {
        string = string.replace(".", "");
    }
    string = string.replace(",", ".");
    if (string) {
        return parseFloat(string);
    } else {
        return 0;
    }

}

function convertRealFloat(valor) {
    var absoluta = Math.floor(valor);
    var fracao = valor - absoluta;
    fracao = String(fracao);
    fracao = fracao.substr(0, 4);
    fracao = parseFloat(fracao);
    return absoluta + fracao;
}

function number_format(number, decimals, dec_point, thousands_sep) {
    // http://kevin.vanzonneveld.net

    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function mostraErro(retornoLaravel, titulo = 'Ocorreu um erro', quantidade = 3) {
    if (retornoLaravel === undefined) {
        return false;
    }
    let mensagem = '';
    titulo = retornoLaravel.msg ? retornoLaravel.msg : titulo;
    let lista = _.keys(retornoLaravel.erros);
    if (lista.length) {
        mensagem += `<ul>`;
        let total = 1;
        lista.every((key, item) => {
            let descricao = retornoLaravel.erros[key][0];
            mensagem += `<li> <strong>${key}:</strong> ${descricao} </li>`;
            total++;
            if (total == quantidade) {
                return false;
            }
        });
        mensagem += `</ul>`;
    } else {
        mensagem = retornoLaravel.message;
    }


    toastr.error(mensagem, titulo);
}

function mostraSucesso(mensagem, titulo) {
    toastr.success(mensagem, titulo);
}

function valida_select(obj) {
    var valor = $(obj).val();
    $(obj).next('div.invalid-feedback').remove();
    if (valor == 0) {

        $(obj).addClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length == 0 && !$(obj).attr('data-toggle')) {
            $(obj).after(`<div class="invalid-feedback">Campo obrigatório</div>`);
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).attr("data-content", "Campo obrigatório");
            $(obj).popover();
            $(obj).popover('enable');
        }

        return false;
    } else {
        $(obj).removeClass('is-invalid');
        if ($(obj).next('div.invalid-feedback').length > 0 && !$(obj).attr('data-toggle')) {
            //$(obj).next('div.invalid-feedback').remove();
        }
        if ($(obj).attr('data-toggle')) {
            $(obj).popover('disable');
        }
        return true;
    }

}
