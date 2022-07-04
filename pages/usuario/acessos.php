<?php
$Sql = "Select menu, submenu "
        . "from acesso "
        . "where usuario_id = '" . crypto::decrypt($codigo) . "'";

$retorno = $conexao->consultar($Sql, array(crypto::decrypt($codigo)), array('codigo'), $usuarioacesso->Codigo);
if ((is_array($retorno)) && (count($retorno) > 0)) {
    foreach ($retorno as $value) {
        $value['submenu'] = trim($value['submenu']);
        $$value['submenu'] = $value['submenu'];
    }
}
?>

<div class="form-row top-20">
    <div class="form-group col-sm-10 offset-sm-1">
        <div class="clearfix">
            <h5 class="float-left">Cadastro</h5>
            <a class="float-right" data-togle="cadastro" data-result="close" rel="openclose"><i class="fas fa-angle-down"></i></a>
        </div>
        <hr>
    </div>

    <div class="col-sm-10 offset-sm-1 d-none" id="cadastro">
        <div class="form-row" style="width: 100%">
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="usuario" <?php echo (isset($usuario) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="usuario">
                        Usuários
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($usuario) ? 'disabled' : ''); ?>" id="div-usuarioincluir">
                    <label style="margin-left: 10px">
                        <input name="usuarioincluir" id="usuarioincluir" type="checkbox" <?php echo (!isset($usuario) ? 'disabled' : ''); ?> value="usuarioincluir" <?php echo (isset($usuarioincluir) ? 'checked' : ''); ?>>
                        Incluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($usuario) ? 'disabled' : ''); ?>" id="div-usuarioalterar">
                    <label style="margin-left: 10px">
                        <input name="usuarioalterar" id="usuarioalterar" type="checkbox" <?php echo (!isset($usuario) ? 'disabled' : ''); ?> value="usuarioalterar" <?php echo (isset($usuarioalterar) ? 'checked' : ''); ?>>
                        Alterar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($usuario) ? 'disabled' : ''); ?>" id="div-usuarioexcluir">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="usuarioexcluir" id="usuarioexcluir" value="usuarioexcluir" <?php echo (!isset($usuario) ? 'disabled' : ''); ?> <?php echo (isset($usuarioexcluir) ? 'checked' : ''); ?>>
                        Excluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($usuario) ? 'disabled' : ''); ?>" id="div-usuarioconsultar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="usuarioconsultar" id="usuarioconsultar" value="usuarioconsultar" <?php echo (!isset($usuario) ? 'disabled' : ''); ?> <?php echo (isset($usuarioconsultar) ? 'checked' : ''); ?>>
                        Consultar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($usuario) ? 'disabled' : ''); ?>" id="div-usuariogerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="usuariogerar" id="usuariogerar" value="usuariogerar" <?php echo (!isset($usuario) ? 'disabled' : ''); ?> <?php echo (isset($usuariogerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="usuariocli" <?php echo (isset($usuariocli) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="usuariocli">
                        Usuários app Cliente
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($usuariocli) ? 'disabled' : ''); ?>" id="div-usuariocliincluir">
                    <label style="margin-left: 10px">
                        <input name="usuariocliincluir" id="usuariocliincluir" type="checkbox" <?php echo (!isset($usuariocli) ? 'disabled' : ''); ?> value="usuariocliincluir" <?php echo (isset($usuariocliincluir) ? 'checked' : ''); ?>>
                        Incluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($usuariocli) ? 'disabled' : ''); ?>" id="div-usuarioclialterar">
                    <label style="margin-left: 10px">
                        <input name="usuarioclialterar" id="usuarioclialterar" type="checkbox" <?php echo (!isset($usuariocli) ? 'disabled' : ''); ?> value="usuarioclialterar" <?php echo (isset($usuarioclialterar) ? 'checked' : ''); ?>>
                        Alterar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($usuariocli) ? 'disabled' : ''); ?>" id="div-usuariocliexcluir">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="usuariocliexcluir" id="usuariocliexcluir" value="usuariocliexcluir" <?php echo (!isset($usuariocli) ? 'disabled' : ''); ?> <?php echo (isset($usuariocliexcluir) ? 'checked' : ''); ?>>
                        Excluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($usuariocli) ? 'disabled' : ''); ?>" id="div-usuariocliconsultar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="usuariocliconsultar" id="usuariocliconsultar" value="usuariocliconsultar" <?php echo (!isset($usuariocli) ? 'disabled' : ''); ?> <?php echo (isset($usuariocliconsultar) ? 'checked' : ''); ?>>
                        Consultar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($usuariocli) ? 'disabled' : ''); ?>" id="div-usuariocligerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="usuariocligerar" id="usuariocligerar" value="usuariocligerar" <?php echo (!isset($usuariocli) ? 'disabled' : ''); ?> <?php echo (isset($usuariocligerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="cliente" <?php echo (isset($cliente) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="cliente">
                        Clientes
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($cliente) ? 'disabled' : ''); ?>" id="div-clienteincluir">
                    <label style="margin-left: 10px">
                        <input name="clienteincluir" id="clienteincluir" type="checkbox" <?php echo (!isset($cliente) ? 'disabled' : ''); ?> value="clienteincluir" <?php echo (isset($clienteincluir) ? 'checked' : ''); ?>>
                        Incluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($cliente) ? 'disabled' : ''); ?>" id="div-clientealterar">
                    <label style="margin-left: 10px">
                        <input name="clientealterar" id="clientealterar" type="checkbox" <?php echo (!isset($cliente) ? 'disabled' : ''); ?> value="clientealterar" <?php echo (isset($clientealterar) ? 'checked' : ''); ?>>
                        Alterar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($cliente) ? 'disabled' : ''); ?>" id="div-clienteexcluir">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="clienteexcluir" id="clienteexcluir" value="clienteexcluir" <?php echo (!isset($cliente) ? 'disabled' : ''); ?> <?php echo (isset($clienteexcluir) ? 'checked' : ''); ?>>
                        Excluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($cliente) ? 'disabled' : ''); ?>" id="div-clienteconsultar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="clienteconsultar" id="clienteconsultar" value="clienteconsultar" <?php echo (!isset($cliente) ? 'disabled' : ''); ?> <?php echo (isset($clienteconsultar) ? 'checked' : ''); ?>>
                        Consultar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($cliente) ? 'disabled' : ''); ?>" id="div-clientegerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="clientegerar" id="clientegerar" value="clientegerar" <?php echo (!isset($cliente) ? 'disabled' : ''); ?> <?php echo (isset($clientegerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="suprimento" <?php echo (isset($suprimento) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="suprimento">
                        Suprimentos
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($suprimento) ? 'disabled' : ''); ?>" id="div-suprimentoincluir">
                    <label style="margin-left: 10px">
                        <input name="suprimentoincluir" id="suprimentoincluir" type="checkbox" <?php echo (!isset($suprimento) ? 'disabled' : ''); ?> value="suprimentoincluir" <?php echo (isset($suprimentoincluir) ? 'checked' : ''); ?>>
                        Incluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($suprimento) ? 'disabled' : ''); ?>" id="div-suprimentoalterar">
                    <label style="margin-left: 10px">
                        <input name="suprimentoalterar" id="suprimentoalterar" type="checkbox" <?php echo (!isset($suprimento) ? 'disabled' : ''); ?> value="suprimentoalterar" <?php echo (isset($suprimentoalterar) ? 'checked' : ''); ?>>
                        Alterar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($suprimento) ? 'disabled' : ''); ?>" id="div-suprimentoexcluir">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="suprimentoexcluir" id="suprimentoexcluir" value="suprimentoexcluir" <?php echo (!isset($suprimento) ? 'disabled' : ''); ?> <?php echo (isset($suprimentoexcluir) ? 'checked' : ''); ?>>
                        Excluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($suprimento) ? 'disabled' : ''); ?>" id="div-suprimentoconsultar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="suprimentoconsultar" id="suprimentoconsultar" value="suprimentoconsultar" <?php echo (!isset($suprimento) ? 'disabled' : ''); ?> <?php echo (isset($suprimentoconsultar) ? 'checked' : ''); ?>>
                        Consultar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($suprimento) ? 'disabled' : ''); ?>" id="div-suprimentogerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="suprimentogerar" id="suprimentogerar" value="suprimentogerar" <?php echo (!isset($suprimento) ? 'disabled' : ''); ?> <?php echo (isset($suprimentogerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="agendamento" <?php echo (isset($agendamento) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="agendamento">
                        Agendamentos
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($agendamento) ? 'disabled' : ''); ?>" id="div-agendamentoincluir">
                    <label style="margin-left: 10px">
                        <input name="agendamentoincluir" id="agendamentoincluir" type="checkbox" <?php echo (!isset($agendamento) ? 'disabled' : ''); ?> value="agendamentoincluir" <?php echo (isset($agendamentoincluir) ? 'checked' : ''); ?>>
                        Incluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($agendamento) ? 'disabled' : ''); ?>" id="div-agendamentoalterar">
                    <label style="margin-left: 10px">
                        <input name="agendamentoalterar" id="agendamentoalterar" type="checkbox" <?php echo (!isset($agendamento) ? 'disabled' : ''); ?> value="agendamentoalterar" <?php echo (isset($agendamentoalterar) ? 'checked' : ''); ?>>
                        Alterar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($agendamento) ? 'disabled' : ''); ?>" id="div-agendamentoexcluir">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="agendamentoexcluir" id="agendamentoexcluir" value="agendamentoexcluir" <?php echo (!isset($agendamento) ? 'disabled' : ''); ?> <?php echo (isset($agendamentoexcluir) ? 'checked' : ''); ?>>
                        Excluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($agendamento) ? 'disabled' : ''); ?>" id="div-agendamentoconsultar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="agendamentoconsultar" id="agendamentoconsultar" value="agendamentoconsultar" <?php echo (!isset($agendamento) ? 'disabled' : ''); ?> <?php echo (isset($agendamentoconsultar) ? 'checked' : ''); ?>>
                        Consultar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($agendamento) ? 'disabled' : ''); ?>" id="div-agendamentogerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="agendamentogerar" id="agendamentogerar" value="agendamentogerar" <?php echo (!isset($agendamento) ? 'disabled' : ''); ?> <?php echo (isset($agendamentogerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="veiculo" <?php echo (isset($veiculo) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="veiculo">
                        Veículos
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($veiculo) ? 'disabled' : ''); ?>" id="div-veiculoincluir">
                    <label style="margin-left: 10px">
                        <input name="veiculoincluir" id="veiculoincluir" type="checkbox" <?php echo (!isset($veiculo) ? 'disabled' : ''); ?> value="veiculoincluir" <?php echo (isset($veiculoincluir) ? 'checked' : ''); ?>>
                        Incluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($veiculo) ? 'disabled' : ''); ?>" id="div-veiculoalterar">
                    <label style="margin-left: 10px">
                        <input name="veiculoalterar" id="veiculoalterar" type="checkbox" <?php echo (!isset($veiculo) ? 'disabled' : ''); ?> value="veiculoalterar" <?php echo (isset($veiculoalterar) ? 'checked' : ''); ?>>
                        Alterar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($veiculo) ? 'disabled' : ''); ?>" id="div-veiculoexcluir">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="veiculoexcluir" id="veiculoexcluir" value="veiculoexcluir" <?php echo (!isset($veiculo) ? 'disabled' : ''); ?> <?php echo (isset($veiculoexcluir) ? 'checked' : ''); ?>>
                        Excluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($veiculo) ? 'disabled' : ''); ?>" id="div-veiculoconsultar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="veiculoconsultar" id="veiculoconsultar" value="veiculoconsultar" <?php echo (!isset($veiculo) ? 'disabled' : ''); ?> <?php echo (isset($veiculoconsultar) ? 'checked' : ''); ?>>
                        Consultar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($veiculo) ? 'disabled' : ''); ?>" id="div-veiculogerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="veiculogerar" id="veiculogerar" value="veiculogerar" <?php echo (!isset($veiculo) ? 'disabled' : ''); ?> <?php echo (isset($veiculogerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-row top-20">
    <div class="form-group col-sm-10 offset-sm-1">
        <div class="clearfix">
            <h5 class="float-left">Movimentação</h5>
            <a class="float-right" data-togle="movimentacao" data-result="close" rel="openclose"><i class="fas fa-angle-down"></i></a>
        </div>
        <hr>
    </div>
    <div class="form-group col-sm-10 offset-sm-1 d-none" id="movimentacao">
        <div class="form-row" style="width: 100%">
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="estoquecliente" <?php echo (isset($estoquecliente) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="estoquecliente">
                        Estoque Clientes
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($estoquecliente) ? 'disabled' : ''); ?>" id="div-estoqueclienteincluir">
                    <label style="margin-left: 10px">
                        <input name="estoqueclienteincluir" id="estoqueclienteincluir" type="checkbox" <?php echo (!isset($estoquecliente) ? 'disabled' : ''); ?> value="estoqueclienteincluir" <?php echo (isset($estoqueclienteincluir) ? 'checked' : ''); ?>>
                        Incluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($estoquecliente) ? 'disabled' : ''); ?>" id="div-estoqueclientealterar">
                    <label style="margin-left: 10px">
                        <input name="estoqueclientealterar" id="estoqueclientealterar" type="checkbox" <?php echo (!isset($estoquecliente) ? 'disabled' : ''); ?> value="estoqueclientealterar" <?php echo (isset($estoqueclientealterar) ? 'checked' : ''); ?>>
                        Alterar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($estoquecliente) ? 'disabled' : ''); ?>" id="div-estoqueclienteexcluir">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="estoqueclienteexcluir" id="estoqueclienteexcluir" value="estoqueclienteexcluir" <?php echo (!isset($estoquecliente) ? 'disabled' : ''); ?> <?php echo (isset($estoqueclienteexcluir) ? 'checked' : ''); ?>>
                        Excluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($estoquecliente) ? 'disabled' : ''); ?>" id="div-estoqueclienteconsultar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="estoqueclienteconsultar" id="estoqueclienteconsultar" value="estoqueclienteconsultar" <?php echo (!isset($estoquecliente) ? 'disabled' : ''); ?> <?php echo (isset($estoqueclienteconsultar) ? 'checked' : ''); ?>>
                        Consultar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($estoquecliente) ? 'disabled' : ''); ?>" id="div-estoqueclientegerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="estoqueclientegerar" id="estoqueclientegerar" value="estoqueclientegerar" <?php echo (!isset($estoquecliente) ? 'disabled' : ''); ?> <?php echo (isset($estoqueclientegerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="apiweb" <?php echo (isset($apiweb) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="apiweb">
                        Envio API Web
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($apiweb) ? 'disabled' : ''); ?>" id="div-apiwebincluir">
                    <label style="margin-left: 10px">
                        <input name="apiwebincluir" id="apiwebincluir" type="checkbox" <?php echo (!isset($apiweb) ? 'disabled' : ''); ?> value="apiwebincluir" <?php echo (isset($apiwebincluir) ? 'checked' : ''); ?>>
                        Incluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($apiweb) ? 'disabled' : ''); ?>" id="div-apiwebalterar">
                    <label style="margin-left: 10px">
                        <input name="apiwebalterar" id="apiwebalterar" type="checkbox" <?php echo (!isset($apiweb) ? 'disabled' : ''); ?> value="apiwebalterar" <?php echo (isset($apiwebalterar) ? 'checked' : ''); ?>>
                        ALterar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($apiweb) ? 'disabled' : ''); ?>" id="div-apiwebvisualizar">
                    <label style="margin-left: 10px">
                        <input name="apiwebvisualizar" id="apiwebvisualizar" type="checkbox" <?php echo (!isset($apiweb) ? 'disabled' : ''); ?> value="apiwebvisualizar" <?php echo (isset($apiwebvisualizar) ? 'checked' : ''); ?>>
                        Visualizar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($apiweb) ? 'disabled' : ''); ?>" id="div-apiwebimprimir">
                    <label style="margin-left: 10px">
                        <input name="apiwebimprimir" id="apiwebimprimir" type="checkbox" <?php echo (!isset($apiweb) ? 'disabled' : ''); ?> value="apiwebimprimir" <?php echo (isset($apiwebimprimir) ? 'checked' : ''); ?>>
                        Imprimir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($apiweb) ? 'disabled' : ''); ?>" id="div-apiwebsolicitar">
                    <label style="margin-left: 10px">
                        <input name="apiwebsolicitar" id="apiwebsolicitar" type="checkbox" <?php echo (!isset($apiweb) ? 'disabled' : ''); ?> value="apiwebsolicitar" <?php echo (isset($apiwebsolicitar) ? 'checked' : ''); ?>>
                        Solicitar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($apiweb) ? 'disabled' : ''); ?>" id="div-apiwebgerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="apiwebgerar" id="apiwebgerar" value="apiwebgerar" <?php echo (!isset($apiweb) ? 'disabled' : ''); ?> <?php echo (isset($apiwebgerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="rastreadorweb" <?php echo (isset($rastreadorweb) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="rastreadorweb">
                        Rastreador Web
                    </label>
                </div> 
                <div class="form-check <?php echo (!isset($rastreadorweb) ? 'disabled' : ''); ?>" id="div-rastreadorwebconsultar">
                    <label style="margin-left: 10px">
                        <input name="rastreadorwebconsultar" id="rastreadorwebconsultar" type="checkbox" <?php echo (!isset($rastreadorweb) ? 'disabled' : ''); ?> value="rastreadorwebconsultar" <?php echo (isset($rastreadorwebconsultar) ? 'checked' : ''); ?>>
                        Visualizar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($rastreadorweb) ? 'disabled' : ''); ?>" id="div-rastreadorwebgerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="rastreadorwebgerar" id="rastreadorwebgerar" value="rastreadorwebgerar" <?php echo (!isset($rastreadorweb) ? 'disabled' : ''); ?> <?php echo (isset($rastreadorwebgerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="importararq" <?php echo (isset($importararq) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="importararq">
                        Importar Arquivo
                    </label>
                </div> 
                <div class="form-check <?php echo (!isset($importararq) ? 'disabled' : ''); ?>" id="div-importararqincluir">
                    <label style="margin-left: 10px">
                        <input name="importararqincluir" id="importararqincluir" type="checkbox" <?php echo (!isset($importararq) ? 'disabled' : ''); ?> value="importararqincluir" <?php echo (isset($importararqincluir) ? 'checked' : ''); ?>>
                        Importar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($importararq) ? 'disabled' : ''); ?>" id="div-importararqgerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="importararqgerar" id="importararqgerar" value="importararqgerar" <?php echo (!isset($importararq) ? 'disabled' : ''); ?> <?php echo (isset($importararqgerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="comprovanteveiculo" <?php echo (isset($comprovanteveiculo) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="comprovanteveiculo">
                        Comprovante Veículo
                    </label>
                </div> 
                <div class="form-check <?php echo (!isset($comprovanteveiculo) ? 'disabled' : ''); ?>" id="div-comprovanteveiculoalterar">
                    <label style="margin-left: 10px">
                        <input name="comprovanteveiculoalterar" id="comprovanteveiculoalterar" type="checkbox" <?php echo (!isset($comprovanteveiculo) ? 'disabled' : ''); ?> value="comprovanteveiculoalterar" <?php echo (isset($comprovanteveiculoalterar) ? 'checked' : ''); ?>>
                        Validar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($comprovanteveiculo) ? 'disabled' : ''); ?>" id="div-comprovanteveiculoconsultar">
                    <label style="margin-left: 10px">
                        <input name="comprovanteveiculoconsultar" id="comprovanteveiculoconsultar" type="checkbox" <?php echo (!isset($comprovanteveiculo) ? 'disabled' : ''); ?> value="comprovanteveiculoconsultar" <?php echo (isset($comprovanteveiculoconsultar) ? 'checked' : ''); ?>>
                        Visualizar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($comprovanteveiculo) ? 'disabled' : ''); ?>" id="div-comprovanteveiculogerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="comprovanteveiculogerar" id="comprovanteveiculogerar" value="comprovanteveiculogerar" <?php echo (!isset($comprovanteveiculo) ? 'disabled' : ''); ?> <?php echo (isset($comprovanteveiculogerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-row top-20">
    <div class="form-group col-sm-10 offset-sm-1">
        <div class="clearfix">
            <h5 class="float-left">Comercial</h5>
            <a class="float-right" data-togle="utilitario" data-result="close" rel="openclose"><i class="fas fa-angle-down"></i></a>
        </div>
        <hr>
    </div>

    <div class="form-group col-sm-10 offset-sm-1 d-none" id="utilitario">
        <div class="form-row" style="width: 100%">
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="prospeccao" <?php echo (isset($prospeccao) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="prospeccao">
                        Prospecção
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($prospeccao) ? 'disabled' : ''); ?>" id="div-prospeccaoincluir">
                    <label style="margin-left: 10px">
                        <input name="prospeccaoincluir" id="prospeccaoincluir" type="checkbox" <?php echo (!isset($prospeccao) ? 'disabled' : ''); ?> value="prospeccaoincluir" <?php echo (isset($prospeccaoincluir) ? 'checked' : ''); ?>>
                        Incluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($prospeccao) ? 'disabled' : ''); ?>" id="div-prospeccaoalterar">
                    <label style="margin-left: 10px">
                        <input name="prospeccaoalterar" id="prospeccaoalterar" type="checkbox" <?php echo (!isset($prospeccao) ? 'disabled' : ''); ?> value="prospeccaoalterar" <?php echo (isset($prospeccaoalterar) ? 'checked' : ''); ?>>
                        Alterar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($prospeccao) ? 'disabled' : ''); ?>" id="div-prospeccaoexcluir">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="prospeccaoexcluir" id="prospeccaoexcluir" value="prospeccaoexcluir" <?php echo (!isset($prospeccao) ? 'disabled' : ''); ?> <?php echo (isset($prospeccaoexcluir) ? 'checked' : ''); ?>>
                        Excluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($prospeccao) ? 'disabled' : ''); ?>" id="div-prospeccaoconsultar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="prospeccaoconsultar" id="prospeccaoconsultar" value="prospeccaoconsultar" <?php echo (!isset($prospeccao) ? 'disabled' : ''); ?> <?php echo (isset($prospeccaoconsultar) ? 'checked' : ''); ?>>
                        Consultar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($prospeccao) ? 'disabled' : ''); ?>" id="div-prospeccaogerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="prospeccaogerar" id="prospeccaogerar" value="prospeccaogerar" <?php echo (!isset($prospeccao) ? 'disabled' : ''); ?> <?php echo (isset($prospeccaogerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="prospeccaolista" <?php echo (isset($prospeccaolista) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="prospeccaolista">
                        Lista Prospecção
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($prospeccaolista) ? 'disabled' : ''); ?>" id="div-prospeccaolistaincluir">
                    <label style="margin-left: 10px">
                        <input name="prospeccaolistaincluir" id="prospeccaolistaincluir" type="checkbox" <?php echo (!isset($prospeccaolista) ? 'disabled' : ''); ?> value="prospeccaolistaincluir" <?php echo (isset($prospeccaolistaincluir) ? 'checked' : ''); ?>>
                        Incluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($prospeccaolista) ? 'disabled' : ''); ?>" id="div-prospeccaolistaalterar">
                    <label style="margin-left: 10px">
                        <input name="prospeccaolistaalterar" id="prospeccaolistaalterar" type="checkbox" <?php echo (!isset($prospeccaolista) ? 'disabled' : ''); ?> value="prospeccaolistaalterar" <?php echo (isset($prospeccaolistaalterar) ? 'checked' : ''); ?>>
                        Alterar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($prospeccaolista) ? 'disabled' : ''); ?>" id="div-prospeccaolistaexcluir">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="prospeccaolistaexcluir" id="prospeccaolistaexcluir" value="prospeccaolistaexcluir" <?php echo (!isset($prospeccaolista) ? 'disabled' : ''); ?> <?php echo (isset($prospeccaolistaexcluir) ? 'checked' : ''); ?>>
                        Excluir
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($prospeccaolista) ? 'disabled' : ''); ?>" id="div-prospeccaolistaconsultar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="prospeccaolistaconsultar" id="prospeccaolistaconsultar" value="prospeccaolistaconsultar" <?php echo (!isset($prospeccaolista) ? 'disabled' : ''); ?> <?php echo (isset($prospeccaolistaconsultar) ? 'checked' : ''); ?>>
                        Consultar
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($prospeccaolista) ? 'disabled' : ''); ?>" id="div-prospeccaolistagerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="prospeccaolistagerar" id="prospeccaolistagerar" value="prospeccaolistagerar" <?php echo (!isset($prospeccaolista) ? 'disabled' : ''); ?> <?php echo (isset($prospeccaolistagerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-row top-20">
    <div class="form-group col-sm-10 offset-sm-1">
        <div class="clearfix">
            <h5 class="float-left">Relatório</h5>
            <a class="float-right" data-togle="relatorio" data-result="close" rel="openclose"><i class="fas fa-angle-down"></i></a>
        </div>
        <hr>
    </div>
    <div class="form-group col-sm-10 offset-sm-1 d-none" id="relatorio">
        <div class="form-row" style="width: 100%">
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="objetorel" <?php echo (isset($objetorel) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="objetorel">
                        Coleta Objetos
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($objetorel) ? 'disabled' : ''); ?>" id="div-objetorelgerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="objetorelgerar" id="objetorelgerar" value="objetorelgerar" <?php echo (!isset($objetorel) ? 'disabled' : ''); ?> <?php echo (isset($objetorelgerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="requisicaorel" <?php echo (isset($requisicaorel) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="requisicaorel">
                        Coleta Requisição
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($requisicaorel) ? 'disabled' : ''); ?>" id="div-requisicaorelgerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="requisicaorelgerar" id="requisicaorelgerar" value="requisicaorelgerar" <?php echo (!isset($requisicaorel) ? 'disabled' : ''); ?> <?php echo (isset($requisicaorelgerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="suprimentoclienterel" <?php echo (isset($suprimentoclienterel) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="suprimentoclienterel">
                        Suprimento Clientes
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($suprimentoclienterel) ? 'disabled' : ''); ?>" id="div-suprimentoclienterelgerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="suprimentoclienterelgerar" id="suprimentoclienterelgerar" value="suprimentoclienterelgerar" <?php echo (!isset($suprimentoclienterel) ? 'disabled' : ''); ?> <?php echo (isset($suprimentoclienterelgerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="agendamentorel" <?php echo (isset($agendamentorel) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="agendamentorel">
                        Agendamento Coleta
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($agendamentorel) ? 'disabled' : ''); ?>" id="div-agendamentorelgerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="agendamentorelgerar" id="agendamentorelgerar" value="agendamentorelgerar" <?php echo (!isset($agendamentorel) ? 'disabled' : ''); ?> <?php echo (isset($agendamentorelgerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6 col-xl-3 marginHorizontal-5">
                <div class="form-check">
                    <label style="font-weight: bold">
                        <input name="acessos[]" id="prospeccaorel" <?php echo (isset($prospeccaorel) ? 'checked' : ''); ?> type="checkbox" onclick="CheckAcesso(this.form, this)" value="prospeccaorel">
                        Contato Prospecção
                    </label>
                </div>
                <div class="form-check <?php echo (!isset($prospeccaorel) ? 'disabled' : ''); ?>" id="div-prospeccaorelgerar">
                    <label style="margin-left: 10px">
                        <input type="checkbox" name="prospeccaorelgerar" id="prospeccaorelgerar" value="prospeccaorelgerar" <?php echo (!isset($prospeccaorel) ? 'disabled' : ''); ?> <?php echo (isset($prospeccaorelgerar) ? 'checked' : ''); ?>>
                        Gerar
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
