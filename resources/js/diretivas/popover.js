Vue.directive('popover', {
    inserted: function (el, binding, vnode) {

        $(el).attr("data-toggle", "popover");
        $(el).attr("data-trigger", "hover");
        $(el).attr("data-placement", "top");
        if (!$(el).attr("title")) {
            $(el).attr("title", "Verificar o erro");
        }
        $(el).attr("data-content", "Campo obrigatório");
    }
});
