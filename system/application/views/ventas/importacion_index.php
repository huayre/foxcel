<?php
$nombre_persona = $this->session->userdata('nombre_persona');
$persona = $this->session->userdata('persona');
$usuario = $this->session->userdata('usuario');
$url = base_url() . "index.php";
if (empty($persona))
    header("location:$url");
$CI = get_instance();
?>
<html>
<head>

    <script type="text/javascript" src="<?php echo base_url(); ?>js/ventas/importacion.js?=<?=JS;?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/funciones.js?=<?=JS;?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/fancybox/jquery.mousewheel-3.0.4.pack.js?=<?=JS;?>"></script>
    <link href="<?=base_url();?>js/fancybox/dist/jquery.fancybox.css?=<?=CSS;?>" rel="stylesheet">
    <script src="<?=base_url();?>js/fancybox/dist/jquery.fancybox.js?=<?=JS;?>"></script>

<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js?=<?=JS;?>"></script>
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.js?=<?=JS;?>"></script>


<link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.css?=<?=CSS;?>" rel="stylesheet">
<link href="<?php echo base_url(); ?>bootstrap/css/bootstrap-theme.css?=<?=CSS;?>" rel="stylesheet">

    <script language="javascript">
        $(document).ready(function () {
            $("a#linkVerCliente, a#linkVerProveedor").fancybox({
                'width': 800,
                'height': 500,
                'autoScale': false,
                'transitionIn': 'none',
                'transitionOut': 'none',
                'showCloseButton': false,
                'modal': true,
                'type': 'iframe'
            });

            $("a#linkVerProducto").fancybox({
                'width': 800,
                'height': 500,
                'autoScale': false,
                'transitionIn': 'none',
                'transitionOut': 'none',
                'showCloseButton': false,
                'modal': true,
                'type': 'iframe'
            });
            $("a#linkVerPersona").fancybox({
                'width': 800,
                'height': 650,
                'autoScale': false,
                'transitionIn': 'none',
                'transitionOut': 'none',
                'showCloseButton': false,
                'modal': true,
                'type': 'iframe'
            });
            $("a.canjear_doc").fancybox({
                'width': 900,
                'height': 550,
                'autoScale': false,
                'transitionIn': 'none',
                'transitionOut': 'none',
                'showCloseButton': false,
                'modal': true,
                'type': 'iframe'
            });
            $("a#comprobante").fancybox({
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
        function seleccionar_cliente(codigo, ruc, razon_social, empresa, persona) {
            $("#cliente").val(codigo);
            $("#ruc_cliente").val(ruc);
            $("#nombre_cliente").val(razon_social);
        }
        function seleccionar_proveedor(codigo, ruc, razon_social) {
            $("#proveedor").val(codigo);
            $("#ruc_proveedor").val(ruc);
            $("#nombre_proveedor").val(razon_social);
        }



        function seleccionar_producto(codigo, interno, familia, stock, costo) {
            $("#producto").val(codigo);
            $("#codproducto").val(interno);

            base_url = $("#base_url").val();
            url = base_url + "index.php/almacen/producto/listar_unidad_medida_producto/" + codigo;
            $.getJSON(url, function (data) {
                $.each(data, function (i, item) {
                    nombre_producto = item.PROD_Nombre;
                });
                $("#nombre_producto").val(nombre_producto);
            });
        }

        var cursor;
        if (document.all) {
            // Está utilizando EXPLORER
            cursor = 'hand';
        } else {
            // Está utilizando MOZILLA/NETSCAPE
            cursor = 'pointer';
        }

    </script>
</head>
<body>
    <div id="pagina">
        <div id="zonaContenido">
            <div align="center">
                <div id="tituloForm" class="header"><?php echo $titulo_busqueda; ?></div>

                <div id="frmBusqueda">
                    <table class="fuente8" width="98%" cellspacing="0" cellpadding="3" border="0">

                        <tr>
                            <td align='left'>Número</td>
                            <td align='left'><input type="text" name="serie" id="serie" value="" placeholder="Serie"
                                class="cajaGeneral" size="3" maxlength="3"/>
                                <input type="text" name="numero" id="numero" value="" placeholder="Numero"
                                class="cajaGeneral" size="10" maxlength="6"/>
                            </td>
                        </tr>
                        <tr>
                            <td align='left'>Proveedor</td>
                            <td align='left'>
                                <input type="hidden" name="proveedor" value=""
                                       id="proveedor" size="5"/>
                                <input type="text" name="ruc_proveedor" value="" placeholder="Ruc"
                                       class="cajaGeneral" id="ruc_proveedor" size="10" maxlength="11"
                                       onblur="obtener_proveedor();"
                                       onkeypress="return numbersonly(this, event, '.');" />
                                <input type="text" name="nombre_proveedor" value="" placeholder="Nombre proveedor"
                                       class="cajaGrande" id="nombre_proveedor" size="40"/>

                            </td>


                        </tr>
                    </table>
                </div>
                <div class="acciones">
                    <div id="botonBusqueda">

                        <ul id="imprimirComprobante" class="lista_botones">
                            <li id="imprimir">Imprimir</li>
                        </ul>

                        <ul  class="lista_botones">
                            <a href="<?php echo base_url()."index.php/ventas/importacion/comprobante_nueva"?>" style="text-decoration: none"><li id="nuevo">Nueva</li></a>
                        </ul>
                        <ul id="limpiarComprobante" class="lista_botones">
                            <li id="limpiar">Limpiar</li>
                        </ul>
                        <ul id="buscarComprobante" class="lista_botones">
                            <li id="buscar">Buscar</li>
                        </ul>
                    </div>

                    <div id="lineaResultado">

                    </div>
                    </div>

                    <div id="cabeceraResultado" class="header">
                            <?php echo $titulo_tabla;?>


                    </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
                        <table class="fuente8 display" id="table-importacion">
                            <div id="cargando_datos" class="loading-table">
                                <img src="<?=base_url().'images/loading.gif?='.IMG;?>">
                            </div>
                            <thead>
                            <tr class="cabeceraTabla">
                                <td style="width:8%" data-orderable="false">ITEM</td>
                                <td style="width:8%" data-orderable="true">SERIE</td>
                                <td style="width:8%" data-orderable="true">NÚMERO</td>
                                <td style="width:20%" data-orderable="false">RAZON SOCIAL</td>
                                <td style="width:10%" data-orderable="true">PERCEPCIÓN</td>
                                <td style="width:2%" data-orderable="false"></td>
                                <td style="width:3%" data-orderable="false"></td>
                                <td style="width:3%" data-orderable="false"></td>
                                <td style="width:3%" data-orderable="false"></td>

                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

    </div>
</div>
</div>
<div id="cargando_datos" style="display: none;position: absolute;
                     width: 100%; height: 100%; left: 0; top: 0px;
                     z-index: 9999">
    <div align="center" style="background: #FFF;
                     z-index: 9999;
                     position: relative;
                     top: 40%; margin: 0 auto; width: 140px; height: 32px;padding: 30px 40px; border: 1px solid #cccccc;"
                     class="fuente8">
        <b>ESPERE POR FAVOR...</b><br>
        <img src="<?php echo base_url() ?>images/cargando.gif?=<?=IMG;?>" border='0'/>
    </div>
</div>





<script>

    $(document).ready(function (){

        $('#table-importacion').DataTable({
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax:{
                url : '<?=base_url();?>index.php/ventas/importacion/datatable_importacion/',
                type: "POST",
                data: { dataString: "" },
                beforeSend: function(){
                    $("#table-importacion .loading-table").show();
                },
                error: function(){
                },
                complete: function(){
                    $("#table-importacion .loading-table").hide();
                }
            },
            language: spanish,
            columnDefs: [{"className": "dt-center", "targets": 0}],
            order: [[ 1, "asc" ]]
        });
    });

    function disparador(idcomprobanteImportacion,pos){

        var url = base_url + "index.php/ventas/importacion/ActualizarPrecioCompra/"+idcomprobanteImportacion;
        $(".disparador_"+pos+" .icon-loading").hide();
        $.ajax({
            type: "POST",
            url: url,
            data: { idcomprobante: idcomprobanteImportacion },
            dataType: 'json',
            beforeSend: function (data) {
                $(".disparador_"+pos+" .icon-loading").show();
            },
            error: function (data) {
                $(".disparador_"+pos+" .icon-loading").hide();
            },
            success: function (data) {

                editarHtml = '<img src="<?php echo base_url(); ?>images/completado.png" width="16" height="16" border="0" title="Completado">';
                $(".editar_data_"+pos).html(editarHtml);
                $(".btn_actualizar_"+pos).hide();

                $(".disparador_"+pos+" .icon-loading").hide();

            }
        });
    }

    $("#buscar").click(function(){

        let ruc_proveedor = $('#ruc_proveedor').val();
        let nombre_proveedor = $('#nombre_proveedor').val();
        let serie = $('#serie').val();
        let numero = $('#numero').val();

        $('#table-importacion').DataTable({
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax:{
                url : '<?=base_url();?>index.php/ventas/importacion/datatable_importacion/',
                type: "POST",
                data: { ruc_proveedor:ruc_proveedor,nombre_proveedor:nombre_proveedor,serie:serie,numero:numero},
                error: function(){
                }
            },
            language: spanish,
            order: [[ 2, "desc" ]]
        });
    });


    $("#btnLiquidacion").on("click", function () {
        var btn = $(this),
            liquidada = btn.data('liquidada'),
            codigo = btn.data('codigo'),
            urlAction = "<?php echo base_url() ?>index.php/ventas/importacion/"+(liquidada ? 'reversion' : 'liquidar')+"_importacion/"+codigo;

        if(!confirm("¿Desea "+(liquidada ? "revertir la liquidacion de " : "liquidar")+" la importación.?")) return;
        
        btn.text(liquidada ? 'Revirtiendo...' : 'Liquidando...').attr("disabled",'');

        //liquidar aqui
        $.ajax({
            url: urlAction,
        })
        .done(function(data) {
            var tblArticulos_ = $("#idTblDetalleArticulos");
            var data = $.parseJSON(data);
            if(!liquidada){
                $.each(data.productos, function(i, articulo) {
                    tblArticulos_.find("#articulo_"+articulo.id).find('.gasto-unitario').text(formatNumber.format(articulo.gastoUnitarioDolar));
                        tblArticulos_.find("#articulo_"+articulo.id).find('.gasto-unitario-total').text(formatNumber.format(articulo.gastoTotalDolar));
                    tblArticulos_.find("#articulo_"+articulo.id).find('.costo-liquidado').text(formatNumber.format(articulo.precioLiquido));
                    tblArticulos_.find("#articulo_"+articulo.id).find('.costo-liquidado-total').text(formatNumber.format(articulo.totalLiquido));
                });

                $("#unitarioGastos").text(formatNumber.format(data.unitarioGastos));
                $("#totalGastos").text(formatNumber.format(data.totalGastos));
                $("#unitarioCostos").text(formatNumber.format(data.unitarioCostos));
                $("#totalCostos").text(formatNumber.format(data.totalCostos));
            }else{
                $("#idTblDetalleArticulos").find('.gasto-unitario').text('');
                $("#idTblDetalleArticulos").find('.gasto-unitario-total').text('');
                $("#idTblDetalleArticulos").find('.costo-liquidado').text('');
                $("#idTblDetalleArticulos").find('.costo-liquidado-total').text('');

                $("#unitarioGastos").text('');
                $("#totalGastos").text('');
                $("#unitarioCostos").text('');
                $("#totalCostos").text('');
            }

            $("#ex-fabrica").text(liquidada ? '0.00' : data.exFabrica.format());
            $("#porc-cif").text(liquidada ? '0.00' : data.porcCIF.format());

            toggleLiquidacion(codigo, !liquidada);
            btn.removeAttr('disabled');
        })
        .fail(function() {
            alert("No se pudo "+(liquidada ? "revertir la liquidacion." : "liquidar."));
            toggleLiquidacion(codigo, liquidada);
            btn.removeAttr('disabled');
        });
        
    });
</script>

<!-- Modal 2-->
<div class="bootstrap modal fade" id="registra-producto2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header row">
            <div class="col-xs-11">
                <h4 class="modal-title">
                    <center><b>LIQUIDACION TOTAL : <span id="name-importacion"></span></b></center>
                </h4>
            </div>
            <div class="col-xs-1">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                </button>
            </div>
        </div>
        <form id="formulario" class="formulario" >
            <div class="modal-body" style="font-size: 1rem;">         
             <!--Cabecera de importacion-->
             <div style="padding-bottom: 3px;">
                <div class="col-xs-4 col-xs-offset-8">
                    <div class="col-xs-3"><b>TDC Dolar</b></div>
                    <div class="col-xs-3" align="right"><b id="tdc-dolar">2.365</b></div>
                    <div class="col-xs-3"><b id="tdc-nombre">TDC EURO</b></div>
                    <div class="col-xs-3" align="right"><b id="tdc-importacion">3.563</b></div>
                </div>
                <div style="clear: both;"></div>
            </div>
             <div style="padding-bottom: 3px;">
                <div class="col-xs-4">
                    <div class="col-xs-3"><b>Valor CIF</b></div>
                    <div class="col-xs-9" align="right"><b>$ <span id="CIF2"></span></b></div>
                </div>
                <div class="col-xs-4 col-xs-offset-4">
                    <div class="col-xs-3"><b></b></div>
                    <div class="col-xs-9"><b><!--origen--></b></div>
                </div>
                <div style="clear: both;"></div>
            </div>
            <div style="padding-bottom: 3px;">
                <div class="col-xs-4">
                    <div class="col-xs-3"><b>FOB DUA</b></div>
                    <div class="col-xs-9" align="right"><b>$ <span id="totalFOBDUA2"></span></b></div>
                </div>
                <div class="col-xs-4">
                    <div class="col-xs-3"><b>Flete DUA</b></div>
                    <div class="col-xs-9" align="right"><b>$ <span id="totalFleteDUA2"></span></b></div>
                </div>
                <div class="col-xs-4">
                    <div class="col-xs-3"><b>SEGURO</b></div>
                    <div class="col-xs-9" align="right"><b>$ <span id="totalSeguro2"></span></b></div>
                </div>
                <div style="clear: both;"></div>
            </div>
            <div>
                <div class="col-xs-4">
                    <div class="col-xs-3"><b>FOB</b></div>
                    <div class="col-xs-9" align="right"><b>$ <span id="totalFOB2"></span></b></div>
                </div>
                <div class="col-xs-4">
                    <div class="col-xs-3"><b>Flete</b></div>
                    <div class="col-xs-9" align="right"><b>$ <span id="totalFlete2"></span></b></div>
                </div>
                <div style="clear: both;"></div>
            </div>
            <hr style="border-color: #000">

            <!--Cuerpo de importacion-->
            <div style="padding-top: 20px;">
                <!--Detalle de productos-->
                <div class="col-xs-9">
                    <div style="max-height: 300px;overflow-y: auto">
                        <table border="0"  id="idTblDetalleArticulos2" class="table table-hover" style="font-size: 1rem;">
                            <colgroup>
                            <col span="2">
                            <col span="2" style="background-color: #f5f5f5;">
                            <col span="2">
                            <col span="2" style="background-color: #f5f5f5;">
                            <col span="2">
                        </colgroup>
                        <thead>
                            <tr>
                                <th style="text-align: center; vertical-align: middle;" rowspan="2">CANTIDAD</th>
                                <th style="text-align: center; vertical-align: middle;" rowspan="2">DESCRIPCION</th>
                                <th style="text-align: center; vertical-align: middle;" colspan="2">PRECIOS <span id="monedaPrecio"></span></th> 
                                <th style="text-align: center; vertical-align: middle;" colspan="2">CAMBIO US$</th>
                                <th style="text-align: center; vertical-align: middle;" colspan="2">GASTOS US$</th>
                                <th style="text-align: center; vertical-align: middle;" colspan="2">COSTOS US$</th>             
                            </tr>
                            <tr>
                                <th style="text-align: center; vertical-align: middle;">UNITARIO</th>
                                <th style="text-align: center; vertical-align: middle;">TOTAL</th>
                                <th style="text-align: center; vertical-align: middle;">UNITARIO</th>
                                <th style="text-align: center; vertical-align: middle;">TOTAL</th>
                                <th style="text-align: center; vertical-align: middle;">UNITARIO</th>
                                <th style="text-align: center; vertical-align: middle;">TOTAL</th>
                                <th style="text-align: center; vertical-align: middle;">UNITARIO</th>
                                <th style="text-align: center; vertical-align: middle;">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"><center><b>TOTALES</b></center></td>
                                <td align="right"><b id="tunifob"></b></td>
                                <td align="right"><b id="tfob"></b></td>
                                <td align="right"><b id="tfobSoles"></b></td>
                                <td align="right"><b id="tfobDolares"></b></td>
                                <td align="right"><b id="unitarioGastos"></b></td>
                                <td align="right"><b id="totalGastos"></b></td>
                                <td align="right"><b id="unitarioCostos2"></b></td>
                                <td align="right"><b id="totalCostos"></b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <hr style="height: 2px;background-color: transparent;">
                <div>
                    <div class="col-xs-6">
                        <table style="font-size: 1rem;width: 100%;" id="tblTotales">
                            <tbody style="border-bottom: 1px dotted #000;">
                                <tr>
                                    <td style="padding: 5px;"><b>TOTAL GASTOS ADUANA</b></td>
                                    <td align="right" style="padding: 5px;"><b><span id="totalzGastosAduana"></span></b></td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px;"><b>FLETE</b></td>
                                    <td align="right" style="padding: 5px;"><b><span id="totalzFlete"></span></b></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #000;color:#FFF">
                                    <td style="padding: 5px;"><b>TOTAL GASTOS USD</b></td>
                                    <td align="right" style="padding: 5px;"><b><span id="totalzGastos"></span></b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-xs-6">
                        <table style="font-size: 1rem;width: 100%;" id="tblTotales">
                            <tbody>
                                <tr style="border: 1px solid #000;">
                                    <td style="padding: 5px;"><b>% EX Fabrica</b></td>
                                    <td align="right" style="padding: 5px;"><b><span id="ex-fabrica2"></span> %</b></td>
                                </tr>
                                <tr style="border: 1px solid #000;">
                                    <td style="padding: 5px;"><b>% CIF</b></td>
                                    <td align="right" style="padding: 5px;"><b><span id="porc-cif2"></span> %</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                                <tr style="background-color: #F5FB6E">
                                    <td style="padding: 5px;"><b>COSTOS AGENTE ADUANA</b></td>
                                    <td align="right" style="padding: 5px;"><b><span id="costo-aduana2"></span></b></td>
                                </tr>
                                <tr>
                                    <td style="padding: 5px;"><b>% CIF</b></td>
                                    <td align="right" style="padding: 5px;"><b><span id="porc-2-cif2"></span> %</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </div>
            <!--Detalle de gastos-->
            <div class="col-xs-3">
                <div>
                    <table style="font-size: 1rem;width: 100%;" id="tblDerechos">
                        <thead>
                            <tr>
                                <th colspan="3">**DOLARES**</th>
                            </tr>
                        </thead>
                        <tbody>
                             <tr>
                                <td><b>AD. VALOREM</b></td>
                                <td align="right"><b><span id="porcentajeADValorem">0</span>%</b></td>
                                <td align="right" style="padding: 2px 3px;"><span id="totalADValorem"></span></td>
                            </tr>
                            <tr>
                                <td><b>I.G.V.</b></td>
                                <td align="right"><b><span id="porcentajeIGV2"></span>%</b></td>
                                <td align="right" style="padding: 2px 3px;"><span id="totalIGV2"></span></td>
                            </tr>
                            <tr>
                                <td><b>I.P.M.</b></td>
                                <td align="right"><b><span id="porcentajeIPM2"></span>%</b></td>
                                <td align="right" style="padding: 2px 3px;"><span id="totalIPM2"></span></td>
                            </tr>
                             <tr>
                                <td colspan="2"><b>TASA DE SERVICIOS</b></td>
                                <td align="right" style="padding: 2px 3px;"><span id="tsaServicios2"></span></td>
                            </tr>
                             <tr>
                                <td><b>PERCEPCIÓN I.G.V.</b></td>
                                <td align="right"><b><span id="porcentajePercepcion2">3.5</span>%</b></td>
                                <td align="right" style="padding: 2px 3px;"><span id="totalPercepcion2"></span></td>
                            </tr>
                        </tbody>
                        <tfoot style="background-color: #000;color:#FFF;padding: 2px;">
                            <tr>
                                <td colspan="2" style="padding: 2px 3px;"><b>TOTAL DERECHOS</b></td>
                                <td align="right" style="padding: 2px 3px;"><b><span id="totalDerechos2"></span></b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <hr style="height: 2px;background-color: #000">
                <div style="padding-top: 10px;">
                    <table style="font-size: 1rem;width: 100%;" id="tblGastosAdicionales2">
                        <thead style="border-bottom: 1px solid #000;">
                            <tr>
                                <th colspan="3">GASTOS ADICIONALES</th>
                            </tr>
                        </thead>
                        <tbody style="border-bottom: 1px dotted #000;">

                        </tbody>
                        <tfoot>
                            <tr>
                                <td><b>SUB TOTAL GASTOS</b></td>
                                <td align="right"><b><span id="subtotalGastos2"></span></b></td>
                            </tr>
                            <tr>
                                <td><b>I.G.V.</b></td>
                                <td align="right"><span id="gastosIGV2"></span></td>
                            </tr>
                            <tr style="background-color: #000;color:#FFF">
                                <td style="padding: 2px 3px;"><b>TOTAL GASTOS USD</b></td>
                                <td align="right" style="padding: 2px 3px;"><b><span id="totalGastosCIGV2"></span></b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <hr style="height: 2px;background-color: #000">
                <div>
                    <table style="font-size: 1rem;width: 100%;" id="tblDerechosGastos">
                        <tbody style="border-bottom: 1px dotted #000;">
                            <tr>
                                <td><b>TOTAL GASTOS</b></td>
                                <td align="right" style="padding: 2px 3px;"><b><span id="totaltGastosIGV2"></span></b></td>
                            </tr>
                            <tr>
                                <td><b>DERECHOS</b></td>
                                <td align="right" style="padding: 2px 3px;"><b><span id="totaltDerechos2"></span></b></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #000;color:#FFF">
                                <td style="padding: 2px 3px;"><b>TOTAL PROFORMA USD</b></td>
                                <td align="right" style="padding: 2px 3px;"><b><span id="totaltImportacion2"></span></b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <div class="modal-footer">
        <div class="row">
            <div class="col-xs-6">

            </div>
            <div class="col-xs-3" hidden>
                <button type="button" id="btnLiquidacion2" data-loading-text="Liquidando..." class="btn btn-sm" autocomplete="off">
                </button>
            </div>
            <div class="col-xs-3" hidden>
                <a target="_blank" onclick="reportePDF()"  class="btn btn-sm btn-danger">Exportar a PDF</a>
            </div>
        </div>
    </div>
</form>
</div>
</div>
</div>

</body>
</html>