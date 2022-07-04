<div class="row" >
    <div class="col-md-12 col-sm-12">
        <h6  class="h6 float-left">Protocolo : </b> <?php echo $this->getApiweb()->getId(); ?></h6>
        <h6  class="h6 float-right"><?php echo (($this->getApiweb()->getObjeto() !== null) ? "Objeto: " : "") ?></b> <?php echo $this->getApiweb()->getObjeto(); ?></h6>
    </div>
    <div class="col-md-12 col-sm-12">
        <hr>
    </div>
</div>
<div class="row" >
<div class="col-md-12 col-sm-12">
    <input type="hidden" name="imprimiu" id="imprimiu" value="<?php echo $this->getApiweb()->getImpressao() ?>"/>
    <button onclick="<?php echo ($this->usuarioacesso->Imprimir?"Imprimirpost('". crypto::encrypt($this->getApiweb()->getId())."', 'apiweb')":"") ?>" <?php echo ($this->usuarioacesso->Imprimir?"":"disabled='true'") ?> class="btn btn-danger texto-branco <?php echo ($this->usuarioacesso->Imprimir?"":"disabled") ?>"><i class="fas fa-file-pdf" aria-hidden="true"></i> Imprimir Etiqueta</button>
    <button id="btnsolicitar"  onclick="<?php echo (($this->usuarioacesso->Solicitar)?"Solicitarpost('". crypto::encrypt($this->getApiweb()->getId())."', 'apiweb')":"") ?>" <?php echo (($this->usuarioacesso->Solicitar)&&(!$this->getApiweb()->getStatusagendamento())?"":"disabled='true'") ?> class="btn btn-info texto-branco <?php echo (($this->usuarioacesso->Solicitar)?"":"disabled") ?>"><i class="fas fa-truck" aria-hidden="true"></i> Solicitar Coleta</button>
</div>
</div>
<div class="panel-body top-10">
    <ul class="nav nav-tabs" id="tabvisualizar" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="dadosdestino-tab" data-toggle="tab" href="#dadosdestino" role="tab" aria-controls="dadosdestino" aria-selected="true">Destinatario</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="dadosremetente-tab" data-toggle="tab" href="#dadosremetente" role="tab" aria-controls="dadosremetente" aria-selected="false">Remetente</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="informacao-tab" data-toggle="tab" href="#informacao" role="tab" aria-controls="informacao" aria-selected="false">Status</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="rastreamento-tab" data-toggle="tab" href="#rastreamento" role="tab" aria-controls="rastreamento" aria-selected="false">Rastreamento</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="dadosdestino" role="tabpanel" aria-labelledby="dadosdestino-tab">
            <?php include 'visualizar/dadosdestino.php'; ?>
        </div>
        <div class="tab-pane fade" id="dadosremetente" role="tabpanel" aria-labelledby="dadosremetente-tab">
            <?php include 'visualizar/dadosremetente.php'; ?>
        </div>
        <div class="tab-pane fade" id="informacao" role="tabpanel" aria-labelledby="informacao-tab">
            <?php include 'visualizar/informacao.php'; ?>
        </div>
         <div class="tab-pane fade" id="rastreamento" role="tabpanel" aria-labelledby="rastreamento-tab">
            <?php include 'visualizar/rastreamento.php'; ?>
        </div>
    </div>
</div>
