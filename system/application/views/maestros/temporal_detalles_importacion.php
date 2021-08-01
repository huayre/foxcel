<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="width: 70%; margin: auto; font-family: Trebuchet MS, sans-serif; font-size: 18px;">
    <!--<div class="total" style="overflow: auto; padding: 2%;">-->
      	<div class="titulo" style="text-align: center;">
      		<h3>Detalle de la LINEA o ITEM de IMPORTACIÓN</h3>
      	</div>
      	<form id="form_tempdetalle" method="post">
      	<div class="contenido" style="width: 90%; margin: auto; overflow: auto;">
	      	<div class="tempde_head">
		      	<div>
		      		Producto - Servicio (CATALOGO)
		      	</div>
		      	<div class="cajaCabecera">
		      		<input type="text" class="form-control" style="display: inline-block; width: 80%;" name="producto" id="producto">

                </div>
                <input  type="hidden" name="id_producto" id="id_producto">
		      	<div class="cajaCabecera">
		      		<span id="tempde_message" style="display: block;"></span>
		      	</div>
		    </div>

		    <div class="tempde_body">
                <div class="row">
                    <div class="col-sm-3 col-md-3 col-lg-3">
                        <label>Cantidad *</label>
                        <input type="text" name="cantidad_producto" id="cantidad_producto" onkeypress="return numbersonly(this,event,'.');" onkeyup="calcular_temProducto_modal();" class="form-control">
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-3">
                        <label>Precio FÁBRICA *</label>
                        <input type="text" name="precio_unitario_producto_fabrica" id="precio_unitario_producto_fabrica"  onkeypress="return numbersonly(this,event,'.');" onkeyup="calcular_temProducto_modal()" class="form-control">
                    </div>

                    <div class="col-sm-3 col-md-3 col-lg-3">
                        <label>Precio referencial ADUANAS *</label>
                        <input type="text" name="precio_unitario_aduanas" id="precio_unitario_aduanas"  onkeypress="return numbersonly(this,event,'.');" onkeyup="calcular_temProducto_modal()" class="form-control">
                    </div>


                </div>
                <div class="row">
                    <div class="col-sm-3 col-md-3 col-lg-3">
                        <label>ADVALOREM</label>
                        <input type="text" name="advalorem" id="advalorem" onkeypress="return numbersonly(this,event,'.');" onkeyup="calcular_temProducto_modal();" class="form-control">
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-3">
                        <label>IGV</label>
                        <input type="text" name="igvproducto" id="igvproducto" onkeypress="return numbersonly(this,event,'.');" onkeyup="calcular_temProducto_modal();" class="form-control">
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-3">
                        <label>IPM</label>
                        <input type="text" name="ipmproducto" id="ipmproducto" onkeypress="return numbersonly(this,event,'.');" onkeyup="calcular_temProducto_modal();" class="form-control">
                    </div>
                </div>

		     	<div class="row">
		     		<div class="col-sm-4 col-md-4 col-lg-4"></div>
		     		<div class="col-sm-3 col-md-3 col-lg-3"></div>
		     		<div class="col-sm-3 col-md-3 col-lg-3" style="margin: 8px 0px">
		     			<button type="button" id="tempde_aceptar" onclick="agregar_producto_temporal();" class="btn btn-success" accesskey="x">Aceptar</button>
		     			<button type="button" id="tempde_cancelar" class="btn btn-danger" onclick="cerrar_ventana_prodtemporal();">Cerrar</button>
		     		</div>
		     	</div>
		    </div>
	     </div>
	 	</form>
    <!--</div>-->
    </div>
  </div>
</div>

<style type="text/css">

    .form-control{
        height: 20px;
    }
	label{
        font-size: 14px;
        font-weight: normal;
    }
	.cajaCabecera input{
		margin-bottom: 1%;
		border: 1px solid #ABA7A6;
		border-radius: 7px;
		height: 30px;
		width: 90%;
	}
	#tempde_tipoIgv{
		font-size: 12px;
		margin-bottom: 1%;
		border: 1px solid #ABA7A6;
		border-radius: 7px;
		height: 30px;
		width:  100%;
	}
	#tempde_tipoIgv:focus{
		color: #495057;
		background-color: #fff;
		border-color: #80bdff;
		border-radius: 7px;
		outline: none;
	}

	.form-control:focus {
		color: #495057;
		background-color: #fff;
		border-color: #80bdff;
		border-radius: 7px;
		outline: none;
	}
	.tempde_stock input{
		margin-bottom: 1%;
		border: 1px solid #ABA7A6;
		border-radius: 7px;
		height: 30px;
		width: 95%;
	}
	.tempde_stock input:focus{
		color: #495057;
		background-color: #fff;
		border-color: #80bdff;
		border-radius: 7px;
		outline: none;
	}
	.row{
		margin: auto;
	}

	.VentasArticulo{
		display: none;
		z-index: 2;
		background: rgba(255,255,255,1);
		position: absolute;
		top: 13em;
		left: 0em;
		right: 0em;
		bottom: 0em;
	}

	.detallesVentasAnteriores{
		position: absolute;
		display: block;
		background: rgba(255,255,255,1);
		padding: 1em;
		border: thin #000 solid;
		border-radius: 1em;
		top: 1em;
		bottom: 1.5em;
		left: 1em;
		right: 1em;
		overflow: auto;
	}

	.detallesVentasAnteriores tr{
		background: #D9D9D9;
	}

	.detallesVentasAnteriores tr:nth-child(2n){
		background: #FFFFFF;
	}

	.detallesVentasAnteriores td, .detallesVentasAnteriores th{
		border-bottom: thin #000 solid;
	}

	th .detaArticulos{
		font-weight: bold;
		font-size: 10pt;
	}

	.detaArticulos{
		font-size: 8pt;
		padding: 0.5em;
	}

	.rowLote{
		display: none;
		position: relative;
		background: #fff;
	}

	.nvoLote a{
		padding-left: 0em;
		padding-right: 0em;
	}

	.nvoLote{
		z-index: 3;
		margin-top: 0.5em; 
		position: absolute;
		display: block;
		background: #fff;
		padding: 1em;
		border: #bbbbbb thin solid;
		border-radius: 1em;
	}

	.btn-close{
		z-index: 3;
		position: absolute;
		top: 3em;
		right: 3em;
		cursor: pointer;
		font-size: 10pt;
	}

	.btn-close:hover{
		font-weight: bold;
	}
</style>

<script type="text/javascript">


(function($, window) {
    'use strict';

    var MultiModal = function(element) {
        this.$element = $(element);
        this.modalCount = 0;
    };

    MultiModal.BASE_ZINDEX = 1040;

    MultiModal.prototype.show = function(target) {
        var that = this;
        var $target = $(target);
        var modalIndex = that.modalCount++;

        $target.css('z-index', MultiModal.BASE_ZINDEX + (modalIndex * 20) + 10);

        // Bootstrap triggers the show event at the beginning of the show function and before
        // the modal backdrop element has been created. The timeout here allows the modal
        // show function to complete, after which the modal backdrop will have been created
        // and appended to the DOM.
        window.setTimeout(function() {
            // we only want one backdrop; hide any extras
            if(modalIndex > 0)
                $('.modal-backdrop').not(':first').addClass('hidden');

            that.adjustBackdrop();
        });
    };

    MultiModal.prototype.hidden = function(target) {
        this.modalCount--;

        if(this.modalCount) {
           this.adjustBackdrop();
            // bootstrap removes the modal-open class when a modal is closed; add it back
            $('body').addClass('modal-open');
        }
    };

    MultiModal.prototype.adjustBackdrop = function() {
        var modalIndex = this.modalCount - 1;
        $('.modal-backdrop:first').css('z-index', MultiModal.BASE_ZINDEX + (modalIndex * 20));
    };

    function Plugin(method, target) {
        return this.each(function() {
            var $this = $(this);
            var data = $this.data('multi-modal-plugin');

            if(!data)
                $this.data('multi-modal-plugin', (data = new MultiModal(this)));

            if(method)
                data[method](target);
        });
    }

    $.fn.multiModal = Plugin;
    $.fn.multiModal.Constructor = MultiModal;

    $(document).on('show.bs.modal', function(e) {
        $(document).multiModal('show', e.target);
    });

    $(document).on('hidden.bs.modal', function(e) {
        $(document).multiModal('hidden', e.target);
    });
}(jQuery, window));

	$(document).ready(function(){

		$("#producto").autocomplete({

                source: function (request, response) {
                	$("#lCompatibles").hide();
                	$(".txtCompatible").hide();
                	$("#txtCompatible").html('');
					$("#tempde_message").html('');
					$("#tempde_message").hide();
                    $.ajax({
                        url: "<?php echo base_url(); ?>index.php/maestros/temporaldetalle/autocomplete_producto/B",
                        type: "POST",
                        data: {
                            term: $("#producto").val()
                        },
                        dataType: "json",
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                select: function (event, ui) {
                	isEncuentra = verificarProductoTempDetalle(ui.item.codigo);

                    if(!isEncuentra){
	                    $("#producto").val(ui.item.value);
	                    $("#id_producto").val(ui.item.codigo);


                    }else{
                        $("#producto").val('');
                        $("#id_producto").val('');
                    	Swal.fire({
			                icon: "info",
			                title: "El producto ya se encuentra ingresado en la lista de detalles.",
			                html: "<b class='color-red'></b>",
			                showConfirmButton: true,
			                timer: 2000
			            });
                    	return !isEncuentra;
                    }
                },
                minLength: 1
            });
	});

function verificarProductoTempDetalle(codigoProducto){
    isEncuentra = false;
    if (arrayidesproductos1.includes(codigoProducto)){
        isEncuentra = true;
    }
    return isEncuentra;
}

    //***VARIABLES***/
     let totalglobalADVALOREM;
     let totalglobalIGV;
     let totalglobalIPM;
     let totalglobalpersepcionDolares;
     let arrayGlobalPrecioVentaSoles =[];
     let arrayGlobalCostoUnitarioSoles = [];
     ///
    let n=0;
    let arraycantidadesproductos1 = [];
    let arraypreciounitarioproductofabrica1 = [];
    let arrayidesproductos1 = [];
    let arrayNombreProductos =[];
    ///
    let arraypreciofabrica1 = [];
    ///
    let arrayFOB2 = [];
    let arrayporcentajeADVALOREM2 = [];
    let arrayporcentajeIGV2 = [];
    let arrayporcentajeIPM2 = [];
    function agregar_producto_temporal(){

        if ($("#producto").val() == '') {
            $("#producto").focus();
            MensajeAlerta('Ingrese el producto.');
            return false;
        }

        if ($("#cantidad_producto").val() == '') {
            MensajeAlerta('Ingrese una cantidad.');
            $("#cantidad_producto").focus();
            return false;
        }
        if ($("#precio_unitario_producto_fabrica").val() == '') {
            MensajeAlerta('Ingrese precio de Fabrica.');
            $("#precio_unitario_producto_fabrica").focus();
            return false;
        }

        if ($("#precio_unitario_aduanas").val() == '') {
            MensajeAlerta('Ingrese precio referencial de ADUANAS.');
            $("#precio_unitario_aduanas").focus();
            return false;
        }




        let cantidad_producto = parseFloat($("#cantidad_producto").val());
        let precio_unitario_aduanas = parseFloat($("#precio_unitario_aduanas").val());
        let comision_broker_porcentaje1   = parseFloat($('#comision_broker_porcentaje1').val())
        let advalorem           = parseFloat($('#advalorem').val());
        if (!advalorem){
            advalorem = 0;
        }
        let igvproducto           = parseFloat($('#igvproducto').val());
        if (!igvproducto){
            igvproducto = 0;
        }
        let ipmproducto           = parseFloat($('#ipmproducto').val());
        if (!ipmproducto){
            ipmproducto = 0;
        }
        let precio_unitario_producto_fabrica           = parseFloat($('#precio_unitario_producto_fabrica').val());
        if (!precio_unitario_producto_fabrica){
            precio_unitario_producto_fabrica = 0;
        }

        let nombre_producto = $('#producto').val();
        let id_producto = $('#id_producto').val();

        if (comision_broker_porcentaje1 >100) {
            MensajeAlerta('la comisión del broker debe ser entre 0% a 100%');
            $("#cantidad_producto").focus();
            return false;
        }
        if (cantidad_producto <= 0) {
            MensajeAlerta('La cantidad debe ser mayor que 0.');
            $("#cantidad_producto").focus();
            return false;
        }
        if (precio_unitario_aduanas <= 0) {
            MensajeAlerta('El precio debe ser mayor que 0.');
            $("#precio_unitario_aduanas").focus();
            return false;
        }

         //**llenar los array**//
         arrayidesproductos1.push(id_producto)
         arraycantidadesproductos1.push(cantidad_producto);
         arraypreciounitarioproductofabrica1.push(precio_unitario_producto_fabrica);
         arrayNombreProductos.push(nombre_producto);
         //
         arraypreciofabrica1.push(cantidad_producto*precio_unitario_producto_fabrica);

         //
         arrayFOB2.push(cantidad_producto*precio_unitario_aduanas)
         arrayporcentajeADVALOREM2.push(advalorem/100);
         arrayporcentajeIGV2.push(igvproducto/100);
         arrayporcentajeIPM2.push(ipmproducto/100)

         calcular_totales_tempdetalle2();
         calcular_totales_tempdetalle1();
         //eliminar los item
         $('.listaitemproductosimportacion').remove();
         for (i=0;i<arrayGlobalPrecioVentaSoles.length;i++){
             fila  = '<tr id="fila'+ i + '" class="itemParTabla listaitemproductosimportacion" >';
             fila += '<td width="10%"><div align="center">' + (i+1) + '</div></td>';
             fila += '<td width="30%"><div align="left"><input type="hidden" name="idsproductos[]"  value="'+arrayidesproductos1[i]+'"/><span>'+arrayNombreProductos[i]+'</span></td>';
             fila += '<td width="10%"><div align="center"><input type="hidden" name="cantidadesproductos[]"  value="'+arraycantidadesproductos1[i]+'" "/><span>'+arraycantidadesproductos1[i]+'</span></td>';
             fila += '<td width="10%"><div align="center"><input type="hidden" name="costosComprasProductos[]"  value="'+arrayGlobalCostoUnitarioSoles[i]+'" "/><span>'+arrayGlobalCostoUnitarioSoles[i].toFixed(3)+'</span></td>';
             fila += '<td width="10%"><div align="center"><input type="hidden" name="preciosproductosPrecioVentaSoles[]"  value="'+arrayGlobalPrecioVentaSoles[i]+'" "/><span>'+arrayGlobalPrecioVentaSoles[i].toFixed(3)+'</span></td></tr>';

             $("#tempde_tblbody").append(fila);
         }
         limpiar_modal()
         n++;
     }


    function cerrar_ventana_prodtemporal(){
        $('.bd-example-modal-lg').modal('toggle');
        $('.modal-backdrop').hide();
    }

	/* ::::::::::::::: FUNCIONES SOLO CALCULOS :::::::::::::::::::::::::::::  */

    function calcular_totales_tempdetalle1(){
        let contador = 0;
        let contador1 = 0;
        let totalpreciofabrica = 0;
        let totalcomisionbroker = 0;
        let totalFOB = 0;
        //input
        let comisionbrokerpocentaje  = parseFloat($('#comision_broker_porcentaje1').val());
        let comisionbrokervalor = comisionbrokerpocentaje/100;
        let montogastoenchinaopcional  = parseFloat($('#montogastoenchinaopcional').val());
        let fleteinternacional  = parseFloat($('#fleteinternacional').val());
        let segurointernacional = parseFloat($('#segurointernacional').val());
        let fletecallaoalmacen = parseFloat($('#fletecallaoalmacen').val());
        let gastodescarga = parseFloat($('#gastodescarga').val());
        let facilidadesaduanas = parseFloat($('#facilidadesaduanas').val());
        let gastoQcobraAduanas = parseFloat($('#gastoQcobraAduanas').val());
        let porcentajeutilidadproducto = $('#porcentajeutilidadproducto').val();
        let valorutilidadproducto = porcentajeutilidadproducto/100;

        //array termporales
        let arraytemporalcomisionbroker = [];
        let arraytemporalgastoschinaparaFOB = [];
        let arraytemporalFOB = [];
        let arraytemporalFlete = [];
        let arraytemporalSeguro = [];
        let arraytemporalValorAduana = [];
        let arraytemporalADVALOREM = [];
        let arraytemporalIGV = [];
        let arraytemporalIPM = [];
        let arraytemporalPercepcionDolares = [];
        let arraytemporalTransporteInterno = [];
        let arraytemporalDescargaInterno = [];
        let arraytemporalFacilidadesAduanas = [];
        let arraytemporalgastoQcobraAduanas = [];
        let arraytemporalCostoTotal = [];
        let arraytemporalPrecioUnitarioDolares = [];
        let arraytemporalCostoUnitarioSoles = [];
        let arraytemporalUtilidadSoles = [];
        var arraytemporalPrecioVentaSoles = [];

        while (contador <= n){
            arraytemporalcomisionbroker[contador] = arraypreciofabrica1[contador]*comisionbrokervalor;
            //total comision broker
            totalpreciofabrica = totalpreciofabrica + arraypreciofabrica1[contador];
            totalcomisionbroker = totalcomisionbroker+ arraytemporalcomisionbroker[contador];
            contador++;
        }
        totalFOB = totalpreciofabrica + totalcomisionbroker+montogastoenchinaopcional;
        while (contador1 <= n){
            //total comision broker
            arraytemporalgastoschinaparaFOB[contador1] = (montogastoenchinaopcional*arraypreciofabrica1[contador1])/totalpreciofabrica;
            arraytemporalFOB[contador1] = arraypreciofabrica1[contador1]+arraytemporalcomisionbroker[contador1]+arraytemporalgastoschinaparaFOB[contador1];
            arraytemporalFlete[contador1] = (fleteinternacional*arraytemporalFOB[contador1])/totalFOB;
            arraytemporalSeguro[contador1] = (arraytemporalFOB[contador1]*segurointernacional)/totalFOB;
            arraytemporalValorAduana[contador1] = arraytemporalFOB[contador1]+arraytemporalFlete[contador1]+arraytemporalSeguro[contador1];
            arraytemporalADVALOREM[contador1] =(totalglobalADVALOREM*arraytemporalFOB[contador1])/totalFOB;
            arraytemporalIGV[contador1] = (totalglobalIGV*arraytemporalFOB[contador1])/totalFOB;
            arraytemporalIPM[contador1] = (totalglobalIPM*arraytemporalFOB[contador1])/totalFOB;
            arraytemporalPercepcionDolares[contador1] = (totalglobalpersepcionDolares*arraytemporalFOB[contador1])/totalFOB;
            arraytemporalTransporteInterno[contador1] =(fletecallaoalmacen*arraytemporalFOB[contador1])/totalFOB;
            arraytemporalDescargaInterno[contador1] = (gastodescarga*arraytemporalFOB[contador1])/totalFOB;
            arraytemporalFacilidadesAduanas[contador1] = (facilidadesaduanas*arraytemporalFOB[contador1])/totalFOB;
            arraytemporalgastoQcobraAduanas[contador1] = (gastoQcobraAduanas*arraytemporalFOB[contador1])/totalFOB;
            arraytemporalCostoTotal[contador1] = arraytemporalValorAduana[contador1]+arraytemporalADVALOREM[contador1]+ arraytemporalIGV[contador1]+arraytemporalIPM[contador1]+arraytemporalPercepcionDolares[contador1]+arraytemporalTransporteInterno[contador1]+ arraytemporalDescargaInterno[contador1]+ arraytemporalFacilidadesAduanas[contador1]+arraytemporalgastoQcobraAduanas[contador1];
            arraytemporalPrecioUnitarioDolares[contador1] =  arraytemporalCostoTotal[contador1]/arraycantidadesproductos1[contador1];
            arraytemporalCostoUnitarioSoles[contador1] = arraytemporalPrecioUnitarioDolares[contador1]*parseFloat($('#tdcDolar').val());
            arraytemporalUtilidadSoles[contador1] = arraytemporalCostoUnitarioSoles[contador1]*valorutilidadproducto;
            arraytemporalPrecioVentaSoles[contador1] =arraytemporalCostoUnitarioSoles[contador1]+ arraytemporalUtilidadSoles[contador1];


            contador1++;
        }


        for (j=0;j<arraytemporalPrecioVentaSoles.length;j++){
            arrayGlobalPrecioVentaSoles[j]=arraytemporalPrecioVentaSoles[j]
        }
        for (j=0;j<arraytemporalCostoUnitarioSoles.length;j++){
            arrayGlobalCostoUnitarioSoles[j]=arraytemporalCostoUnitarioSoles[j]
        }


    }

    function calcular_totales_tempdetalle2(){
      let contador = 0;
      let totalFOB2 = 0;
      let totalADVALOREM2 = 0;
      let totalIGV2 = 0;
      let totalIPM2 = 0;
      let totalTributos = 0;
      let totalvalorenaduana = 0;
      let totalpersepcionDolares = 0;
      let totalpersepcionSoles = 0;
        //
      let arraytemporalflete = [];
      let arraytemporalseguro = [];
      let arraytemporalvaloraduana = [];
      let arraytemporalvalorADVALOREM = [];
      let arraytemporalvalorIGV = [];
      let arraytemporalvalorIPM = [];
      //input
      let fleteinternacional  = parseFloat($('#fleteinternacional').val());
      let segurointernacional = parseFloat($('#segurointernacional').val());
      let porcentajepercepcion          = parseFloat($('#percepcion').val());
      let valorpercepcion = porcentajepercepcion/100
        //total FOB
      for (i=0 ;i< arrayFOB2.length;i++){
          totalFOB2 = totalFOB2 + arrayFOB2[i];
      }


      while (contador <= n){
          arraytemporalflete[contador]  = (fleteinternacional*arrayFOB2[contador])/totalFOB2;
          arraytemporalseguro[contador] = (segurointernacional*arrayFOB2[contador])/totalFOB2;
          arraytemporalvaloraduana[contador] = arrayFOB2[contador]+arraytemporalflete[contador]+arraytemporalseguro[contador];
          arraytemporalvalorADVALOREM[contador] =  arraytemporalvaloraduana[contador]*arrayporcentajeADVALOREM2[contador];
          arraytemporalvalorIGV[contador] = (arraytemporalvaloraduana[contador]+arraytemporalvalorADVALOREM[contador])*arrayporcentajeIGV2[contador];
          arraytemporalvalorIPM[contador] = (arraytemporalvaloraduana[contador]+arraytemporalvalorADVALOREM[contador])*arrayporcentajeIPM2[contador];
          contador++;
      }
        //total ADVALOREM
        for (i=0 ;i< arraytemporalvalorADVALOREM.length;i++){
            totalADVALOREM2 = totalADVALOREM2 + arraytemporalvalorADVALOREM[i];
        }
        totalglobalADVALOREM =  totalADVALOREM2;
        //total IGV
        for (i=0 ;i< arraytemporalvalorIGV.length;i++){
            totalIGV2 = totalIGV2 + arraytemporalvalorIGV[i];
        }
        totalglobalIGV=totalIGV2;
        //total IPM
        for (i=0 ;i< arraytemporalvalorIPM.length;i++){
            totalIPM2 = totalIPM2 + arraytemporalvalorIPM[i];
        }
        totalglobalIPM = totalIPM2;
        // total valor en ADUANA
        for (i=0 ;i< arraytemporalvaloraduana.length;i++){
            totalvalorenaduana = totalvalorenaduana + arraytemporalvaloraduana[i];
        }
        totalTributos = totalADVALOREM2+totalIGV2+totalIPM2;
        totalpersepcionDolares = (totalvalorenaduana+totalTributos)*valorpercepcion;
        totalglobalpersepcionDolares = totalpersepcionDolares
        totalpersepcionSoles = parseFloat($('#tdcDolar').val())*totalpersepcionDolares;

        $('#percepciondolares').val(totalpersepcionDolares.toFixed(3))
        $('#percepcionsoles').val(totalpersepcionSoles.toFixed(3))



    }
    function calcular_temProducto_modal(){

    }


	function MensajeAlerta(mensaje){
        Swal.fire({
            icon: "info",
            title: mensaje,
            html: "<b class='color-red'></b>",
            showConfirmButton: true,
            timer: 1500
        });
    }



</script>