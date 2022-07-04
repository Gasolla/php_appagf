<?php
$codigo = (filter_input(INPUT_GET, 'codigo') ?: 0);
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
        <h3  class="h3-1 float-left">Incluir Lista Prospecção</h3>
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
        <div class="form-row <?php echo ($usuarioacesso->Agencia === 0 ? "d-flex" : "d-none") ?>">
            <?php $controller->addAgencia(); ?>
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6 <?php echo ($usuarioacesso->Agencia === 0 ? "" : "d-none") ?>" id="form-agencia"> 
                <label for="agencia">Agência</label>
                <select name="agencia" id="agencia" rel="select2" class="form-control">
                    <option value="">Selecione a agência</option>
                    <?php foreach ($controller->getAgencia() as $value) { ?>
                        <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                <?php echo ($controller->getProspeccao()->getAgencia() == $value->getId() ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                            <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-xs-12  col-sm-12 col-md-12  col-lg-12" id="form-nome">  
                <label for="nome">Nome</label>
                <input  type="text" class="form-control" id="nome" placeholder="digite o nome" maxlength="100"
                        name="nome"  value="<?php echo $controller->getProspeccao()->getNome() ?>"/>

            </div>    
        </div>
        <div class="form-row">
            <div class="form-group col-xs-12  col-sm-7 col-md-9  col-lg-9" id="form-email">  
                <label for="email">Email</label>
                <input  type="text" class="form-control" id="email"  placeholder="digite o email"  maxlength="100"  onblur="ValidaEmail(this)"
                        name="email"  value="<?php echo $controller->getProspeccao()->getEmail() ?>"/>
            </div>
            <div class="form-group col-xs-12  col-sm-5 col-md-3  col-lg-3" id="form-telefone">  
                <label for="telefone">Telefone</label>
                <input  type="text" class="form-control" id="telefone"  placeholder="digite o telefone"  maxlength="14" rel="telefone"
                        name="telefone"  value="<?php echo $controller->getProspeccao()->getFone() ?>"/>
            </div>   

        </div>
        <div class="form-row" >
            <div class="col-md-12 col-sm-12">
                <h6  class="h3-1">Informações do endereço</h6>
            </div>
            <div class="col-md-12 col-sm-12">
                <hr>
            </div>
            <div class="col-xs-12  col-sm-12 col-md-12  col-lg-12">
                <div class="form-row">
                    <div class="campos form-group col-xs-12  col-sm-12 col-md-12  col-lg-12">
                        <label for="txtEndereco">Endereço</label>
                        <input type="text" class="form-control" id="txtEndereco" name="txtEndereco" 
                               value="<?php echo $controller->getProspeccao()->getEnderecoExtenso() ?>"
                               placeholder="Digite o endereço" />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-xs-8  col-sm-8 col-md-9  col-lg-9" readonly="true" id="form-cidade">  
                        <label for="cidade">Cidade</label>
                        <input  type="text" class="form-control" id="cidade"  placeholder="Cidade"  maxlength="100"
                                name="cidade"  value="<?php echo $controller->getProspeccao()->getCidade() ?>"/>
                    </div> 
                    <div class="form-group col-xs-4  col-sm-4 col-md-3  col-lg-3" id="form-uf">  
                        <label for="uf">UF</label>
                        <select class="form-control" rel="select2" name="uf" id="uf">
                            <option value="">Selecione a uf</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "AC" ? "selected='true'" : "") ?> value="AC">AC</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "AL" ? "selected='true'" : "") ?> value="AL">AL</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "AM" ? "selected='true'" : "") ?> value="AM">AM</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "AP" ? "selected='true'" : "") ?> value="AP">AP</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "BA" ? "selected='true'" : "") ?> value="BA">BA</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "CE" ? "selected='true'" : "") ?> value="CE">CE</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "DF" ? "selected='true'" : "") ?> value="DF">DF</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "ES" ? "selected='true'" : "") ?> value="ES">ES</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "GO" ? "selected='true'" : "") ?> value="GO">GO</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "MA" ? "selected='true'" : "") ?> value="MA">MA</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "MG" ? "selected='true'" : "") ?> value="MG">MG</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "MS" ? "selected='true'" : "") ?> value="MS">MS</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "MT" ? "selected='true'" : "") ?> value="MT">MT</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "PA" ? "selected='true'" : "") ?> value="PA">PA</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "PB" ? "selected='true'" : "") ?> value="PB">PB</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "PE" ? "selected='true'" : "") ?> value="PE">PE</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "PI" ? "selected='true'" : "") ?> value="PI">PI</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "PR" ? "selected='true'" : "") ?> value="PR">PR</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "RJ" ? "selected='true'" : "") ?> value="RJ">RJ</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "RN" ? "selected='true'" : "") ?> value="RN">RN</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "RO" ? "selected='true'" : "") ?> value="RO">RO</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "RR" ? "selected='true'" : "") ?> value="RR">RR</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "RS" ? "selected='true'" : "") ?> value="RS">RS</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "SC" ? "selected='true'" : "") ?> value="SC">SC</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "SE" ? "selected='true'" : "") ?> value="SE">SE</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "SP" ? "selected='true'" : "") ?> value="SP">SP</option>
                            <option <?php echo ($controller->getProspeccao()->getUF() == "TO" ? "selected='true'" : "") ?> value="TO">TO</option>
                        </select>
                    </div>   
                </div>
            </div>
        </div>
        <div class="form-row <?php echo ((crypto::decrypt($codigo) > 0) ? "d-flex" : "d-none") ?> " >
            <div class="col-md-12 col-sm-12">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="visitar" id="visitar" value="F">
                    <label class="form-check-label" for="visitar">
                        Prospecção já faz parte de nossa carteira de cliente.
                    </label>
                </div>
            </div>
            <div class="col-md-12 col-sm-12">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="visitar" id="visitar" value="T">
                    <label class="form-check-label" for="visitar">
                        Marque aqui par realizar a prospecção.
                    </label>
                </div>
            </div>
        </div>
        <div class="form-row ">
            <div class="form-group col-sm-12 col-md-12 text-center"> 
                <button class="btn btn-success <?php echo ($salvar ? "" : "disabled") ?>" <?php echo ($salvar ? "" : "disabled") ?> id="salvar" rel="tooltip" data-placement="top" type="submit" title="Salvar">Salvar</button>
                <a class="btn btn-danger" rel="tooltip" data-placement="top"  title="Voltar" href="<?php echo "{$pagina}?pag={$pag}&acao=index{$url}"; ?>">Voltar</a>
            </div>
        </div>
    </div>
    <hr>
</form>

