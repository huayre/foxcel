<script type="text/javascript" src="<?php echo base_url(); ?>js/compras/ocompra.js?=<?=JS;?>"></script>
<link href="<?=base_url();?>js/fancybox/dist/jquery.fancybox.css?=<?=CSS;?>" rel="stylesheet">
<script src="<?=base_url();?>js/fancybox/dist/jquery.fancybox.js?=<?=JS;?>"></script>

<script type="text/javascript">
    var base_url = '<?=base_url();?>';
</script>
<script type="text/javascript" src="<?=base_url();?>js/nicEdit/nicEdit.js?=<?=JS;?>"></script>

<script type="text/javascript">
    bkLib.onDomLoaded(function() {
        new nicEditor({fullPanel : true}).panelInstance('mensaje');
    });

    $(document).ready(function () {
        $("a#linkVerProveedor").fancybox({
            'width': 700,
            'height': 450,
            'autoScale': false,
            'transitionIn': 'none',
            'transitionOut': 'none',
            'showCloseButton': false,
            'modal': true,
            'type': 'iframe'
        });

        $("a#linkVerProducto").fancybox({
            'width': 800,
            'height': 650,
            'autoScale': false,
            'transitionIn': 'none',
            'transitionOut': 'none',
            'showCloseButton': false,
            'modal': true,
            'type': 'iframe'
        });
    });
</script>

<div id="pagina">
    <div id="zonaContenido">
        <div align="center">
            <div id="tituloForm" class="header"><?php echo $titulo_busqueda; ?></div>
            <div id="frmBusqueda">
                <form id="form_busqueda" name="form_busqueda" method="post">
                        <div class="row">
                            
                            <div class="col-sm-1 col-md-1 col-lg-1 form-group">
                                <label for="fechai">Desde: </label>
                            </div>
                            <div class="col-sm-2 col-md-2 col-lg-2 form-group">
                                <input id="fechai" name="fechai" type="date" class="form-control w-porc-90 h-1" placeholder="fecha inicial" maxlength="30" value="">
                            </div>
                            <div class="col-sm-1 col-md-1 col-lg-1 form-group">
                                <label for="fechaf">Hasta: </label>
                            </div>
                            <div class="col-sm-2 col-md-2 col-lg-2 form-group">
                                <input id="fechaf" name="fechaf" type="date" class="form-control w-porc-90 h-1" maxlength="100" placeholder="fecha fin" value="">
                            </div>
                            
                                                
                        </div>

                        <div class="row">
                            <?php if ($tipo_oper == 'V') { ?>
                            <div class="col-sm-1 col-md-1 col-lg-1 form-group">
                                <label for="nombre_cliente">Cliente: </label>
                            </div>
                            <div class="col-sm-2 col-md-2 col-lg-2 form-group">

                                <input id="ruc_cliente" name="ruc_cliente" type="text" class="form-control w-porc-90 h-1" placeholder="RUC/DNI" value="">
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4 form-group">
                                <input id="nombre_cliente" name="nombre_cliente" type="text" class="form-control w-porc-90 h-1"  placeholder="DENOMINACION" value="">
                            </div>
                            <?php } else { ?>
                            <div class="col-sm-1 col-md-1 col-lg-1 form-group">
                                <label for="nombre_proveedor">Proveedor: </label>
                            </div>
                            <div class="col-sm-2 col-md-2 col-lg-2 form-group">
                                <input id="ruc_proveedor" name="ruc_proveedor" type="text" class="form-control w-porc-90 h-1" placeholder="RUC/DNI" value="">
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-4 form-group">
                                <input id="nombre_proveedor" name="nombre_proveedor" type="text" class="form-control w-porc-90 h-1"  placeholder="DENOMINACION" value="">
                            </div>
                            <?php } ?>
                            <div class="col-sm-1 col-md-1 col-lg-1 form-group">
                                <label for="cboVendedor">Vendedor: </label>
                            </div>
                            <div class="col-sm-2 col-md-2 col-lg-2 form-group">
                                <select id="cboVendedor" name="cboVendedor" class="form-control h-2">
                                    <?=$cboVendedor;?>
                                </select>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1 form-group">
                                <label for="fechaf">Serie: </label>
                            </div>
                            <div class="col-sm-1 col-md-1 col-lg-1 form-group">
                                <input id="serie" name="serie" type="text" class="form-control w-porc-90 h-1" maxlength="100" placeholder="serie" value="">
                            </div>
                            <div class="col-sm-1 col-md-1 col-lg-1 form-group">
                                <label for="numero">Numero: </label>
                            </div>
                            <div class="col-sm-1 col-md-1 col-lg-1 form-group">
                                <input id="numero" name="numero" type="text" class="form-control w-porc-90 h-1" maxlength="100" placeholder="numero" value="">
                            </div>
                            <div hidden>
                            <div class="col-sm-1 col-md-1 col-lg-1 form-group">
                            <label for="numeroI">N° Inicio</label> 
                            </div>
                            <div class="col-sm-1 col-md-1 col-lg-1 form-group">
                            <input type="number" step="1" min="1" name="numeroI" id="numeroI" size="3" class="form-control w-porc-90 h-1"/>
                            </div>
                            <div class="col-sm-1 col-md-1 col-lg-1 form-group" >
                            <label for="numeroF">N° Fin</label>
                            </div>
                            <div class="col-sm-1 col-md-1 col-lg-1 form-group" >
                            <input type="number" step="1" min="1" name="numeroF" id="numeroF" size="3" class="form-control w-porc-90 h-1"/>
                            </div>
                            <div class="col-sm-1 col-md-1 col-lg-1" >
                            <?php if ($tipo_oper == "V"){ ?>
                            <span id="imprimirOcompraFiltro"> <a href="#"  class="element"> <img src="<?=base_url().'images/pdf.png?='.IMG;?>" height=""> </a> </span>
                            </div>
                            <div class="col-sm-1 col-md-1 col-lg-1">
                            <span id="downloadOcompra"> <a href="#"  class="element"> <img src="<?=base_url().'images/pdf.png?='.IMG;?>" height=""> </a> </span>
                            <?php } ?>
                            </div>
                        </div>
                        </div>
                </form>
            </div>
            <div id="cargarBusqueda" >
                <div class="acciones">
                    <div id="botonBusqueda"> <?php
                        if ($tipo_oper == "V"){ ?>
                            <ul id="downloadOcompra" class="lista_botones">
                                <li id="excel">Cotizados</li>
                            </ul>
                            <ul id="imprimirOcompra" class="lista_botones">
                                <li id="imprimir">Imprimir</li>
                            </ul> <?php
                        } ?>
                        <ul id="nuevoOcompa" class="lista_botones">
                            <li id="nuevo">Cotiz. de <?=($tipo_oper == 'V') ? 'Venta' : 'Compra';?></li>
                        </ul>
                        <ul id="limpiarO" class="lista_botones">
                            <li id="limpiar">Limpiar</li>
                        </ul>
                        <ul id="buscarO" class="lista_botones">
                            <li id="buscar">Buscar</li>
                        </ul>
                    </div>
                    <div id="lineaResultado">
                        <table class="fuente7" width="100%" cellspacing="0" cellpadding="3" border="0">
                            <tr>
                                <td width="50%" align="left">N de registros encontrados:&nbsp;<?php echo $registros; ?> </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="cabeceraResultado" class="header"><?php echo $titulo_tabla; ?></div>
                <div id="frmResultado">
                    <div id="cargando_datos" class="loading-table">
                        <img src="<?=base_url().'images/loading.gif?='.IMG;?>">
                    </div>
                    <form id="frmEvaluar" name="frmEvaluar" method="post" action="<?php echo base_url(); ?>index.php/compras/ocompra/evaluar_ocompra/">
                        <input type="hidden" value="" id="flag" name="flag"/>
                        <table class="fuente8 display" width="100%" cellspacing="0" cellpadding="3" border="0" id="table-ocompra">
                            <thead>
                                <tr class="cabeceraTabla">
                                    <th style="width:10%" data-orderable="true">FECHA</th>
                                    <th style="width:5%" data-orderable="false">NÚMERO</th>
                                    <th style="width:20%" data-orderable="false">RAZON SOCIAL</th>
                                    <th style="width:5%" data-orderable="false"></th>
                                    
                                    <th style="width:8%" data-orderable="false"></th>
                                    <th style="width:4%" data-orderable="false"></th>
                                    <th style="width:4%" data-orderable="false"></th>
                                    
                                </tr>
                            </thead>
                            <tbody> <?php
                                ?>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div style="margin-top: 15px;"><?php echo $paginacion; ?></div>
            </div>
            <?php echo $oculto ?>
        </div>
    </div>
</div>

<div class="modal fade modal-envmail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width: 700px; height: auto; margin: auto; font-family: Trebuchet MS, sans-serif; font-size: 10pt;">
            <form method="post" id="form-mail">
                <div class="contenido" style="width: 100%; margin: auto; height: auto; overflow: auto;">
                    <div class="tempde_head">

                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-7 col-md-7 col-lg-7" style="text-align: center;">
                                <h3>ENVIO DE DOCUMENTOS POR CORREO</h3>
                            </div>
                        </div>

                        <input type="hidden" id="idDocMail" name="idDocMail">
                    </div>

                    <div class="tempde_body">
                        
                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-5 col-md-5 col-lg-5">
                                <label for="ncliente">Cliente:</label>
                            </div>
                            <div class="col-sm-2 col-md-2 col-lg-2">
                                <label for="doccliente">Ruc / Dni:</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-5 col-md-5 col-lg-5">
                                <input type="text" class="form-control" id="ncliente" name="ncliente" value="" placeholder="Razón social" readonly>
                            </div>
                            <div class="col-sm-2 col-md-2 col-lg-2">
                                <input type="text" class="form-control" id="doccliente" name="doccliente" value="" placeholder="N° documento" readonly>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-7 col-md-7 col-lg-7">
                                <label for="destinatario">Destinatarios:</label>
                                <span class="mail-contactos"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-7 col-md-7 col-lg-7">
                                <input type="text" class="form-control" id="destinatario" name="destinatario" value="" placeholder="Correo">
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-7 col-md-7 col-lg-7">
                                <label for="asunto">Asunto:</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-7 col-md-7 col-lg-7">
                                <input type="text" class="form-control" id="asunto" name="asunto" value="" placeholder="Asunto">
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-7 col-md-7 col-lg-7">
                                <label for="mensaje">Mensaje:</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1 col-md-1 col-lg-1"></div>
                            <div class="col-sm-7 col-md-7 col-lg-7">
                                <textarea id="mensaje" name="mensaje" style="width: 520px; height: 300px">
                                    <p><b>SRES.</b> <span class="mail-cliente"></span></p>
                                    <p><span class="mail-empresa-envio"></span>, ENVÍA UN DOCUMENTO ELECTRÓNICO.</p>
                                    <p><b>SERIE Y NÚMERO:</b> <span class="mail-serie-numero"></span></p>
                                    <p><b>FECHA DE EMISIÓN:</b> <span class="mail-fecha"></span></p>
                                    <p><b>IMPORTE:</b> <span class="mail-importe"></span></p>
                                </textarea>
                            </div>
                        </div>
                        <br>

                    </div>
                    <div class="tempde_footer">
                        <div class="row">
                            <div class="col-sm-6 col-md-6 col-lg-6"></div>
                            <div class="col-sm-4 col-md-4 col-lg-4">
                                <span class="icon-loading-md"></span>
                                <div style="float: right">
                                    <span class="btn btn-success btn-sendMail">Enviar</span>
                                    &nbsp;
                                    <span class="btn btn-danger btn-close-envmail">Cerrar</span>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        
        $("#nombre_producto").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "<?php echo base_url(); ?>index.php/maestros/temporaldetalle/autocomplete_producto/B//",
                    type: "POST",
                    data: {
                        term: $("#nombre_producto").val(), TipCli: "", marca: "", modelo: "" 
                    },
                    dataType: "json",
                    success: function (data) {
                        response(data);
                    }
                });
            },
            select: function (event, ui) {
                $("#producto").val(ui.item.codigo);
                $("#nombre_producto").val(ui.item.descripcion);
            },
            minLength: 2
        });

        $("#nombre_producto").keyup(function(){
            var cadena = $("#nombre_producto").val();
            if ( cadena.length == 0 ){
                $("#producto").val("");
            }
        });
    
        $('#table-ocompra').DataTable({
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax:{
                    url : "<?=base_url();?>index.php/compras/ocompra/datatable_seguimientoOcompra/<?=$tipo_oper;?>",
                    type: "POST",
                    data: { dataString: "" },
                    beforeSend: function(){
                        $(".loading-table").show();
                    },
                    error: function(){
                    },
                    complete: function(){
                        $(".loading-table").hide();
                    }
            },
            language: spanish,
            order: [[ 1, "desc" ]]
        });

        $("#buscarO").click(function(){

            fechai          = $("#fechai").val();
            fechaf          = $("#fechaf").val();
            nombre_cliente  = $("#nombre_cliente").val();
            ruc_cliente     = $("#ruc_cliente").val();
            nombre_proveedor = $("#nombre_proveedor").val();
            ruc_proveedor   = $("#ruc_proveedor").val();
            producto        = $("#producto").val();
            aprobado        = $("#aprobado").val();
            ingreso         = $("#ingreso").val();
            cboVendedor     = $("#cboVendedor").val();
            codigoEmpleado  = $("#codigoEmpleado").val();
            serie           = $("#serie").val();
            numero          = $("#numero").val();

            $('#table-ocompra').DataTable({
                filter: false,
                destroy: true,
                processing: true,
                serverSide: true,
                ajax:{
                        url : "<?=base_url();?>index.php/compras/ocompra/datatable_seguimientoOcompra/<?=$tipo_oper;?>",
                        type: "POST",
                        data: {
                                fechai: fechai, 
                                fechaf: fechaf,
                                nombre_cliente: nombre_cliente,
                                ruc_cliente: ruc_cliente,
                                nombre_proveedor: nombre_proveedor,
                                ruc_proveedor: ruc_proveedor,
                                producto: producto,
                                aprobado: aprobado,
                                ingreso: ingreso,
                                cboVendedor: cboVendedor,
                                serie: serie,
                                numero: numero,
                                codigoEmpleado: codigoEmpleado
                        },
                        beforeSend: function(){
                            $(".loading-table").show();
                        },
                        error: function(){
                        },
                        complete: function(){
                            $(".loading-table").hide();
                        }
                },
                language: spanish,
                order: [[ 1, "desc" ]]
            });
        });

        $("#limpiarO").click(function(){

            $("#fechai").val("");
            $("#fechaf").val("");
            $("#nombre_cliente").val("");
            $("#ruc_cliente").val("");
            $("#nombre_proveedor").val("");
            $("#ruc_proveedor").val("");
            $("#producto").val("");
            $("#aprobado").val("");
            $("#ingreso").val("");
            $("#cboVendedor").val("");
            $("#codigoEmpleado").val("");
            $("#serie").val("");
            $("#numero").val("");

            fechai = "";
            fechaf = "";
            nombre_cliente = "";
            ruc_cliente = "";
            nombre_proveedor = "";
            ruc_proveedor = "";
            producto = "";
            aprobado = "";
            ingreso = "";
            cboVendedor = "";
            codigoEmpleado = "";
            serie = "";
            numero = "";

            $('#table-ocompra').DataTable({
                filter: false,
                destroy: true,
                processing: true,
                serverSide: true,
                ajax:{
                        url : "<?=base_url();?>index.php/compras/ocompra/datatable_seguimientoOcompra/<?=$tipo_oper;?>",
                        type: "POST",
                        data: {
                                fechai: fechai, 
                                fechaf: fechaf,
                                nombre_cliente: nombre_cliente,
                                ruc_cliente: ruc_cliente,
                                nombre_proveedor: nombre_proveedor,
                                ruc_proveedor: ruc_proveedor,
                                producto: producto,
                                aprobado: aprobado,
                                ingreso: ingreso,
                                cboVendedor: cboVendedor,
                                serie: serie,
                                numero: numero,
                                codigoEmpleado: codigoEmpleado
                        },
                        beforeSend: function(){
                            $(".loading-table").show();
                        },
                        error: function(){
                        },
                        complete: function(){
                            $(".loading-table").hide();
                        }
                },
                language: spanish,
                order: [[ 1, "desc" ]]
            });
        });

        $(".btn-close-envmail").click(function(){
            $(".modal-envmail").modal("hide");
        });

        $(".btn-sendMail").click(function(){

            documento = '<?=(isset($id_documento)) ? $id_documento : "";?>';
            codigo = $("#idDocMail").val();

            destinatario = $("#destinatario").val();
            asunto = $("#asunto").val();
            mensaje = $(".nicEdit-main").html();

            if ( codigo == ""){
                Swal.fire({
                        icon: "warning",
                        title: "Sin documento. Intentelo nuevamente.",
                        showConfirmButton: true,
                        timer: 2000
                });
                return null;
            }

            if ( destinatario == ""){
                Swal.fire({
                        icon: "warning",
                        title: "Debe ingresar un destinatario.",
                        showConfirmButton: true,
                        timer: 2000
                });
                $("#destinatario").focus();
                return null;
            }
            else{
                correosI = destinatario.split(",");
                /* DEVELOPING
                expr = /^[a-zA-Z0-9@_.\-]{1,3}$/
                
                for (var i = 0; i < correosI.length - 1; i++) {
                    if ( expr.test(correosI[i]) == false ){
                        alert("Verifique que todos los correos indicados sean validos.");
                    }
                }*/
            }

            if ( asunto == ""){
                Swal.fire({
                        icon: "warning",
                        title: "Indique el asunto del correo.",
                        showConfirmButton: true,
                        timer: 2000
                });
                $("#asunto").focus();
                return null;
            }

            var url = "<?=base_url();?>index.php/compras/ocompra/sendDocMail";
            $.ajax({
                url:url,
                type:"POST",
                data:{ codigo: codigo, destinatario: destinatario, asunto: asunto, mensaje: mensaje, documento: documento },
                dataType:"json",
                error:function(data){
                },
                beforeSend: function(){
                    $(".tempde_footer .icon-loading-md").show();
                    $(".btn-sendMail").hide();
                },
                success:function(data){
                    if (data.result == "success"){
                        Swal.fire({
                            icon: "success",
                            title: "Correo electronico enviado.",
                            showConfirmButton: false,
                            timer: 2000
                        });
                        $(".modal-envmail").modal("hide");
                    }
                    else{
                        Swal.fire({
                            icon: "error",
                            title: "Correo no enviado, intentelo nuevamente.",
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                },
                complete: function(){
                    $(".tempde_footer .icon-loading-md").hide();
                    $(".btn-sendMail").show();
                }
            });
        });
    });

    function open_mail( id ){
        $(".modal-envmail").modal("toggle");

        url = "<?=base_url();?>index.php/compras/ocompra/getInfoSendMail";

        $.ajax({
            url: url,
            type: "POST",
            data:{ id: id },
            dataType: "json",
            error: function(){

            },
            beforeSend: function(){
                $("#idDocMail").val("");
                $("#ncliente").val("");
                $("#doccliente").val("");
                $("#doccliente").val("");
                $("#destinatario").val("");

                $(".mail-cliente").html("");
                $(".mail-empresa-envio").html("");
                $(".mail-serie-numero").html("");
                $(".mail-fecha").html("");
                $(".mail-importe").html("");

                $(".mail-contactos").html("");
            },
            success: function(data){
                var info = data.info[0];

                if (data.match == true){
                    $("#idDocMail").val(info.codigo);
                    $("#ncliente").val(info.nombre);
                    $("#doccliente").val(info.ruc);
                    $("#doccliente").val(info.ruc);

                    $(".mail-cliente").html(info.nombre);
                    $(".mail-empresa-envio").html(info.empresa_envio);
                    $(".mail-serie-numero").html(info.serie + " - " + info.numero );
                    $(".mail-fecha").html(info.fecha);
                    $(".mail-importe").html(info.importe);

                    $.each(info.contactos, function(i, item){
                        var inputsContactos = "&nbsp;<input type='checkbox' class='ncontacto' onclick='ingresar_correo(\"" + item.correo + "\")' value='" + item.correo + "'>" + item.contacto;
                        $(".mail-contactos").append(inputsContactos);
                    });
                }
            }
        });
    }



    function ingresar_correo(correo){

        destinatarios = $("#destinatario").val();

        correosI = destinatarios.split(",");
        cantidad = correosI.length;

        add = true;

        for (i = 0; i < cantidad; i++){
            if ( correosI[i] == correo )
                add = false;
        }

        if ( add == true){
            if (destinatarios != "")
                $("#destinatario").val(destinatarios + "," + correo);
            else
                $("#destinatario").val(correo);
        }
        else{
            nvoCorreos = "";
            for (i = 0; i < cantidad; i++){
                if ( correosI[i] != correo ){
                    if ( i > 0 && nvoCorreos != "" ){
                        nvoCorreos += ",";
                    }
                    
                    nvoCorreos += correosI[i];
                }
            }

            $("#destinatario").val(nvoCorreos);
        }
    }

</script>