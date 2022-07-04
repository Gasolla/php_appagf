<div class="container top-10">
    <div class="form-row">
        <div class="col-md-3 offset-md-1"><b>Nome:</b></div>
        <div class="col-md-8 offset-md-1"><?php echo $this->getRastreadorweb()->getNomedestino() ?></div>
    </div>   
    <div class="form-row">
        <div class="col-md-3 offset-md-1"><b>CEP:</b></div>
        <div class="col-md-7 offset-md-1"><?php echo $this->getRastreadorweb()->getCepdestino() ?></div>
    </div>
    <div class="form-row">
        <div class="col-md-3 offset-md-1"><b>Data cadastro:</b></div>
        <div class="col-md-7 offset-md-1"><?php echo $this->getRastreadorweb()->getDatahora() ?></div>
    </div>  
    <div class="form-row">
        <div class="col-md-3 offset-md-1"><b>Data postagem:</b></div>
        <div class="col-md-7 offset-md-1"><?php echo $this->getRastreadorweb()->getDatapostagem() ?></div>
    </div>
    <div class="form-row">
        <div class="col-md-3 offset-md-1"><b>Data entrega:</b></div>
        <div class="col-md-7 offset-md-1"><?php echo $this->getRastreadorweb()->getDataentrega() ?></div>
    </div>
    <div class="form-row">
        <div class="col-md-3 offset-md-1"><b>Status:</b></div>
        <div class="col-md-7 offset-md-1"><?php echo $this->getRastreadorweb()->getStatussro() ?></div>
    </div>
    <div class="form-row">
        <div class="col-md-3 offset-md-1"><b>Descrição:</b></div>
        <div class="col-md-7 offset-md-1"><?php echo $this->getRastreadorweb()->getDescricao() ?></div>
    </div> 
</div>

