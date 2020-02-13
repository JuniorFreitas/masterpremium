const mix = require('laravel-mix');

mix
    .js('resources/js/app.js', 'public/js')
    //Configurações
    .js('resources/js/g/configuracoes/habilidades/app.js', 'public/js/g/habilidades/')
    .js('resources/js/g/configuracoes/papeis/app.js', 'public/js/g/papeis/')
    .js('resources/js/g/configuracoes/horario-acesso/app.js', 'public/js/g/horario-acesso/')
    //Usúarios
    .js('resources/js/g/usuarios/usuarios/app.js', 'public/js/g/usuarios/')
    .js('resources/js/g/usuarios/alterar-senha/app.js', 'public/js/g/alterar-senha/')
    //Site G/
    .js('resources/js/g/site/galeria/app.js', 'public/js/g/site/galeria')
    .js('resources/js/g/site/clientes/app.js', 'public/js/g/site/clientes')
    .js('resources/js/g/site/testemunhal/app.js', 'public/js/g/site/testemunhal/')
;

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
//.extract(['vue'])

mix.sass('resources/sass/app.scss', 'public/css');

mix.babel([
    'resources/js/funcoes.js',
    'resources/js/jquery.mask.js',
    'resources/js/jquery.maskMoney.js',
], 'public/js/funcoes.js');

mix.disableNotifications();

if (mix.inProduction()) {
    mix.version();
}
