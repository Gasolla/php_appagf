<div class="form-row" >
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
                       value="<?php echo $controller->getProspeccao()->getEnderecoExtenso() ?>"
                       placeholder="Informe o endereço para busca" />
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-xs-4  col-sm-4 col-md-3  col-lg-3" id="form-cep">  
                <label for="cep">CEP</label>
                <input  type="text" class="form-control" id="cep" readonly="true" placeholder="CEP"  maxlength="9" 
                        name="cep"  value="<?php echo $controller->getProspeccao()->getCep() ?>"/>
            </div>
            <div class="form-group col-xs-8  col-sm-8 col-md-9  col-lg-9" id="form-bairro">  
                <label for="bairro">Bairro</label>
                <input  type="text" class="form-control" id="bairro" readonly="true" placeholder="Bairro"  maxlength="100"
                        name="bairro"  value="<?php echo $controller->getProspeccao()->getBairro() ?>"/>
            </div>     
        </div>
        <div class="form-row">
            <div class="form-group col-xs-12  col-sm-12 col-md-12  col-lg-12" id="form-rua">  
                <label for="rua">Rua</label>
                <input  type="text" class="form-control" id="rua" readonly="true" placeholder="Rua/Avenida"  maxlength="100"
                        name="rua"  value="<?php echo $controller->getProspeccao()->getRua() ?>"/>
            </div>  
        </div>
        <div class="form-row">
            <div class="form-group col-xs-6  col-sm-6 col-md-5  col-lg-5" id="form-numero">  
                <label for="cep">Numero</label>
                <input  type="text" class="form-control" id="numero" readonly="true"  placeholder="Número"  maxlength="30" 
                        name="numero"  value="<?php echo $controller->getProspeccao()->getNumero() ?>"/>
            </div>
            <div class="form-group col-xs-6  col-sm-6 col-md-7  col-lg-7" id="form-complemento">  
                <label for="rua">Complemento</label>
                <input  type="text" class="form-control" id="complemento" readonly="true"  placeholder="Complemento"  maxlength="50"
                        name="complemento"  value="<?php echo $controller->getProspeccao()->getComplemento() ?>"/>
            </div>     
        </div>
        <div class="form-row">
            <div class="form-group col-xs-8  col-sm-8 col-md-9  col-lg-9"  id="form-cidade">  
                <label for="cidade">Cidade</label>
                <input  type="text" class="form-control" id="cidade"  placeholder="Cidade"  maxlength="100" readonly="true"
                        name="cidade"  value="<?php echo $controller->getProspeccao()->getCidade() ?>"/>
            </div> 
            <div class="form-group col-xs-4  col-sm-4 col-md-3  col-lg-3" id="form-uf">  
                <label for="uf">UF</label>
                <select class="form-control" rel="select2" name="uf" id="uf" readonly="true">
                    <option value=""></option>
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
    <div class="col-xs-12  col-sm-12 col-md-6  col-lg-6">
        <div id="mapa">

        </div>
    </div>
</div>
<input type="hidden" id="txtLatitude" name="txtLatitude"  value="<?php echo $controller->getProspeccao()->getLatitude() ?>"/>
<input type="hidden" id="txtLongitude" name="txtLongitude" value="<?php echo $controller->getProspeccao()->getLongitude() ?>" />
<input type="hidden" id="estado" name="estado" value="<?php echo $controller->getProspeccao()->getUF() ?>" />
