<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#"> <img src="image/mr.jpg" alt="Grupo MRS" name="Grupo MRS" id="Grupo MRS" class="navbar-img img-responsive img-thumbnail" /> </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Alterna navegação">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse div-nav" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <?php if ((in_array("cadastro", $usuarioacesso->Menus))){ ?>
            <li class="nav-item dropdown <?php echo ($menu == "Cadastro" ? "active" : "") ?>">
                <a style="font-size: 16px;" class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    CADASTRO
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink1">
                    <?php  if (in_array("agendamento", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("agendamento", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("agendamento", $usuarioacesso->Headers))?"disabled='true'":"") ?> href="<?php echo ((!in_array("agendamento", $usuarioacesso->Headers))?"#":"agendamento?pag=1&acao=index") ?>">Agendamento</a>
                    <?php } if (in_array("cliente", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("cliente", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("cliente", $usuarioacesso->Headers))?"disabled='true'":"") ?>  href="<?php echo ((!in_array("cliente", $usuarioacesso->Headers))?"#":"cliente?pag=1&acao=index") ?>">Clientes</a>
                    <?php } if (in_array("usuario", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("usuario", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("usuario", $usuarioacesso->Headers))?"disabled='true'":"") ?> href="<?php echo ((!in_array("usuario", $usuarioacesso->Headers))?"#":"usuario?pag=1&acao=index") ?>">Usuários</a>
                    <?php } if (in_array("usuariocli", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("usuariocli", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("usuariocli", $usuarioacesso->Headers))?"disabled='true'":"") ?> href="<?php echo ((!in_array("usuariocli", $usuarioacesso->Headers))?"#":"usuariocli?pag=1&acao=index") ?>">Usuários app cliente</a>
                    <?php } if (in_array("suprimento", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("suprimento", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("suprimento", $usuarioacesso->Headers))?"disabled='true'":"") ?> href="<?php echo ((!in_array("suprimento", $usuarioacesso->Headers))?"#":"suprimento?pag=1&acao=index") ?>">Suprimentos</a>
                    <?php } if (in_array("veiculo", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("veiculo", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("veiculo", $usuarioacesso->Headers))?"disabled='true'":"") ?> href="<?php echo ((!in_array("veiculo", $usuarioacesso->Headers))?"#":"veiculo?pag=1&acao=index") ?>">Veículo</a>
                    <?php } ?>
                    
                </div>
            </li>
            <?php } if ((in_array("movimentacao", $usuarioacesso->Menus))){ ?>
            <li class="nav-item dropdown <?php echo ($menu == "Movimentacao" ? "active" : "") ?>">
                <a style="font-size: 16px;" class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    MOVIMENTAÇÃO
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                    <?php  if (in_array("estoquecliente", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("estoquecliente", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("estoquecliente", $usuarioacesso->Headers))?"disabled='true'":"") ?>  href="<?php echo ((!in_array("estoquecliente", $usuarioacesso->Headers))?"#":"estoquecliente?pag=1&acao=index") ?>">Estoque CLientes</a>
                    <?php } if (in_array("apiweb", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("apiweb", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("apiweb", $usuarioacesso->Headers))?"disabled='true'":"") ?>  href="<?php echo ((!in_array("apiweb", $usuarioacesso->Headers))?"#":"apiweb?pag=1&acao=index") ?>">Envio API Web</a>
                    <?php } if (in_array("importararq", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("importararq", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("importararq", $usuarioacesso->Headers))?"disabled='true'":"") ?>  href="<?php echo ((!in_array("importararq", $usuarioacesso->Headers))?"#":"importararq?pag=1&acao=index") ?>">Importar Arquivo</a>
                    <?php } if (in_array("rastreadorweb", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("rastreadorweb", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("rastreadorweb", $usuarioacesso->Headers))?"disabled='true'":"") ?>  href="<?php echo ((!in_array("rastreadorweb", $usuarioacesso->Headers))?"#":"rastreadorweb?pag=1&acao=index") ?>">Rastreador Web</a>
                    <?php } if (in_array("comprovanteveiculo", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("comprovanteveiculo", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("comprovanteveiculo", $usuarioacesso->Headers))?"disabled='true'":"") ?>  href="<?php echo ((!in_array("comprovanteveiculo", $usuarioacesso->Headers))?"#":"comprovanteveiculo?pag=1&acao=index") ?>">Comprovante Veículo</a>
                    <?php  } ?> 
                </div>
            </li> 
            <?php } if ((in_array("utilitario", $usuarioacesso->Menus))){ ?>
            <li class="nav-item dropdown <?php echo ($menu == "Utilitario" ? "active" : "") ?>">
                <a style="font-size: 16px;" class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    COMERCIAL
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                    <?php  if (in_array("prospeccao", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("prospeccao", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("prospeccao", $usuarioacesso->Headers))?"disabled='true'":"") ?>  href="<?php echo ((!in_array("prospeccao", $usuarioacesso->Headers))?"#":"prospeccao?pag=1&acao=index") ?>">Prospecção</a>
                    <?php } if (in_array("prospeccaolista", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("prospeccaolista", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("prospeccaolista", $usuarioacesso->Headers))?"disabled='true'":"") ?>  href="<?php echo ((!in_array("prospeccaolista", $usuarioacesso->Headers))?"#":"prospeccaolista?pag=1&acao=index") ?>">Lista Prospecção</a>
                    <?php  } ?> 
                </div>
            </li>
            <?php } if ((in_array("relatorio", $usuarioacesso->Menus))){ ?>
            <li class="nav-item dropdown <?php echo ($menu == "Relatorio" ? "active" : "") ?>">
                <a style="font-size: 16px;" class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink3" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    RELATÓRIO
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink3">
                    <?php  if (in_array("objetorel", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("objetorel", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("objetorel", $usuarioacesso->Headers))?"disabled='true'":"") ?>  href="<?php echo ((!in_array("objetorel", $usuarioacesso->Headers))?"#":"objetorel?pag=1&acao=index") ?>">Coleta Objetos</a>
                    <?php } if (in_array("requisicaorel", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("requisicaorel", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("requisicaorel", $usuarioacesso->Headers))?"disabled='true'":"") ?>  href="<?php echo ((!in_array("requisicaorel", $usuarioacesso->Headers))?"#":"requisicaorel?pag=1&acao=index") ?>">Coleta Requisição</a>
                    <?php } if (in_array("suprimentoclienterel", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("suprimentoclienterel", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("suprimentoclienterel", $usuarioacesso->Headers))?"disabled='true'":"") ?>  href="<?php echo ((!in_array("suprimentoclienterel", $usuarioacesso->Headers))?"#":"suprimentoclienterel?pag=1&acao=index") ?>">Suprimento Clientes</a>
                    <?php } if (in_array("agendamentorel", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("agendamentorel", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("agendamentorel", $usuarioacesso->Headers))?"disabled='true'":"") ?>  href="<?php echo ((!in_array("agendamentorel", $usuarioacesso->Headers))?"#":"agendamentorel?pag=1&acao=index") ?>">Agendamento Coleta</a>
                    <?php } if (in_array("prospeccaorel", $usuarioacesso->Headers)){ ?>
                    <a class="dropdown-item <?php echo ((!in_array("prospeccaorel", $usuarioacesso->Headers))?"disabled":"") ?>" <?php echo ((!in_array("prospeccaorel", $usuarioacesso->Headers))?"disabled='true'":"") ?>  href="<?php echo ((!in_array("prospeccaorel", $usuarioacesso->Headers))?"#":"prospeccaorel?pag=1&acao=index") ?>">Contato Prospecção</a>
                    <?php  } ?>
                </div>
            </li>
            <?php } ?>
            <li class="nav-item">
                <a style="font-size: 16px;" class="nav-link" data-toggle="modal" data-target="#MsnSair">
                    <span class="glyphicon glyphicon-remove"></span> SAIR
                </a>
            </li>
        </ul>
    </div>
</nav>