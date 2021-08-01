
<script src="<?php echo base_url(); ?>js/jquery.columns.min.js?=<?=JS;?>"></script>
<link href="<?=base_url();?>js/fancybox/dist/jquery.fancybox.css?=<?=CSS;?>" rel="stylesheet">
<script src="<?=base_url();?>js/fancybox/dist/jquery.fancybox.js?=<?=JS;?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/importacion/importacionv1.js?=<?=JS;?>"></script>


<form id="frmImportacion" method="post">

    <div id="zonaContenido" align="center">
        <div id="frmBusqueda">
            <table class="fuente8" width="100%" cellspacing="0" cellpadding="5" border="0" >
                <tr>
                    <td width="">Número*
                        <input class="cajaGeneral" name="serie" type="text" id="serie" size="3" maxlength="4" placeholder="SERIE"/>&nbsp;
                        <input class="cajaGeneral" name="numero" type="text" id="numero" size="6" maxlength="8" placeholder="NÚMERO"/></td>
                    <td ></td>
                    <td></td>
                </tr>

               <tr>

                   <td>
                       Comisión del broker*
                       <input type="text" name="comision_broker_porcentaje1" onkeypress="return numbersonly(this,event,'.');" class="cajaMinima"  id="comision_broker_porcentaje1"> %
                   </td>
                   <td>
                       Monto de gastos en  en China
                       <input type="text" name="montogastoenchinaopcional" id="montogastoenchinaopcional" class="cajaPequena" onkeypress="return numbersonly(this,event,'.');">
                   </td>
                   <td>
                       Flete internacional
                       <input type="text" name="fleteinternacional" id="fleteinternacional" class="cajaPequena" onkeypress="return numbersonly(this,event,'.');">
                   </td>

               </tr>
               <tr>
                   <td>
                       Seguro internacional
                       <input type="text" name="segurointernacional" id="segurointernacional" class="cajaPequena" onkeypress="return numbersonly(this,event,'.');">
                   </td>
                   <td>
                      Percepción
                       <input type="text" name="percepcion" id="percepcion" class="cajaPequena" onkeypress="return numbersonly(this,event,'.');">
                      % TDC

                       Dolar : &nbsp;
                       <input name="tdcDolar" type="text" class="cajaGeneral cajaSoloLectura" style="width: 28px" id="tdcDolar" size="3" value="<?php echo $tdcDolar; ?>" onkeypress="return numbersonly(this,event,'.');" readonly="readonly"/>&nbsp;
                   </td>
                   <td>
                       Flete de Callao a Almacén
                       <input type="text" name="fletecallaoalmacen" id="fletecallaoalmacen" class="cajaPequena" onkeypress="return numbersonly(this,event,'.');">
                   </td>

               </tr>
                <tr>
                    <td >
                        Gastos de Descarga <small style="display: block">de personal del contenedor al almacén importador</small>
                        <input type="text" name="gastodescarga" id="gastodescarga" class="cajaPequena" onkeypress="return numbersonly(this,event,'.');">
                    </td>
                    <td >
                        Gastos de facilidades en Aduanas
                        <input type="text" name="facilidadesaduanas" id="facilidadesaduanas" class="cajaPequena" onkeypress="return numbersonly(this,event,'.');">
                    </td>
                    <td >
                        Gastos que cobra Aduanas
                        <input type="text" name="gastoQcobraAduanas" id="gastoQcobraAduanas" class="cajaPequena" onkeypress="return numbersonly(this,event,'.');">
                    </td>
                </tr>
                <tr>
                    <td >
                        Utilidad <small style="display: block">porcentaje de ulitidad de los productos</small>
                        <input type="text" name="porcentajeutilidadproducto" id="porcentajeutilidadproducto" class="cajaPequena" onkeypress="return numbersonly(this,event,'.');">
                    %
                    </td>
                    <td>
                        Proveedor*
                        <input type="hidden" name="idproveedor" id="idproveedor" size="5" value="<?php echo $proveedor ?>"/>
                        <input name="buscar_proveedor" type="text" class="cajaGeneral" id="buscar_proveedor" size="10" placeholder="ruc"  title="Ingrese parte del nombre o el nro. de documento, luego presione ENTER."/>&nbsp;
                        <input type="text" name="nombre_proveedor" class="cajaGeneral cajaSoloLectura" id="nombre_proveedor" size="25" maxlength="50" placeholder="razon social"/>
                    </td>
                </tr>

            </table>
        </div>
        <div id="frmBusqueda"  <?php echo $hidden; ?> class="box-add-product" style="text-align: right;" >
            <a href="#" style="color:#ffffff;" class="btn btn-primary"  onclick="abrir_modal(); ">Agregar Items</a></td>
        </div>
        <!-- TABLA DETALLE DE TEMPORAL -->
        <?php $this->load->view('maestros/temporal_subdetalles_importacion'); ?>
        <!-- FIN DE TABLA TEMPORAL DETALLE -->
        <div id="frmBusqueda3">
            <table width="100%" border="0" align="right" cellpadding="3" cellspacing="0" class="fuente8">
                <tr>
                    <td width="75%" align="right"></td>
                    <td class="busqueda">Percepción en Dolares</td>
                    <td align="right">
                        <div align="right"><input class="cajaTotales" name="percepciondolares" type="text" id="percepciondolares" size="12" align="right" readonly="readonly" value="<?=(isset($inafectototal)) ? round($inafectototal, 2) : '0';?>"/></div>
                    </td>
                </tr>
                <tr>
                    <td width="75%" align="right"></td>
                    <td class="busqueda">Percepción en soles</td>
                    <td align="right">
                        <div align="right"><input class="cajaTotales" name="percepcionsoles" type="text" id="percepcionsoles" size="12" align="right" readonly="readonly" value="<?=(isset($gratuitatotal)) ? round($gratuitatotal, 2) : '0';?>"/></div>
                    </td>
                </tr>

            </table>
        </div>


        <div id="botonBusqueda2" style="padding-top:20px;">
            <img id="loading" src="<?php echo base_url(); ?>images/loading.gif?=<?=IMG;?>" style="visibility: hidden"/>

            <a href="javascript:;" id="grabarImportacion"><img src="<?php echo base_url(); ?>images/botonaceptar.jpg?=<?=IMG;?>" width="85" height="22" class="imgBoton"></a>


            <a href="javascript:;" onclick="limpiarDatos()"><img src="<?php echo base_url(); ?>images/botonlimpiar.jpg?=<?=IMG;?>" width="69" height="22" class="imgBoton"></a>
            <a href="<?php echo base_url()."index.php/ventas/importacion/comprobantes"?>"" "><img src="<?php echo base_url(); ?>images/botoncancelar.jpg?=<?=IMG;?>" width="85" height="22" class="imgBoton"></a>
            <input type="hidden" name="salir" id="salir" value="0"/>

        </div>
    </div>


    <style type="text/css">
        #popup {
            left: 0;
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 1001;
        }

        .content-popup {
            margin:0px auto;
            margin-top:150px;
            position:relative;
            padding:10px;
            width:300px;
            min-height:150px;
            border-radius:4px;
            background-color:#FFFFFF;
            box-shadow: 0 2px 5px #666666;
        }

        .content-popup h2 {
            color:#48484B;
            border-bottom: 1px solid #48484B;
            margin-top: 0;
            padding-bottom: 4px;
        }

        .popup-overlay {
            left: 0;
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 999;
            display:none;
            background-color: #777777;
            cursor: pointer;
            opacity: 0.7;
        }

        .close {
            position: absolute;
            right: 15px;
        }
        #btnInventario{
            size: 20px;
            width: 200px;
            height: 50px;
            border-radius: 33px 33px 33px 33px;
            -moz-border-radius: 33px 33px 33px 33px;
            -webkit-border-radius: 33px 33px 33px 33px;
            border: 0px solid #000000;
            background-color:rgba(199, 255, 206, 1);
        }
    </style>

</form>

<?php  $this->load->view('maestros/temporal_detalles_importacion'); ?>

<script>
    $("#nombre_proveedor").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "<?php echo base_url(); ?>index.php/compras/proveedor/autocomplete/",
                type: "POST",
                data: { term: $("#nombre_proveedor").val() },
                dataType: "json",
                success: function (data) {
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            $("#buscar_proveedor").val(ui.item.ruc);
            $("#nombre_proveedor").val(ui.item.nombre);
            $("#idproveedor").val(ui.item.codigo);
            $("#ruc_proveedor").val(ui.item.ruc);
            $("#codigoEmpresa").val(ui.item.codigoEmpresa);
        },
        minLength: 2
    });

    $('#grabarImportacion').click(function (){
        let serie =$('#serie').val();
        let numero =$('#numero').val();
        let comision_broker_porcentaje1 =$('#comision_broker_porcentaje1').val();
        let fleteinternacional =$('#fleteinternacional').val();
        let segurointernacional =$('#segurointernacional').val();
        let percepcion =$('#percepcion').val();
        let montogastoenchinaopcional = $('#montogastoenchinaopcional').val();
        let fletecallaoalmacen = $('#fletecallaoalmacen').val();
        let gastodescarga = $('#gastodescarga').val();
        let facilidadesaduanas = $('#facilidadesaduanas').val();
        let gastoQcobraAduanas = $('#gastoQcobraAduanas').val();
        let porcentajeutilidadproducto = $('#porcentajeutilidadproducto').val();
        let nombre_proveedor = $('#nombre_proveedor').val();

        if (!serie){
            MensajeAlerta('Ingrese la Serie del comprobante');
            $("#serie").focus();
            return false;
        }
        if (!numero){
            MensajeAlerta('Ingrese el Número del comprobante');
            $("#numero").focus();
            return false;
        }

        if(!comision_broker_porcentaje1){
            MensajeAlerta('Ingrese la comisión del BROKER');
            $("#comision_broker_porcentaje1").focus();
            return false;
        }
        if(comision_broker_porcentaje1 > 100){
            MensajeAlerta('la comisión del broker debe ser entre 0% a 100%');
            $("#comision_broker_porcentaje1").focus();
            return false;
        }

        if (!montogastoenchinaopcional){
            MensajeAlerta('Ingrese el monto de gastos en China');
            $("#montogastoenchinaopcional").focus();
            return false;
        }

        if(!fleteinternacional){
            MensajeAlerta('Ingrese el flete Internacional');
            $("#fleteinternacional").focus();
            return false;
        }
        if(!segurointernacional){
            MensajeAlerta('Ingrese el Seguro Internacional');
            $("#segurointernacional").focus();
            return false;
        }
        if(!percepcion){
            MensajeAlerta('Ingrese la percepción');
            $("#percepcion").focus();
            return false;
        }
        if(percepcion > 100){
            MensajeAlerta('la percepción debe ser entre 0% a 100%');
            $("#percepcion").focus();
            return false;
        }


        if (!fletecallaoalmacen){
            MensajeAlerta('Ingrese el flete del Callao a Almacén');
            $("#fletecallaoalmacen").focus();
            return false;
        }

        if (!gastodescarga){
            MensajeAlerta('Ingrese los gastos de Descarga');
            $("#gastodescarga").focus();
            return false;
        }

        if (!facilidadesaduanas){
            MensajeAlerta('Ingrese los gastos de Facilidades en Aduanas');
            $("#facilidadesaduanas").focus();
            return false;
        }

        if (!gastoQcobraAduanas){
            MensajeAlerta('Ingrese los gastos que Cobra Aduanas');
            $("#gastoQcobraAduanas").focus();
            return false;
        }
        if (!porcentajeutilidadproducto){
            MensajeAlerta('Ingrese el porcentaje de utilidad ');
            $("#porcentajeutilidadproducto").focus();
            return false;
        }

        if(porcentajeutilidadproducto > 100){
            MensajeAlerta('la utilidad debe ser entre 0% a 100%');
            $("#porcentajeutilidadproducto").focus();
            return false;
        }
        if (!nombre_proveedor)
        {
            MensajeAlerta('Ingrese el Proveedor');
            $("#nombre_proveedor").focus();
            return false;

        }
        if (arrayGlobalCostoUnitarioSoles.length==0){
            MensajeAlerta('Ingrese Por lo menos Un Item');

            return false;
        }
        dataString = $('#frmImportacion').serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url()?>index.php/ventas/importacion/crear_importacion",
            data : dataString,
            dataType: 'json',
            error: function(data) {
                Swal.fire({
                    icon: "error",
                    title: "No se puedo completar la operación - Revise los campos ingresados.",
                    showConfirmButton: false,
                    timer: 2000
                });
            },
            success: function(data){
                Swal.fire({
                    icon: "success",
                    title: "Documento  generado Correctamente.",
                    showConfirmButton: true
                }).then(function(){
                    if (data.result == true)
                        location.href = "<?php echo base_url()?>index.php/ventas/importacion/comprobantes";
                });

            }
        });
    });

    function limpiarDatos(){
        let serie =$('#serie').val('');
        let numero =$('#numero').val('');
        let comision_broker_porcentaje1 =$('#comision_broker_porcentaje1').val('');
        let fleteinternacional =$('#fleteinternacional').val('');
        let segurointernacional =$('#segurointernacional').val('');
        let percepcion =$('#percepcion').val('');
        let montogastoenchinaopcional = $('#montogastoenchinaopcional').val('');
        let fletecallaoalmacen = $('#fletecallaoalmacen').val('');
        let gastodescarga = $('#gastodescarga').val('');
        let facilidadesaduanas = $('#facilidadesaduanas').val('');
        let gastoQcobraAduanas = $('#gastoQcobraAduanas').val('');
        let porcentajeutilidadproducto = $('#porcentajeutilidadproducto').val('');
        let nombre_proveedor = $('#nombre_proveedor').val('');
        let buscar_proveedor = $('#buscar_proveedor').val('');
    }
</script>


