<link href="<?=base_url();?>js/fancybox/dist/jquery.fancybox.css?=<?=CSS;?>" rel="stylesheet">
<script src="<?=base_url();?>js/fancybox/dist/jquery.fancybox.js?=<?=JS;?>"></script>

<div class="container-fluid">
    <div class="row header">
        <div class="col-md-12 col-lg-12">
            <div><?=$titulo;?></div>
        </div>
    </div>
    <form id="form_busqueda" method="post">
        <div class="row fuente8 py-1">
           
            <div class="col-sm-5 col-md-5 col-lg-5">
                <label for="search_descripcion">PRODUCTO</label>
                <input type="text" name="search_descripcion" id="search_descripcion" value="" placeholder="Nombre del producto" class="form-control h-1 w-porc-90"/>
                <input type="hidden" name="producto" id="producto" value="" placeholder="codigo" class="form-control h-1 w-porc-90"/>
            </div>
            <div class="col-sm-4 col-md-4 col-lg-4">
                <label for="search_tipo">ALMACEN</label>
                <?=$cboAlmacen;?>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2">
                <label for="search_fechai">FECHA INICIO</label>
                <input type="date" name="search_fechai" id="search_fechai" value="" class="form-control h-1 w-porc-90"/>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2">
                <label for="search_fechaf">FECHA FIN</label>
                <input type="date" name="search_fechaf" id="search_fechaf" value="" class="form-control h-1 w-porc-90"/>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
                    <div class="acciones">
                        <div id="botonBusqueda">
                            <ul id="limpiarC" class="lista_botones">
                                <li id="limpiar">Limpiar</li>
                            </ul>
                            <ul id="buscarC" class="lista_botones">
                                <li id="buscar">Buscar</li>
                            </ul> 
                        </div>
                        <div id="lineaResultado">Registros encontrados</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
                    <div class="header text-align-center"><?=$titulo;?></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
                    <table class="fuente8 display" id="table-movimiento">
                        <div id="cargando_datos" class="loading-table">
                            <img src="<?=base_url().'images/loading.gif?='.IMG;?>">
                        </div>
                        <thead>
                            <tr class="cabeceraTabla">
            <td style="width:10%" data-orderable="false" title="">FECHA MOV.</td>
            <td style="width:10%" data-orderable="false" title="">NUM DOC</td>
            <td style="width:30%" data-orderable="false" title="">CLIENTE</td>
            <td style="width:10%" data-orderable="false" title="">TIPO MOV.</td>
            <td style="width:10%" data-orderable="false" title="">CANTIDAD</td>
            <td style="width:10%" data-orderable="false" title="">P.UNITARIO</td>
            <td style="width:10%" data-orderable="false" title="">SUBTOTAL</td>
            <td style="width:10%" data-orderable="false" title="">TOTAL</td>
            <td style="width:10%" data-orderable="false" title="">ALMACEN ORIGEN</td>
                                
                            </tr>
                        </thead>
                        <tbody id="tbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    base_url = "<?=$base_url;?>";

    $(document).ready(function(){

        $("#search_descripcion").autocomplete({
                source: function (request, response) {

                    tipo_oper='V';
                    $.ajax({
                        url: "<?php echo base_url(); ?>index.php/maestros/temporaldetalle/autocomplete_producto/B/" + <?php echo $compania;?>+"/"+$("#almacen").val(),
                        type: "POST",
                        data: {
                            term: $("#search_descripcion").val(), TipCli: $("#TipCli").val(), familia: $("#tempde_filtro_familia").val(), marca: $("#tempde_filtro_marca").val(), modelo: $("#tempde_filtro_modelo").val(), tipo_oper: tipo_oper 
                        },
                        dataType: "json",
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                select: function (event, ui) {
                    /**si el producto tiene almacen : es que no esta inventariado en ese almacen , se le asigna el almacen general de cabecera**/
                    
                    
                    $("#producto").val(ui.item.codigo);
                    $("#search_codigo").val(ui.item.value);
                    $("#search_descripcion").val(ui.item.nombre);       
                        
                    
                },
                minLength: 1
            });

     
         $("#search_codigo").autocomplete({
                source: function (request, response) {
                    compania = <?php echo $_SESSION["compania"]?>;
                    almacen = $("#almacen").val();
                    tipo_oper="V";
                    $.ajax({
                        url: "<?php echo base_url(); ?>index.php/almacen/producto/autocompletado_producto_x_codigo",
                        type: "POST",
                        data: {
                            term: $("#search_codigo").val(), flag: "B", compania: compania, almacen: almacen
                        },
                        dataType: "json",
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                select: function (event, ui) {
                    
                    $("#producto").val(ui.item.codigo);
                    $("#search_codigo").val(ui.item.value);
                    $("#search_descripcion").val(ui.item.nombre);
                },
                minLength: 1
            });

       /* $('#table-movimiento').DataTable({
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax:{
                    url : '<?=base_url();?>index.php/almacen/kardex/datatable_kardex/',
                    type: "POST",
                    data: { dataString: "" },
                    beforeSend: function(){
                        $("#table-movimiento .loading-table").show();
                    },
                    error: function(){
                    },
                    complete: function(){
                        $("#table-movimiento .loading-table").hide();
                    }
            },
            language: spanish,
            columnDefs: [{"className": "dt-center", "targets": 0}],
            order: [[ 1, "desc" ]]
        });*/

        $("#buscarC").click(function(){
            search();
        });

        $("#limpiarC").click(function(){
            $("#search_descripcion").val("");
            $("#search_codigo").val("");
            $("#producto").val("");
            $("#search_fechai").val("");
            $("#search_fechaf").val("");
            $("#almacen").val("");
            $("#tbody").empty();
        });

        $('#form_busqueda').keypress(function(e){
            if ( e.which == 13 ){
                return false;
            } 
        });

        $('#search_descripcion').keyup(function(e){
            if ( e.which == 13 ){
                if( $(this).val() != '' )
                    search();
            }
        });

       
    });

    function search( search = true){
        if (search == true){
            producto = $("#producto").val();
            search_descripcion = $("#search_descripcion").val();
            search_fechai = $("#search_fechai").val();
            search_fechaf = $("#search_fechaf").val();
            almacen = $("#almacen").val();
            if (producto=="" || producto==null) {
                Swal.fire({
                    icon: "warning",
                    title: "Debe ingresar un producto",
                    html: "<b class='color-red'>Para realizar la busqueda debe seleccionar un producto</b>",
                    showConfirmButton: true
                });
                $("#search_descripcion").focus();
                return false;
            }
        }
        else{
            $("#search_descripcion").val("");
            $("#producto").val("");
            $("#search_fechai").val("");
            $("#search_fechaf").val("");
            $("#almacen").val("");

            search_codigo = "";
            search_descripcion = "";
            search_tipo = "";
            search_fechai = "";
            search_fechaf = "";
            almacen = "";
            producto="";
        }
        
        $('#table-movimiento').DataTable({
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            paging: false,
            ajax:{
                    url : '<?=base_url();?>index.php/almacen/kardex/datatable_kardex/',
                    type: "POST",
                    data: {
                            producto: producto,
                            descripcion: search_descripcion,
                            almacen: almacen,
                            fechai: search_fechai,
                            fechaf: search_fechaf
                    },
                    beforeSend: function(){
                        $("#table-movimiento .loading-table").show();
                    },
                    error: function(){
                    },
                    complete: function(){
                        $("#table-movimiento .loading-table").hide();
                    }
            },
            language: spanish,
            columnDefs: [{"className": "dt-center", "targets": 0}],
            order: [[ 1, "desc" ]]
        });
    }


</script>