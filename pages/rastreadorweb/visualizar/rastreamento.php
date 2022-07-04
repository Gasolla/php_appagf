<div class="container top-10">
     <div  class="table-responsive class-grid" id="listarastreador" >
        <table border="0" width="100%" class="table-bordered">
            <caption>Total: <?php echo count($this->getRastreamento()) ?></caption>
            <thead>
                <tr>
                    <th scope="col" class='text-left th-grid'>CIDADE</th>
                    <th scope="col" class='text-left th-grid'>UF</th>
                    <th scope="col" class='text-left th-grid'>LOCAL</th>
                    <th scope="col" class='text-left th-grid'>DATA</th>
                    <th scope="col" class='text-left th-grid'>DESCRIÇÃO</th>	    
                </tr>
            </thead>
            <tbody>
                <?php
                $contzebra = 0;
                if (count($this->getRastreamento()) > 0) {
                    foreach ($this->getRastreamento() as $key => $value) {
                        if ($contzebra % 2 == 0) {
                            $color = " bgcolor=#CED8F6 ";
                        } else {
                            $color = " bgcolor=#FFFFFF ";
                        }
                        ?>		
                        <tr id='<?php echo crypto::encrypt($key) ?>'> 
                            <td class="td-grid text-left" <?php echo $color ?>><?php echo $value->getCidade(); ?></td>
                            <td class="td-grid text-left" <?php echo $color ?>><?php echo $value->getUf() ?></td>
                            <td class="td-grid text-left" <?php echo $color ?>><?php echo $value->getLocal() ?></td>
                            <td class="td-grid text-left" <?php echo $color ?>><?php echo $value->getData() ?></td> 
                            <td class="td-grid text-left" <?php echo $color ?>><?php echo $value->getDescricao() ?></td> 
                        </tr>
                        <?php
                        $contzebra++;
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="5" class="background-white ">
                            <span>Nenhum andamento encontrado!</span>
                        </td>
                    </tr>           

                    <?php
                }
                ?>       
            </tbody>
        </table>
    </div>  
</div>

