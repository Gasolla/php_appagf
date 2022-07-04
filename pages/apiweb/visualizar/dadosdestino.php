<div class="container top-10">
    <div class="form-row">
        <div class="col-md-2 offset-md-1"><b>Nome:</b></div>
        <div class="col-md-8 offset-md-1"><?php echo $this->getApiweb()->getNomedestino() ?></div>
    </div>   
    <div class="form-row">
        <div class="col-md-2 offset-md-1"><b>Endereço:</b></div>
        <div class="col-md-8 offset-md-1"><?php echo $this->getApiweb()->getEnderecodestino()." ".$this->getApiweb()->getNumerodestino() ?></div>
    </div>
    <div class="form-row">
        <div class="col-md-2 offset-md-1"><b>Bairro:</b></div>
        <div class="col-md-8 offset-md-1"><?php echo $this->getApiweb()->getBairrodestino() ?></div>
    </div>
    <div class="form-row">
        <div class="col-md-3 offset-md-1"><b>Cidade:</b></div>
        <div class="col-md-8 "><?php echo $this->getApiweb()->getCidadedestino() ?></div>
    </div>
    <div class="form-row">
        <div class="col-md-2 offset-md-1"><b>UF:</b></div>
        <div class="col-md-8 offset-md-1"><?php echo $this->getApiweb()->getUfdestino() ?></div>
    </div>
    <div class="form-row">
        <div class="col-md-2 offset-md-1"><b>CEP:</b></div>
        <div class="col-md-8 offset-md-1"><?php echo $this->getApiweb()->getCepdestino() ?></div>
    </div>
    
</div>

