<?php
class usuarioacesso{
    public $Usuario;
    public $Nome;
    public $Cliente;
    public $Password;
    public $Codigo;
    public $Acesso;
    public $Agencia;
    public $Retorno;
    
    public $Headers = array();
    public $Menus = array();
    public $Pagina = false;
    public $Alterar = false;
    public $Excluir = false;
    public $Incluir = false;
    public $Consultar = False;
    public $Gerar = False;
    public $Imprimir = False;
    public $Visualizar = False;
    public $Solicitar = False;
    
    
  
    public function usuarioacesso($Codigo, $conexao, $pagina = ""){
        $Sql = "Select Codigo, Nome, Acesso, Usuario, Cliente_id Cliente, 
                ISNULL(Agencia_id, 0) Agencia, Alterar,
                (Select count(*) total from UsuariosWebRequisicao
                 where usuario =  ? and 
                    CAST(CURRENT_TIMESTAMP as date) = CAST(DtHr as date) and 
                    DATEPART(HOUR, CURRENT_TIMESTAMP) = DATEPART(HOUR, DtHr) and
                    (DATEPART(MINUTE, CURRENT_TIMESTAMP) -1) = DATEPART(MINUTE, DtHr)) requisicoes 
                From UsuariosWeb
                where Codigo = ? and Inativo = ? ";
        $this->Retorno = $conexao->consultar($Sql, array(crypto::decrypt($Codigo), crypto::decrypt($Codigo), 'F'), array('codigo1', 'codigo2', 'inativo'), crypto::decrypt($Codigo));
        if ($this->Retorno===False){
            exit;
	}
        $this->Usuario = $this->Retorno[0]['Usuario'];
        $this->Nome = $this->Retorno[0]['Nome'];
        $this->Cliente = $this->Retorno[0]['Cliente'];
        $this->Codigo = $this->Retorno[0]['Codigo'];
        $this->Acesso = $this->Retorno[0]['Acesso']; 
        $this->Agencia = $this->Retorno[0]['Agencia'];
        $this->Password = ($this->Retorno[0]['Alterar']=="T");
        date_default_timezone_set('America/Sao_Paulo');
        if ($this->Retorno[0]['requisicoes']>20){
            $wC = array('DtHrBloqueio');
            $wV = array(date('d/m/Y H:i:s'), $this->Codigo);
            $wP = array('DtHrBloqueio', 'codigo');
            $this->Retorno = $conexao->alterar("UsuariosWeb", $wC, $wV, " WHERE Codigo = ?", $wP, $this->Codigo);
            if ($this->Retorno === false) {
                exit;
            }
        }
        $Sql = "Select menu, submenu from acesso "
                . "where usuario_id = ?";
        $this->Retorno = $conexao->consultar($Sql, array(crypto::decrypt($Codigo)), array('codigo'), crypto::decrypt($Codigo));
        if ($this->Retorno===FALSE){
            exit();}
        
        if ((is_array($this->Retorno))&&(count($this->Retorno)>0)){
            foreach ($this->Retorno as $value) {
                extract(array_map('_corrige_saida_html',$value));
                if ($menu===$pagina){
                    $this->Pagina = TRUE;
                }
                if ($submenu===$pagina."incluir"){
                    $this->Incluir = TRUE;
                }
                if ($submenu===$pagina."alterar"){
                    $this->Alterar = TRUE;
                }
                if ($submenu===$pagina."consultar"){
                    $this->Consultar = TRUE;
                }
                if ($submenu===$pagina."excluir"){
                    $this->Excluir = TRUE;
                }
                if ($submenu===$pagina."gerar"){
                    $this->Gerar = TRUE;
                }
                if ($submenu===$pagina."imprimir"){
                    $this->Imprimir = TRUE;
                }
                if ($submenu===$pagina."visualizar"){
                    $this->Visualizar = TRUE;
                }
                if ($submenu===$pagina."solicitar"){
                    $this->Solicitar = TRUE;
                }
                
                
                if (!in_array($menu, $this->Headers)){
                    $this->Headers[] = $menu;                    
                }
                $this->addMenu($menu);
            }
        }       
    }    
    
    private function addMenu($menu){
        if ((in_array($menu, array('agendamento', 'suprimento', 'cliente', 'usuario')))&&
            (!in_array('cadastro', $this->Menus))){
            $this->Menus[] = 'cadastro';
        }else if ((in_array($menu, array('estoquecliente', 'apiweb', 'importararq', 'rastreadorweb')))&&
            (!in_array('movimentacao', $this->Menus))){
            $this->Menus[] = 'movimentacao';
        }else if ((in_array($menu, array('prospeccao', 'prospeccaolista')))&&
            (!in_array('utilitario', $this->Menus))){
            $this->Menus[] = 'utilitario';
        }else if ((in_array($menu, array('objetorel', 'requisicaorel', 'suprimentoclienterel', 'agendamentorel', 'prospeccaorel')))&&
            (!in_array('relatorio', $this->Menus))){
            $this->Menus[] = 'relatorio';
        }
                   
    }
}
?>
