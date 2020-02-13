<?php

namespace App\Models;

use MasterTag\DataHora;

class Sistema
{

    public const UrlServidor = 'http://159.89.154.53:8991/3hmMaxB0QB0zvE48exportsBGQG3bheYiaQP1cWIqdhPL1lbv5g9tWBnBhRUDIJCRFM2gqbZSALev3zPcZVbHlZS';

    public static $EMAIL_RESCISAO = "rescisao@roquemacatrao.com.br";

    public static $EMAIL_CONTRATO = "contratos@roquemacatrao.com.br";
    public static $SENHA_EMAIL_CONTRATO = "contratos2015";

    public static $EMAIL_CORRETORES = "vendas@roquemacatrao.com.br";

    public static $EMAIL_SISTEMA = "sistema@roquemacatrao.com.br";
    public static $SENHA_EMAIL_SISTEMA = "zcwsFgZdwO";

    public static $API_KEY = 'hVBcx5=F?vqSK86'; // nao lembro agora


    // ############################################## Enviar e-mail ###############################################

    /*public static function enviarEmail($arrayDe,$arrayPara,$assunto,$mensagem,$arrayFile=NULL){
        $envio= new PHPMailer();
        $envio->SetFrom($arrayDe['email'],utf8_decode($arrayDe['nome']));
        $envio->IsHTML(true); // Caso queira enviar em HTML
        $envio->IsSendmail();

        $envio->Subject=html_entity_decode(utf8_decode($assunto));
        $envio->Body=utf8_decode($mensagem);

        foreach ($arrayPara as $para){
            $envio->AddAddress($para['email'],utf8_decode($para['nome']));
        }
        if(isset($arrayFile)){
            foreach ($arrayFile as $arquivo){
                $envio->AddAttachment($arquivo['arquivo'],$arquivo['nome']);
            }
        }

        $enviou = $envio->Send();
        //$enviou=true;
        if($enviou){
            return TRUE;
        }else{
            return FALSE;
        }
    }*/

    //################################################## NIVEL e ACESSO ###########################################


    public static function nomeDoGrupo()
    {

        $usuario = auth()->user();

        return Papel::findOrFail($usuario->grupo_id)->nome;
    }

    public static function nomeDoUsuario()
    {
        return auth()->user()->nome;
    }

    public static function emailDoGrupo()
    {
        $usuario = auth()->user();

        $grupo = Papel::find($usuario->grupo_id);
        if ($grupo) {
            return $grupo->email;
        }
        return self::$EMAIL_CONTRATO; // no caso de não encontrar
    }

    public static function permitirLinks($links)
    {

        if (func_num_args() == 0) {
            return false;
        }
        $lista = [];

        for ($i = 0; $i < func_num_args(); $i++) {
            $lista[] = func_get_arg($i);
        }


        $retorno = false;
        $listaDeHabilidade = auth()->user()->listaDeHabilidades();
        $listaDeHabilidade = collect($listaDeHabilidade);

        foreach ($lista as $habilidade) {
            if ($listaDeHabilidade->search($habilidade) != false) {
                $retorno = true;
                break;
            }
        }

        return $retorno;

    }

    // Gera uma chave unica para cada operação de pagina. Muito usado para identificar os upLoads do usuario em sessão
    public static function gerarChave()
    {

        return auth()->id() . "_" . str_random(30);
    }

    public static function imovelEmFavoritos($ID_IMOVEL)
    {
        if (session()->has('LISTA_FAVORITOS')) { // se já foi criado um array de sessao com esta nome...
            $lista = collect(session('LISTA_FAVORITOS')); // pegar o array

            return $lista->search($ID_IMOVEL);

        } else {
            return FALSE;
        }
    }


    public static function limparTemporarios()
    {

        /*$conecta=$CONEXAO;

        // Buscar Anexos de cliente
        $comando = "select arquivoGrande_ac, arquivoPequeno_ac FROM clientes_anexos
		WHERE temporario_ac='s' and enviado_ac=:USUARIO";

        $resposta = $conecta->prepare($comando);
        $resposta->bindValue(":USUARIO", $_SESSION['ID_USUARIO'], PDO::PARAM_INT);
        $resposta->execute();
        foreach ($resposta->fetchAll(PDO::FETCH_ASSOC) as $linha) {
            unlink('gerenciador/arquivos/anexos-clientes/'.$linha['arquivoGrande_ac']);
            unlink('gerenciador/arquivos/anexos-clientes/'.$linha['arquivoPequeno_ac']);
        }

        // Apagar anexos de cliente do banco
        $comando = "delete FROM clientes_anexos WHERE temporario_ac='s' and enviado_ac=:USUARIO";

        $resposta = $conecta->prepare($comando);
        $resposta->bindValue(":USUARIO", $_SESSION['ID_USUARIO'], PDO::PARAM_INT);
        $resposta->execute();
        //----------------------------------------------------------------------------------------

        // Buscar anexos de imoveis
        $comando = "select arquivoGrande_ai, arquivoPequeno_ai FROM imoveis_anexos
		WHERE temporario_ai='s' and idusuario_usu=:USUARIO";

        $resposta = $conecta->prepare($comando);
        $resposta->bindValue(":USUARIO", $_SESSION['ID_USUARIO'], PDO::PARAM_INT);
        $resposta->execute();
        foreach ($resposta->fetchAll(PDO::FETCH_ASSOC) as $linha) {
            unlink('gerenciador/arquivos/anexos-imoveis/'.$linha['arquivoGrande_ai']);
            unlink('gerenciador/arquivos/anexos-imoveis/'.$linha['arquivoPequeno_ai']);
        }

        // Apagar anexos de imoveis do banco
        $comando = "delete FROM imoveis_anexos
		WHERE temporario_ai='s' and idusuario_usu=:USUARIO";

        $resposta = $conecta->prepare($comando);
        $resposta->bindValue(":USUARIO", $_SESSION['ID_USUARIO'], PDO::PARAM_INT);
        $resposta->execute();

        //--------------------------------------------------------------------------------------------

        // Buscar fotos do imoveis
        $comando = "select arquivoGrande_if, arquivoPequeno_if FROM imoveis_fotos
		WHERE temporario_if='s' and idusuario_usu=:USUARIO";

        $resposta = $conecta->prepare($comando);
        $resposta->bindValue(":USUARIO", $_SESSION['ID_USUARIO'], PDO::PARAM_INT);
        $resposta->execute();
        foreach ($resposta->fetchAll(PDO::FETCH_ASSOC) as $linha) {
            unlink('../imagens/fotos_imovel/'.$linha['arquivoGrande_if']);
            unlink('../imagens/fotos_imovel/'.$linha['arquivoPequeno_if']);
        }

        // Apagar fotos do imoveis do banco
        $comando = "delete FROM imoveis_fotos WHERE temporario_if='s' and idusuario_usu=:USUARIO";

        $resposta = $conecta->prepare($comando);
        $resposta->bindValue(":USUARIO", $_SESSION['ID_USUARIO'], PDO::PARAM_INT);
        $resposta->execute();


        //----- Contratos em PDF e arquivos temporarios
        require_once("Arquivos.php");
        Arquivos::excluirTemporarios($conecta);*/

    }

    public static function atualizaUltimoAcesso()
    {

        $agora = new DataHora();
        $usuario = auth()->user();
        $usuario->ultimo_acesso = $agora->dataHoraInsert();
        $usuario->save();

    }

    public static function sistemaDisponivel($CONEXAO)
    {
        $agora = new DataHora();
        $comando = "SELECT hora_abertura_hs,hora_fechamento_hs FROM horario_sistema WHERE ativo_hs='s'";
        $resposta = $CONEXAO->prepare($comando);
        $resposta->execute();
        foreach ($resposta->fetchAll(PDO::FETCH_ASSOC) as $linha) {
            if ($agora->horaInsert() >= $linha['hora_abertura_hs'] && $agora->horaInsert() <= $linha['hora_fechamento_hs']) {
                return TRUE;
            } else {
                if (Sistema::permitirModulo('acesso-sistema-horario', NULL, $CONEXAO)) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        }
        return true; // se não estiver ativo
    }

    // Retorna TRUE ou FALSE, se o site se encontra em manutenção programada. Ja atualiza o campode ativo ou não
    public static function siteOnline($CONEXAO)
    {
        /*$conecta=$CONEXAO;

        $comando = "SELECT  * FROM site";
        $resposta = $conecta->prepare($comando);
        $resposta->execute();

        foreach ($resposta->fetchAll(PDO::FETCH_ASSOC) as $linha) {
            // verificar o tempo
            $agora = new DataHora();
            $previsao = new DataHora($linha['datahora_volta_site']);
            $dados = DataHora::distanciaTempo($agora->dataHoraInsert(),$previsao->dataHoraInsert());

            if($dados['segundo'] <= 0 && $linha['ativo_site']=='n'){ // se agora já passou da previsao e ainda esta com status antigo, mudar
                $comando = "UPDATE  site set ativo_site=:ATIVO";
                $resposta = $conecta->prepare($comando);
                $resposta->bindValue(":ATIVO", 's', PDO::PARAM_STR);
                $resposta->execute();
                return TRUE;
            }

            if($linha['ativo_site']=='n'){
                return FALSE;
            }else{
                return TRUE;
            }
        }*/

    }

    // Data limite para lançamentos ou alterações em conta corrente
    public static function dataFechamentoFinanceiro($CONEXAO, $data = "")
    {
        /*if($data==""){
            $comando = "SELECT * FROM fechamento_financeiro";
            $resposta = $CONEXAO->prepare($comando);
            $resposta->execute();

            foreach ($resposta->fetchAll(PDO::FETCH_ASSOC) as $linha) {
                return $linha['data_ff'];
            }
        }else{
            $data= new DataHora($data);
            $comando = "UPDATE fechamento_financeiro SET data_ff=:DATA_FECHAMENTO";
            $resposta = $CONEXAO->prepare($comando);
            $resposta->bindValue(":DATA_FECHAMENTO", $data->dataInsert(), PDO::PARAM_STR);
            $resposta->execute();
        }*/
    }

    /*
        set o status do site com horario
    */
    public static function statusSite($CONEXAO, $ativo, $dataHora)
    {
        /*$dataHora = new DataHora($dataHora);

        $comando = "UPDATE  site set ativo_site=:ATIVO, datahora_volta_site=:DATA_HORA";
        $resposta = $CONEXAO->prepare($comando);
        $resposta->bindValue(":ATIVO", $ativo, PDO::PARAM_STR);
        $resposta->bindValue(":DATA_HORA", $dataHora->dataHoraInsert(), PDO::PARAM_STR);
        $resposta->execute();*/
    }


    public static function validaCPF($cpf)
    {
        // Converte em somente número todos os digitos
        $cpf = str_pad(preg_replace('/[^0-9]/i', '', $cpf), 11, '0', STR_PAD_LEFT);
        // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
        if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
            return false;
        } else {   // Calcula os números para verificar se o CPF é verdadeiro
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }

                $d = ((10 * $d) % 11) % 10;

                if ($cpf{$c} != $d) {
                    return response()->json([
                        'msg' => 'CPF Inválido',
                    ], 400);
                }
            }

            return true;
        }
    }

    // Verifica se CPF do cliente já existe no sistema cadastrado
    public static function verificaCpfCadastrado($classe, $cpf)
    {
        if (!empty($cpf)) {
            if (self::validaCPF($cpf)) {
                $result = $classe::where('cpf', $cpf)->first();
                if (!$result) {
                    return response()->json([], 201);
                } else {
                    return response()->json([
                        'msg' => 'CPF ja cadastrado em nosso Banco de dados',
                    ], 400);
                }
            } else {
                return response()->json([
                    'msg' => 'CPF Inválido',
                ], 400);
            }
        }

    }

    public static function validaCNPJ($cnpj)
    {
        $cnpj = str_replace(".", "", $cnpj);
        $cnpj = str_replace("/", "", $cnpj);
        $cnpj = str_replace("-", "", $cnpj);
        if (strlen($cnpj) != 14) {
            return false;
        }
        $soma1 = ($cnpj[0] * 5) +
            ($cnpj[1] * 4) +
            ($cnpj[2] * 3) +
            ($cnpj[3] * 2) +
            ($cnpj[4] * 9) +
            ($cnpj[5] * 8) +
            ($cnpj[6] * 7) +
            ($cnpj[7] * 6) +
            ($cnpj[8] * 5) +
            ($cnpj[9] * 4) +
            ($cnpj[10] * 3) +
            ($cnpj[11] * 2);
        $resto = $soma1 % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;

        $soma2 = ($cnpj[0] * 6) +
            ($cnpj[1] * 5) +
            ($cnpj[2] * 4) +
            ($cnpj[3] * 3) +
            ($cnpj[4] * 2) +
            ($cnpj[5] * 9) +
            ($cnpj[6] * 8) +
            ($cnpj[7] * 7) +
            ($cnpj[8] * 6) +
            ($cnpj[9] * 5) +
            ($cnpj[10] * 4) +
            ($cnpj[11] * 3) +
            ($cnpj[12] * 2);
        $resto = $soma2 % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;
        if (($cnpj[12] == $digito1) && ($cnpj[13] == $digito2)) {
            return true;
        } else {
            return response()->json([
                'msg' => 'CNPJ Inválido',
            ], 400);
        }
    }

    // Verifica se CNPJ já existe
    public static function verificaCnpjCadastrado($classe, $cnpj)
    {
        if (!empty($cnpj)) {
            if (self::validaCNPJ($cnpj)) {
                $result = $classe::where('cnpj', $cnpj)->first();
                if (!$result) {
                    return response()->json([], 201);
                } else {
                    return response()->json([
                        'msg' => 'CNPJ ja cadastrado em nosso Banco de dados',
                    ], 400);
                }
            } else {
                return response()->json([
                    'msg' => 'CNPJ Inválido',
                ], 400);
            }
        }
    }

    // Auxiliar de caluclar % de qualquer valor
    public static function pctDe($valor, $pct)
    {
        $resposta = $valor * ($pct / 100);
        if ($resposta < 0.00) {
            return 0.00;
        } else {
            return $resposta;
        }
    }

    public static function horaJs()
    {
        $agora = new DataHora(null);
        $ano = $agora->ano();
        $mes = $agora->mes();
        $dia = $agora->dia();

        $hora = $agora->hora();
        $minuto = $agora->minuto();
        $segundo = $agora->segundo();
        $mes--;

        return "$ano,$mes,$dia,$hora,$minuto,$segundo";
    }

    public static function hoje()
    {
        $agora = new DataHora(null);
        return "São Luís-MA, " . $agora->dia() . " de " . $agora->mesExtM() . " de " . $agora->ano() . " - ";
    }

    public static function DinheiroInsert($dinheiro)
    {
        $valorForma = str_replace('.', '', $dinheiro);
        return $valorForma = str_replace(',', '.', $valorForma);
    }

    public static function DinheiroFormat($dinheiro)
    {
        return number_format($dinheiro, ',', '.');
    }

    public static function convertBase($arquivo)
    {

        $path = storage_path($arquivo);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        echo $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    public static function dataExtensa($data)
    {
        $data = new DataHora($data . ' 00:00:00');
        return $data->dataCompletaExt();
    }

    public static function valorPorExtenso($valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false)
    {
        $valor = removerFormatacaoNumero($valor);

        $singular = null;
        $plural = null;

        if ($bolExibirMoeda) {
            $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        } else {
            $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        }

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");


        if ($bolPalavraFeminina) {

            if ($valor == 1) {
                $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
            } else {
                $u = array("", "um", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
            }
            $c = array("", "cem", "duzentas", "trezentas", "quatrocentas", "quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
        }
        $z = 0;
        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        for ($i = 0; $i < count($inteiro); $i++) {
            for ($ii = mb_strlen($inteiro[$i]); $ii < 3; $ii++) {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }
        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < count($inteiro); $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($inteiro) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000")
                $z++;
            elseif ($z > 0)
                $z--;

            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
                $r .= (($z > 1) ? " de " : "") . $plural[$t];

            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        $rt = mb_substr($rt, 1);

        return ($rt ? trim($rt) : "zero");
    }

    public static function convertFloat($numeroString)
    {
        $numeroString = str_replace('.', '', $numeroString);
        $numeroString = str_replace(',', '.', $numeroString);
        return floatval($numeroString);
    }
}
