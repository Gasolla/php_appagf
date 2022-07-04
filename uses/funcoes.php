<?php

function _corrige_saida($texto, $html) {
    if (is_array($texto)) {
        foreach ($texto as $indice => $conteudo) {
            $texto[$indice] = _corrige_saida($conteudo, $html);
        }
    } else {
        $texto = stripslashes($texto);
        if ($html) {
            $texto = htmlentities($texto, ENT_QUOTES, 'UTF-8');
        }
    }
    return $texto;
}

function _corrige_saida_html($texto) {
    return _corrige_saida($texto, true);
}

function _corrige_saida_txt($texto) {
    return _corrige_saida($texto, false);
}

function soNumero($str) {
    return preg_replace("/[^0-9]/", "", $str);
}

function DecodificaFiltro($Filtro) {
    $Filtro = str_replace("*", "'", $Filtro);
    $Filtro = str_replace("$", '+', $Filtro);
    $Filtro = str_replace("@", "%", $Filtro);
    return $Filtro;
}

function CodificaFiltro($Filtro) {
    $Filtro = str_replace("'", "*", $Filtro);
    $Filtro = str_replace("+", '$', $Filtro);
    $Filtro = str_replace("%", "@", $Filtro);
    return $Filtro;
}

function ChecarCampo($tabela, $condicao, $conexao) {
    $sql = "Select count(*) total from {$tabela} " . (($condicao <> '') ? "Where {$condicao}" : "");
    $retorno = $conexao->consultar($sql, array(), array(), '', false);
    if ($retorno == false) {
        return true;
    } else {
        return ($retorno[0]['total'] > 0);
    }
}

function CodigoTab($tabela, $condicao, $conexao) {
    $sql = "Select Max(Codigo) Codigo from {$tabela} "; ///.
    $sql .= (($condicao <> '') ? "Where {$condicao}" : "");
    $retorno = $conexao->consultar($sql, array(), array(), '', false);
    if ($retorno == false) {
        return $retorno;
    } else {
        $codigo = $retorno[0]['Codigo'] + 1;
        while (strlen($codigo) < 6) {
            $codigo = "0" . $codigo;
        }
        return $codigo;
    }
}

function ajusta_temporario_excel($usuario) {
    clearstatcache();
    $caminho = "../protocolo/Tmp/$usuario/";
    if (!file_exists($caminho)) {
        if (!mkdir($caminho, 0755, true)) {
            return false;
        }
    } else {
        if ($ponteiro = opendir($caminho)) {
            while (($arquivo = readdir($ponteiro)) !== false) {
                if (($arquivo != '.') && ($arquivo != '..')) {
                    unlink($caminho . $arquivo);
                }
            }
        }
        closedir($ponteiro);
    }
    return $caminho;
}

function checaAcessoExterno($acesso) {
    if ($acesso !== "T") {
        return false;
        return ("192.168.1." !== substr(get_client_ip(), 0, 10));
    } else {
        return false;
    }
}

function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } else if (isset($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = 'UNKNOWN';
    }
    return $ipaddress;
}

function login($usuario, $password, $conexao) {
    // Usando definições pré-estabelecidas significa que a injeção de SQL (um tipo de ataque) não é possível. 
    $sql = "Select Senha, Nome, Acesso, Codigo, Usuario, Cliente_id Cliente, "
            . "CONVERT(NVARCHAR(5), Aberto, 113) Aberto, 
	       CONVERT(NVARCHAR(5), Fechado, 113) Fechado , 
               UsuariosWeb.Externo, 
               ISNULL(UsuariosWeb.tentativa, '0') tentativa,
               DATEDIFF(HOUR,DtHrBloqueio, CURRENT_TIMESTAMP) horas,
               DATEDIFF(DAY,DtHrExpirar, CURRENT_TIMESTAMP) Expirar "
            . "from UsuariosWeb "
            . "Where Usuario = ? and Inativo = ? and "
            . "Portal = ?";
    $wV = array(trocaAspas($_POST['usuario']), 'F', 'COLETORMRS');
    $wP = array('usuario', 'inativo', 'portal');
    $retorno = $conexao->consultar($sql, $wV, $wP);
    if ($retorno == false) {
        exit("ajax_htm\nn0\nn{$conexao->mensagem}");
    }

    if ($conexao->contagem != 0) {
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
        $confirma = TRUE;
        foreach ($retorno as $key => $value) {
            if ($value['tentativa'] >= 3) {
                exit("ajax_htm\nn0\nnSenha bloqueada!<br> Usuário Execedeu o limites de tentativas de acesso!");
            }
            if ((strtoupper(sha1('M1' . htmlentities(stripslashes($password)) . 'D45')) == Trim(strtoupper($value['Senha'])))) {
                $confirma = FALSE;
                if (checaHorarioAcesso($conexao, $value['Codigo'])) {
                    exit("ajax_htm\nn0\nnPara seguraça das informações, horário de funcionamento das {$value['Aberto']} às {$value['Fechado']}!");
                } else if (checaAcessoExterno($value['Externo'])) {
                    exit("ajax_htm\nn0\nnUsuario não tem permissao para acesso externo!");
                } else if ($value['horas'] <= 6) {
                    exit("ajax_htm\nn0\nnUsuário bloqueado por suspeita de robotização, o acesso será restabelecido em 6 horas, qualquer necessidade entrar em contato com o suporte.");
                }//else if ($value['Expirar']>90){
                //   exit("ajax_htm\nn1\nnPrezado usuário sua senha expirou! <br> Por favor solicitar nova senha.");
                //}

                $_SESSION['Nome'] = $value['Nome'];
                $_SESSION['Usuario'] = $value['Usuario'];
                $_SESSION['Cliente'] = crypto::encrypt($value['Cliente']);
                $_SESSION['Codigo'] = crypto::encrypt($value['Codigo']);
                $_SESSION['Acesso'] = crypto::encrypt($value['Acesso']);
                $_SESSION['Sistema'] = crypto::encrypt("Coletor");
                $_SESSION['Externo'] = crypto::encrypt($value['Externo']);
                $_SESSION['Login_String'] = hash('sha512', $value['Senha'] . $user_browser . get_client_ip());
                $P1 = array('tentativa');
                $P2 = array('tentativa', 'codigo');
                $V = array("0", $retorno[0]['Codigo']);
                $conexao->alterar("UsuariosWeb", $P1, $V, 'Where codigo = ?', $P2, $retorno[0]['Codigo']);

                return TRUE;
            }
        }
        if ($confirma) {
            $P1 = array('tentativa');
            $P2 = array('tentativa', 'codigo');
            $V = array(($retorno[0]['tentativa'] + 1), $retorno[0]['Codigo']);
            $conexao->alterar("UsuariosWeb", $P1, $V, 'Where codigo = ?', $P2, $retorno[0]['Codigo']);
            exit("ajax_htm\nn0\nnSenha incorreta!<br>Após 3 tentativas o usuário sera bloqueado!<br>Número de tentativa(s) " . ($retorno[0]['tentativa'] + 1) . ".");
        }
    } else {
        exit("ajax_htm\nn0\nnCliente não cadastrado!");
    }
}

function login_check($conexao) {
    // Verifica se todas as variáveis das sessões foram definidas 
    if ((isset($_SESSION['Sistema'])) && (crypto::decrypt($_SESSION['Sistema']) !== "Coletor")) {
        return false;
    }

    if (isset($_SESSION['Usuario'], $_SESSION['Codigo'], $_SESSION['Cliente'], $_SESSION['Nome'], $_SESSION['Acesso'], $_SESSION['Externo'], $_SESSION['Login_String'])) {
        if (checaHorarioAcesso($conexao, crypto::decrypt($_SESSION['Codigo']))) {
            return false;
        } else if (checaAcessoExterno(crypto::decrypt($_SESSION['Externo']))) {
            return false;
        }

        $login_string = $_SESSION['Login_String'];
        $userNome = $_SESSION['Nome'];
        $userUsuario = $_SESSION['Usuario'];
        $userCliente = crypto::decrypt($_SESSION['Cliente']);
        $userCodigo = crypto::decrypt($_SESSION['Codigo']);
        $userAcesso = crypto::decrypt($_SESSION['Acesso']);

        // Pega a string do usuário.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
        $sql = "Select Senha, DATEDIFF(HOUR,DtHrBloqueio, CURRENT_TIMESTAMP) horas "
                . "From UsuariosWeb "
                . "Where Nome = ? and "
                . "Usuario = ? and  "
                . "Cliente_id = ? and "
                . "Codigo = ? and "
                . "Acesso = ? and "
                . "Inativo = ? and "
                . "Portal = ?";

        $wV = array($userNome, $userUsuario, $userCliente, $userCodigo, $userAcesso, 'F', 'COLETORMRS');
        $wP = array('nome', 'usuario', 'cliente', 'codigo', 'acesso', 'inativo', 'protal');
        $retorno = $conexao->consultar($sql, $wV, $wP);
        if ($conexao->contagem == 1) {
            foreach ($retorno as $key => $value) {
                // Caso o usuário exista, pega variáveis a partir do resultado.                 $stmt->bind_result($password);
                $login_check = hash('sha512', $value['Senha'] . $user_browser . get_client_ip());

                if (($login_check == $login_string) && ($value['horas'] > 6)) {
                    // Logado!!!
                    return true;
                } else {
                    // Não foi logado 
                    return false;
                }
            }
        } else {
            // Não foi logado 
            return false;
        }
    } else {
        // Não foi logado 
        return false;
    }
}

function sec_session_start() {
    $session_name = 'COLETORMRS';   // Estabeleça um nome personalizado para a sessão
    $secure = false;
    // Isso impede que o JavaScript possa acessar a identificação da sessão.
    $httponly = true;
    // Assim você força a sessão a usar apenas cookies. 
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        exit('0\nErro initiate a safe session (ini_set)');
    }
    // Obtém params de cookies atualizados.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
    // Estabelece o nome fornecido acima como o nome da sessão.
    session_name($session_name);
    session_start();            // Inicia a sessão PHP 
    session_regenerate_id();    // Recupera a sessão e deleta a anterior. 
}

function sec_session_destroy() {
    // Desfaz todos os valores da sessão  
    $_SESSION = array();

// obtém os parâmetros da sessão 
    $params = session_get_cookie_params();

// Deleta o cookie em uso. 
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);

// Destrói a sessão 
    session_destroy();
}

function quodestr($texto) {
    $texto = "'{$texto}'";
    return $texto;
}

function removeAcentos($txt) {
    $comAcentos = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì',
        'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú',
        'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë',
        'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'O', 'Ù',
        'Ü', 'Ú');

    $semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i',
        'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u',
        'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E',
        'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U',
        'U', 'U');

    return str_replace($comAcentos, $semAcentos, $txt);
}

function paramstostring($texto) {
    $texto = removeAcentos($texto);
    $texto = utf8_decode($texto);
    $texto = trocaAspas($texto);
    return strtoupper($texto);
}

function trocaAspas($texto) {
    $texto = str_replace("'", '"', $texto);
    return $texto;
}

function checaHorarioAcesso($conexao, $codigo) {
    $SQL = "Select count(*) Total From UsuariosWeb "
            . "Where Aberto < cast(CURRENT_TIMESTAMP as time) "
            . "and Fechado > cast(CURRENT_TIMESTAMP as time)"
            . " and codigo = ?";
    $retorno = $conexao->consultar($SQL, array($codigo), array('codigo'));
    if ($retorno === FALSE) {
        return TRUE;
    }
    if ($retorno[0]['Total'] == 0) {
        return TRUE;
    }
    return FALSE;
}

function getURL($request) {
    $url = '';

    if (isset($request['nome']) && $request['nome'] !== "") {
        $url .= "&nome=" . $request['nome'];
    }

    if (isset($request['cpfcnpj']) && $request['cpfcnpj'] !== "") {
        $url .= "&cpfcnpj=" . $request['cpfcnpj'];
    }

    if (isset($request['usuario']) && $request['usuario'] !== "") {
        $url .= "&usuario=" . $request['usuario'];
    }

    if (isset($request['sigla']) && $request['sigla'] !== "") {
        $url .= "&sigla=" . $request['sigla'];
    }

    if (isset($request['inativo']) && $request['inativo'] !== "") {
        $url .= "&inativo=" . $request['inativo'];
    }

    if (isset($request['cidata']) && $request['cidata'] !== "") {
        $url .= "&cidata=" . $request['cidata'];
    }

    if (isset($request['cfdata']) && $request['cfdata'] !== "") {
        $url .= "&cfdata=" . $request['cfdata'];
    }
    
    if (isset($request['pidata']) && $request['pidata'] !== "") {
        $url .= "&pidata=" . $request['pidata'];
    }

    if (isset($request['pfdata']) && $request['pfdata'] !== "") {
        $url .= "&pfdata=" . $request['pfdata'];
    }


    if (isset($request['cliente_id']) && $request['cliente_id'] !== "") {
        $url .= "&cliente_id=" . $request['cliente_id'];
    }

    if (isset($request['agencia_id']) && $request['agencia_id'] !== "") {
        $url .= "&agencia_id=" . $request['agencia_id'];
    }

    if (isset($request['suprimento_id']) && $request['suprimento_id'] !== "") {
        $url .= "&suprimento_id=" . $request['suprimento_id'];
    }

    if (isset($request['pidata']) && $request['pidata'] !== "") {
        $url .= "&pidata=" . $request['pidata'];
    }

    if (isset($request['pfdata']) && $request['pfdata'] !== "") {
        $url .= "&pfdata=" . $request['pfdata'];
    }

    if (isset($request['objeto']) && $request['objeto'] !== "") {
        $url .= "&objeto=" . $request['objeto'];
    }

    if (isset($request['pendencia']) && $request['pendencia'] !== "") {
        $url .= "&pendencia=" . $request['pendencia'];
    }

    if (isset($request['comercial']) && $request['comercial'] !== "") {
        $url .= "&comercial=" . $request['comercial'];
    }

    if (isset($request['comercial_id']) && $request['comercial_id'] !== "") {
        $url .= "&comercial_id=" . $request['comercial_id'];
    }

    if (isset($request['usuario_id']) && $request['usuario_id'] !== "") {
        $url .= "&usuario_id=" . $request['usuario_id'];
    }

    if (isset($request['status']) && $request['status'] !== "") {
        $url .= "&status=" . $request['status'];
    }

    if (isset($request['seguimento']) && $request['seguimento'] !== "") {
        $url .= "&seguimento=" . $request['seguimento'];
    }


    return $url;
}

function leftcaracter($valor, $caracter, $qtde){
    return str_pad($valor, $qtde, $caracter , STR_PAD_LEFT);            
}

function rigthcaracter($valor, $caracter, $qtde){
    return str_pad($valor, $qtde, $caracter , STR_PAD_RIGHT);            
}

function maskcep($valor){
    return substr($valor, 0, 5)."-".substr($valor, 4, 3) ;
}

function UnificarPDF($ElementosPDF, $Caminho) {
    $pdf = new FPDI();
    foreach ($ElementosPDF as $key => $value) {
        $paginas = array();
        $pdf->setSourceFile($ElementosPDF[$key]);
        $numeropaginas = $pdf->parsers[$pdf->currentFilename]->getPageCount();
        for ($i = 1; $i <= $numeropaginas; $i++) {
            $paginas[] = $pdf->importPage($i, '/MediaBox');
        }
        foreach ($paginas as $key => $value1) {
            $pdf->addPage();
            $pdf->useTemplate($paginas[$key], 10, 10, 190);
        }
        unset($paginas);
    }
    $pdf->Output($Caminho, 'F');
    return TRUE;
}


function UnificarEtiquetas($ElementosPDF, $Caminho) {
    $pdf = new FPDI();
    foreach ($ElementosPDF as $key => $value) {
        $paginas = array();
        $pdf->setSourceFile($ElementosPDF[$key]);
        $numeropaginas = $pdf->parsers[$pdf->currentFilename]->getPageCount();
        for ($i = 1; $i <= $numeropaginas; $i++) {
            $paginas[] = $pdf->importPage($i);
        }
        foreach ($paginas as $key => $value1) {
            $pdf->addPage("P", array(115,100));
            $pdf->useTemplate($paginas[$key]);
        }
        unset($paginas);
    }
    $pdf->Output($Caminho, 'F');
    return TRUE;
}

function checarArquivo($arquivo) {
    $mgs = "OK";
    switch ($arquivo['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_INI_SIZE:
            $mgs = "Arquivo maior que o permitido pelo servidor";
            break;
        case UPLOAD_ERR_FORM_SIZE:
            $mgs = "Arquivo maior que o permitido pelo cliente";
            break;
        case UPLOAD_ERR_PARTIAL:
            $mgs = "Arquivo carregado parcialmente";
            break;
        case UPLOAD_ERR_NO_FILE:
            $mgs = "Nenhum arquivo carregado";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $mgs = "Pasta temporária não encontrada";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $mgs = "Sem permissão para gravação do arquivo";
            break;
        case UPLOAD_ERR_EXTENSION:
            $mgs = "Arquivo negado por um complemento do servidor";
            break;
    }

    if (($mgs == "OK") && ($arquivo['size'] == '0')) {
        $mgs = "Arquivo vazio";
    }

    if (($mgs == "OK") && (!is_uploaded_file($arquivo['tmp_name']))) {
        $mgs = "Falha de segurança na recepção do arquivo";
    }

    return $mgs;
}


function preparaString($texto) {
    $texto = str_replace("Á", "A", $texto);
    $texto = str_replace("á", "A", $texto);
    $texto = str_replace("Â", "A", $texto);
    $texto = str_replace("Ã", "A", $texto);
    $texto = str_replace("â", "A", $texto);
    $texto = str_replace("À", "A", $texto);
    $texto = str_replace("à", "A", $texto);
    $texto = str_replace("ã", "A", $texto);
    $texto = str_replace("È", "E", $texto);
    $texto = str_replace("É", "E", $texto);
    $texto = str_replace("Ê", "E", $texto);
    $texto = str_replace("è", "E", $texto);
    $texto = str_replace("é", "E", $texto);
    $texto = str_replace("ê", "E", $texto);
    $texto = str_replace("ô", "O", $texto);
    $texto = str_replace("Ò", "O", $texto);
    $texto = str_replace("ò", "O", $texto);
    $texto = str_replace("Õ", "O", $texto);
    $texto = str_replace("õ", "O", $texto);
    $texto = str_replace("Ó", "O", $texto);
    $texto = str_replace("ó", "O", $texto);
    $texto = str_replace("Ô", "O", $texto);
    $texto = str_replace("Ç", "C", $texto);
    $texto = str_replace("ç", "C", $texto);
    $texto = str_replace("Í", "I", $texto);
    $texto = str_replace("í", "I", $texto);
    $texto = str_replace("Î", "I", $texto);
    $texto = str_replace("î", "I", $texto);
    $texto = str_replace("Ì", "I", $texto);
    $texto = str_replace("ì", "I", $texto);
    $texto = str_replace("Ú", "U", $texto);
    $texto = str_replace("ú", "U", $texto);
    $texto = str_replace("Û", "U", $texto);
    $texto = str_replace("û", "U", $texto);
    $texto = str_replace("Ù", "U", $texto);
    $texto = str_replace("ù", "U", $texto);
    $texto = str_replace("€", "", $texto);
    $texto = str_replace("'", "", $texto);
    return strtoupper($texto);
}

function strtofloat($valor) {
    if (strpos($valor, ",") > 0) {
        $valor = str_replace(".", "", $valor);
    }
    $valor = str_replace(",", ".", $valor);
    return $valor;
}


function getSeguro() {
    $upper = implode('', range('A', 'Z')); // ABCDEFGHIJKLMNOPQRSTUVWXYZ
    $nums = implode('', range(0, 9)); // 0123456789

    $alphaNumeric = $upper . $nums; // ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789
    $string = '';
    $len = 10; // numero de chars
    for ($i = 0; $i < $len; $i++) {
        $string .= $alphaNumeric[rand(0, strlen($alphaNumeric) - 1)];
    }
    return $string;
}

?>