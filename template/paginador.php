<div class="container">
    <div class="row ">
        <div class="col-md-12 text-center paginacao">
            <div id="page">
                <ul class="pagination justify-content-center"></ul>
            </div>
        </div>
    </div>
</div>


<?php if ($page > 1) { ?>
    <script>
        var pagina = "<?php echo $_SERVER ['REQUEST_URI']; ?>";
        pagina = pagina.split("?");
        var parametrosDaUrl = pagina[1];
        var paginaGet = pagina[0];
        var form = document.getElementById("formulario");

        $(function () {
            window.pagObj = $('.pagination').twbsPagination({
                totalPages: <?php echo $page ?>,
                visiblePages: 5,
                startPage: <?php echo $_REQUEST['pag'] ?>,
                onPageClick: function (event, page) {
                    console.info(page + ' (from options)');
                }
            }).on('page', function (event, page) {
                form.action = paginaGet + "?pag=" + page;
                form.submit();
                abreEspera();
            });
        });
    </script>
<?php } ?>
                    


