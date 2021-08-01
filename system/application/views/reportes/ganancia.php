<link href="<?=base_url();?>js/fancybox/dist/jquery.fancybox.css?=<?=CSS;?>" rel="stylesheet">
<script src="<?=base_url();?>js/fancybox/dist/jquery.fancybox.js?=<?=JS;?>"></script>
<style>
    #detalles{
        font-size: 12px;
        background-color: #849cb6;
        padding: 2px;
        border-radius: 5px 5px 5px 5px ;
        width: 55%;

    }
</style>
<div class="container-fluid">
    <div class="row header">
        <div class="col-md-12 col-lg-12">
            <div><?=$titulo;?></div>
        </div>
    </div>
    <form id="form_busqueda" method="post">
        <section>
            
        <div class="row fuente8 py-1">
            <div class="col-sm-4 col-md-4 col-lg-4">
                <label for="search_descripcion">PRODUCTO</label>
                <input type="text" name="search_descripcion" id="search_descripcion" value="" placeholder="Nombre del producto" class="form-control h-1 w-porc-90"/>
                <input type="hidden" name="producto" id="producto" value="" placeholder="codigo" class="form-control h-1 w-porc-90"/>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2">
                <label for="search_fechai">FECHA INICIO</label>
                <input type="date" name="search_fechai" id="search_fechai" value="" class="form-control h-1 w-porc-90"/>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2">
                <label for="search_fechaf">FECHA FIN</label>
                <input type="date" name="search_fechaf" id="search_fechaf" value="" class="form-control h-1 w-porc-90"/>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2">
                <ul style="list-style: none; margin: 0px; padding: 0px;">
                    <li>
                        <input type="checkbox" name="locales"   checked id="localestotal">
                        Todos
                        <br>
                        <input type="checkbox" name="locales"  id="localesactual">
                        Local Actual
                    </li>
                    <li>
                       <!-- <input type="checkbox" name="TODOS" id="TODOS" value="0" <?php /*if($TODOS==true) echo 'checked="checked"'; */?> />TODOS-->
                    </li>
                    <?php /*
                    foreach($lista_companias as $valor){
                        echo '<li><input type="checkbox" name="COMPANIA_'.$valor->COMPP_Codigo.'" id="COMPANIA_'.$valor->COMPP_Codigo.'" value="'.$valor->COMPP_Codigo.'" '.($valor->checked==true ? 'checked="checked"' : '').' />'.$valor->EESTABC_Descripcion.'</li>';
                    }
                    */?>
                </ul>
            </div>
            <div class="col-sm-3 col-md-3 col-lg-3">
                <label for="moneda">MONEDA</label>
                <select name="moneda" id="moneda" class="form-control w-porc-90 h-2"> <?php
                    if ($moneda != NULL){
                        foreach ($moneda as $indice => $val){ ?>
                            <option value="<?=$val->MONED_Codigo;?>"><?="$val->MONED_Simbolo | $val->MONED_Descripcion";?></option> <?php
                        }
                    } ?>
                </select>
            </div>
        </div>
        </section>
        <section>
            <div class="row">
                <table id="detalles">
                    <thead>
                       <tr>
                           <th>Establecimiento</th>
                           <th>Costo Total</th>
                           <th>Venta Total</th>
                           <th>Utilidad</th>
                           <th>Utilidad%</th>
                       </tr>
                    </thead>
                </table>

            </div>
        </section>
        
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
                            <ul onclick="descargarExcel()" class="lista_botones">
                                <li id="excel">Reporte</li>
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
                    <table class="fuente8 display" id="table-ganancia">
                        <div id="cargando_datos" class="loading-table">
                            <img src="<?=base_url().'images/loading.gif?='.IMG;?>">
                        </div>
                        <thead>
                            <tr class="cabeceraTabla">
                                <td style="width:10%" data-orderable="false" title="">Fecha</td>
                                <td style="width:10%" data-orderable="false" title="">Establec</td>
                                <td style="width:22%" data-orderable="false" title="">Producto</td>
                                <td style="width:05%" data-orderable="false" title="">CANT</td>
                                <td style="width:05%" data-orderable="false" title="">Moneda</td>
                                <td style="width:08%" data-orderable="false" title="">PU. Costo</td>
                                <td style="width:08%" data-orderable="false" title="">PU. Venta</td>
                                <td style="width:08%" data-orderable="false" title="">Costo</td>
                                <td style="width:08%" data-orderable="false" title="">Venta</td>
                                <td style="width:08%" data-orderable="false" title="">Utilidad</td>
                                <td style="width:08%" data-orderable="false" title="">%utulidad</td>
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

        $('#table-ganancia').DataTable({
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax:{
                    url : '<?=base_url();?>index.php/reportes/ventas/datatable_ganancia/',
                    type: "POST",
                    data: {
                            datastring:""
                    },

                    beforeSend: function(){
                        $("#table-ganancia .loading-table").show();

                    },
                    "dataSrc": function ( json ) {

                        for (let i=0;i<json.detalles.length;i++)
                        {
                            let fila='<tr class="filasdetalles">' +
                                '<td>'+json.detalles[i].compania+'</td>' +
                                '<td>'+json.detalles[i].costototal+'</td>' +
                                '<td>'+json.detalles[i].ventatotal+'</td>' +
                                '<td>'+json.detalles[i].utilidad+'</td>' +
                                '<td>'+json.detalles[i].proc_utilidad+'</td>' +
                                '</tr>'
                            $('#detalles').append(fila);
                        }

                        return json.data;

                    }

            },
            language: spanish,
            columnDefs: [{"className": "dt-center", "targets": 0}],
            order: [[ 1, "desc" ]]
        });
        $("#search_descripcion").autocomplete({
                source: function (request, response) {

                    tipo_oper='V';
                    $.ajax({
                        url: "<?php echo base_url(); ?>index.php/maestros/temporaldetalle/autocomplete_producto/B/" + <?php echo $compania;?>+"/1",
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

     
        $("#buscarC").click(function(){

            $('.filasdetalles').remove();

            producto            = $("#producto").val();
            search_fechai       = $("#search_fechai").val();
            search_fechaf       = $("#search_fechaf").val();
            moneda              = $("#moneda").val();
            if($("#localestotal").is(':checked')) {
                companias='todos';
            }

            if($("#localesactual").is(':checked')) {
                companias = 'compania_actual';
            }

            $('#table-ganancia').DataTable({
                filter: false,
                destroy: true,
                processing: true,
                serverSide: true,
                ajax:{
                    url : '<?=base_url();?>index.php/reportes/ventas/datatable_ganancia/',
                    type: "POST",
                    data: {
                        producto: producto,
                        companias: companias,
                        moneda: moneda,
                        fechai: search_fechai,
                        fechaf: search_fechaf
                    },

                    beforeSend: function(){
                        $("#table-ganancia .loading-table").show();
                    },
                    error: function(){
                    },
                    complete: function(){
                        $("#table-ganancia .loading-table").hide();
                    },
                    "dataSrc": function ( json ) {

                        for (let i=0;i<json.detalles.length;i++)
                        {
                            let fila='<tr class="filasdetalles">' +
                                '<td>'+json.detalles[i].compania+'</td>' +
                                '<td>'+json.detalles[i].costototal+'</td>' +
                                '<td>'+json.detalles[i].ventatotal+'</td>' +
                                '<td>'+json.detalles[i].utilidad+'</td>' +
                                '<td>'+json.detalles[i].proc_utilidad+'</td>' +
                                '</tr>'
                            $('#detalles').append(fila);
                        }

                        return json.data;

                    }
                },
                language: spanish,
                columnDefs: [{"className": "dt-center", "targets": 0}],
                order: [[ 1, "desc" ]]
            });
        });

        $("#limpiarC").click(function(){

           
            location.href = "<?php echo base_url()?>index.php/reportes/ventas/ganancia";
        });

        $('#form_busqueda').keypress(function(e){
            if ( e.which == 13 ){
                return false;
            } 
        });

        $('#search_descripcion').keyup(function(e){
            if ( e.which == 13 ){
                if( $(this).val() != '' )
                    profits();
            }
        });
        $('#search_fechai').keyup(function(e){
            if ( e.which == 13 ){
                if( $(this).val() != '' )
                    profits();
            }
        });
        $('#search_fechaf').keyup(function(e){
            if ( e.which == 13 ){
                if( $(this).val() != '' )
                    profits();
            }
        });

       
    });

    function profits( search = true){

        var companias = new Array();
        var j = 1;
        var k = 0;
        for (var i = 1; i <= <?php echo count($lista_companias);?>; i++) {
            var checkBox = document.getElementById("COMPANIA_"+j);
            if (checkBox.checked == true){
                 companias[k] = $("#COMPANIA_"+j).val();
                 k++;   
            }
            j++;

         
        }

        if (search == true){
            producto            = $("#producto").val();
            search_descripcion  = $("#search_descripcion").val();
            search_fechai       = $("#search_fechai").val();
            search_fechaf       = $("#search_fechaf").val();
            moneda              = $("#moneda").val();
            
            var checkedall = document.getElementById("TODOS");
            todos="";
            if (checkedall.checked == true){
                 todos = $("#TODOS").val();
            }
            

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

            search_codigo       = "";
            search_descripcion  = "";
            search_tipo         = "";
            search_fechai       = "";
            search_fechaf       = "";
            companias           = "";
            producto            = "";
            todos               = ""
        }
        
        $('#table-ganancia').DataTable({
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax:{
                    url : '<?=base_url();?>index.php/reportes/ventas/datatable_ganancia/',
                    type: "POST",
                    data: {
                            producto: producto,
                            descripcion: search_descripcion,
                            companias: companias,
                            todos: todos,
                            moneda: moneda,
                            fechai: search_fechai,
                            fechaf: search_fechaf
                    },
                    beforeSend: function(){
                        $("#table-ganancia .loading-table").show();
                    },
                    error: function(){
                    },
                    complete: function(){
                        $("#table-ganancia .loading-table").hide();
                    }
            },
            language: spanish,
            columnDefs: [{"className": "dt-center", "targets": 0}],
            order: [[ 1, "desc" ]]
        });
    }


    $("#localestotal").click(function() {
        $( "#localesactual" ).prop( "checked", false );

    });

    $("#localesactual").click(function() {
        $( "#localestotal" ).prop( "checked", false );

    });


    function descargarExcel() {
        base_url = "<?php echo base_url();?>";
        if (!$('#producto').val()){
            productoBuscar_id= 'noValue';
        }
        else {
            productoBuscar_id= $('#producto').val();
        }

        if ($('#search_fechai').val() && $('#search_fechaf').val()){
            IntervalosFechas = $('#search_fechai').val()+'-'+$('#search_fechaf').val();
        }
        else {
            IntervalosFechas = 'noValue';
        }



        if($("#localestotal").is(':checked')) {
            Companias='todos';
        }

        if($("#localesactual").is(':checked')) {
            Companias = 'compania_actual';
        }
        moneda=$('#moneda').val();


       location.href = base_url + "index.php/reportes/ventas/gananciaExcel/"+productoBuscar_id+"/"+Companias+"/"+IntervalosFechas+"/"+moneda;
    }


</script>



