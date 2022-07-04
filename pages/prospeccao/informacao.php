<div class="form-row <?php echo ($usuarioacesso->Agencia === 0 ? "d-flex" : "d-none") ?>">
    <?php $controller->addAgencia(); ?>
    <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6" id="form-agencia"> 
        <label for="agencia">Agência</label>
        <select name="agencia" id="agencia" rel="select2" class="form-control">
            <option value="">Selecione a agência</option>
            <?php foreach ($controller->getAgencia() as $value) { ?>
                <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                        <?php echo ($controller->getProspeccao()->getAgencia() == $value->getId() ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                    <?php } ?>
        </select>
    </div>
     <div class="form-group col-xs-12  col-sm-6 col-md-6  col-lg-6"> 
        <label for="comercial">Comercial</label>
        <input type="text" name="comercial" readonly="true" id="comercial" class="form-control" value="<?php echo $controller->getProspeccao()->getUsuario() ?>"/>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-xs-12  col-sm-12 col-md-8  col-lg-8"> 
        <div class="form-row">
            <div class="form-group col-xs-12  col-sm-12 col-md-12  col-lg-12" id="form-nome">  
                <label for="nome">Nome Cliente</label>
                <input  type="text" class="form-control" id="nome" placeholder="digite o nome do cliente" maxlength="100"
                        name="nome"  value="<?php echo $controller->getProspeccao()->getNome() ?>"/>

            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-xs-12  col-sm-12 col-md-12  col-lg-12" id="form-contato">  
                <label for="contato">Nome Contato</label>
                <input  type="text" class="form-control" id="contato" placeholder="digite o nome do contato" maxlength="100"
                        name="contato"  value="<?php echo $controller->getProspeccao()->getContato() ?>"/>

            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-xs-12  col-sm-12 col-md-7  col-lg-7" id="form-email">  
                <label for="email">Email</label>
                <input  type="text" class="form-control" id="email"  placeholder="digite o email"  maxlength="100"  onblur="ValidaEmail(this)"
                        name="email"  value="<?php echo $controller->getProspeccao()->getEmail() ?>"/>
            </div>
            <div class="form-group col-xs-12  col-sm-12 col-md-5  col-lg-5" id="form-telefone">  
                <label for="telefone">Telefone</label>
                <input  type="text" class="form-control" id="telefone"  placeholder="digite o telefone"  maxlength="14" rel="telefone"
                        name="telefone"  value="<?php echo $controller->getProspeccao()->getFone() ?>"/>
            </div>   
        </div>
        <div class="form-row">
            <div class="form-group col-xs-12  col-sm-12 col-md-7  col-lg-7" id="form-ramo">  
                <label for="ramo">Ramo Atividade</label>
                <input  type="text" class="form-control" id="ramo"  placeholder="digite o ramo de atividade"  maxlength="50" 
                        name="ramo"  value="<?php echo $controller->getProspeccao()->getRamo() ?>"/>
            </div>
            <div class="form-group col-xs-12  col-sm-12 col-md-5  col-lg-5" id="form-volume">  
                <label for="volume">Volume Medio</label>
                <input  type="text" class="form-control" id="volume"  placeholder="digite o volume"  maxlength="20" 
                        name="volume"  value="<?php echo $controller->getProspeccao()->getVolume() ?>"/>
            </div>   
        </div>
        <div class="form-group col-xs-12  col-sm-12 col-md-12  col-lg-12"> 
            <label for="comentario">Comentário</label>
            <textarea class="form-control" rows="5" name="comentario" id="comentario"><?php echo $controller->getProspeccao()->getComentario() ?></textarea>
        </div>
    </div>
    <div class="form-group col-xs-12  col-sm-12 col-md-4  col-lg-4"> 
        <div class="form-row">
            <div class="form-group col-xs-12  col-sm-12 col-md-12  col-lg-12" id="form-datacontato">  
                <label for="datacontato">Data do contato</label><!--Este campo Pesquisa na Tabela notificação (DtHr)-->
                <div class="input-group date contato" data-provide="datepicker" data-date-format="dd/mm/yyyy" 
                     data-date-language="pt-BR" data-date-autoclose="true" 
                     data-date-today-highlight="true" data-date-orientation="bottom"> 
                    <input  type="text" class="form-control datepicker" id="datacontato" placeholder="Data do contato" 
                            name="datacontato" maxlength="10"
                            value="<?php echo ((($controller->getProspeccao()->getDatacontato() != "")) ? $controller->getProspeccao()->getDatacontato() : date("d/m/Y")) ?>"/>
                    <div class="input-group-addon imput-calendario">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div> 
                <script>
                    $('.contato').datepicker({
                        startDate: "-3d",
                        endDate: "0d",
                        daysOfWeekDisabled: "0"
                    });
                </script>
            </div>
            <div class="form-group col-xs-12  col-sm-12 col-md-12  col-lg-12" id="form-ocorrencia">  
                <h4>Informações do contato</h4>
                <hr>
                <div class="form-check">
                    <input <?php echo ($controller->getProspeccao()->getOcorrencia() == "N" ? "checked" : "") ?>
                        class="form-check-input" type="radio" name="ocorrencia" id="ocorrencia1" value="N" onclick="seleciona_motivo(false, 'form-naofechado', this)">
                    <label class="form-check-label" for="ocorrencia1">
                        Postagem outra AGF
                    </label>
                </div>
                <div class="form-check">
                    <input <?php echo ($controller->getProspeccao()->getOcorrencia() == "S" ? "checked" : "") ?>
                        class="form-check-input" type="radio" name="ocorrencia" id="ocorrencia2" value="S" onclick="seleciona_motivo(false, 'form-naofechado', this)">
                    <label class="form-check-label" for="ocorrencia2">
                        Fechado
                    </label>
                </div>
                <div class="form-check">
                    <input <?php echo ($controller->getProspeccao()->getOcorrencia() == "C" ? "checked" : "") ?>
                        class="form-check-input" type="radio" name="ocorrencia" id="ocorrencia3" value="C" onclick="seleciona_motivo(true, 'form-naofechado', this)">
                    <label class="form-check-label" for="ocorrencia3">
                        Não Fechado
                    </label>
                </div>
                <?php if (($controller->getProspeccao()->getOcorrencia() == "R")){ ?>
                <script>setContato(false);</script>
                <?php } ?>
                <div class="form-check">
                    <input <?php echo ($controller->getProspeccao()->getOcorrencia() == "R" ? "checked" : "") ?>
                        class="form-check-input" type="radio" name="ocorrencia" id="ocorrencia4" value="R" onclick="seleciona_motivo(true, 'form-naofechado', this)">
                    <label class="form-check-label" for="ocorrencia4">
                        Não Atendeu Telefone
                    </label>
                </div>
                <div class="form-row <?php echo (in_array($controller->getProspeccao()->getOcorrencia() ,array("C", "R")) ? "d-flex" : "d-none") ?> " id="form-naofechado">
                    <div class="form-group col-xs-11  col-sm-11 col-md-11  col-lg-11 offset-xs-1 offset-md-1 offset-lg-1 offset-md-1">  
                        <hr>
                        <h6>Motivo <b>Não</b> <i>"Fechado/Atendeu Telefone"</i></h6>
                        <hr>
                        <div class="form-check">
                            <input <?php echo ($controller->getProspeccao()->getNaofechado() == "N" ? "checked" : "") ?>
                                class="form-check-input" type="radio" name="naofechado" id="naofechado1" value="N">
                            <label class="form-check-label" for="naofechado1">
                                Sem interesse no momento
                            </label>
                        </div>
                        <div class="form-check">
                            <input <?php echo ($controller->getProspeccao()->getNaofechado() == "A" ? "checked" : "") ?>
                                class="form-check-input" type="radio" name="naofechado" id="naofechado2" value="A">
                            <label class="form-check-label" for="naofechado2">
                                Em processo de avaliação 
                            </label>
                        </div>
                        <div class="form-check">
                            <input <?php echo ($controller->getProspeccao()->getNaofechado() == "V" ? "checked" : "") ?>
                                class="form-check-input" type="radio" name="naofechado" id="naofechado3" value="V">
                            <label class="form-check-label" for="naofechado3">
                                Verificando com responsável 
                            </label>
                        </div>
                        <div class="form-check">
                            <input <?php echo ($controller->getProspeccao()->getNaofechado() == "R" ? "checked" : "") ?>
                                class="form-check-input" type="radio" name="naofechado" id="naofechado4" value="R">
                            <label class="form-check-label" for="naofechado4">
                                Não Atendeu Telefone 
                            </label>
                        </div>
                        <div class="form-group top-15" id="form-datanovo">  
                            <label for="datanovo">Data novo contato</label><!--Este campo Pesquisa na Tabela notificação (DtHr)-->
                            <div class="input-group date bloqueio" data-provide="datepicker" data-date-format="dd/mm/yyyy" 
                                 data-date-language="pt-BR" data-date-autoclose="true" 
                                 data-date-today-highlight="true" data-date-orientation="bottom"> 
                                <input  type="text" class="form-control datepicker" id="datanovo" placeholder="Data novo contato" 
                                        name="datanovo" maxlength="10"
                                        value="<?php echo ((($controller->getProspeccao()->getDatanovo() != "")) ? $controller->getProspeccao()->getDatanovo() : date("d/m/Y")) ?>"/>
                                <div class="input-group-addon imput-calendario">
                                    <i class="fas fa-calendar-alt fa-2x"></i>
                                </div>
                            </div> 
                            <script>
                                $('.bloqueio').datepicker({
                                    startDate: "0d",
                                    endDate: "+60d",
                                    daysOfWeekDisabled: "0"
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
