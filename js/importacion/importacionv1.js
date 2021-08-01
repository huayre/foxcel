function MensajeAlerta(mensaje){
    Swal.fire({
        icon: "info",
        title: mensaje,
        html: "<b class='color-red'></b>",
        showConfirmButton: true,
        timer: 1500
    });
}

function abrir_modal(){
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
    $('.bd-example-modal-lg').modal('show');
    limpiar_modal();
}

function limpiar_modal(){
    $('#form_tempdetalle').trigger("reset");

}







