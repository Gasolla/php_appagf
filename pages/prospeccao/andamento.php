<div class="container top-10">
    <h2>Histórico do contato</h2>
    <hr>
    <div id="div-andamento">
        <?php if (count($controller->getProspeccaohistorico())===0) { ?>
            <div class="bg-light text-dark card-andamento">
                <div class="card-header clearfix">
                    <span class="float-left"><b>Nenhumm histórico encontrado.</b> </span>
                </div>
            </div>
        <?php } ?>
        <?php foreach ($controller->getProspeccaohistorico() as $key => $value) { ?>
            <div class="bg-dark card-andamento">
                <div class="card-header clearfix">
                    <span class="float-left"><b>Contato:</b> <?php echo $key+1 ?></span>
                    <span class="float-right"><b>Data:</b> <?php echo $value->getData() ?></span>
                </div>
                <div class="card-body">
                    <p><span><b>Situação: </b><?php echo ($value->getOcorrencia()) ?></span></p>
                    <p><span><b>Motivo: </b><?php echo ($value->getMotivo()) ?></span></p>
                    <p><span><b>Data para novo contato: </b><?php echo($value->getNovadata()) ?></span></p>
                    <p><span><b>Comentario:</b></span></p>
                    <p><?php echo utf8_decode($value->getComentario()) ?></p>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
