<?php

use App\Models\Habilidade;
use Illuminate\Database\Seeder;

class HabilidadesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*//Habilidades------------------------------
        $lista[]=['nome'=>'habilidades','descricao'=> 'Acessar rota/menu habilidades'];
        $lista[]=['nome'=>'habilidades_insert','descricao'=> 'Pode inserir uma nova habilidade'];
        $lista[]=['nome'=>'habilidades_update','descricao'=> 'Pode alterar uma habilidade'];
        $lista[]=['nome'=>'habilidades_delete','descricao'=> 'Pode apagar uma habilidade'];

        //Papeis------------------------------
        $lista[]=['nome'=>'papel','descricao'=> 'Acessar rota/menu papeis'];
        $lista[]=['nome'=>'papel_insert','descricao'=> 'Pode cadastrar um papel'];
        $lista[]=['nome'=>'papel_update','descricao'=> 'Pode alterar um papel'];
        $lista[]=['nome'=>'papel_delete','descricao'=> 'Pode apagar um papel'];

        //Usuários------------------------------
        $lista[]=['nome'=>'usuarios','descricao'=> 'Acessar rota/menu usuários'];
        $lista[]=['nome'=>'usuarios_insert','descricao'=> 'Pode cadastrar um usuário'];
        $lista[]=['nome'=>'usuarios_update','descricao'=> 'Pode alterar um usuário'];
        $lista[]=['nome'=>'usuarios_delete','descricao'=> 'Pode apagar um usuário'];
        $lista[]=['nome'=>'alterar-senha','descricao'=> 'Pode alterar sua propria senha de acesso ao sistema'];

        //Clientes------------------------------
        $lista[]=['nome'=>'clientes','descricao'=> 'Acessar rota/menu clientes'];
        $lista[]=['nome'=>'clientes_insert','descricao'=> 'Pode cadastrar um cliente'];
        $lista[]=['nome'=>'clientes_update','descricao'=> 'Pode alterar um cliente'];
        $lista[]=['nome'=>'clientes_delete','descricao'=> 'Pode apagar um cliente'];
        */
        /*
                //Curriculos------------------------------
                $lista[] = ['nome' => 'curriculos', 'descricao' => 'Acessar rota/menu curriculos'];
                $lista[] = ['nome' => 'curriculos_insert', 'descricao' => 'Pode cadastrar um curriculo'];
                $lista[] = ['nome' => 'curriculos_update', 'descricao' => 'Pode alterar um curriculo'];
                $lista[] = ['nome' => 'curriculos_delete', 'descricao' => 'Pode apagar um curriculo'];

                //FeedBack Curriculos------------------------------
                $lista[] = ['nome' => 'feedback_curriculos', 'descricao' => 'Acessar rota/menu Feedback Curriculos'];
                $lista[] = ['nome' => 'feedback_curriculos_insert', 'descricao' => 'Pode cadastrar um Feedback Curriculo'];
                $lista[] = ['nome' => 'feedback_curriculos_update', 'descricao' => 'Pode alterar um Feedback Curriculo'];
                $lista[] = ['nome' => 'feedback_curriculos_delete', 'descricao' => 'Pode apagar um Feedback Curriculo'];

                //ParecerRH------------------------------
                $lista[] = ['nome' => 'parecer_rh', 'descricao' => 'Acessar rota/menu Parecer Rh'];
                $lista[] = ['nome' => 'parecer_rh_insert', 'descricao' => 'Pode cadastrar um Parecer Rh'];
                $lista[] = ['nome' => 'parecer_rh_update', 'descricao' => 'Pode alterar um Parecer Rh'];
                $lista[] = ['nome' => 'parecer_rh_delete', 'descricao' => 'Pode apagar um Parecer Rh'];

                //ParecerRota------------------------------
                $lista[] = ['nome' => 'parecer_rota', 'descricao' => 'Acessar rota/menu Parecer Rota Transporte'];
                $lista[] = ['nome' => 'parecer_rota_insert', 'descricao' => 'Pode cadastrar um Parecer Rota Transporte'];
                $lista[] = ['nome' => 'parecer_rota_update', 'descricao' => 'Pode alterar um Parecer Rota Transporte'];
                $lista[] = ['nome' => 'parecer_rota_delete', 'descricao' => 'Pode apagar um Parecer Rota Transporte'];

                //ParecerTestePratico------------------------------
                $lista[] = ['nome' => 'parecer_teste_pratico', 'descricao' => 'Acessar rota/menu Parecer Teste Prático'];
                $lista[] = ['nome' => 'parecer_teste_pratico_insert', 'descricao' => 'Pode cadastrar um Parecer Teste Prático'];
                $lista[] = ['nome' => 'parecer_teste_pratico_update', 'descricao' => 'Pode alterar um Parecer Teste Prático'];
                $lista[] = ['nome' => 'parecer_teste_pratico_delete', 'descricao' => 'Pode apagar um Parecer Teste Prático'];

                //ParecerEntrevista------------------------------
                $lista[] = ['nome' => 'parecer_entrevista', 'descricao' => 'Acessar rota/menu Parecer Entrevistas Técnicas'];
                $lista[] = ['nome' => 'parecer_entrevista_insert', 'descricao' => 'Pode cadastrar um Parecer Entrevista Técnica'];
                $lista[] = ['nome' => 'parecer_entrevista_update', 'descricao' => 'Pode alterar um Parecer Entrevista Técnica'];
                $lista[] = ['nome' => 'parecer_entrevista_delete', 'descricao' => 'Pode apagar um Parecer Entrevista Técnica'];

                //Resultado Integrado------------------------------
                $lista[] = ['nome' => 'resultado_integrado', 'descricao' => 'Acessar rota/menu Resultados Integrados'];
                $lista[] = ['nome' => 'resultado_integrado_insert', 'descricao' => 'Pode cadastrar um Resultado'];
                $lista[] = ['nome' => 'resultado_integrado_update', 'descricao' => 'Pode alterar um Resultado'];
                $lista[] = ['nome' => 'resultado_integrado_delete', 'descricao' => 'Pode apagar um Resultado'];

                //Admissão------------------------------
                $lista[] = ['nome' => 'admissao', 'descricao' => 'Acessar rota/menu Admissões'];
                $lista[] = ['nome' => 'admissao_insert', 'descricao' => 'Pode cadastrar um Admissão'];
                $lista[] = ['nome' => 'admissao_update', 'descricao' => 'Pode alterar um Admissão'];
                $lista[] = ['nome' => 'admissao_delete', 'descricao' => 'Pode apagar um Admissão'];



                //Cloud------------------------------
                $lista[] = ['nome' => 'cloud', 'descricao' => 'Acessar rota/menu Cloud'];
                $lista[] = ['nome' => 'cloud_insert', 'descricao' => 'Pode cadastrar um Cloud'];
                $lista[] = ['nome' => 'cloud_update', 'descricao' => 'Pode alterar um Cloud'];
                $lista[] = ['nome' => 'cloud_delete', 'descricao' => 'Pode apagar um Cloud'];

                //Cloud------------------------------
                $lista[] = ['nome' => 'cloud_bpse', 'descricao' => 'Acessar rota/menu Cloud BPSE'];
                $lista[] = ['nome' => 'cloud_bpse_insert', 'descricao' => 'Pode cadastrar um Cloud BPSE'];
                $lista[] = ['nome' => 'cloud_bpse_update', 'descricao' => 'Pode alterar um Cloud BPSE'];
                $lista[] = ['nome' => 'cloud_bpse_delete', 'descricao' => 'Pode apagar um Cloud BPSE'];

                //Cloud------------------------------
                $lista[] = ['nome' => 'cloud_clientes', 'descricao' => 'Acessar rota/menu Cloud Clientes'];
                $lista[] = ['nome' => 'cloud_clientes_insert', 'descricao' => 'Pode cadastrar um Cloud Clientes'];
                $lista[] = ['nome' => 'cloud_clientes_update', 'descricao' => 'Pode alterar um Cloud Clientes'];
                $lista[] = ['nome' => 'cloud_clientes_delete', 'descricao' => 'Pode apagar um Cloud Clientes'];

                //Cloud------------------------------
                $lista[] = ['nome' => 'cloud_publica', 'descricao' => 'Acessar rota/menu Cloud Pública'];
                $lista[] = ['nome' => 'cloud_publica_insert', 'descricao' => 'Pode cadastrar um Cloud Pública'];
                $lista[] = ['nome' => 'cloud_publica_update', 'descricao' => 'Pode alterar um Cloud Pública'];
                $lista[] = ['nome' => 'cloud_publica_delete', 'descricao' => 'Pode apagar um Cloud Pública'];



                //Cloud------------------------------
                $lista[] = ['nome' => 'cloud_configuracoes', 'descricao' => 'Acessar rota/menu Cloud Configurações'];
                $lista[] = ['nome' => 'cloud_configuracoes_insert', 'descricao' => 'Pode cadastrar um Cloud Configurações'];
                $lista[] = ['nome' => 'cloud_configuracoes_update', 'descricao' => 'Pode alterar um Cloud Configurações'];
                $lista[] = ['nome' => 'cloud_configuracoes_delete', 'descricao' => 'Pode apagar um Cloud Configurações'];

                //Galeria------------------------------
                $lista[] = ['nome' => 'galeria_site', 'descricao' => 'Acessar rota/menu Site - Galeria'];
                $lista[] = ['nome' => 'galeria_site_insert', 'descricao' => 'Pode cadastrar uma Galeria'];
                $lista[] = ['nome' => 'galeria_site_update', 'descricao' => 'Pode alterar uma Galeria'];
                $lista[] = ['nome' => 'galeria_site_delete', 'descricao' => 'Pode apagar uma Galeria'];

                */

        //Cartela Cliente------------------------------
        $lista[] = ['nome' => 'cartela_cliente_site', 'descricao' => 'Acessar rota/menu Site - Cartela Cliente'];
        $lista[] = ['nome' => 'cartela_cliente_site_insert', 'descricao' => 'Pode cadastrar uma Cartela Cliente'];
        $lista[] = ['nome' => 'cartela_cliente_site_update', 'descricao' => 'Pode alterar uma Cartela Cliente'];
        $lista[] = ['nome' => 'cartela_cliente_site_delete', 'descricao' => 'Pode apagar uma Cartela Cliente'];

        //DEPOIMENTOS------------------------------
        $lista[] = ['nome' => 'depoimento_site', 'descricao' => 'Acessar rota/menu Site - Depoimento'];
        $lista[] = ['nome' => 'depoimento_site_insert', 'descricao' => 'Pode cadastrar um Depoimento'];
        $lista[] = ['nome' => 'depoimento_site_update', 'descricao' => 'Pode alterar um Depoimento'];
        $lista[] = ['nome' => 'depoimento_site_delete', 'descricao' => 'Pode apagar um Depoimento'];


        foreach ($lista as $habilidade) {
            Habilidade::create($habilidade);
        }

    }
}
