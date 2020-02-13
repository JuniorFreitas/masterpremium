<?php

/*
  Desenvolvido por: Master Tag Desenvolvimento Web Ltda
  Autor: Felipe Augusto
  versao: 3.1.0
  ultima atualização: 30/10/2017 18:32:00



  Modos de uso
  new DataHora(); // tempo atual
  new DataHora('1984-09-03'); do banco
  new DataHora('19/03/1984'); pelo usuario
  new DataHora('1984-09-03 10:16:00'); do banco
  new DataHora('19/03/1984 10:16:00'); pelo usuario
  new DataHora('10:16:00'); ou do banco ou pelo usuario
  new DataHora(1509392102); TimeStamp

 */

namespace MasterTag;

use \DateTime;
use \DateInterval;

date_default_timezone_set("America/Fortaleza");

// Recusa o horário de verao de SP (uma hora a mais);


class DataHora
{

    // Propriedades

    private $dia = "dd";
    private $mes = "mm";
    private $ano = "aaaa";
    private $hora = "hh";
    private $minuto = "mm";
    private $segundo = "ss";

    // nomes por extenso minúsculo
    private $mes_extm = array("null", "janeiro", "fevereiro", "março", "abril", "maio", "junho", "julho", "agosto", "setembro", "outubro", "novembro", "dezembro");
    private $dia_extm = array("null", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove", "dez", "onze", "doze", "treze", "quatorze", "quinze", "dezeseis", "dezesete", "dezoito", "dezenove", "vinte", "vinte e um", "vinte e dois", "vinte três", "vinte e quatro", "vinte e cinco", "vinte e seis", "vinte e sete", "vinte e oito", "vinte e nove", "trinta", "trinta e um");
    private $dia_semana_extm = array("null", "segunda-feira", "terça-feira", "quarta-feira", "quinta-feira", "sexta-feira", "sábado", "domingo");
    // nomes por extenso maiúsculo
    private $mes_extM = array("null", "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
    private $dia_extM = array("null", "Um", "Dois", "Três", "Quatro", "Cinco", "Seis", "Sete", "Oito", "Nove", "Dez", "Onze", "Doze", "Treze", "Quatorze", "Quinze", "Dezeseis", "Dezesete", "Dezoito", "Dezenove", "Vinte", "Vinte e um", "Vinte e dois", "Vinte três", "Vinte e quatro", "Vinte e cinco", "Vinte e seis", "Vinte e sete", "Vinte e oito", "Vinte e nove", "Trinta", "Trinta e um");
    private $dia_semana_extM = array("null", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado", "Domingo");

    private $DateTime; // Objeto DateTime

    //CONSTRUTOR
    public function __construct($data_e_hora = NULL)
    {

        if (isset($data_e_hora)) { // se for passado algum parametro...

            // TimeStamp
            if (preg_match("/^\d{1,9999}$/", $data_e_hora)) {

                $date = new DateTime();
                //$date->setTimestamp(1509392102);
                $date->setTimestamp($data_e_hora);

                $this->DateTime = $date;

                $this->dia = $this->DateTime->format('d');
                $this->mes = $this->DateTime->format('m');
                $this->ano = $this->DateTime->format('Y');
                //----------------------------
                $this->hora = $this->DateTime->format('H');
                $this->minuto = $this->DateTime->format('i');
                $this->segundo = $this->DateTime->format('s');
            }

            // se informar por exemplo '1984-03-19 10:58:00'...
            if (preg_match("/^\d{4}-\d{2}-\d{2} \d{2}\:\d{2}\:\d{2}$/", $data_e_hora)) {
                $this->DateTime = new DateTime($data_e_hora);

                $this->dia = $this->DateTime->format('d');
                $this->mes = $this->DateTime->format('m');
                $this->ano = $this->DateTime->format('Y');
                //----------------------------
                $this->hora = $this->DateTime->format('H');
                $this->minuto = $this->DateTime->format('i');
                $this->segundo = $this->DateTime->format('s');
            }

            //  // se for '19/03/1984 10:58:00'
            if (preg_match("/^\d{2}\/\d{2}\/\d{4} \d{2}\:\d{2}\:\d{2}$/", $data_e_hora)) {

                $partes = explode(' ', $data_e_hora); // separa as partes data e hora
                $pt_Data = explode('/', $partes[0]); // divide a data  em dia mes e ano se for '19/03/1984' 10:58:00

                $DIA = $pt_Data[0];
                $MES = $pt_Data[1];
                $ANO = $pt_Data[2];

                $pt_Hora = explode(":", $partes[1]); // separa em hora , minuto e segundo
                $HORA = $pt_Hora[0];
                $MINUTO = $pt_Hora[1];
                $SEGUNDO = $pt_Hora[2];

                $data_e_hora = "$ANO-$MES-$DIA $HORA:$MINUTO:$SEGUNDO";
                $this->DateTime = new DateTime($data_e_hora);

                $this->dia = $this->DateTime->format('d');
                $this->mes = $this->DateTime->format('m');
                $this->ano = $this->DateTime->format('Y');
                //----------------------------
                $this->hora = $this->DateTime->format('H');
                $this->minuto = $this->DateTime->format('i');
                $this->segundo = $this->DateTime->format('s');

            }

            //  // se for '19/03/1984 10:58'
            if (preg_match("/^\d{2}\/\d{2}\/\d{4} \d{2}\:\d{2}$/", $data_e_hora)) {

                $partes = explode(' ', $data_e_hora); // separa as partes data e hora
                $pt_Data = explode('/', $partes[0]); // divide a data  em dia mes e ano se for '19/03/1984' 10:58

                $DIA = $pt_Data[0];
                $MES = $pt_Data[1];
                $ANO = $pt_Data[2];

                $pt_Hora = explode(":", $partes[1]); // separa em hora , minuto
                $HORA = $pt_Hora[0];
                $MINUTO = $pt_Hora[1];
                $SEGUNDO = 00;

                $data_e_hora = "$ANO-$MES-$DIA $HORA:$MINUTO:$SEGUNDO";
                $this->DateTime = new DateTime($data_e_hora);

                $this->dia = $this->DateTime->format('d');
                $this->mes = $this->DateTime->format('m');
                $this->ano = $this->DateTime->format('Y');
                //----------------------------
                $this->hora = $this->DateTime->format('H');
                $this->minuto = $this->DateTime->format('i');
                $this->segundo = $this->DateTime->format('s');

            }

            //-------------------------------------------------APENAS DATA OU HORA------------------------------------------------

            // se for passado 00:00:00
            if (preg_match("/^\d{2}\:\d{2}\:\d{2}$/", $data_e_hora)) {

                $this->DateTime = new DateTime($data_e_hora);

                $this->hora = $this->DateTime->format('H');
                $this->minuto = $this->DateTime->format('i');
                $this->segundo = $this->DateTime->format('s');

            }


            // se for passado 00:00
            if (preg_match("/^\d{2}\:\d{2}$/", $data_e_hora)) {

                $this->DateTime = new DateTime($data_e_hora . ":00"); // completar com segundos

                $this->hora = $this->DateTime->format('H');
                $this->minuto = $this->DateTime->format('i');
                $this->segundo = $this->DateTime->format('s');

            }

            // se for passado 13/03/2011, ou 3/3/2011

            if (preg_match("/^\d{1,2}\/\d{1,2}\/\d{4}$/", $data_e_hora)) {
                $partes = explode('/', $data_e_hora); // separa as partes data e hora
                $DIA = $partes[0];
                $MES = $partes[1];
                $ANO = $partes[2];

                $data_e_hora = "$ANO-$MES-$DIA";
                $this->DateTime = new DateTime($data_e_hora);

                $this->dia = $this->DateTime->format('d');
                $this->mes = $this->DateTime->format('m');
                $this->ano = $this->DateTime->format('Y');

            }

            // se for passado 2011-03-13
            if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $data_e_hora)) {

                $this->DateTime = new DateTime($data_e_hora);

                $this->dia = $this->DateTime->format('d');
                $this->mes = $this->DateTime->format('m');
                $this->ano = $this->DateTime->format('Y');
            }

        } else { // se foi passado NULL...
            $this->atual(); // aplica a data e hora atual
        }
    }// FIM DO CONSTRUTOR


    //METODOS

    // Formatos de data-----------------------------------------------------------
    public function dataInsert()
    {// cadastrar no banco
        return $this->ano() . '-' . $this->mes() . '-' . $this->dia();
    }

    public function horaInsert()
    {// cadastrar no banco
        return $this->hora() . ':' . $this->minuto() . ':' . $this->segundo();
    }

    public function dataHoraInsert()
    {// cadastrar no banco
        return $this->dataInsert() . ' ' . $this->horaInsert();
    }

    public function dataCompleta()
    {
        return $this->dia . "/" . $this->mes . "/" . $this->ano;
    }

    public function dataCompletaExt()
    {
        return $this->dia . " de " . $this->mesExtM() . " de " . $this->ano();
    }

    public function horaCompleta()
    {
        return $this->hora . ":" . $this->minuto . ":" . $this->segundo;
    }

    // Dia----------------------------------------------------------------------------
    public function dia()
    {
        return $this->dia;
    }

    public function setDia($dia)
    {
        $this->DateTime->setDate($this->ano, $this->mes, $dia);

        $this->dia = $this->DateTime->format('d');
        $this->mes = $this->DateTime->format('m');
        $this->ano = $this->DateTime->format('Y');
        // caso seja passado dia, 31 em um mes de fevereiro(28 dias) , passa automaticamente para o proximo mês.
    }

    public function diaExt()
    {// dia por extenso

        return $this->dia_extM[intval($this->dia())];

    }

    public function diaExtM()
    {// dia por extenso

        return $this->dia_extM[intval($this->dia())];
    }

    public function diaSemanaExt()
    {

        return $this->dia_semana_extm[$this->diaSemanaNum()]; // dia da semana de 1 a 7
    }

    public function diaSemanaExtM()
    {
        return $this->dia_semana_extM[$this->diaSemanaNum()]; // dia da semana de 1 a 7
    }

    public function diaSemanaNum()
    { // retorna dia da semana atual em numero, 1 (segunda) e 7 (domingo)
        return $this->DateTime->format("N");
    }

    public function finalDeSemana()
    {

        if ($this->diaSemanaNum() == 6 || $this->diaSemanaNum() == 7) {
            return true;
        } else {
            return false;
        }

    }

    public function primeiroDiaSemanaNum()
    {
        $data = new DateTime($this->dataInsert());
        $data->setDate($this->ano(), $this->mes(), 1);
        return $data->format('N');
    }

    public function primeiroDiaSemanaExt()
    {
        return $this->dia_semana_extm[$this->primeiroDiaSemanaNum()]; // dia da semana de 1 a 7
    }


    public function ultimoDiaMes()
    {
        $data = new DateTime($this->dataInsert());
        return $data->format('t');
    }

    // Mês -------------------------------------------------------------------
    public function mes()
    {
        return $this->mes;
    }

    public function setMes($mes)
    {

        $this->DateTime->setDate($this->ano, $mes, $this->dia);

        $this->dia = $this->DateTime->format('d');
        $this->mes = $this->DateTime->format('m');
        $this->ano = $this->DateTime->format('Y');
    }

    public function mesExt()
    {

        return $this->mes_extm[intval($this->mes())];

    }

    public function mesExtM()
    {

        return $this->mes_extM[intval($this->mes())];
    }

    // Ano-----------------------------------------------------------------
    public function ano()
    {
        return $this->ano;
    }

    public function setAno($ano)
    {

        $this->DateTime->setDate($ano, $this->mes, $this->dia);

        $this->dia = $this->DateTime->format('d');
        $this->mes = $this->DateTime->format('m');
        $this->ano = $this->DateTime->format('Y');

    }

    // Minuto------------------------------------------------
    public function hora()
    {
        return $this->hora;
    }

    public function setHora($hora)
    {
        $hora = intval($hora);
        $this->DateTime->setTime($hora, $this->minuto, $this->segundo);

        $this->hora = $this->DateTime->format('H');
        $this->minuto = $this->DateTime->format('i');
        $this->segundo = $this->DateTime->format('s');

    }

    public function minuto()
    {
        return $this->minuto;
    }

    public function setMinuto($minuto)
    {

        $this->DateTime->setTime($this->hora, $minuto, $this->segundo);

        $this->hora = $this->DateTime->format('H');
        $this->minuto = $this->DateTime->format('i');
        $this->segundo = $this->DateTime->format('s');

    }

    // Segundo ---------------------------------------
    public function segundo()
    {
        return $this->segundo;
    }

    public function setSegundo($segundo)
    {

        $this->DateTime->setTime($this->hora, $this->minuto, $segundo);

        $this->hora = $this->DateTime->format('H');
        $this->minuto = $this->DateTime->format('i');
        $this->segundo = $this->DateTime->format('s');

    }


    // Pula logo para o proximo dia util. Caso nao seja, retorna a data normal
    public function proximoDiaUtil()
    {
        while ($this->finalDeSemana()) {
            $this->addDia(1);
        }
        return $this->dataCompleta();
    }

    // Pula logo para o anterior dia util. Caso nao seja, retorna a data normal
    public function anteriorDiaUtil()
    {
        while ($this->finalDeSemana()) {
            $this->subtrairDia(1);
        }
        return $this->dataCompleta();
    }

    /*
    Retorna uma data dd/mm/aaaa  do proximo dia informado
    $IGNORAR_IGUAL=TRUE, ignora se o dia já é o informado
    $FEVEREIRO = TRUE, tentar compensar o 30 de fevereiro por exemplo para dia 02/03
    */
    public function proximoDia($dia, $NAO_ACEITA_IGUAL = TRUE, $FEVEREIRO = TRUE)
    {
        $hoje = new DataHora($this->dataCompleta());
        if ($NAO_ACEITA_IGUAL === FALSE && $dia == $hoje->dia()) {
            $this->dia = $hoje->dia();
            $this->mes = $hoje->mes();
            $this->ano = $hoje->ano();
            return true;
        }

        if ($NAO_ACEITA_IGUAL == TRUE && $dia == $hoje->dia()) {
            $hoje->addDia(1);
        }

        if ($FEVEREIRO && $dia > 28) {

            if ($hoje->mes() == 1 && $hoje->dia() > $dia) { // se for o mes seguinte

                if (!checkdate(02, $dia, $hoje->ano())) {
                    $teste = new DataHora("$dia/02/{$hoje->ano()}");
                    $this->dia = $teste->dia();
                    $this->mes = $teste->mes();
                    $this->ano = $teste->ano();
                    return true;
                }

            }


            if ($hoje->mes() == 2) {
                if (!checkdate(02, $dia, $hoje->ano())) { // se nao existe dia 29,30,31 de fevereiro
                    $teste = new DataHora("$dia/02/{$hoje->ano()}");
                    $this->dia = $teste->dia();
                    $this->mes = $teste->mes();
                    $this->ano = $teste->ano();
                    return true;
                }
            }


        }


        while ($hoje->dia() != $dia) { // e quando for fevereiro (dia 30)?
            $hoje->addDia(1);
        }

        $this->dia = $hoje->dia();
        $this->mes = $hoje->mes();
        $this->ano = $hoje->ano();
        // Não usei setDia,setMes,setAno, pois puderia mudar o mês automaticamente


    }

    // Dia anterior mais proximo, com o dia informado
    public function anteriorDia($dia, $NAO_ACEITA_IGUAL = FALSE)
    {
        $hoje = new DataHora($this->dataCompleta());

        if ($NAO_ACEITA_IGUAL === FALSE && $dia == $hoje->dia()) {
            $this->dia = $hoje->dia();
            $this->mes = $hoje->mes();
            $this->ano = $hoje->ano();
            return true;
        }
        while ($hoje->dia() != $dia) {
            $hoje->subtrairDia(1);
        }

        $this->dia = $hoje->dia();
        $this->mes = $hoje->mes();
        $this->ano = $hoje->ano();
        // Não usei setDia,setMes,setAno, pois puderia mudar o mês automaticamente
    }

    public function nomeUnico()
    {// auxilia em nomes de arquivos
        $this->atual();
        return $this->dia() . $this->mes() . $this->ano() . $this->hora() . $this->minuto() . $this->segundo();
    }


    // Manipulação de tempo
    public function addDia($dias, $atualizaObjetoAtual = true)
    { // adiciona quantos dias vc quiser passando quantidade de dias.

        $dias = intval($dias);

        if ($atualizaObjetoAtual) {
            $this->DateTime->add(new DateInterval("P" . $dias . "D"));
            $this->dia = $this->DateTime->format('d');
            $this->mes = $this->DateTime->format('m');
            $this->ano = $this->DateTime->format('Y');
            return $this->dataCompleta();
        } else {
            $data = new DateTime($this->dataInsert());
            $data->add(new DateInterval("P" . $dias . "D"));
            return $data->format('d/m/Y');
        }


    }

    public function subtrairDia($dias)
    { // subtrai quantos dias vc quiser passando quantidade de dias.
        $dias = intval($dias);

        $this->DateTime->sub(new DateInterval("P" . $dias . "D"));
        $this->dia = $this->DateTime->format('d');
        $this->mes = $this->DateTime->format('m');
        $this->ano = $this->DateTime->format('Y');
        return $this->dataCompleta();
    }

    public function addMes($meses)
    {
        $meses = intval($meses);

        $this->DateTime->add(new DateInterval("P" . $meses . "M"));
        $this->dia = $this->DateTime->format('d');
        $this->mes = $this->DateTime->format('m');
        $this->ano = $this->DateTime->format('Y');
        return $this->dataCompleta();
    }

    public function subtrairMes($meses)
    {
        $meses = intval($meses);

        $this->DateTime->sub(new DateInterval("P" . $meses . "M"));
        $this->dia = $this->DateTime->format('d');
        $this->mes = $this->DateTime->format('m');
        $this->ano = $this->DateTime->format('Y');
        return $this->dataCompleta();
    }

    public function addAno($anos)
    {
        $anos = intval($anos);

        $this->DateTime->add(new DateInterval("P" . $anos . "Y"));
        $this->dia = $this->DateTime->format('d');
        $this->mes = $this->DateTime->format('m');
        $this->ano = $this->DateTime->format('Y');
        return $this->dataCompleta();
    }

    public function subtrairAno($anos)
    {
        $anos = intval($anos);

        $this->DateTime->sub(new DateInterval("P" . $anos . "Y"));
        $this->dia = $this->DateTime->format('d');
        $this->mes = $this->DateTime->format('m');
        $this->ano = $this->DateTime->format('Y');
        return $this->dataCompleta();
    }

    public function addSegundo($segundos)
    {
        $segundos = intval($segundos);

        $this->DateTime->add(new DateInterval("PT" . $segundos . "S"));

        // pode ter mudado a data
        $this->dia = $this->DateTime->format('d');
        $this->mes = $this->DateTime->format('m');
        $this->ano = $this->DateTime->format('Y');

        $this->hora = $this->DateTime->format('H');
        $this->minuto = $this->DateTime->format('i');
        $this->segundo = $this->DateTime->format('s');
        return $this->horaCompleta();
    }

    public function subtrairSegundo($segundos)
    {
        $segundos = intval($segundos);

        $this->DateTime->sub(new DateInterval("PT" . $segundos . "S"));

        // pode ter mudado a data
        $this->dia = $this->DateTime->format('d');
        $this->mes = $this->DateTime->format('m');
        $this->ano = $this->DateTime->format('Y');

        $this->hora = $this->DateTime->format('H');
        $this->minuto = $this->DateTime->format('i');
        $this->segundo = $this->DateTime->format('s');
        return $this->horaCompleta();
    }

    public function addMinuto($minutos)
    {
        $minutos = intval($minutos);

        $this->DateTime->add(new DateInterval("PT" . $minutos . "M"));

        // pode ter mudado a data
        $this->dia = $this->DateTime->format('d');
        $this->mes = $this->DateTime->format('m');
        $this->ano = $this->DateTime->format('Y');

        $this->hora = $this->DateTime->format('H');
        $this->minuto = $this->DateTime->format('i');
        $this->segundo = $this->DateTime->format('s');
        return $this->horaCompleta();
    }

    public function subtrairMinuto($minutos)
    {
        $minutos = intval($minutos);

        $this->DateTime->sub(new DateInterval("PT" . $minutos . "M"));

        // pode ter mudado a data
        $this->dia = $this->DateTime->format('d');
        $this->mes = $this->DateTime->format('m');
        $this->ano = $this->DateTime->format('Y');

        $this->hora = $this->DateTime->format('H');
        $this->minuto = $this->DateTime->format('i');
        $this->segundo = $this->DateTime->format('s');
        return $this->horaCompleta();
    }

    public function addHora($horas)
    {
        $horas = intval($horas);

        $this->DateTime->add(new DateInterval("PT" . $horas . "H"));
        // pode ter mudado a data
        $this->dia = $this->DateTime->format('d');
        $this->mes = $this->DateTime->format('m');
        $this->ano = $this->DateTime->format('Y');

        $this->hora = $this->DateTime->format('H');
        $this->minuto = $this->DateTime->format('i');
        $this->segundo = $this->DateTime->format('s');
        return $this->horaCompleta();
    }

    public function subtrairHora($horas)
    {
        $horas = intval($horas);

        $this->DateTime->add(new DateInterval("PT" . $horas . "H"));

        // pode ter mudado a data
        $this->dia = $this->DateTime->format('d');
        $this->mes = $this->DateTime->format('m');
        $this->ano = $this->DateTime->format('Y');

        $this->hora = $this->DateTime->format('H');
        $this->minuto = $this->DateTime->format('i');
        $this->segundo = $this->DateTime->format('s');
        return $this->horaCompleta();
    }

    // Distancia de intervalos ##############################################################

    // retorna true se a Data do obj está dentro do intervalo dos parametros $inicio e $fim (dd/mm/aaaa)
    public function intervaloDias($dataCompletaInicio, $dataCompletaFim)
    {

        $hoje = new DateTime($this->dataInsert());

        $inicio = new DataHora($dataCompletaInicio);
        $inicio = new DateTime($inicio->dataInsert());

        $fim = new DataHora($dataCompletaFim);
        $fim = new DateTime($fim->dataInsert());

        if ($hoje < $inicio || $hoje > $fim) {
            return false;
        } else {
            return true;
        }
    }

    // retorna true se a hora do objeto (data e hora completa) está dentro do intervalo dos parametros $inicio e $fim
    public function intervaloTempo($inicio, $fim)
    {
        $hoje = new DateTime($this->dataHoraInsert());

        $inicio = new DataHora($inicio);
        $inicio = new DateTime($inicio->dataHoraInsert());

        $fim = new DataHora($fim);
        $fim = new DateTime($fim->dataHoraInsert());

        if ($hoje < $inicio || $hoje > $fim) {
            return false;
        } else {
            return true;
        }
    }

    // retorna true se a hora do objeto (hh:mm:ss) está dentro do intervalo dos parametros $inicio e $fim
    public function intervaloHoras($inicio, $fim)
    {
        $hoje = new DateTime($this->horaInsert());

        $inicio = new DataHora($inicio);
        $inicio = new DateTime($inicio->horaInsert());

        $fim = new DataHora($fim);
        $fim = new DateTime($fim->horaInsert());

        if ($hoje < $inicio || $hoje > $fim) {
            return false;
        } else {
            return true;
        }
    }

    // quantidade de minutos
    public static function diferencaMinutos($hora_menor, $hora_maior)
    {
        $VALORES = array();

        $hora_menor = new DataHora($hora_menor);
        $hora_maior = new DataHora($hora_maior);

        $menor = new DateTime($hora_menor->horaInsert());
        $maior = new DateTime($hora_maior->horaInsert());


        $dados_difereca = $menor->diff($maior, FALSE);
        // 1- data invertida, 0 - normal. Porem eu uso sempre a dataMaior primeiro entao é ao contrario
        $invertido = $dados_difereca->invert;
        $VALORES['invert'] = $dados_difereca->invert;

        //Hora
        if ($invertido) {
            $VALORES['hora'] = $dados_difereca->h * -1;
        } else {
            $VALORES['hora'] = $dados_difereca->h;
        }

        //Minuto
        if ($invertido) {
            $VALORES['minuto'] = $dados_difereca->i * -1;
        } else {
            $VALORES['minuto'] = $dados_difereca->i;
        }

        return ($VALORES['hora'] * 60) + ($VALORES['minuto']); // apenas em minutos
    }

    public static function diferencaAnos($dataMenor, $dataMaior)
    {
        $VALORES = array();

        $dataMenor = new DataHora($dataMenor);
        $dataMaior = new DataHora($dataMaior);


        $menor = new DateTime($dataMenor->dataInsert());
        $maior = new DateTime($dataMaior->dataInsert());


        $dados_difereca = $menor->diff($maior, FALSE);
        // 1- data invertida, 0 - normal. Porem eu uso sempre a dataMaior primeiro entao é ao contrario
        $invertido = $dados_difereca->invert;
        $VALORES['invert'] = $dados_difereca->invert;

        // Ano
        if ($invertido) {
            $VALORES['ano'] = $dados_difereca->y * -1;
        } else {
            $VALORES['ano'] = $dados_difereca->y;
        }

        return $VALORES['ano'];
    }

    public static function diferencaDias($dataMenor, $dataMaior)
    {
        $VALORES = array();

        $dataMenor = new DataHora($dataMenor);
        $dataMaior = new DataHora($dataMaior);

        $menor = new DateTime($dataMenor->dataInsert());
        $maior = new DateTime($dataMaior->dataInsert());

        $dados_difereca = $menor->diff($maior, FALSE);
        // 1- data invertida, 0 - normal. Porem eu uso sempre a dataMaior primeiro entao é ao contrario
        $invertido = $dados_difereca->invert;
        $VALORES['invert'] = $dados_difereca->invert;

        //Total dias
        if ($invertido) {
            $VALORES['totalDias'] = $dados_difereca->days * -1;
        } else {
            $VALORES['totalDias'] = $dados_difereca->days;
        }


        return $VALORES['totalDias'];

    }

    // Mes e ano de referencia do mes passado
    public function mesAnoReferencia()
    {
        //echo "Aqui:".$this->dataCompleta()."<br>";
        $nova = new DataHora($this->dataCompleta());
        $nova->setDia(01); // joga pra incio de mes
        $nova->subtrairMes(1); // volta para o mes anterior
        //echo "Nova:".$nova->dataCompleta()."<br>";
        return $nova->mes() . "/" . $nova->ano();
    }

    public function atual()
    { // aplica data e hora atual

        $this->DateTime = new DateTime();

        $this->dia = $this->DateTime->format('d');
        $this->mes = $this->DateTime->format('m');
        $this->ano = $this->DateTime->format('Y');

        $this->hora = $this->DateTime->format('H');
        $this->minuto = $this->DateTime->format('i');
        $this->segundo = $this->DateTime->format('s');
    }

    //----------------------------------------METODOS ESTATICOS-------------------------------------------------------

    /*
        Retorna um array nominal com detalhes sobre a distancia do tempo (dd/mm/aaaa hh:mm:ss)
        $VALORES['dia'],'mes','ano','hora','minuto','segundo','totalDias'
    */
    public static function distanciaTempo($tempoCompletoMenor, $tempoCompletoMaior)
    {
        $VALORES = array();
        $tempoCompletoMenor = new DataHora($tempoCompletoMenor);
        $tempoCompletoMaior = new DataHora($tempoCompletoMaior);


        $menor = new DateTime($tempoCompletoMenor->dataHoraInsert());
        $maior = new DateTime($tempoCompletoMaior->dataHoraInsert());


        $dados_difereca = $menor->diff($maior, FALSE);
        // 1- data invertida, 0 - normal. Porem eu uso sempre a dataMaior primeiro entao é ao contrario
        $invertido = $dados_difereca->invert;
        $VALORES['invert'] = $dados_difereca->invert;

        // Ano
        if ($invertido) {
            $VALORES['ano'] = $dados_difereca->y * -1;
        } else {
            $VALORES['ano'] = $dados_difereca->y;
        }

        // Mes
        if ($invertido) {
            $VALORES['mes'] = $dados_difereca->m * -1;
        } else {
            $VALORES['mes'] = $dados_difereca->m;
        }

        //Dia
        if ($invertido) {
            $VALORES['dia'] = $dados_difereca->d * -1;
        } else {
            $VALORES['dia'] = $dados_difereca->d;
        }

        //Hora
        if ($invertido) {
            $VALORES['hora'] = $dados_difereca->h * -1;
        } else {
            $VALORES['hora'] = $dados_difereca->h;
        }

        //Minuto
        if ($invertido) {
            $VALORES['minuto'] = $dados_difereca->i * -1;
        } else {
            $VALORES['minuto'] = $dados_difereca->i;
        }

        //Segundo
        if ($invertido) {
            $VALORES['segundo'] = $dados_difereca->s * -1;
        } else {
            $VALORES['segundo'] = $dados_difereca->s;
        }


        //Total dias
        if ($invertido) {
            $VALORES['totalDias'] = $dados_difereca->days * -1;
        } else {
            $VALORES['totalDias'] = $dados_difereca->days;
        }


        return $VALORES;

    }

    public static function formatarNumero($numero)
    {
        if ($numero < 10) {
            if (strpos($numero, "0") === false) {
                $numero = "0" . $numero;
            }
        }
        return $numero;
    }

    // Se informar uma data invalida como 31/02/2013, o metodo faz a correcao, e traz 28/02/2013 (como sendo o ultimo dia daquele mes)
    public static function dataValida($data_string)
    {

        // se for passado 13/03/2011
        if (preg_match("/^\d{2}\/\d{2}\/\d{4}$/", $data_string)) {

            $partes = explode('/', $data_string); // separa as partes data e hora
            $DIA = $partes[0];
            $MES = $partes[1];
            $ANO = $partes[2];

            // Saber quantos mes, e anos , vao ficar, para uma data invalida como 01/13/2013 por exemplo
            $data = new DataHora("01/" . $MES . "/" . $ANO);

            $novo_MES = $data->mes();
            $novo_ANO = $data->ano();
            //return $data->dataCompleta();

            if (checkdate($novo_MES, $DIA, $novo_ANO)) { // depois de calcular meses e anos, essa data existir, retorna
                $data = new DataHora($DIA . "/" . $novo_MES . "/" . $novo_ANO);
                return $data->dataCompleta();
            } else { // senao, achar a ultima data , retirando a sobra de dias.
                $data = new DataHora($DIA . "/" . $novo_MES . "/" . $novo_ANO);
                //echo "Ficou a data depois: ".$data->dataCompleta()."<br>";
                $dataMesPassado = new DataHora($data->dataCompleta());
                $dataMesPassado->subtrairDia(intval($data->dia()));
                return $dataMesPassado->dataCompleta();
            }
        }

        // se for passado 2011-03-13
        if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $data_string)) {

            $partes = explode('-', $data_string); // separa as partes data e hora
            $DIA = $partes[2];
            $MES = $partes[1];
            $ANO = $partes[0];

            // Saber quantos mes, e anos , vao ficar, para uma data invalida como 01/13/2013 por exemplo
            $data = new DataHora("01/" . $MES . "/" . $ANO);

            $novo_MES = $data->mes();
            $novo_ANO = $data->ano();
            //return $data->dataCompleta();

            if (checkdate($novo_MES, $DIA, $novo_ANO)) { // depois de calcular meses e anos, essa data existir, retorna
                $data = new DataHora($DIA . "/" . $novo_MES . "/" . $novo_ANO);
                return $data->dataCompleta();
            } else { // senao, achar a ultima data , retirando a sobra de dias.
                $data = new DataHora($DIA . "/" . $novo_MES . "/" . $novo_ANO);
                //echo "Ficou a data depois: ".$data->dataCompleta()."<br>";
                $dataMesPassado = new DataHora($data->dataCompleta());
                $dataMesPassado->subtrairDia(intval($data->dia()));
                return $dataMesPassado->dataCompleta();
            }

        }

    }


}

// FIM DA CLASSE

/*$contrato=26;
$diaVencimento=25;
$vencimetoParcela= "26/09/2013";
$hoje= new DataHora("27/08/2013");


$antigaDataVencimento = new DataHora($vencimetoParcela); // 20/09/2013
$antigaDataInicio = new DataHora($antigaDataVencimento->dataCompleta());
$antigaDataInicio->subtrairMes(1); // 20/08/2013

if($diaVencimento > $contrato){
	//echo "Para frente<br>";
	$novaDataInicioParcela = new DataHora($antigaDataInicio->proximoDia($diaVencimento));
	$novaDataVencimento = new DataHora($antigaDataVencimento->proximoDia($diaVencimento));
	echo "VAI JOGAR PARA FRENTE , o incio e o fim do contrato<br>";

	echo "Novo inicio: ".$novaDataInicioParcela->dataCompleta()."<br>";
	echo "Novo vencimento em: ".$novaDataVencimento->dataCompleta()."<br>";

}

if($diaVencimento < $contrato){


	$novoVencimentoTeste = new DataHora($vencimetoParcela);
	$novoVencimentoTeste->anteriorDia($diaVencimento);
	$diferencaDias = DataHora::diferencaDias($hoje->dataCompleta(),$novoVencimentoTeste->dataCompleta());

	echo "Diferença de {$hoje->dataCompleta()} para {$novoVencimentoTeste->dataCompleta()} é: ".$diferencaDias."<br>";

	if( $diferencaDias < 0 ){ // se ficar atras do dia de hoje (vencida)
		//echo "Diferença dias ($diferencaDias) >= a metada dos dias($metadeDosDias)<br>";
		$novaDataInicioParcela = new DataHora($antigaDataInicio->proximoDia($diaVencimento));
		$novaDataVencimento = new DataHora($antigaDataVencimento->proximoDia($diaVencimento));
		echo "---VAI JOGAR PARA FRENTE , o incio e o fim do contrato<br>";
	}

	if( $diferencaDias >= 0 ){ // se ficar hoje, ou mais para frente, da tempo de chamar para mais perto
		//echo "Diferença dias ($diferencaDias) < a metada dos dias($metadeDosDias)<br>";
		// Se caso eu nunca comecei essa parcela, chamar para mais perto
		$novaDataInicioParcela = new DataHora($antigaDataInicio->anteriorDia($diaVencimento));
		$novaDataVencimento = new DataHora($antigaDataVencimento->anteriorDia($diaVencimento));
		echo "---Vai jogar para TRAS, o incio e o fim do contrato<br>";
	}


	echo "Novo inicio: ".$novaDataInicioParcela->dataCompleta()."<br>";
	echo "Novo vencimento em: ".$novaDataVencimento->dataCompleta()."<br>";

}
*/
//$data= new DataHora("30/09/2013");
//$data->anteriorDia(5);
//echo $data->dataCompleta();
