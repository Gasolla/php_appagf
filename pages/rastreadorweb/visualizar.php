<div class="row" >
    <div class="col-md-12 col-sm-12">
        <h6  class="h6 float-left">Protocolo : </b> <?php echo $this->getRastreadorweb()->getId(); ?></h6>
        <h6  class="h6 float-right"><?php echo (($this->getRastreadorweb()->getObjeto() !== null) ? "Objeto: " : "") ?></b> <?php echo $this->getRastreadorweb()->getObjeto(); ?></h6>
    </div>
    <div class="col-md-12 col-sm-12">
        <hr>
    </div>
</div>
<div class="panel-body top-10">
    <ul class="nav nav-tabs" id="tabvisualizar" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="dadosdestino-tab" data-toggle="tab" href="#dadosdestino" role="tab" aria-controls="dadosdestino" aria-selected="true">Destinatario</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="rastreamento-tab" data-toggle="tab" href="#rastreamento" role="tab" aria-controls="rastreamento" aria-selected="false">Rastreamento</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="dadosdestino" role="tabpanel" aria-labelledby="dadosdestino-tab">
            <?php include 'visualizar/dadosdestino.php'; ?>
        </div>
        
        <div class="tab-pane fade" id="rastreamento" role="tabpanel" aria-labelledby="rastreamento-tab">
            <?php include 'visualizar/rastreamento.php'; ?>
        </div>
    </div>
</div>
