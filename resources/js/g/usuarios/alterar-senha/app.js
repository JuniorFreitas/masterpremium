const app = new Vue({
    el: '#app',
    data: {
        preloadAjax: false,
    },
    methods: {
        alterar: function () {

            $(':input').trigger('blur');
            if ($(':input.is-invalid').length) {
                alert('Verificar os erros');
                return false;
            }

            var dados = {};
            dados.password = $('#password').val();
            dados.password_confirmation = $('#password_confirmation').val();
            dados._method = 'PUT';
            this.preloadAjax = true;

            $.post(`${URL_ADMIN}/alterar-senha`, dados).done((data) => {

                this.preloadAjax = false;
                mostraSucesso('Senha alterada com sucesso');
            }).fail((data) => {
                this.preloadAjax = false;
            });
        }


    }
});
