<?php
#Remove Acentos

use App\Models\FormaPagamento;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function tirarAcentos($string){
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/"),explode(" ","a A e E i I o O u U n N c C"),$string);
}

function getIdEmpresa()
{
    return session()->get('id_empresa');
}

//função que verifica se existem letras na data informada, se não, modifica para o formato compatível com timestamp do mysql.
function verifyDateFormat($expected_date)
{
    $regex_date = "/[a-zA-Z]/";
    preg_match($regex_date, $expected_date, $matches);

    if( count($matches) > 0 ){
        $response = [
            "message" => "campo de data, não coloque letras, formato correto aaaa-mm-dd ou dd/mm/aaaa"
        ];
        return response()->json($response, 406);
    }

    $parse = str_replace('/', '-', $expected_date);
    $formated_date = \Carbon\Carbon::parse($parse)->format('Y-m-d');

    return $formated_date;
}

function formatValue($expected_value)
{
    $value = str_replace('.', '', $expected_value);
    $formated_value = str_replace(',', '.', $value);

    return  $formated_value;
}

function validateImputNumbers($expected_value)
{
    if( $expected_value == null ){
        return true;
    }else{
        $regex_number = "/[0-9]/";
        preg_match($regex_number, $expected_value, $matches);

        if( count($matches) > 0 ){
            return true;
        }else{
            return false;
        }
    }
}

function calculaJurosSimplesOuComposto($id_formapagto, $VrGeralContaRec, $vr_juro, $vr_entrada = 0)
{
    // iDia = Juros % / 100 e é dividido por 30 para achar a porcentagem ao dia
    //  n = nr. de prestaçoes
    // PV = Valor Presente ou Valor total da venda
    // ValorDivParc = PV/n valor presente dividido pelo nr de parcelas

    $formaPagto         = FormaPagamento::find($id_formapagto);

    if( !$formaPagto ){
        return redirect()->back()->with([
            'titulo'    => 'Falhou!!!',
            'tipo'      => "error",
            'message'   => "Não existe esta forma de pagamento"
        ]);
    }

    if( $vr_juro != null && $vr_juro > floatval(0) ){
        $vr_juro = $vr_juro;
    }else{
        $vr_juro = $formaPagto->vr_juros;
    }

    if( $vr_entrada > 0 || $vr_entrada != null ){
        $vr_entrada = formatValue($vr_entrada);
    }else{
        $vr_entrada = 0;
    }

    $iDia = $PV = $ValorDivParc = $vr_parcela = 0;
    $n  = $formaPagto->nr_parcelas;

    if( $formaPagto->tp_pagto == "V" ){
        $vr_parcela = formatValue($VrGeralContaRec);
        $vr_entrada = formatValue($VrGeralContaRec);
        $vr_juro    = "";
    }else{
        if( $formaPagto->ds_entrada == "N" && $formaPagto->tp_calculo == "S" ){
            $iDia           = ( $vr_juro / 30 ) / 100;
            $PV             = formatValue($VrGeralContaRec);
            $ValorDivParc   = ( $PV / $n );

            $vr_dia1 = ( $ValorDivParc * ( $formaPagto->vr_dia1 * $iDia ) );
            $vr_dia2 = ( $ValorDivParc * ( $formaPagto->vr_dia2 * $iDia ) );
            $vr_dia3 = ( $ValorDivParc * ( $formaPagto->vr_dia3 * $iDia ) );
            $vr_dia4 = ( $ValorDivParc * ( $formaPagto->vr_dia4 * $iDia ) );
            $vr_dia5 = ( $ValorDivParc * ( $formaPagto->vr_dia5 * $iDia ) );
            $vr_dia6 = ( $ValorDivParc * ( $formaPagto->vr_dia6 * $iDia ) );
            $vr_dia7 = ( $ValorDivParc * ( $formaPagto->vr_dia7 * $iDia ) );
            $vr_dia8 = ( $ValorDivParc * ( $formaPagto->vr_dia8 * $iDia ) );
            $vr_dia9 = ( $ValorDivParc * ( $formaPagto->vr_dia9 * $iDia ) );
            $vr_dia10 = ( $ValorDivParc * ( $formaPagto->vr_dia10 * $iDia ) );
            $vr_dia11 = ( $ValorDivParc * ( $formaPagto->vr_dia11 * $iDia ) );
            $vr_dia12 = ( $ValorDivParc * ( $formaPagto->vr_dia12 * $iDia ) );
            $vr_dia13 = ( $ValorDivParc * ( $formaPagto->vr_dia13 * $iDia ) );
            $vr_dia14 = ( $ValorDivParc * ( $formaPagto->vr_dia14 * $iDia ) );
            $vr_dia15 = ( $ValorDivParc * ( $formaPagto->vr_dia15 * $iDia ) );
            $vr_dia16 = ( $ValorDivParc * ( $formaPagto->vr_dia16 * $iDia ) );
            $vr_dia17 = ( $ValorDivParc * ( $formaPagto->vr_dia17 * $iDia ) );
            $vr_dia18 = ( $ValorDivParc * ( $formaPagto->vr_dia18 * $iDia ) );
            $vr_dia19 = ( $ValorDivParc * ( $formaPagto->vr_dia19 * $iDia ) );
            $vr_dia20 = ( $ValorDivParc * ( $formaPagto->vr_dia20 * $iDia ) );
            $vr_dia21 = ( $ValorDivParc * ( $formaPagto->vr_dia21 * $iDia ) );
            $vr_dia22 = ( $ValorDivParc * ( $formaPagto->vr_dia22 * $iDia ) );
            $vr_dia23 = ( $ValorDivParc * ( $formaPagto->vr_dia23 * $iDia ) );
            $vr_dia24 = ( $ValorDivParc * ( $formaPagto->vr_dia24 * $iDia ) );
            $vr_dia25 = ( $ValorDivParc * ( $formaPagto->vr_dia25 * $iDia ) );
            $vr_dia26 = ( $ValorDivParc * ( $formaPagto->vr_dia26 * $iDia ) );
            $vr_dia27 = ( $ValorDivParc * ( $formaPagto->vr_dia27 * $iDia ) );
            $vr_dia28 = ( $ValorDivParc * ( $formaPagto->vr_dia28 * $iDia ) );
            $vr_dia29 = ( $ValorDivParc * ( $formaPagto->vr_dia29 * $iDia ) );
            $vr_dia30 = ( $ValorDivParc * ( $formaPagto->vr_dia30 * $iDia ) );
            $vr_dia31 = ( $ValorDivParc * ( $formaPagto->vr_dia31 * $iDia ) );
            $vr_dia32 = ( $ValorDivParc * ( $formaPagto->vr_dia32 * $iDia ) );
            $vr_dia33 = ( $ValorDivParc * ( $formaPagto->vr_dia33 * $iDia ) );
            $vr_dia34 = ( $ValorDivParc * ( $formaPagto->vr_dia34 * $iDia ) );
            $vr_dia35 = ( $ValorDivParc * ( $formaPagto->vr_dia35 * $iDia ) );
            $vr_dia36 = ( $ValorDivParc * ( $formaPagto->vr_dia36 * $iDia ) );

            $vr_parcela = ( ( $PV + $vr_dia1 + $vr_dia2 + $vr_dia3 + $vr_dia4 + $vr_dia5 + $vr_dia6 + $vr_dia7 + $vr_dia8 + $vr_dia9 + $vr_dia10
                        + $vr_dia11 + $vr_dia12 + $vr_dia13 + $vr_dia14 + $vr_dia15 + $vr_dia16 + $vr_dia17 + $vr_dia18 + $vr_dia19 + $vr_dia20
                        + $vr_dia21 + $vr_dia22 + $vr_dia23 + $vr_dia24 + $vr_dia25 + $vr_dia26 + $vr_dia27 + $vr_dia28 + $vr_dia29 + $vr_dia30
                        + $vr_dia31 + $vr_dia32 + $vr_dia33 + $vr_dia34 + $vr_dia35 + $vr_dia36 ) / $n );
            $vr_entrada = 0;
        }elseif( $formaPagto->ds_entrada == "S" && $formaPagto->tp_calculo == "S" ){
            $iDia           = ( $vr_juro / 30 ) / 100;
            $n              = ($formaPagto->nr_parcelas - 1);
            $PV             = (formatValue($VrGeralContaRec) - $vr_entrada);
            $ValorDivParc   = ( $PV / $n );

            $vr_dia1 = ( $ValorDivParc * ( $formaPagto->vr_dia1 * $iDia ) );
            $vr_dia2 = ( $ValorDivParc * ( $formaPagto->vr_dia2 * $iDia ) );
            $vr_dia3 = ( $ValorDivParc * ( $formaPagto->vr_dia3 * $iDia ) );
            $vr_dia4 = ( $ValorDivParc * ( $formaPagto->vr_dia4 * $iDia ) );
            $vr_dia5 = ( $ValorDivParc * ( $formaPagto->vr_dia5 * $iDia ) );
            $vr_dia6 = ( $ValorDivParc * ( $formaPagto->vr_dia6 * $iDia ) );
            $vr_dia7 = ( $ValorDivParc * ( $formaPagto->vr_dia7 * $iDia ) );
            $vr_dia8 = ( $ValorDivParc * ( $formaPagto->vr_dia8 * $iDia ) );
            $vr_dia9 = ( $ValorDivParc * ( $formaPagto->vr_dia9 * $iDia ) );
            $vr_dia10 = ( $ValorDivParc * ( $formaPagto->vr_dia10 * $iDia ) );
            $vr_dia11 = ( $ValorDivParc * ( $formaPagto->vr_dia11 * $iDia ) );
            $vr_dia12 = ( $ValorDivParc * ( $formaPagto->vr_dia12 * $iDia ) );
            $vr_dia13 = ( $ValorDivParc * ( $formaPagto->vr_dia13 * $iDia ) );
            $vr_dia14 = ( $ValorDivParc * ( $formaPagto->vr_dia14 * $iDia ) );
            $vr_dia15 = ( $ValorDivParc * ( $formaPagto->vr_dia15 * $iDia ) );
            $vr_dia16 = ( $ValorDivParc * ( $formaPagto->vr_dia16 * $iDia ) );
            $vr_dia17 = ( $ValorDivParc * ( $formaPagto->vr_dia17 * $iDia ) );
            $vr_dia18 = ( $ValorDivParc * ( $formaPagto->vr_dia18 * $iDia ) );
            $vr_dia19 = ( $ValorDivParc * ( $formaPagto->vr_dia19 * $iDia ) );
            $vr_dia20 = ( $ValorDivParc * ( $formaPagto->vr_dia20 * $iDia ) );
            $vr_dia21 = ( $ValorDivParc * ( $formaPagto->vr_dia21 * $iDia ) );
            $vr_dia22 = ( $ValorDivParc * ( $formaPagto->vr_dia22 * $iDia ) );
            $vr_dia23 = ( $ValorDivParc * ( $formaPagto->vr_dia23 * $iDia ) );
            $vr_dia24 = ( $ValorDivParc * ( $formaPagto->vr_dia24 * $iDia ) );
            $vr_dia25 = ( $ValorDivParc * ( $formaPagto->vr_dia25 * $iDia ) );
            $vr_dia26 = ( $ValorDivParc * ( $formaPagto->vr_dia26 * $iDia ) );
            $vr_dia27 = ( $ValorDivParc * ( $formaPagto->vr_dia27 * $iDia ) );
            $vr_dia28 = ( $ValorDivParc * ( $formaPagto->vr_dia28 * $iDia ) );
            $vr_dia29 = ( $ValorDivParc * ( $formaPagto->vr_dia29 * $iDia ) );
            $vr_dia30 = ( $ValorDivParc * ( $formaPagto->vr_dia30 * $iDia ) );
            $vr_dia31 = ( $ValorDivParc * ( $formaPagto->vr_dia31 * $iDia ) );
            $vr_dia32 = ( $ValorDivParc * ( $formaPagto->vr_dia32 * $iDia ) );
            $vr_dia33 = ( $ValorDivParc * ( $formaPagto->vr_dia33 * $iDia ) );
            $vr_dia34 = ( $ValorDivParc * ( $formaPagto->vr_dia34 * $iDia ) );
            $vr_dia35 = ( $ValorDivParc * ( $formaPagto->vr_dia35 * $iDia ) );
            $vr_dia36 = ( $ValorDivParc * ( $formaPagto->vr_dia36 * $iDia ) );

            $vr_parcela = ( ( $PV + $vr_dia1 + $vr_dia2 + $vr_dia3 + $vr_dia4 + $vr_dia5 + $vr_dia6 + $vr_dia7 + $vr_dia8 + $vr_dia9 + $vr_dia10
                        + $vr_dia11 + $vr_dia12 + $vr_dia13 + $vr_dia14 + $vr_dia15 + $vr_dia16 + $vr_dia17 + $vr_dia18 + $vr_dia19 + $vr_dia20
                        + $vr_dia21 + $vr_dia22 + $vr_dia23 + $vr_dia24 + $vr_dia25 + $vr_dia26 + $vr_dia27 + $vr_dia28 + $vr_dia29 + $vr_dia30
                        + $vr_dia31 + $vr_dia32 + $vr_dia33 + $vr_dia34 + $vr_dia35 + $vr_dia36 ) / $n );

            if( formatValue($VrGeralContaRec) > 0 ){
                $vr_entrada = formatValue($vr_entrada);
            }else{
                $vr_entrada = 0;
            }

            $n = $formaPagto->nr_parcelas;
        }elseif( $formaPagto->ds_entrada == "N" && $formaPagto->tp_calculo == "N" ){
            $vr_parcela = (formatValue($VrGeralContaRec) / $n);
            $vr_entrada = 0;
        }elseif( $formaPagto->ds_entrada == "S" && $formaPagto->tp_calculo == "N" ){

            if( $formaPagto->ds_entrada == "N" ){
                $vr_parcela = (formatValue($VrGeralContaRec) / $n);
                $vr_entrada = (formatValue($VrGeralContaRec) / $n);
            }else{
                if( $n > 1 ){
                    if( $vr_entrada == null || $vr_entrada == 0 ){
                        $vr_parcela = (formatValue($VrGeralContaRec) / $n);
                        $vr_entrada = (formatValue($VrGeralContaRec) / $n);
                    }else{
                        $vr_parcela = ((formatValue($VrGeralContaRec) - $vr_entrada) / ($n - 1));
                    }
                }else{
                    $vr_parcela = formatValue($VrGeralContaRec);
                }

                $vr_entrada = $vr_entrada;
            }
        }elseif( $formaPagto->ds_entrada == "N" && $formaPagto->tp_calculo == "C" ){
            // Cáculo de parcelas com juros compostos
            // pmt = Valor da Parcela
            // pv = Valor Presente ou Valor total da venda
            // i  = Juros % / 100
            // n = nr. de prestaçoes
            // ^ = elevado a
            // pmt = (pv * i * (1 + i)^n)/ ((1+ i)^n -1)

            $i           = ( $vr_juro/100 );
            $pv          = formatValue($VrGeralContaRec);
            $exponencial = pow( (1 + $i), $n);
            $pmt         = (($pv * $i) * $exponencial) / ($exponencial - 1);

            $vr_parcela  = $pmt;
            $vr_entrada  = 0;
        }elseif( $formaPagto->ds_entrada == "S" && $formaPagto->tp_calculo == "C" ){
            // Cáculo de parcelas com juros compostos
            // pmt = Valor da Parcela
            // pv = Valor Presente ou Valor total da venda menos o valor da entrada
            // i  = Juros % / 100
            // n = nr. de parcelas menos a parcela de entrada
            // ^ = elevado a
            // pmt = (pv * i * (1 + i)^n)/ [((1+ i)^n -1)]*(1 + i)
            //Se não tiver alteração na entrada, a entrada é calculado automaticamente

            if( $vr_juro != null && $vr_juro > floatval(0) ){
                $vr_juro = $vr_juro;
            }else{
                $vr_juro = $formaPagto->vr_juros;
            }

            $i              = ( $vr_juro / 100 );
            $n              = ($formaPagto->nr_parcelas - 1);
            $pv             = (formatValue($VrGeralContaRec) - $vr_entrada);
            $exponencial    = pow((1 + $i), $n);
            $pmt            = ($pv * $i * $exponencial) / ($exponencial - 1);
            $vr_parcela     = $pmt;

            if( $vr_entrada > 0 ){
                $vr_entrada = $vr_entrada;
            }else{
                $vr_entrada = $pmt;
            }

            $n              = $formaPagto->nr_parcelas;
        }
    }

    return [
        "vr_parcela"    => number_format($vr_parcela, 2, ',', '.'),
        "vr_entrada"    => $formaPagto->ds_entrada == "S" ? number_format($vr_entrada, 2, ',', '.') : 0,
        "vr_juro"       => $vr_juro,
        "nr_parcelas"   => $n,
        "ds_entrada"    => $formaPagto->ds_entrada,
    ];
}

function eMestre()
{
    if( Auth::user()->permissao === "mestre" ){
        return true;
    }else{
        return false;
    }
}

function eAdmin()
{
    if( Auth::user()->permissao === "admin" ){
        return true;
    }else{
        return false;
    }
}

function eUsuario()
{
    if( Auth::user()->permissao === "usuario" ){
        return true;
    }else{
        return false;
    }
}

function eParticipante()
{
    if( Auth::user()->permissao === "participante" ){
        return true;
    }else{
        return false;
    }
}

function valorPorExtenso($valor=0) {
	$singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
	$plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");

	$c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
	$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
	$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
	$u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");

	$z=0;
    $rt="";

	$valor = number_format($valor, 2, ".", ".");
	$inteiro = explode(".", $valor);
	for($i=0;$i<count($inteiro);$i++)
		for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
			$inteiro[$i] = "0".$inteiro[$i];

	// $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
	$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
	for ($i=0;$i<count($inteiro);$i++) {
		$valor = $inteiro[$i];
		$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
		$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
		$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

		$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
		$t = count($inteiro)-1-$i;
		$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
		if ($valor == "000")$z++; elseif ($z > 0) $z--;
		if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
		if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
	}

	return($rt ? $rt : "zero");
}

#função para envio de email
define("MAIL_PRIORITY", 1);
define("SMTP_ADDRESS", "webseven.tk");
define("SMTP_PORT", 465);
define("SMTP_USER", "no-reply@webseven.tk");
define("SMTP_PASS", "masterkey$377");
define("FROM_EMAIL", "no-reply@webseven.tk");
define("SMTP_DEBUG", false);
define("SMTP_AUTH", true);
define("SMTP_SECURE", true);
define("FROM_DOMAIN", "webseven.tk");
define("NOME_EMP", "WebSeven");
define("NOME_ADM", 'Stênio Francis');
define("EMAIL_ADM", 'stenio@inforservice.com.br');

function sendMail(array $to, $subject, $message, $from = null, $replyto = null, $anexos = array(), $copy = null)
{
	$mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Host = SMTP_ADDRESS;
    $mail->SMTPAuth = SMTP_AUTH;
    $mail->SMTPDebug = SMTP_DEBUG;
    $mail->Debugoutput = 'html';

    if(SMTP_SECURE){
        $mail->SMTPSecure = 'ssl';
    }

    $mail->Port = SMTP_PORT;
    $mail->IsHTML(true);
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->Sender = SMTP_USER;
    $mail->From = FROM_EMAIL;
    $mail->FromName = $from;
    $mail->Priority = MAIL_PRIORITY;
    $mail->AddCustomHeader("mailed-by: ".FROM_DOMAIN);

	foreach ($to as $sending)
		$mail->AddAddress($sending['email'], $sending['nome']);

	if ($replyto)
		$mail->AddReplyTo($replyto['email'], $replyto['nome']);

	if ($copy)
		$mail->AddBCC($copy['email'], $copy['nome']);

	$mail->IsHTML(true);
	$mail->CharSet = 'utf-8';

	$mail->Subject = $subject;
	$mail->Body = $message;

	foreach ($anexos as $a)
		$mail->AddAttachment($a, basename($a));


	$enviado = $mail->Send();

	if ($enviado) {
		return true;
	} else {
		return false;
	}

	$mail->ClearAllRecipients();
	$mail->ClearAttachments();
}
