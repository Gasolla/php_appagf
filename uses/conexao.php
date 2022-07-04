<?php

    require('Email/Log.php');
    require('Email/Email.php');
    require('Email/phpmailer/class.phpmailer.php');

class Conexao {

    public $ArqIni;
    public $mensagem;
    public $contagem;
    protected $modo_transacao;
    protected $dbhost;
    protected $db;
    protected $user;
    protected $password;
    protected $conexao;
    protected $requisicao = false;
    
    public function __construct() {
        $this->ArqIni = parse_ini_file('MRS.ini', true);
        $this->dbhost = str_replace("*", "=", $this->ArqIni['Geral']['TH']);
        $this->db = str_replace("*", "=", $this->ArqIni['Geral']['DB']);
        $this->user = str_replace("*", "=", $this->ArqIni['Geral']['SU']);
        $this->password = str_replace("*", "=", $this->ArqIni['Geral']['SP']);
        $this->contagem = 0;
        $this->mensagem = '';
        $this->modo_transacao = false;
    }

    /**
     *
     * funcao que abre a conexao com o banco 
     * 
     * @access protected
     * @return bool ou array
     *
     */
    public function conectar() {
        $infromacao = array(
                                "Database" => crypto::decrypt($this->db), 
                                "UID" => crypto::decrypt($this->user), 
                                "PWD" => crypto::decrypt($this->password)
                );
		//var_dump($infromacao);
		//echo '<br>'.crypto::decrypt($this->dbhost);
        $this->conexao = sqlsrv_connect(crypto::decrypt($this->dbhost), $infromacao);
        if ($this->conexao === false) {
            $this->mensagem = 'Não foi possível conectar-se ao servidor!';
            return false;
        }
        return true;
    }


    /**
     *
     * funcao deletar registro recebe o sql mais os parametros 
     *  {valor} 
     * @access protected
     * @return bool
     *
     */
    
    public function deletar($tsql, $valores, $paramsname, $usuario = '', $validar = true) {
        $this->contagem = 0;
        if (trim($tsql) === "") {
            $this->mensagem = "SQL não definido!";
            return false;
        }else if ($validar &&(count($valores) === 0)) {
            $this->mensagem = "Numero de parametros inválido!";
            return false;
        }
        $retorno = $this->executar($tsql, $this->getParametrosPrepare($valores, $paramsname), $usuario);
        if ($retorno===false){
            $this->mensagem = "deletar:Não foi possivel realizar exclusão!";
        }
        return $retorno;
    }
    
    /**
     *
     * funcao inserir tabela recebe a tabela do banco os campos sao passados exemplo 
     *  {campo = valor} 
     * @access protected
     * @return bool
     *
     */
    
    public function inserir($tabela, $campos, $valores, $paramsname, $usuario = '') {
        $this->contagem = 0;
        if ($tabela === "") {
            $this->mensagem = "Tabela nao encontrada!";
            return false;
        }else if (count($campos) === 0) {
            $this->mensagem = "Numero de campos vazio!";
            return false;
        }else if (count($valores) === 0) {
            $this->mensagem = "Numero de valores vazio!";
            return false;
        }else if (count($campos)<>count($valores)){
            $this->mensagem = "Numero de Campos e valores não coincidem!<br> Campos: ".count($campos)." <br> Valores: ".count($valores);
            return false;    
        }
        $tsql = "insert into {$tabela} (".implode(",", $campos ).") " . 
                " values(".$this->getParametrosInsert($campos).")";
        
        $retorno = $this->executar($tsql, $this->getParametrosPrepare($valores, $paramsname), $usuario);
        if ($retorno===false){
            $this->mensagem = "Inserir:Não foi possivel realizar inclusão!";
        }
        return $retorno;
    }
    
    private function getParametrosInsert($wArr){
        $result = '';
        for ($i = 0;$i < count($wArr);$i++) {
            $result .= ($result===""?"?":",?");
        }
        return $result;
    }
    

    /**
     *
     * funcao alterar tabela recebe a tabela do banco os campos sao passados exemplo 
     *  {campo = valor}  e a condicao seria a clausula where passada por parametro
     * @access protected
     * @return bool
     *
     */
    public function alterar($tabela, $campos, $valores, $condicao, $paramsname, $usuario = '') {
        $this->contagem = 0;
        if ($tabela === "") {
            $this->mensagem = "Tabela nao encontrada!";
            return false;
        }else if (count($campos) === 0) {
            $this->mensagem = "Numero de campos vazio!";
            return false;
        }else if (count($valores) === 0) {
            $this->mensagem = "Numero de valores vazio!";
            return false;
        }else if (count($campos)>=count($valores)){
            $this->mensagem = "Numero de Campos e valores não coincidem!<br> Campos: ".count($campos)." <br> Valores: ".count($valores);
            return false;    
        }
        $tsql = "Update {$tabela} set ".$this->getParametrosUpdate($campos)." {$condicao}";       
        $retorno = $this->executar($tsql, $this->getParametrosPrepare($valores, $paramsname), $usuario);
        if ($retorno===false){
            $this->mensagem = "Alterar:Não foi posivel realizar alteração!";
        }
        return $retorno;
        
    }

    private function getParametrosUpdate($wArr){
        $result = '';
        foreach ($wArr as $value) {
            $result .= ($result===""?$value."=?":",".$value."=?");    
        }
        return $result;
    }
       
    
    /**
     *
     * funcao consultar sql completo usado principalmente para o comando select 
     * 
     * @access protected
     * @return bool ou array
     *
     */
    
    public function consultar($tsql, $valores, $paramsname, $usuario = '', $validar = true) {
        $this->contagem = 0;
        if (trim($tsql) == '') {
            $this->mensagem = 'Consulta não definida!';
            return false;
        } else if ($validar && (count($valores)==0)){
            $this->mensagem = '"Numero de parametros inválido!"';
            return false;    
        }
        
        $retorno = $this->executar_query($tsql, $this->getParametrosPrepare($valores, $paramsname), $usuario);
        if ($retorno===false){
            $this->mensagem = "Consultar:Não foi possível realizar a consulta!";
        }
        return $retorno;
    }
    
   
    public function getConexao() {
        return $this->conexao;
    }

    public function getRequisicao(){
        return $this->requisicao;
    }
    
    public  function setRequisicao($requisicao){
        $this->requisicao = $requisicao;
    }

    private function getParametrosPrepare($valores, $paramsname){
        $wArray = array();
        foreach ($valores as $key => $value) {
            $$paramsname[$key] = $value;
            $wArray[] =   &$$paramsname[$key];
        }
        return $wArray;
    }
    
    private function executar_query($tsql, $params, $usuario){
        $this->GerarLogRequisicao();
        $retorno = true;
        $options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
        $stmt = sqlsrv_prepare($this->conexao, $tsql, $params, $options);
        if (!$stmt) {
            $retorno = false;
        } 
        if ($retorno && (!sqlsrv_execute($stmt))){/* Execute query the statement. */
            $retorno = false;
        }
        if ((!$retorno) && (($erros = sqlsrv_errors(SQLSRV_ERR_ERRORS)) != null)) {
            new Log($this->mensagem . "<br><br>SQL:<br>" . $tsql .  
                                      "<br><br>PARAMETROS:<br>". print_r($params, true). 
                                      "<br><br>ERROR:<br>". print_r($erros, true). 
                                      "<br><br>IP:<br>".get_client_ip(). 
                                      "<br><br>USUARIO:<br>".($usuario==''?"NAO DEFINIDO":$usuario));
        }
        if ($retorno){
            $matriz = array();
            $this->contagem = 0;
            while ($registro = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $this->contagem++;
                $matriz[] = $registro;
            }
            if ($this->contagem > 0) {
                $retorno = $matriz;
            }
        }
        sqlsrv_free_stmt($stmt); /* Free the statement and connection resources. */
        return $retorno;     
    }
    
    
    
    private function executar($tsql, $params, $usuario){
        $this->GerarLogRequisicao();
        $this->contagem = 0;
        $retorno = true;
        $options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
        $stmt = sqlsrv_prepare($this->conexao, $tsql, $params, $options);
        if (!$stmt) {
            $retorno = false;
        } 
        if ($retorno && (!sqlsrv_execute($stmt))) {/* Execute the statement. */  
            $retorno = false;
        }
        if ((!$retorno) && (($erros = sqlsrv_errors(SQLSRV_ERR_ERRORS)) != null)) {
            new Log($this->mensagem . "<br><br>SQL:<br>" . $tsql .  
                                      "<br><br>PARAMETROS:<br>". print_r($params, true). 
                                      "<br><br>ERROR:<br>". print_r($erros, true). 
                                      "<br><br>IP:<br>".get_client_ip(). 
                                      "<br><br>USUARIO:<br>".($usuario==''?"NAO DEFINIDO":$usuario));
        }
        if ($retorno){
            $this->contagem = sqlsrv_rows_affected($stmt);
        }
        sqlsrv_free_stmt($stmt); /* Free the statement and connection resources. */
        return $retorno;
    }
     

    public function GerarLogRequisicao() {
        if ($this->getRequisicao()){
            $retorno = true;
            $usuario = (isset($_SESSION['Codigo'])? crypto::decrypt($_SESSION['Codigo']):"");
            $tsql = "exec sp_UsuariosWebRequisicao '{$_SERVER['REMOTE_ADDR']}', '" .
                    $usuario . "', '" . $_SERVER['REQUEST_URI'] . "', 'COLETOR MRS'";
            $stmt = sqlsrv_prepare($this->conexao, $tsql);
            if (!$stmt) {
                $retorno = false;
            } 
            if ($retorno && (!sqlsrv_execute($stmt))){
                $retorno = false;
            }
            if ((!$retorno) && (($erros = sqlsrv_errors(SQLSRV_ERR_ERRORS)) != null)) {
                $this->mensagem = "GERAR LOG: FALHA NA GERACAO DO LOG ";
                new Log($this->mensagem . "<br><br>SQL:<br>" . $tsql .  
                                      "<br><br>ERROR:<br>". print_r($erros, true). 
                                      "<br><br>IP:<br>".get_client_ip(). 
                                      "<br><br>USUARIO:<br>".($usuario==''?"NAO DEFINIDO":$usuario));
                return false;
            }
        }
        $this->setRequisicao(false);
    }

    protected function executar_requisicao($tsql, $metodo, $mensagem) {
        $this->GerarLogRequisicao();
        $metodo1 = explode('::', $metodo);
        if ($metodo1[1] == 'inicializar_transacao') {
            $retorno = sqlsrv_begin_transaction($this->conexao);
        } else if ($metodo1[1] == 'efetivar_transacao') {
            $retorno = sqlsrv_commit($this->conexao);
        } else if ($metodo1[1] == 'cancelar_transacao') {
            $retorno = sqlsrv_rollback($this->conexao);
        } else{
            return false;
        }

        if ((!$retorno) && (($erros = sqlsrv_errors(SQLSRV_ERR_ERRORS)) != null)) {
            $this->mensagem = "$metodo1[1]: $mensagem ";
            new Log($this->mensagem . "<br><br>SQL:<br>" . $tsql .  
                                      "<br><br>ERROR:<br>". print_r($erros, true). 
                                      "<br><br>IP:<br>".get_client_ip());
            
            return false;
        }
        
        return true;
    }

    /**
     *
     * Inicializar uma transação e retorna true em caso de sucesso ou falso para erro
     *
     * @access public
     * @param void
     * @return bool
     *
     */
    public function inicializar_transacao() {
        if ($this->modo_transacao) {
            $this->mensagem = __METHOD__ . ': Outra transação já ativa';
            return false;
        }
        $sql = "BEGIN TRANSACTION TRANSACAO";
        $retorno = $this->executar_requisicao($sql, __METHOD__, 'Não foi possível iniciar a transação');
        $this->modo_transacao = $retorno;
        return $retorno;
    }

    /**
     *
     * Finalizar uma transação com a efetivação das requisições e retorno de true em
     * caso de sucesso ou false para erro
     *
     * @access public
     * @param void
     * @return bool
     *
     */
    public function efetivar_transacao() {
        if (!$this->modo_transacao) {
            $this->mensagem = __METHOD__ . ': Não há transação ativa';
            return false;
        }
        $sql = "COMMIT TRANSACTION TRANSACAO";
        $retorno = $this->executar_requisicao($sql, __METHOD__, 'Não foi possível efetivar a transação');
        if ($retorno) {
            $this->modo_transacao = false;
        }
        return $retorno;
    }

    /**
     *
     * Finalizar uma transação com o cancelamento das requisições e retorno de true
     * em caso de sucesso ou false para erro
     *
     * @access public
     * @param void
     * @return bool
     *
     */
    public function cancelar_transacao() {
        if (!$this->modo_transacao) {
            $this->mensagem = __METHOD__ . ': Não há transação ativa';
            return false;
        }
        $sql = "ROLLBACK TRANSACTION TRANSACAO";
        $retorno = $this->executar_requisicao($sql, __METHOD__, 'Não foi possível desfazer a transação');
        if ($retorno) {
            $this->modo_transacao = false;
        }
        return $retorno;
    }

}
?>