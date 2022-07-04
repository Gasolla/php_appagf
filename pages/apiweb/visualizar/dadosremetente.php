<div class="container top-10">
    <div class="form-row">
        <div class="col-md-2 offset-md-1"><b>Nome:</b></div>
        <div class="col-md-8 offset-md-1"><?php echo $this->getApiweb()->getNomeremetente() ?></div>
    </div>   
    <div class="form-row">
        <div class="col-md-2 offset-md-1"><b>Endereço:</b></div>
        <div class="col-md-8 offset-md-1"><?php echo $this->getApiweb()->getEnderecoremetente()." ".$this->getApiweb()->getNumeroremetente() ?></div>
    </div>
    <div class="form-row">
        <div class="col-md-2 offset-md-1"><b>Bairro:</b></div>
        <div class="col-md-8 offset-md-1"><?php echo $this->getApiweb()->getBairroremetente() ?></div>
    </div>
    <div class="form-row">
        <div class="col-md-3 offset-md-1"><b>Cidade:</b></div>
        <div class="col-md-8 "><?php echo $this->getApiweb()->getCidaderemetente() ?></div>
    </div>
    <div class="form-row">
        <div class="col-md-2 offset-md-1"><b>UF:</b></div>
        <div class="col-md-8 offset-md-1"><?php echo $this->getApiweb()->getUfremetente() ?></div>
    </div>
    <div class="form-row">
        <div class="col-md-2 offset-md-1"><b>UF:</b></div>
        <div class="col-md-8 offset-md-1"><?php echo $this->getApiweb()->getCepremetente() ?></div>
    </div>
</div>

