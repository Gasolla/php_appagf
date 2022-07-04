
<?php $controller->addAlertaContato(); ?>
<div class="col-12 <?php echo ($controller->count > 0 ? "" : "d-none") ?>">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Agendamentos para hoje.</strong> 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button><br>
        <span>Abaixo segue seus agendamentos para hoje.</span>
        <hr>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Data</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Telefone</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($controller->getAlertacontato() as $key => $value) { ?>
                <tr>
                    <th scope="row"><?php echo ($key+1) ?></th>
                    <td><?php echo $value->getData() ?></td>
                    <td><?php echo $value->getNome() ?></td>
                    <td><?php echo $value->getFone() ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php $controller->addAlertaAtraso(); ?>
<div class="col-12 <?php echo ($controller->count > 0 ? "" : "d-none") ?>">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Retorno em atraso!</strong> 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button><br>
        <span>Abaixo seque seus agendamentos em atraso.</span>
        <hr>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Data</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Telefone</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($controller->getAlertaatraso() as $key => $value) { ?>
                <tr>
                    <th scope="row"><?php echo ($key+1) ?></th>
                    <td><?php echo $value->getData() ?></td>
                    <td><?php echo $value->getNome() ?></td>
                    <td><?php echo $value->getFone() ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php $controller->addAlertaPrimeiro(); ?>
<div class="col-12 <?php echo ($controller->count > 0 ? "" : "d-none") ?>">
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>1° contato pendente!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button><br>
        <span>Abaixo segue suas pendências aguandando para o 1° contato.</span>
        <hr>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Data</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Telefone</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($controller->getAlertaprimeiro() as $key => $value) { ?>
                <tr>
                    <th scope="row"><?php echo ($key+1) ?></th>
                    <td><?php echo $value->getData() ?></td>
                    <td><?php echo $value->getNome() ?></td>
                    <td><?php echo $value->getFone() ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

