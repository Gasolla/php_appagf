<div id=boxes>
    <div id=dialogo class="window fixed">
        <i class="fas fa-spinner fa-pulse fa-5x fa-fw"></i>
    </div>
</div>
<div id=espera></div>

<div class="modal fade" id="MsnInicial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Atenção Mensagem</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">Fechar</span>
                </button>
            </div>
            <div class="modal-body">
                <span>Tem certeza que deseja apagar o título?</span>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="window.location.href = 'home'" class="btn btn-success">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="MsnSair" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Atenção Mensagem</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">Fechar</span>
                </button>
            </div>
            <div class="modal-body">
                <span><h3/>Tem certeza que deseja sair?</h3></span>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="window.location.href = 'Sair.php'" class="btn btn-success">OK</button>
                <button type="button"  class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="msgs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Atenção Mensagem</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">Fechar</span>
                </button>
            </div>
            <div class="modal-body">
                <h4><b></b></h4>
            </div>
            <div class="modal-footer">
                <button type="button"  class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalimg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">visualizar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">Fechar</span>
                </button>
            </div>
            <div class="modal-body">
                <img  />
            </div>
            <div class="modal-footer">
                <button type="button"  class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="MsnExcluir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form name="form" id="form" action="app" method="POST" onsubmit="return Excluir(this)">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Atenção Mensagem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">Fechar</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span></span>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="acao" value="excluir"/>
                    <input type="hidden" name="codigo" id="codigo" value=""/>
                    <input type="hidden" name="url" value="<?php echo "{$pagina}?pag={$pag}{$url}"; ?>"/>
                    <input type="hidden" name="class" id="class" value=""/>  
                    <button type="submit" class="btn btn-primary">OK</button>
                    <button type="button"  class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>



<div class="modal fade" id="MsnVisualizar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Visualizar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">Fechar</span>
                </button>
            </div>
            <div class="modal-body">
              
            </div>
            <div class="modal-footer">
                <button type="button"  class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="MsnUpload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Atenção Mensagem</h5>
                <button type="button" class="close" onclick="window.location.reload();" aria-label="Fechar">
                    <span aria-hidden="true">Fechar</span>
                </button>
            </div>
            <div class="modal-body">
                <h4><b></b></h4>
            </div>
            <div class="modal-footer">
                <button type="button"  class="btn btn-primary" onclick="window.location.reload();">OK</button>
            </div>
        </div>
    </div>
</div>
