/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

window.toastr = require('toastr');
toastr.options.closeButton = true;
//
// window.select2 = require('select2');
// require('select2/dist/js/i18n/pt-BR');
// $.fn.select2.defaults.set("theme", "bootstrap4");
// $.fn.select2.defaults.set("width", "100%");
// $.fn.select2.defaults.set("minimumInputLength", "2");


window.moment = require('moment');
require('bootstrap-daterangepicker');
moment.locale('pt-BR');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('modal', require('./components/Modal.vue'));
Vue.component('autocomplete', require('./components/AutoComplete.vue'));


//Diretivas globais
require('./diretivas/mascaras');
require('./diretivas/popover');


window.URL_SITE = process.env.MIX_URL_SITE;
window.URL_ADMIN = process.env.MIX_URL_ADMIN;
window.URL_PUBLICO = process.env.MIX_URL_PUBLICO;
