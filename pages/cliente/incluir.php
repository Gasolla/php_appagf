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
        <h3  class="h3-1 float-left">Incluir Cliente</h3>
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
                <div class="form-row <?php echo ($usuarioacesso->Agencia === 0 ? "d-flex" : "d-none") ?>">
                    <label for="agencia">Agência</label>
                    <select name="agencia" id="agencia" rel="select2" class="form-control"
                            onchange='onChangeAgencia("<?php echo "{$pagina}?pag={$pag}"; ?>&acao=incluir<?php echo (isset($_REQUEST['codigo']) ? "&codigo={$_REQUEST['codigo']}" : "") ?><?php echo $url ?>", this)'>
                        <option value="">Selecione a agência</option>
                        <?php foreach ($controller->getAgencia() as $value) { ?>
                            <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                    <?php echo ($controller->getCliente()->getAgencia() == $value->getId() ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                                <?php } ?>
                    </select>
                </div>
            </div>
            <?php $controller->addComercial(true); ?>
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6" id="form-comercial">  
                <label for="comercial">Comercial</label>
                <select name="comercial" id="comercial" rel="select2" class="form-control">
                    <option value="">Selecione o comercial</option>
                    <?php foreach ($controller->getComercial() as $value) { ?>
                        <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                <?php echo ($controller->getCliente()->getComercial() == $value->getId() ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                            <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-xs-12  col-sm-8 col-md-9  col-lg-9" id="form-nome">  
                <label for="nome">Nome</label>
                <input  type="text" class="form-control" id="nome" placeholder="digite o nome"
                        name="nome"  value="<?php echo $controller->getCliente()->getNome() ?>"/>

            </div>
            <div class="form-group col-xs-12  col-sm-4 col-md-3  col-lg-3" id="form-microvisual">  
                <label for="microvisual">Cód. Microvisual</label>
                <input  type="text" class="form-control" id="microvisual" placeholder="digite o codigo"
                        name="microvisual"  value="<?php echo $controller->getCliente()->getMicrovisual() ?>"/>

            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-xs-6  col-sm-6 col-md-8  col-lg-8" id="form-apelido">  
                <label for="apelido">Nome Fantasia</label>
                <input  type="text" class="form-control" id="apelido" placeholder="digite o nome fantasia"
                        name="apelido"  value="<?php echo $controller->getCliente()->getApelido() ?>"/>

            </div>
            <div class="form-group col-xs-6  col-sm-6 col-md-4  col-lg-4" id="form-cpfcnpj">  
                <label for="cpfcnpj">CPF/CNPJ</label>
                <input  type="text" class="form-control" id="cpfcnpj"  placeholder="digite o cpf ou cnpj" onblur="onBlurcpfcnpj(this)"
                        name="cpfcnpj" maxlength="18" value="<?php echo $controller->getCliente()->getDocumento() ?>"/>
            </div>     
        </div>
        <div class="form-row">
            <div class="form-group col-xs-12  col-sm-12 col-md-6  col-lg-6" id="form-email">  
                <label for="email">Email</label>
                <input  type="text" class="form-control" id="email"  placeholder="digite o email"  maxlength="100"  onblur="ValidaEmail(this)"
                        name="email"  value="<?php echo $controller->getCliente()->getEmail() ?>"/>
            </div>
            <div class="form-group col-xs-12  col-sm-6 col-md-3  col-lg-3" id="form-telefone">  
                <label for="telefone">Telefone</label>
                <input  type="text" class="form-control" id="telefone"  placeholder="digite o telefone"  maxlength="14" rel="telefone"
                        name="telefone"  value="<?php echo $controller->getCliente()->getFone() ?>"/>
            </div>   
            <div class="form-group col-xs-12  col-sm-6 col-md-3  col-lg-3" id="form-motoqueiro">  
                <?php $controller->addMotorista(); ?>
                <label for="motoqueiro">Motorista</label>
                <select class="form-control" id="motoqueiro" name="motoqueiro" rel="select2">
                    <option value="">Selecione o motorista</option>
                    <?php
                        foreach ($controller->getMotorista() as $value) {
                            ?>
                            <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                    <?php echo ((($controller->getCliente()->getMotoqueiro() == $value->getId())) ? "selected='true'" : "") ?> ><?php echo $value->getNome() ?></option> 
                                    <?php
                                }
                           ?>
                </select>
            </div>    
        </div>
        <div class="row" >
            <div class="col-md-12 col-sm-12">
                <h6  class="h3-1">Informações do endereço</h6>
            </div>
            <div class="col-md-12 col-sm-12">
                <hr>
            </div>
            <div class="col-xs-12  col-sm-12 col-md-6  col-lg-6">
                <div class="form-row">
                    <div class="campos form-group col-xs-12  col-sm-12 col-md-12  col-lg-12">
                        <input type="text" class="form-control" id="txtEndereco" name="txtEndereco" 
                               value="<?php echo $controller->getEndereco()->getEnderecoExtenso() ?>"
                               placeholder="Informe o endereço para busca" />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-xs-4  col-sm-4 col-md-3  col-lg-3" id="form-cep">  
                        <label for="cep">CEP</label>
                        <input  type="text" class="form-control" id="cep" readonly="true" placeholder="CEP"  maxlength="9" 
                                name="cep"  value="<?php echo $controller->getEndereco()->getCep() ?>"/>
                    </div>
                    <div class="form-group col-xs-8  col-sm-8 col-md-9  col-lg-9" id="form-bairro">  
                        <label for="bairro">Bairro</label>
                        <input  type="text" class="form-control" id="bairro" readonly="true" placeholder="Bairro"  maxlength="100"
                                name="bairro"  value="<?php echo $controller->getEndereco()->getBairro() ?>"/>
                    </div>     
                </div>
                <div class="form-row">
                    <div class="form-group col-xs-12  col-sm-12 col-md-12  col-lg-12" id="form-rua">  
                        <label for="rua">Rua</label>
                        <input  type="text" class="form-control" id="rua" readonly="true" placeholder="Rua/Avenida"  maxlength="100"
                                name="rua"  value="<?php echo $controller->getEndereco()->getRua() ?>"/>
                    </div>  
                </div>
                <div class="form-row">
                    <div class="form-group col-xs-6  col-sm-6 col-md-5  col-lg-5" id="form-numero">  
                        <label for="cep">Numero</label>
                        <input  type="text" class="form-control" id="numero"  placeholder="Número"  maxlength="30" 
                                name="numero"  value="<?php echo $controller->getEndereco()->getNumero() ?>"/>
                    </div>
                    <div class="form-group col-xs-6  col-sm-6 col-md-7  col-lg-7" id="form-complemento">  
                        <label for="rua">Complemento</label>
                        <input  type="text" class="form-control" id="complemento"  placeholder="Complemento"  maxlength="50"
                                name="complemento"  value="<?php echo $controller->getEndereco()->getComplemento() ?>"/>
                    </div>     
                </div>
                <div class="form-row">
                    <div class="form-group col-xs-8  col-sm-8 col-md-9  col-lg-9" readonly="true" id="form-cidade">  
                        <label for="cidade">Cidade</label>
                        <input  type="text" class="form-control" id="cidade"  placeholder="Cidade"  maxlength="100"
                                name="cidade"  value="<?php echo $controller->getEndereco()->getCidade() ?>"/>
                    </div> 
                    <div class="form-group col-xs-4  col-sm-4 col-md-3  col-lg-3" id="form-uf">  
                        <label for="uf">UF</label>
                        <select class="form-control" rel="select2" name="uf" id="uf" readonly="true">
                            <option value="">Selecione a uf</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "AC" ? "selected='true'" : "") ?> value="AC">AC</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "AL" ? "selected='true'" : "") ?> value="AL">AL</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "AM" ? "selected='true'" : "") ?> value="AM">AM</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "AP" ? "selected='true'" : "") ?> value="AP">AP</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "BA" ? "selected='true'" : "") ?> value="BA">BA</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "CE" ? "selected='true'" : "") ?> value="CE">CE</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "DF" ? "selected='true'" : "") ?> value="DF">DF</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "ES" ? "selected='true'" : "") ?> value="ES">ES</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "GO" ? "selected='true'" : "") ?> value="GO">GO</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "MA" ? "selected='true'" : "") ?> value="MA">MA</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "MG" ? "selected='true'" : "") ?> value="MG">MG</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "MS" ? "selected='true'" : "") ?> value="MS">MS</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "MT" ? "selected='true'" : "") ?> value="MT">MT</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "PA" ? "selected='true'" : "") ?> value="PA">PA</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "PB" ? "selected='true'" : "") ?> value="PB">PB</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "PE" ? "selected='true'" : "") ?> value="PE">PE</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "PI" ? "selected='true'" : "") ?> value="PI">PI</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "PR" ? "selected='true'" : "") ?> value="PR">PR</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "RJ" ? "selected='true'" : "") ?> value="RJ">RJ</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "RN" ? "selected='true'" : "") ?> value="RN">RN</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "RO" ? "selected='true'" : "") ?> value="RO">RO</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "RR" ? "selected='true'" : "") ?> value="RR">RR</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "RS" ? "selected='true'" : "") ?> value="RS">RS</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "SC" ? "selected='true'" : "") ?> value="SC">SC</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "SE" ? "selected='true'" : "") ?> value="SE">SE</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "SP" ? "selected='true'" : "") ?> value="SP">SP</option>
                            <option <?php echo ($controller->getEndereco()->getUF() == "TO" ? "selected='true'" : "") ?> value="TO">TO</option>
                        </select>
                    </div>   
                </div>
            </div>
            <div class="col-xs-12  col-sm-12 col-md-6  col-lg-6">
                <div id="mapa">

                </div>
            </div>
        </div>
        <input type="hidden" id="txtLatitude" name="txtLatitude"  value="<?php echo $controller->getEndereco()->getLatitude() ?>"/>
        <input type="hidden" id="txtLongitude" name="txtLongitude" value="<?php echo $controller->getEndereco()->getLongitude() ?>" />
        <input type="hidden" id="estado" name="estado" value="<?php echo $controller->getEndereco()->getUF() ?>" />
        <div class="form-row ">
            <div class="form-group col-sm-12 col-md-12 text-center"> 
                <button class="btn btn-success <?php echo ($salvar ? "" : "disabled") ?>" <?php echo ($salvar ? "" : "disabled") ?> id="salvar" rel="tooltip" data-placement="top" type="submit" title="Salvar">Salvar</button>
                <a class="btn btn-danger" rel="tooltip" data-placement="top"  title="Voltar" href="<?php echo "{$pagina}?pag={$pag}&acao=index{$url}"; ?>">Voltar</a>
            </div>
        </div>
    </div>
    <hr>
</form>
<script>Incia_Varial('<?php echo $controller->getEndereco()->getLatitude() ?>', '<?php echo $controller->getEndereco()->getLongitude() ?>')</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCMeZs26mvBa7A6iUj7-kAUWuMZkVy_Jmw&callback=<?php echo (crypto::decrypt($codigo) > 0 ? "Carrega_Mapa" : "Carrega_Mapa_Inicial") ?>&lenguage=BR&region=BR"></script>

