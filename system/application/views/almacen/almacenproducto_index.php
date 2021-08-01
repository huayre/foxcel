 <script src="<?php echo base_url(); ?>js/jquery.columns.min.js?=<?=JS;?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/almacen/almacenproducto.js?=<?=JS;?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/funciones.js?=<?=JS;?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/fancybox/jquery.mousewheel-3.0.4.pack.js?=<?=JS;?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/fancybox/jquery.fancybox-1.3.4.pack.js?=<?=JS;?>"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js?=<?=JS;?>"></script>
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.js?=<?=JS;?>"></script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>js/fancybox/jquery.fancybox-1.3.4.css?=<?=CSS;?>" media="screen" />
<script type="text/javascript">
    $(document).ready(function() {
        $("a#linkSerie").fancybox({
                'width'	     : 750,
                'height'         : 540,
                'autoScale'	     : false,
                'transitionIn'   : 'none',
                'transitionOut'  : 'none',
                'showCloseButton': false,
                'modal'          : false,
                'type'	     : 'iframe'
        });

        $("#dialogSeries").dialog({
    		resizable: false,
    	    height: "auto",
    	    width: 600,
            autoOpen: false,
            show: {
              effect: "blind",
              duration: 1000
            },
            hide: {
              effect: "explode",
              duration: 1000
            }
          });
        
        $('#limpiar').click(function(){
            
        location.href="<?php echo base_url() ?>index.php/almacen/almacenproducto/listar";  
        });

        $('.paginacion').live('click', function(){
            var urls = "<?php echo base_url() ?>index.php/almacen/producto/buscar_productos/";
            $('#cuerpoPagina').html('<div><img src="images/loading.gif?=<?=IMG;?>" width="70px" height="70px"/></div>');
            var page = $(this).attr('data');        
            var dataString = 'page='+page;
            $.ajax({
                type: "GET",
                url: urls,
                data: dataString,
                success: function(data) {
                    $('#cuerpoPagina').fadeIn(1000).html(data);
                }
            });
        });
    });
</script>

<div id=dialogSeries title="Series Ingresadas">
  <div id="mostrarDetallesSeries">
	<div id="detallesSeries"></div>	
  </div>
</div>
<div id="pagina">
    <div id="zonaContenido">
    <div align="center">
        <div id="tituloForm" class="header"><?php echo $titulo_tabla;;?></div>
        <div id="cuerpoPagina">
            <div id="frmBusqueda" >
        		<input type="hidden" name="almacen_id" id="almacen_id" value="<?=$_SESSION['compania'];?>">
                <?php //echo $form_open;?>
                    <table class="fuente8" width="98%" cellspacing="0" cellpadding="3" border="0">
                       
                        <tr>
                            <td>
                                <label for="">Marca: </label>
                            </td>
                            <td colspan="3">
                                <select class="cajaMedia" name="txtMarca" id="txtMarca">
                                    <option value=""> :: TODOS :: </option> <?php
                                        foreach ($listaMarcas as $key => $value) { ?>
                                            <option value='<?=$value->MARCP_Codigo;?>'> <?=$value->MARCC_Descripcion;?> </option> <?php
                                        } ?>
                                </select>
                            </td>
                            <td rowspan="2" style="text-align: center;">
                                <a href="javascript:;" onclick="descargarExcel()">
                                    <img src="<?php echo base_url();?>images/xls.png?=<?=IMG;?>" width="32px" class="imgBoton" onMouseOver="style.cursor=cursor">
                                    <br>General
                                </a>
                            </td>
                            <td rowspan="2" style="text-align: center">
                                <!--<a href="javascript:;" onclick="descargarExcelDetallado()">
                                    <img src="<?php echo base_url();?>images/xls.png?=<?=IMG;?>" width="32px" class="imgBoton" onMouseOver="style.cursor=cursor">
                                    <br><br>Detallado
                                </a>-->
                            </td>
                        </tr>
                        <tr>
                            <td align='left' width="10%">
                                <label for="nombre_prod">Producto: </label>
                            </td>
                            <td align='left' width="70%"><input name="nombre_prod" class="cajaGrande" id="nombre_prod" type="text" value="<?php echo $nombre_prod ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td align='left' width="10%">
                                <label for="compatibilidad">Comptabilidad: </label>
                            </td>
                            <td align='left' width="70%"><input name="compatibilidad" class="cajaGrande" id="compatibilidad" type="text" value="<?php echo $compatibilidad ?>"/>
                                &nbsp;&nbsp;&nbsp;
                                <a href="#" id="buscar">
                                    <img src="<?php echo base_url();?>images/botonbuscar.jpg?=<?=IMG;?>" width="69" height="22" class="imgBoton" onMouseOver="style.cursor=cursor">
                                </a>
                                &nbsp;&nbsp;&nbsp;
                                <a href="#" id="limpiarBusqueda">
                                    <img src="<?php echo base_url();?>images/botonlimpiar.jpg?=<?=IMG;?>" width="69" height="22" class="imgBoton" onMouseOver="style.cursor=cursor">
                                </a>
                            </td>
                        </tr>
                    </table>
                <?php //echo $form_close;?>
            </div>
         
            <div id="frmResultado">
                <div id="cargando_datos" class="loading-table">
                    <img src="<?=base_url().'images/loading.gif?='.IMG;?>">
                </div>
                <?php echo $form_open2;?>
                <input type="hidden" name="compania" id="compania"/>
                <input type="hidden" name="almacen" id="almacen" />
                <input type="hidden" name="producto" id="producto" />
                <input type="hidden" name="codproducto" id="codproducto" />
                <input type="hidden" name="nombre_producto" id="nombre_producto" />
                <a href="javascript:;" id="linkSerie"></a>
                <table class="display fuente8" width="100%" cellspacing="0" cellpadding="3" border="0" id="table-stock" data-page-length='25'>
                    <thead>
                        <tr class="cabeceraTabla">
                            <th width="10%" data-orderable="true">CODIGO</th>
                            <th width="30%" data-orderable="true">DESCRIPCION</th>
                            <th width="20%" data-orderable="true">COMPATIBILIDAD</th>
                            <th width="10%" data-orderable="false">STOCK</th>
                            <th width="10%" data-orderable="false">UND</th>
                            <th width="15%" data-orderable="false">ALMACEN</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <?php echo $form_close2;?>
            </div>
             <div style="margin-top: 15px;"><?php echo $paginacion;?></div>
          
            <input type="hidden" id="iniciopagina" name="iniciopagina">
            <input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
            <input type="hidden" name="base_url" id="base_url" value="<?php echo base_url();?>">
        </div>
    </div>
</div>			
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#nombre_prod').keyup(function(e){
            var key=e.keyCode || e.which;
            if (key==13){
               
                $("#buscar").click();
            } 
        });
        $('#table-stock').DataTable({
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax:{
                    url : "<?=base_url();?>index.php/almacen/almacenproducto/datatable_almacen_producto",
                    type: "POST",
                    data: { dataString: "" },
                    beforeSend: function(){
                    },
                    error: function(){
                    }
            },
            language: spanish
        });

        $("#buscar").click(function(){
            marca = $('#txtMarca').val();
            modelo = $('#txtModelo').val();
            producto = $('#nombre_prod').val();
            compatibilidad = $('#compatibilidad').val();
            
            $('#table-stock').DataTable({
                filter: false,
                destroy: true,
                processing: true,
                serverSide: true,
                ajax:{
                        url : "<?=base_url();?>index.php/almacen/almacenproducto/datatable_almacen_producto",
                        type: "POST",
                        data: { txtMarca: marca, txtModelo: modelo, nombre_prod: producto, compatibilidad: compatibilidad },
                        error: function(){
                        }
                },
                language: spanish
            });
        });

        $("#limpiarBusqueda").click(function(){
            $('#txtMarca').val('');
            $('#txtModelo').val('');
            $('#nombre_prod').val('');
            $('#compatibilidad').val('');

            $('#table-stock').DataTable({
                filter: false,
                destroy: true,
                processing: true,
                serverSide: true,
                ajax:{
                        url : "<?=base_url();?>index.php/almacen/almacenproducto/datatable_almacen_producto",
                        type: "POST",
                        data: { txtMarca: "", txtModelo: "", nombre_prod: "" ,compatibilidad:""},
                        error: function(){
                        }
                },
                language: spanish
            });
        });
    });
</script>