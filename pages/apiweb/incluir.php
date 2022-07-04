<?php
$codigo = $acao = (filter_input(INPUT_GET, 'codigo') ?: 0);
$sucesso = $controller->index($codigo);
if ($sucesso === false) {
    exit(header("location:{$pagina}?pag={$pag}&acao=index{$url}"));
}

if (((!$usuarioacesso->Incluir) && (!$usuarioacesso->Alterar) && (!$usuarioacesso->Consultar))) {
    exit(header("location:{$pagina}?pag={$pag}&acao=index{$url}"));
}
$salvar = (($usuarioacesso->Incluir && (crypto::decrypt($codigo) === false)) || ($usuarioacesso->Alterar && (crypto::decrypt($codigo) > 0)));
?>
<div class="row" >
    <div class="col-md-12 col-sm-12">
        <h3  class="h3-1 float-left">Incluir Envio API</h3>
        <a href="<?php echo "{$pagina}?pag={$pag}&acao=index{$url}"; ?>" class="btn-link float-right link-voltar"><i class="fas fa-chevron-left"></i> Voltar</a>
    </div>
    <div class="col-md-12 col-sm-12">
        <hr>
    </div>
</div>
<form class="form-group" name="formulario" id="formulario" 
      action="app" method="POST" onsubmit="return Gravar(this)">
    <input type="hidden" name="acao" id="acao" value="<?php echo ((crypto::decrypt($codigo) > 0) ? "alterar" : "incluir") ?>"/>
    <input type="hidden" name="codigo" id="codigo" value="<?php echo $codigo ?>"/>
    <input type="hidden" name="class" id="class" value="<?php echo $pagina ?>"/>
    <input type="hidden" name="url" id="url" value="<?php echo "{$pagina}?pag={$pag}{$url}"; ?>"/> 
    <div class="panel-body">
        <div class="form-row">
            <?php $controller->addAgencia(); ?>
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6 <?php echo ($usuarioacesso->Agencia === 0 ? "" : "d-none") ?>" id="form-agencia"> 
                <label for="agencia">Agência</label>
                <select name="agencia" id="agencia" rel="select2" class="form-control"
                        onchange='onChangeAgencia("<?php echo "{$pagina}?pag={$pag}"; ?>&acao=incluir<?php echo (isset($_REQUEST['codigo']) ? "&codigo={$_REQUEST['codigo']}" : "") ?><?php echo $url ?>", this)'>
                    <option value="">Selecione a agência</option>
                    <?php foreach ($controller->getAgencia() as $value) { ?>
                        <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                <?php echo ($controller->getApiweb()->getAgencia() == $value->getId() ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                            <?php } ?>
                </select>
            </div>
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6 <?php echo ($usuarioacesso->Cliente === 0 ? "" : "d-none") ?>" id="form-cliente">  
                <?php $controller->addCliente(true);?>
                <label for="cliente">Cliente</label>
                <select class="form-control" id="cliente" name="cliente" rel="select2">
                    <option value="">Selecione o cliente</option>
                    <?php foreach ($controller->getCliente() as $value) { ?>
                        <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                <?php echo ($controller->getApiweb()->getCliente() == $value->getId() ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                            <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-xs-12  col-sm-12 col-md-6  col-lg-6" id="form-nome">  
                <label for="nome">Nome</label>
                <input  type="text" class="form-control" id="nome" placeholder="digite o nome" maxlength="100"
                        name="nome"  value="<?php echo $controller->getApiweb()->getNomedestino() ?>"/>

            </div>
            <div class="form-group col-xs-12  col-sm-12 col-md-6  col-lg-6" id="form-endereco">  
                <label for="endereco">Endereço</label>
                <input  type="text" class="form-control" id="endereco" placeholder="digite o endereco" maxlength="150"
                        name="endereco"  value="<?php echo $controller->getApiweb()->getEnderecodestino() ?>"/>

            </div>
            <div class="form-group col-xs-12  col-sm-12 col-md-6  col-lg-6" id="form-numero">  
                <label for="numero">Numero</label>
                <input  type="text" class="form-control" id="numero" placeholder="digite o numero" maxlength="50"
                        name="numero"  value="<?php echo $controller->getApiweb()->getNumerodestino() ?>"/>

            </div>
            <div class="form-group col-xs-12  col-sm-12 col-md-6  col-lg-6" id="form-bairro">  
                <label for="bairro">Bairro</label>
                <input  type="text" class="form-control" id="bairro" placeholder="digite o bairro" maxlength="50"
                        name="bairro"  value="<?php echo $controller->getApiweb()->getBairrodestino() ?>"/>

            </div>
            <div class="form-group col-xs-12  col-sm-12 col-md-6  col-lg-6" id="form-cidade">  
                <label for="cidade">Cidade</label>
                <input  type="text" class="form-control" id="cidade" placeholder="digite o cidade" maxlength="50"
                        name="cidade"  value="<?php echo $controller->getApiweb()->getCidadedestino() ?>"/>

            </div>
            <div class="form-group col-xs-4  col-sm-6 col-md-3  col-lg-3" id="form-uf">  
                <label for="uf">UF</label> 
                <select class="form-control" rel="select2" name="uf" id="uf" readonly="true">
                    <option value="">Selecione a uf</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "AC" ? "selected='true'" : "") ?> value="AC">AC</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "AL" ? "selected='true'" : "") ?> value="AL">AL</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "AM" ? "selected='true'" : "") ?> value="AM">AM</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "AP" ? "selected='true'" : "") ?> value="AP">AP</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "BA" ? "selected='true'" : "") ?> value="BA">BA</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "CE" ? "selected='true'" : "") ?> value="CE">CE</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "DF" ? "selected='true'" : "") ?> value="DF">DF</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "ES" ? "selected='true'" : "") ?> value="ES">ES</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "GO" ? "selected='true'" : "") ?> value="GO">GO</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "MA" ? "selected='true'" : "") ?> value="MA">MA</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "MG" ? "selected='true'" : "") ?> value="MG">MG</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "MS" ? "selected='true'" : "") ?> value="MS">MS</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "MT" ? "selected='true'" : "") ?> value="MT">MT</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "PA" ? "selected='true'" : "") ?> value="PA">PA</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "PB" ? "selected='true'" : "") ?> value="PB">PB</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "PE" ? "selected='true'" : "") ?> value="PE">PE</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "PI" ? "selected='true'" : "") ?> value="PI">PI</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "PR" ? "selected='true'" : "") ?> value="PR">PR</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "RJ" ? "selected='true'" : "") ?> value="RJ">RJ</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "RN" ? "selected='true'" : "") ?> value="RN">RN</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "RO" ? "selected='true'" : "") ?> value="RO">RO</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "RR" ? "selected='true'" : "") ?> value="RR">RR</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "RS" ? "selected='true'" : "") ?> value="RS">RS</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "SC" ? "selected='true'" : "") ?> value="SC">SC</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "SE" ? "selected='true'" : "") ?> value="SE">SE</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "SP" ? "selected='true'" : "") ?> value="SP">SP</option>
                    <option <?php echo ($controller->getApiweb()->getUfdestino() == "TO" ? "selected='true'" : "") ?> value="TO">TO</option>
                </select>
            </div>
            <div class="form-group col-xs-12  col-sm-6 col-md-3  col-lg-3" id="form-cep">  
                <label for="cep">CEP</label>
                <input  type="text" class="form-control" id="cep" placeholder="digite o cep" maxlength="8"
                        onkeypress="return OnlyNumber(event)"
                        name="cep"  value="<?php echo $controller->getApiweb()->getCepdestino() ?>"/>

            </div>
        </div>
        <div class="form-row ">
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6" id="form-servico">  
                <?php $controller->addServico(true);?>
                <label for="servico">Tipo Serviço</label>
                <select class="form-control" id="servico" name="servico" rel="select2">
                    <option value="">Selecione o serviço</option>
                    <?php foreach ($controller->getServico() as $value) { ?>
                        <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                <?php echo ($controller->getApiweb()->getServico() == $value->getId() ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                            <?php } ?>
                </select>
            </div>
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6" id="form-cartao">  
                <label for="cartao">Número Cartão</label>
                <input  type="text" class="form-control" id="cartao" placeholder="digite o cartao" maxlength="10"
                        onkeypress="return OnlyNumber(event)"
                        name="cartao"  value="<?php echo $controller->getApiweb()->getCartao() ?>"/>

            </div>
        </div>
        <?php if ((crypto::decrypt($codigo) > 0)&&($controller->getApiweb()->getStatus()=="Finalizado")){ ?>
        <div class="form-row ">
            <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 top-20">  
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="reenvio" id="reenvio" value="T">
                    <label class="form-check-label" for="imediata">
                        Cadastrar uma nova postagem para este registro.
                    </label>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="form-row ">
            <div class="form-group col-sm-12 col-md-12 text-center"> 
                <button class="btn btn-success <?php echo ($salvar ? "" : "disabled") ?>" <?php echo ($salvar ? "" : "disabled") ?> id="salvar" rel="tooltip" data-placement="top" type="submit" title="Salvar">Salvar</button>
                <a class="btn btn-danger" rel="tooltip" data-placement="top"  title="Voltar" href="<?php echo "{$pagina}?pag={$pag}&acao=index{$url}"; ?>">Voltar</a>
            </div>
        </div>
    </div>
    <hr class="margim-footer">
</form>
