<script src="<?php echo base_url(); ?>js/jquery.columns.min.js?=<?=JS;?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/almacen/kardex.js?=<?=JS;?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/fancybox/jquery.mousewheel-3.0.4.pack.js?=<?=JS;?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/fancybox/jquery.fancybox-1.3.4.pack.js?=<?=JS;?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>js/fancybox/jquery.fancybox-1.3.4.css?=<?=CSS;?>" media="screen"/>

<script type="text/javascript">
    $(document).ready(function () {
        $("a#linkVerProducto").fancybox({
            'width': 800,
            'height': 500,
            'autoScale': false,
            'transitionIn': 'none',
            'transitionOut': 'none',
            'showCloseButton': true,
            'modal': false,
            'type': 'iframe'
        });

        
    });
    $(function () {

        $("#dialogSeries").dialog({
            resizable: false,
            height: "auto",
            width: 400,
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
        
        $('.kardex_prod').click(function (e) {
            $('#linkVerProducto').attr('href', '<?php echo base_url(); ?>index.php/almacen/producto/ventana_busqueda_producto_kardex/B/' + $('#nombre_producto').val()).click();
        });

        $("#nombre_producto").autocomplete({

            source: function (request, response) {

                $.ajax({
                    url: "<?php echo base_url(); ?>index.php/almacen/producto/autocomplete/B/" + $("#compania").val()+"/"+$("#almacen").val(),
                    type: "POST",
                    data: {term: $("#nombre_producto").val()},
                    dataType: "json",
                    success: function (data) {
                        response(data);
                    }
                });
            },
            select: function (event, ui) {
                $("#buscar_producto").val(ui.item.codinterno);
                $("#producto").val(ui.item.codigo);
                $("#codproducto").val(ui.item.codinterno);
                $("#codproducto2").val(ui.item.codinterno);
                $("#flagGenInd").val(ui.item.flagGenInd);
            },

            minLength: 2

        });

        $("#codproducto2").autocomplete({

            source: function (request, response) {

                var flag = "B";

                $.ajax({
                    url: "<?php echo base_url(); ?>index.php/almacen/producto/autocompletado_producto_x_codigo/",
                    type: "POST",
                    data: {
                        term: $("#codproducto2").val(),
                        compania : $("#compania").val(),
                        flag : flag
                    },
                    dataType: "json",
                    success: function (data) {
                        response(data);
                    }
                });
            },
            select: function (event, ui) {
                $("#buscar_producto").val(ui.item.codinterno);
                $("#producto").val(ui.item.codigo);
                $("#codproducto").val(ui.item.codinterno);
                $("#codproducto2").val(ui.item.codinterno);
                $('#nombre_producto').val(ui.item.value);
            },

            minLength: 2

        });

    });

    function seleccionar_producto(producto, cod_interno, descripcion, familia, stock, costo, nombre) {
        $("#producto").val(producto);
        $("#codproducto").val(cod_interno);
        $("#codproducto2").val(cod_interno);
        $("#nombre_producto").val(descripcion);
        //obtener_producto();
    }
</script>
<div id="pagina">
    <div id="zonaContenido">
        <div id=dialogSeries title="Series Ingresadas">
            <div id="mostrarDetallesSeries">  
                <div id="detallesSeries"></div>
            </div>
        </div>
        <div align="center">
            <div id="tituloForm" class="header"><?php echo $titulo_tabla;; ?></div>
            <div id="frmBusqueda" style="height:92px;">
                <?php echo $form_open; ?>
                <table class="fuente8" width="98%" cellspacing="0" cellpadding="3" border="0">
                    <tr>
                        <td align='left' width="8%">Producto</td>
                        <td align='left' width="50%">
                            <input type="hidden" name="producto" id="producto" value=""/>
                            <input type="hidden" name="flagGenInd" id="flagGenInd" value=""/>
                            
                            <input name="compania" type="hidden" id="compania" value="<?php echo $compania; ?>">
                            <input type="hidden" id="codproducto" name="codproducto" class="cajaGeneral" size="10" placeholder="Codigo" maxlength="20" value="" />&nbsp;
                            <input type="text" id="codproducto2" name="codproducto2" class="cajaGeneral" size="10" placeholder="Codigo" maxlength="20" value="" />&nbsp;
                            <input type="text" class="cajaGeneral cajaSoloLectura" id="nombre_producto" placeholder="Nombre producto" name="nombre_producto" size="40" maxlength="50" value=""/>
                            <?php echo $cboProducto; ?>
                        </td>

                        <td align='left' width="8%">Fecha Inicial</td>
                        <td align='left' width="13%">
                            <?php echo $fechai; ?>
                            <img src="<?php echo base_url(); ?>images/calendario.png?=<?=IMG;?>" name="Calendario1" id="Calendario1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" title="Calendario"/>
                            <script type="text/javascript">
                                Calendar.setup({
                                    inputField: "fechai",      // id del campo de texto
                                    ifFormat: "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                                    button: "Calendario1"   // el id del bot���0�7�0�0n que lanzar����0�8 el calendario
                                });
                            </script>
                        </td>
                        <td align='left' width="8%">Fecha final</td>
                        <td align='left' width="13%">
                            <?php echo $fechaf; ?>
                            <img src="<?php echo base_url(); ?>images/calendario.png?=<?=IMG;?>" name="Calendario2" id="Calendario2" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" title="Calendario2"/>
                            <script type="text/javascript">
                                Calendar.setup({
                                    inputField: "fechaf",      // id del campo de texto
                                    ifFormat: "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                                    button: "Calendario2"   // el id del bot���0�7�0�0n que lanzar����0�8 el calendario
                                });
                            </script>
                        </td>
                    </tr>
                    <tr>
                        <td align='left'>Almacen</td>
                        <td align='left'><?php echo $cboAlmacen; ?></td>
                        <td align='left'></td>
                        <td align='left'></td>
                        <td align='left'><?php // Es para una busqueda por FIFO echo $tipo_val1; ?></td>
                        <td align='left'><?php echo $tipo_val2; ?></td>
                        <td align='left'>&nbsp;</td>
                        <td align='center'>
                            <a href="#" id="buscarKardex">
                                <img src="<?php echo base_url(); ?>images/botonbuscar.jpg?=<?=IMG;?>" width="69" height="22" class="imgBoton" onMouseOver="style.cursor=cursor">
                            </a>&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                        <td align='center'>
                            <a href="#" id="limpiarkardex">
                                <img src="<?php echo base_url(); ?>images/botonlimpiar.jpg?=<?=IMG;?>" width="69" height="22" class="imgBoton" onMouseOver="style.cursor=cursor">
                            </a>&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
                <?php echo $oculto; ?>
                <?php echo $form_close; ?>
            </div>
            <div id="activarBusqueda" ></div>
            <input type="hidden" id="iniciopagina" name="iniciopagina">
            <input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
            <input type="hidden" name="base_url" id="base_url" value="<?php echo base_url(); ?>">
        </div>
    </div>
    <div id="cargando_datos" style="display: none;position: absolute; width: 100%; height: 100%; left: 0; top: 0px; z-index: 9999">
        <div align="center" style="background: #FFF; z-index: 9999; position: relative; top: 40%; margin: 0 auto; width: 140px; height: 32px;padding: 30px 40px; border: 1px solid #cccccc;" class="fuente8">
            <b>ESPERE POR FAVOR...</b><br>
            <img src="<?php echo base_url() ?>images/cargando.gif?=<?=IMG;?>" border='0'/>
        </div>
    </div>
</div>