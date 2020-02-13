<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', 'g/login');

Route::group(['prefix' => 'publico', 'as' => 'publico.'], function () {
    Route::post('upload', 'PublicoController@upload')->name('upload');
    Route::get('foto/{nome}', 'PublicoController@download')->name('foto-download');
});


Route::group(['prefix' => 'g'], function () {
    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::get('sair', 'Auth\LoginController@logout')->name('logout');
    // Registration Routes...
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register')->middleware('auth', 'habilidades');
    Route::post('register', 'Auth\RegisterController@register')->middleware('auth', 'habilidades');
    // Password Reset Routes...
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::group(['middleware' => ['auth', 'habilidades'], 'as' => 'g.'], function () {

        Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');

        // Site
        Route::group(['as' => 'site.'], function () {
            //ANEXO DE FOTOS
            Route::post('galeria/uploadFotos', 'GaleriaController@uploadFotos')->name('galeria.upload-fotos')->middleware('can:galeria_site');
            Route::get('galeria/fotoDownload/{arquivo}', 'GaleriaController@download')->name('galeria.foto-download')->middleware('can:galeria_site');

            Route::post('galeria/atualizar', 'GaleriaController@atualizar')->name('galeria.atualizar')->middleware('can:galeria_site');
            Route::resource('galeria', 'GaleriaController', ['parameters' => ['galeria' => 'galeria']])->middleware('can:galeria_site');

            //CLIENTES
            Route::group(['as' => 'cliente.'], function () {
                Route::post('cliente-logo/atualizar', 'ClienteLogoSiteController@atualizar')->name('atualizar')->middleware('can:cartela_cliente_site');
                Route::post('cliente-logo/fotoUpload', 'ClienteLogoSiteController@fotoUpload')->name('upload-fotos')->middleware('can:cartela_cliente_site_insert');
//                Route::get('cliente/fotoDownload/{arquivo}', 'ClientesController@download')->name('foto-download');
                Route::resource('cliente-logo', 'ClienteLogoSiteController')->middleware('can:cartela_cliente_site');
            });

            Route::group(['as' => 'testemunhal.'], function () {
                // Anexos
                Route::post('testemunhal/uploadAnexos', 'TestemunhalController@uploadAnexos')->name('testemunhal.upload-anexos')->middleware('can:depoimento_site_insert');
                Route::get('testemunhal/anexo/{arquivo}', 'TestemunhalController@anexoShow')->name('testemunhal.anexo-show');
                Route::get('testemunhal/anexoDownload/{arquivo}', 'TestemunhalController@download')->name('testemunhal.anexo-download');
                Route::delete('testemunhal/anexo/{arquivo}', 'TestemunhalController@anexoDelete')->name('testemunhal.anexo-delete');


                Route::post('testemunhal/atualizar', 'TestemunhalController@atualizar')->name('testemunhal.atualizar')->middleware('can:depoimento_site');
                Route::resource('testemunhal', 'TestemunhalController')->middleware('can:depoimento_site');
            });
        });


        // Configurações
        Route::group(['as' => 'configuracoes.'], function () {
            // Habilidades
            Route::post('habilidades/atualizar', 'HabilidadesController@atualizar')->name('habilidades.atualizar')->middleware('can:habilidades'); // manter essa rota antes do resource
            Route::resource('habilidades', 'HabilidadesController')->middleware('can:habilidades');

            // Papeis
            Route::post('papeis/atualizar', 'PapeisController@atualizar')->name('papeis.atualizar')->middleware('can:papel');
            Route::resource('papeis', 'PapeisController', ['parameters' => ['papeis' => 'papel']])->middleware('can:papel');

            // Horario acesso
            Route::get('horario-acesso', 'HorarioAcessoController@index')->name('horario-acesso.index')->middleware('can:horario-acesso');
            Route::get('horario-acesso/init', 'HorarioAcessoController@init')->name('horario-acesso.init')->middleware('can:horario-acesso');
            Route::put('horario-acesso', 'HorarioAcessoController@update')->name('horario-acesso.update')->middleware('can:horario-acesso');
            Route::put('horario-acesso/ativa-desativa', 'HorarioAcessoController@ativaDesativa')->name('horario-acesso.ativaDesativa')->middleware('can:horario-acesso');

        });

        Route::group(['as' => 'usuarios.'], function () {
            //Usuários
            Route::post('usuarios/atualizar', 'UserController@atualizar')->name('usuarios.atualizar')->middleware('can:usuarios');
            Route::resource('usuarios', 'UserController')->middleware('can:usuarios');

            //Alterar senha
            Route::get('alterar-senha', 'AlterarSenhaController@index')->name('alterar-senha.index')->middleware('can:alterar-senha');
            Route::put('alterar-senha', 'AlterarSenhaController@update')->name('alterar-senha.update')->middleware('can:alterar-senha');
        });

    });

});

