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
        <h3  class="h3-1 float-left">Incluir Prospecção</h3>
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
        <ul class="nav nav-tabs" id="tabusuarios" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="informacao-tab" data-toggle="tab" href="#informacao" role="tab" aria-controls="informacao" aria-selected="true">Informações</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="endereco-tab" data-toggle="tab" href="#endereco" role="tab" aria-controls="endereco" aria-selected="false">Endereços</a>
            </li>
             <li class="nav-item">
                <a class="nav-link" id="andamento-tab" data-toggle="tab" href="#andamento" role="tab" aria-controls="andamento" aria-selected="false">Histórico</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="informacao" role="tabpanel" aria-labelledby="informacao-tab">
                <?php include 'informacao.php'; ?>
            </div>
            <div class="tab-pane fade" id="endereco" role="tabpanel" aria-labelledby="endereco-tab">
                <?php include 'endereco.php'; ?>
            </div>
            <div class="tab-pane fade" id="andamento" role="tabpanel" aria-labelledby="andamento-tab">
                <?php include 'andamento.php'; ?>
            </div>
        </div>

        <div class="form-row ">
            <div class="form-group col-sm-12 col-md-12 text-center"> 
                <button class="btn btn-success <?php echo ($salvar ? "" : "disabled") ?>" <?php echo ($salvar ? "" : "disabled") ?> id="salvar" rel="tooltip" data-placement="top" type="submit" title="Salvar">Salvar</button>
                <a class="btn btn-danger" rel="tooltip" data-placement="top"  title="Voltar" href="<?php echo "{$pagina}?pag={$pag}&acao=index{$url}"; ?>">Voltar</a>
            </div>
        </div>
    </div>
    <hr>
</form>
<script>Incia_Varial('<?php echo $controller->getProspeccao()->getLatitude() ?>', '<?php echo $controller->getProspeccao()->getLongitude() ?>')</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCMeZs26mvBa7A6iUj7-kAUWuMZkVy_Jmw&callback=<?php echo (crypto::decrypt($codigo) > 0 ? "Carrega_Mapa" : "Carrega_Mapa_Inicial") ?>&lenguage=BR&region=BR"></script>

