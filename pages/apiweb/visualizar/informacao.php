<div class="container top-10">
    <div class="form-row">
        <div class="col-md-3 offset-md-1"><b>Peso:</b></div>
        <div class="col-md-7 offset-md-1"><?php echo $this->getApiweb()->getPeso() ?></div>
    </div>
    <div class="form-row">
        <div class="col-md-3 offset-md-1"><b>Valor:</b></div>
        <div class="col-md-7 offset-md-1"><?php echo $this->getApiweb()->getValor() ?></div>
    </div>

    <div class="form-row">
        <div class="col-md-3 offset-md-1"><b>Data cadastro:</b></div>
        <div class="col-md-7 offset-md-1"><?php echo $this->getApiweb()->getDatahora() ?></div>
    </div>  
    <div class="form-row">
        <div class="col-md-3 offset-md-1"><b>Data coleta:</b></div>
        <div class="col-md-7 offset-md-1"><?php echo $this->getApiweb()->getDatacoleta() ?></div>
    </div>
    <div class="form-row">
        <div class="col-md-3 offset-md-1"><b>Data postagem:</b></div>
        <div class="col-md-7 offset-md-1"><?php echo $this->getApiweb()->getDatapostagem() ?></div>
    </div>
    <div class="form-row">
        <div class="col-md-3 offset-md-1"><b>Data entrega:</b></div>
        <div class="col-md-7 offset-md-1"><?php echo $this->getApiweb()->getDataentrega() ?></div>
    </div>
    <div class="form-row">
        <div class="col-md-3 offset-md-1"><b>Status:</b></div>
        <div class="col-md-7 offset-md-1"><?php echo $this->getApiweb()->getStatus() ?></div>
    </div>
    <div class="form-row">
        <div class="col-md-3 offset-md-1"><b>Descrição:</b></div>
        <div class="col-md-7 offset-md-1"><?php echo $this->getApiweb()->getDescricao() ?></div>
    </div>    
</div>

