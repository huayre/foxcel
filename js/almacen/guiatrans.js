var base_url;
jQuery(document).ready(function () {

    base_url = $("#base_url").val();

    tipo_codificacion = $("#tipo_codificacion").val();

    $("#nuevaGuiatrans").click(function () {
        url = base_url + "index.php/almacen/guiatrans/nueva" + "/";
        location.href = url;
    });
    $("#grabarGuiatrans").click(function () {
        codigo = $("#codigo_guiatrans").val();
        
        /**validacion de que exista ma misma cantidad de productos con serie ingresada**/
        //n = document.getElementById('tblDetalleGuiaTrans').rows.length; OLD
        n = document.getElementById('tempde_tbl').rows.length;
        if(n!=0){
         var  isSalir=false;
            for ( x=0; x<n; x++){
                valor= "flagGenIndDet["+x+"]"; 
                var  valor_flagGenIndDet = document.getElementById(valor).value ;
                valorAccion="detaccion["+x+"]"; 
                var  valorAccionReal = document.getElementById(valorAccion).value ;
                if(valor_flagGenIndDet=='I'  && (valorAccionReal!=null  &&  valorAccionReal!='e')){
                    valor= "prodcodigo["+x+"]"; 
                    var  valorProducto = document.getElementById(valor).value ;
                       
                    valor= "prodcantidad["+x+"]"; 
                    var  valorCantidad = document.getElementById(valor).value ;
                    valorAlmacen= "almacenProducto["+x+"]"; 
                    var  valorAlmacen = document.getElementById(valorAlmacen).value ;
                    
                    
                    /**verifico si ese producto seriado del almacen origen esta inventariado en el almacen destino***/     
                    
                    almacenDestino = $('#almacen_destino').val();
                    urlVerificacion = base_url + "index.php/almacen/producto/verificarInventariadoAlmacen/"+valorProducto+"/"+almacenDestino+"/"+true;
                    $.ajax({
                        async: false,
                        url: urlVerificacion,
                        beforeSend: function (data) {
                        },  
                        error: function (data) {
                            $('img#loading').css('visibility', 'hidden');
                            console.log(data);
                            alert('No se puedo completar la operación - Revise los campos ingresados.');
                            isSalir=true;
                            return false;
                        },
                        success: function (data) {
                            $('img#loading').css('visibility', 'hidden');
                            if(data==0){
                               valorPD= "proddescri["+x+"]"; 
                               var  valorPDVA = document.getElementById(valorPD).value ;
                               alert("producto : "+valorPDVA+", no se encuentra inventariado en Almacen de Destino.");
                               trTabla=x;
                               document.getElementById(trTabla).style.background = "#Eec0000";
                               isSalir=true;
                               return false;
                            }
                            
                        }
                     });
                    
                    /**fin de verificacion**/
                    if(isSalir==true){
                        break;
                    }   
                    /**fin de verificacion**/

                       /**verificar si existe la misma cantidad por producto y seria**/
                       urlVerificacion = base_url + "index.php/ventas/comprobante/verificacionCantidadJson";
                       $.ajax({
                           type: "POST",
                           async: false,
                           url: urlVerificacion,
                           data: {valorProductoJ:valorProducto,valorCantidadJ:valorCantidad,almacen:valorAlmacen},
                           beforeSend: function (data) {
                           },  
                           error: function (data) {
                               $('img#loading').css('visibility', 'hidden');
                               console.log(data);
                               alert('No se puedo completar la operación - Revise los campos ingresados.')
                           },
                           success: function (data) {
                               $('img#loading').css('visibility', 'hidden');
                               if(data==0){
                                    valorPD= "proddescri["+x+"]"; 
                                    var  valorPDVA = document.getElementById(valorPD).value ;
                                    if (confirm("cantidad por producto y serie no coinciden - "+valorPDVA+"<br>Si esta seguro/a de haber cargado todos los imeis ignore este mensaje y presione aceptar.")){
                                        trTabla=x;
                                        document.getElementById(trTabla).style.background = "#ffadad";
                                        isSalir=false;
                                        return false;
                                    }
                                    else{
                                        trTabla=x;
                                        document.getElementById(trTabla).style.background = "#ffadad";
                                        isSalir=true;
                                        return false;
                                    }
                               }
                           }
                        });
               
                       /**fin de verificacion**/
                       if(isSalir==true){
                        break;
                       }   
                   }
                   
               }
            
            
            if(isSalir==true){
                //  $('#grabarComprobante').css('visibility', 'visible');
                $('img#loading').css('visibility', 'hidden');
                return false;
            }
       }
        
        /**fin de validacion**/
        
        $('img#loading').css('visibility', 'visible');
        // Sirve para editar y insertar
        url = base_url + "index.php/almacen/guiatrans/grabar";

        dataString = $('#frmGuiatrans').serialize();
        $.post(url, dataString, function (data) {
            $('img#loading').css('visibility', 'hidden');
            switch (data.result) {
                case 'ok':
                    location.href = base_url + "index.php/almacen/guiatrans/listar";
                    break;
                case 'error':
                    $('input[type="text"][readonly!="readonly"], select, textarea').css('background-color', '#FFFFFF');
                    $('#' + data.campo).css('background-color', '#FFC1C1').focus();
                    break;
                case 'error2':
                    $('input[type="text"][readonly!="readonly"], select, textarea').css('background-color', '#FFFFFF');
                    var element = document.getElementById(data.campo);
                    element.style.backgroundColor = '#FFC1C1';
                    break;
            }
        }, 'json');
    });
    
    $("#cancelarGuiatrans").click(function () {
        url = base_url + "index.php/almacen/guiatrans/listar/";
        location.href = url;
    });

    $('#almacen_destino').change(function () {
        if ($('#almacen_destino').val() != '' && $('#almacen_destino').val() == $('#almacen').val()) {
            alert('El ALMACEN DESTINO debe ser diferente al ALMACEN ORIGEN.');
            $('#almacen_destino').val('').focus();
            return false;
        }
        return true;
    });

    $('#linkEnviarProhibido').click(function () {
        alert('Aun no se ah confirmado la transferencia!');
    });

    $('#idRecibido').click(function () {
        alert('Transferencia realizada correctamente!');
    });
    $('#idRecibido2').click(function () {
        alert('Transferencia realizada correctamente!');
    });

    $('#idDevolucion').click(function () {
        alert('La transferencia fue devuelta a su ORIGEN!');
    });

    $('#linkAnulado').click(function(){
        alert('Transferencia anulada por el origen');
    });

    $("#linkVerSerieNum").click(function () {
        var temp = $("#linkVerSerieNum p").html();
        var serienum = temp.split('-');
        switch (tipo_codificacion) {
            case '1':
                $("#numero").val(serienum[1]);
                break;
            case '2':
                $("#serie").val(serienum[0]);
                $("#numero").val(serienum[1]);
                break;
        }
    });

});

/********************************************************************************************/

// Esto de prueba por si acaso existe un error
function cargarTransferencia2(estado, guiaTrans){
    var codUsuario = $('#codUsuario').val();
    location.href = base_url + 'index.php/almacen/guiatrans/cargarTransferencia/'+codUsuario+"/"+guiaTrans+"/"+estado;
}



function cargarTransferencia(estado, guiaTrans) {
	var mensajeConfirmacion = "";
	switch (estado) {
		case 0:
			mensajeConfirmacion = "¿Estas seguro(a) de realizar la transferencia?";

		break;
		case 1:
			mensajeConfirmacion = "¿Estas seguro(a) de confirmar el transito del envio?";
		break;
		case 2:
			mensajeConfirmacion = "¿Estas seguro(a) de cancelar la transferencia?";
		break;
	}

	Swal.fire({
	  title: mensajeConfirmacion,
	  text: 'Si deseas continuar presiona Si',
	  icon: 'question',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Si',
	  cancelButtonText: 'No'
	}).then((result) => {
	  if (result.isConfirmed) {
			var codUsuario = $('#codUsuario').val();
			if (estado <= -1 || guiaTrans <= 0) {
				Swal.fire({
            icon: "warning",
            title: "Existe un error con la transferencia",
            html: "<b class='color-red'>Si el problema persiste, comuníquese con soporte</b>",
            showConfirmButton: true,
            timer: 1500
        });
				return false;
			} else {

				$("#trans"+guiaTrans).hide();
				url = base_url + 'index.php/almacen/guiatrans/cargarTransferencia';

				$.ajax({
					url: url,
					type: "POST",
					data: {estado: estado, guiaTrans: guiaTrans, usuario: codUsuario},
					dataType: "json",
					beforeSend: function (data) {
					},
          success: function (data) {
						console.log(data);
						// Sirve para verificar si el movimiento ya fue ejecutado
						if(typeof(data.movimiento) != "undefined" && data.movimiento == "Movimiento ya realizado por el DESTINO") {
							alert('EL movimiento ya fue ejecutado por el DESTINO! \nVUELVA A RECARGAR LA PAGINA');
						}else {
							var redirect_url = "";
							flag = data.flagEstado;
							usuario_guia = data.usuario_guia;
							guia_trans = data.guia_trans;
							estado_trans = data.estado_trans;
							updateGuiaInySa = data.updateGuiaInySa;
							switch (flag) {
								case 0:
									if (updateGuiaInySa == true) {
										$("#limpiarG").click();
									} else {
										Swal.fire({
						            icon: "warning",
						            title: "¡Lo siento! Ah ocurrido un error al realizar la transferencia!",
						            html: "<b class='color-red'>Si el problema persiste, comuníquese con soporte</b>",
						            showConfirmButton: true,
						            timer: 1500
						        });
									}
								break;
								case 1:
									if (updateGuiaInySa == true) {
										$("#limpiarG").click();
									} else {
										Swal.fire({
						            icon: "warning",
						            title: "¡Lo siento! Ah ocurrido un error al confirmar la transferencia!",
						            html: "<b class='color-red'>Si el problema persiste, comuníquese con soporte</b>",
						            showConfirmButton: true,
						            timer: 1500
							       });
									}
								break;
								case 2:
									if (updateGuiaInySa == true) {
										$("#limpiarG").click();
									} else {
										Swal.fire({
						            icon: "warning",
						            title: "¡Ha ocurrido un error al transitar la transferencia!",
						            html: "<b class='color-red'>Si el problema persiste, comuníquese con soporte</b>",
						            showConfirmButton: true,
						            timer: 1500
							       });
									}
								break;
								case 3:
									if (updateGuiaInySa == true) {
										$("#limpiarG").click();
									} else {
										Swal.fire({
						            icon: "warning",
						            title: "¡Ha ocurrido un error al cancelar la transferencia!",
						            html: "<b class='color-red'>Si el problema persiste, comuníquese con soporte</b>",
						            showConfirmButton: true,
						            timer: 1500
							       });
									}
								break;
							}
							$("#limpiarG").click();
						}
          },
          error: function (HXR, error, xd) {
              console.log('errorr');
          }
        });
      }
	  }
	})
}

function cargarTransferencia_original(estado, guiaTrans) {
    
    var mensajeConfirmacion = "";
    switch (estado) {
        case 0:
            mensajeConfirmacion = "¿Estas seguro(a) de realizar la transferencia?";
            break;
        case 1:
            mensajeConfirmacion = "¿Estas seguro(a) de confirmar el transito del envio?";
            break;
       	case 2:
            mensajeConfirmacion = "¿Estas seguro(a) de cancelar la transferencia?";
            break;
    }

    var confirmacion = confirm(mensajeConfirmacion);

    if (confirmacion == true) {
        var codUsuario = $('#codUsuario').val();
        if (estado <= -1) {
            alert('Existe un error con la transferencia');
            return false;
        } else if (guiaTrans <= 0) {
            alert('Existe un error con la transferencia');
            return false;
        } else {
            $("#trans"+guiaTrans).hide();
            url = base_url + 'index.php/almacen/guiatrans/cargarTransferencia';

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    estado: estado,
                    guiaTrans: guiaTrans,
                    usuario: codUsuario
                },
                dataType: "json",
                beforeSend: function (data) {

                },
                success: function (data) {
                    console.log(data);
                    // Sirve para verificar si el movimiento ya fue ejecutado
                    if(typeof(data.movimiento) != "undefined" && data.movimiento == "Movimiento ya realizado por el DESTINO") {
                        alert('EL movimiento ya fue ejecutado por el DESTINO! \nVUELVA A RECARGAR LA PAGINA');
                    }else {

                        var redirect_url = "";

                        flag = data.flagEstado;
                        usuario_guia = data.usuario_guia;
                        guia_trans = data.guia_trans;
                        estado_trans = data.estado_trans;
                        updateGuiaInySa = data.updateGuiaInySa;
                        switch (flag) {
                            case '0':
                            case 0:
                                if (updateGuiaInySa == true) {
                                    redirect_url = base_url + "index.php/almacen/guiatrans/listar/";
                                } else {
                                    $('#mensajeTransferencia').val("<img src='" + base_url + "images/verguenza.gif' alt='Lo siento' > ¡Lo siento! Ah ocurrido un error al realizar la transferencia!");
                                }
                                break;
                            case '1':
                            case 1:
                                if (updateGuiaInySa == true) {
                                    redirect_url = base_url + "index.php/almacen/guiatrans/listar/";
                                } else {
                                    $('#mensajeTransferencia').val("<img src='" + base_url + "images/verguenza.gif' alt='Lo siento' > ¡Lo siento! Ah ocurrido un error al confirmar la transferencia!");
                                }
                                break;
                            case '2':
                            case 2:
                                if (updateGuiaInySa == true) {
                                    redirect_url = base_url + "index.php/almacen/guiatrans/listar/";
                                } else {
                                    $('#mensajeTransferencia').val("<img src='" + base_url + "images/verguenza.gif' alt='Lo siento' > Ah ocurrido un error al transitar la transferencia!");
                                }
                                break;
                            case '3':
                            case 3:
                                if (updateGuiaInySa == true) {
                                    redirect_url = base_url + "index.php/almacen/guiatrans/listar/";
                                } else {
                                    $('#mensajeTransferencia').val("<img src='" + base_url + "images/verguenza.gif' alt='Lo siento' > Ah ocurrido un error al cancelar la transferencia!");
                                }
                                break;
                        }

                        location.href = redirect_url;
                    }
                },
                error: function (HXR, error, xd) {
                    console.log('errorr');
                }
            });

        }
    } else {
        return false;
    }
}

function editar_guiatrans(guiatrans) {
    location.href = base_url + "index.php/almacen/guiatrans/editar/" + guiatrans;
}

function listar_unidad_medida_producto(producto) {
    limpiar_combobox('unidad_medida');

    base_url = $("#base_url").val();
    url = base_url + "index.php/almacen/producto/listar_unidad_medida_producto/" + producto;
    select = document.getElementById('unidad_medida');
    $.getJSON(url, function (data) {
        $.each(data, function (i, item) {
            codigo = item.UNDMED_Codigo;
            descripcion = item.UNDMED_Descripcion;
            simbolo = item.UNDMED_Descripcion;
            nombre_producto = item.PROD_Nombre;
            nombrecorto_producto = item.PROD_NombreCorto;
            marca = item.MARCC_Descripcion;
            modelo = item.PROD_Modelo;
            presentacion = item.PROD_Presentacion;
            opt = document.createElement('option');
            texto = document.createTextNode(simbolo);
            opt.appendChild(texto);
            opt.value = codigo;
            if (i == 0)
                opt.selected = true;
            select.appendChild(opt);
        });
        var nombre;
        if (nombrecorto_producto)
            nombre = nombrecorto_producto;
        else
            nombre = nombre_producto;
        if (marca)
            nombre += ' / Marca:' + marca;
        if (modelo)
            nombre += ' / Modelo: ' + modelo;
        if (presentacion)
            nombre += ' / Prest: ' + presentacion;
        $("#nombre_producto").val(nombre);
    });
}


function guiatrans_ver_pdf(guiatrans, img = 0) {
    url = base_url + "index.php/almacen/guiatrans/guiatrans_ver_pdf/" + guiatrans + "/pdf/" + img;
    window.open(url, '', "width=800,height=600,menubars=no,resizable=no;")
}
function guiatrans_ver_pdf_conmenbrete(guiatrans) {
    tipo_oper = $("#tipo_oper").val();
    url = base_url + "index.php/almacen/guiatrans/guiatrans_ver_pdf_conmenbrete/" + guiarem + "/0";
    window.open(url, '', "width=800,height=600,menubars=no,resizable=no;")
}

function anular_guia(codigo){

	url = base_url + 'index.php/almacen/guiatrans/anular_trasnferencia';

	Swal.fire({
	  title: '¿Estas seguro(a) que deseas anular esta guia de transferencia?',
	  text: "La anulación no se podrá revertir",
	  icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Si, anular',
	  cancelButtonText: 'Cancelar'
	}).then((result) => {
	  if (result.isConfirmed) {
	    $.ajax({
	      url: url,
	      type: "POST",
	      data: {
	          codigo: codigo
	      },
	      dataType: "json",
	      beforeSend: function (data) {
	      },
	      success: function (data){
	         Swal.fire(
				      'Transferencia Anulada!',
				      'La guia ha sido anulada',
				      'success'
				    )
	          $("#limpiarG").click();
	      },
	      error: function (HXR, error, xd) {
	          console.log('errorr');
	      }
	  });
	  }
	})
}

///////////////////////////////////////////////////////////////////////




