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
            <div class="col-sm-4 col-md-4 col-lg-4"></div>
            <div class="col-sm-2 col-md-2 col-lg-2">
                <input type="text" name="search_descripcion" id="search_descripcion" value="" placeholder="Proyecto" class="form-control h-1 w-porc-90"/>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2">
                <input type="hidden" name="search_cliente" id="search_cliente" value="" class="form-control h-1"/>
                <input type="number" name="search_ruc" id="search_ruc" value="" placeholder="Número de ruc" class="form-control h-1 w-porc-90"/>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2">
                <input type="text" name="search_razon_social" id="search_razon_social" value="" placeholder="Razón social" class="form-control h-1 w-porc-90"/>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 pall-0">
                    <div class="acciones">
                        <div id="botonBusqueda">
                            <ul class="lista_botones">
                                <li id="nuevo" data-toggle='modal' data-target='#add_proyecto'>Proyecto</li>
                            </ul>
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
                    <table class="fuente8 display" id="table-proyecto">
                        <div id="cargando_datos" class="loading-table">
                            <img src="<?=base_url().'images/loading.gif?='.IMG;?>">
                        </div>
                        <thead>
                            <tr class="cabeceraTabla">
                                <td style="width:20%" data-orderable="true">PROYECTO</td>
                                <td style="width:65%" data-orderable="false">DESCRIPCIÓN</td>
                                <td style="width:05%" data-orderable="false"></td>
                                <td style="width:05%" data-orderable="false"></td>
                                <td style="width:05%" data-orderable="false"></td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="add_proyecto" class="modal fade" role="dialog">
    <div class="modal-dialog w-porc-60">
        <div class="modal-content">
            <form id="formProyecto" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div style="text-align: center;">
                    <h3><b>REGISTRAR PROYECTO</b></h3>
                </div>
                <div class="modal-body panel panel-default">
                    <input type="hidden" id="proyecto" name="proyecto" value="">

                    <div class="row form-group">
                        <div class="col-sm-11 col-md-11 col-lg-11 header">
                            <span>INFORMACIÓN DEL PROYECTO</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <label for="nombre_proyecto">NOMBRE *</label>
                            <input type="text" id="nombre_proyecto" name="nombre_proyecto" class="form-control h-2" placeholder="Nombre del proyecto" value="">
                        </div>
                        <div class="col-sm-2 col-md-2 col-lg-2">
                            <label for="fecha_inicio">FECHA DE INICIO *</label>
                            <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control h-2" value="">
                        </div>
                        <div class="col-sm-2 col-md-2 col-lg-2">
                            <label for="fecha_final">FECHA FINAL *</label>
                            <input type="date" id="fecha_final" name="fecha_final" class="form-control h-2" value="">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-10 col-md-10 col-lg-10">
                            <label for="codigo_proyecto">DESCRIPCIÓN *</label>
                            <textarea class="form-control h-5" placeholder="Descripción del proyecto"></textarea>
                        </div>
                    </div>
                    
                    <div class="row form-group">
                        <div class="col-sm-11 col-md-11 col-lg-11 header">
                            <span>CLIENTE</span>
                            <input type="hidden" id="cliente" name="cliente" value="">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-2 col-md-2 col-lg-2">
                            <label for="ruc">RUC</label>
                            <input type="number" id="ruc" name="ruc" class="form-control h-2" placeholder="Número de documento" value="">
                        </div>
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <label for="razon_social">RAZÓN SOCIAL</label>
                            <input type="text" id="razon_social" name="razon_social" class="form-control h-2" placeholder="Razón social" value="">
                        </div>
                    </div>
                    
                    <div class="row form-group">
                        <div class="col-sm-11 col-md-11 col-lg-11 header">
                            <span>DIRECCIONES</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" accesskey="x" onclick="registrar_proyecto()">Guardar Registro</button>
                    <button type="button" class="btn btn-info" onclick="clean()">Limpiar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    base_url = "<?=$base_url;?>";

    $(document).ready(function(){
        $('#table-proyecto').DataTable({
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax:{
                    url : '<?=base_url();?>index.php/maestros/proyecto/datatable_proyecto/',
                    type: "POST",
                    data: { dataString: "" },
                    beforeSend: function(){
                        $("#table-proyecto .loading-table").show();
                    },
                    error: function(){
                    },
                    complete: function(){
                        $("#table-proyecto .loading-table").hide();
                    }
            },
            language: spanish,
            columnDefs: [{"className": "dt-center", "targets": 0}],
            order: [[ 0, "asc" ]]
        });

        $("#buscarC").click(function(){
            search();
        });

        $("#limpiarC").click(function(){
            search(false);
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

        
        $("#razon_social, #search_razon_social").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: base_url + "index.php/ventas/cliente/autocomplete/",
                    type: "POST",
                    data: {
                        term: $("#razon_social").val()
                    },
                    dataType: "json",
                    success: function (data) {
                        response(data);
                    }
                });
            },
            select: function (event, ui){
                $("#cliente").val(ui.item.codigo);
                $("#ruc").val(ui.item.ruc);
                $("#razon_social").val(ui.item.nombre);

                $("#search_cliente").val(ui.item.codigo);
                $("#search_ruc").val(ui.item.ruc);
                $("#search_razon_social").val(ui.item.nombre);
            },
            minLength: 2
        });

        $("#ruc, #search_ruc").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: base_url + "index.php/ventas/cliente/autocomplete_ruc/",
                    type: "POST",
                    data: {
                        term: $("#ruc").val()
                    },
                    dataType: "json",
                    success: function (data){
                        response(data);
                    }
                });
            },
            select: function (event, ui) {
                $("#cliente").val(ui.item.codigo);
                $("#ruc").val(ui.item.ruc);
                $("#razon_social").val(ui.item.nombre);

                $("#search_cliente").val(ui.item.codigo);
                $("#search_ruc").val(ui.item.ruc);
                $("#search_razon_social").val(ui.item.nombre);
            },
            minLength: 2
        });
    });

    function search( search = true){
        if (search == true){
            search_descripcion = $("#search_descripcion").val();
            search_cliente = $("#search_cliente").val();
        }
        else{
            $("#search_descripcion").val("");
            $("#search_cliente").val("");

            search_descripcion = "";
            search_cliente = "";
            
            $("#search_ruc").val("");
            $("#search_razon_social").val("");
        }
        
        $('#table-proyecto').DataTable({
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax:{
                    url : '<?=base_url();?>index.php/maestros/proyecto/datatable_proyecto/',
                    type: "POST",
                    data: {
                            nombre: search_descripcion,
                            cliente: search_cliente
                    },
                    beforeSend: function(){
                        $("#table-proyecto .loading-table").show();
                    },
                    error: function(){
                    },
                    complete: function(){
                        $("#table-proyecto .loading-table").hide();
                    }
            },
            language: spanish,
            columnDefs: [{"className": "dt-center", "targets": 0}],
            order: [[ 0, "asc" ]]
        });
    }

    function editar(id){
        var url = base_url + "index.php/maestros/proyecto/getProyecto";
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            data:{
                    proyecto: id
            },
            beforeSend: function(){
                clean();
            },
            success: function(data){
                if (data.match == true) {
                    info = data.info;

                    $("#proyecto").val(info.proyecto);
                    $("#codigo_proyecto").val(info.codigo);
                    $("#descripcion_proyecto").val(info.descripcion);

                    $("#add_proyecto").modal("toggle");
                }
                else{
                    Swal.fire({
                                icon: "info",
                                title: "Información no disponible.",
                                html: "<b class='color-red'></b>",
                                showConfirmButton: true,
                                timer: 4000
                            });
                }
            },
            complete: function(){
            }
        });
    }

    function registrar_proyecto(){
        Swal.fire({
                    icon: "info",
                    title: "¿Esta seguro de guardar el registro?",
                    html: "<b class='color-red'></b>",
                    showConfirmButton: true,
                    showCancelButton: true,
                    confirmButtonText: "Aceptar",
                    cancelButtonText: "Cancelar"
                }).then(result => {
                    if (result.value){
                        var proyecto = $("#proyecto").val();
                        var descripcion = $("#descripcion_proyecto").val();
                        validacion = true;

                        if (descripcion == ""){
                            Swal.fire({
                                        icon: "error",
                                        title: "Verifique los datos ingresados.",
                                        html: "<b class='color-red'>Debe ingresar una descripcion.</b>",
                                        showConfirmButton: true,
                                        timer: 4000
                                    });
                            $("#descripcion_proyecto").focus();
                            validacion = false;
                            return null;
                        }

                        if (validacion == true){
                            var url = base_url + "index.php/maestros/proyecto/guardar_registro";
                            var info = $("#formProyecto").serialize();
                            $.ajax({
                                type: 'POST',
                                url: url,
                                dataType: 'json',
                                data: info,
                                success: function(data){
                                    if (data.result == "success") {
                                        if (proyecto == "")
                                            titulo = "¡Registro exitoso!";
                                        else
                                            titulo = "¡Actualización exitosa!";

                                        Swal.fire({
                                            icon: "success",
                                            title: titulo,
                                            showConfirmButton: true,
                                            timer: 2000
                                        });

                                        clean();
                                    }
                                    else{
                                        Swal.fire({
                                            icon: "error",
                                            title: "Sin cambios.",
                                            html: "<b class='color-red'>La información no fue registrada/actualizada, intentelo nuevamente.</b>",
                                            showConfirmButton: true,
                                            timer: 4000
                                        });
                                    }
                                },
                                complete: function(){
                                    $("#descripcion_proyecto").focus();
                                }
                            });
                        }
                    }
                });
    }

    function deshabilitar(proyecto){
        Swal.fire({
                    icon: "info",
                    title: "Debe confirmar esta acción.",
                    html: "<b class='color-red'>Esta acción no se puede deshacer</b>",
                    showConfirmButton: true,
                    showCancelButton: true,
                    confirmButtonText: "Aceptar",
                    cancelButtonText: "Cancelar"
                }).then(result => {
                    if (result.value){
                        var url = base_url + "index.php/maestros/proyecto/deshabilitar_proyecto";
                        $.ajax({
                            type: 'POST',
                            url: url,
                            dataType: 'json',
                            data: {
                                proyecto: proyecto
                            },
                            success: function(data){
                                if (data.result == "success") {
                                    titulo = "¡Registro eliminado!";
                                    Swal.fire({
                                        icon: "success",
                                        title: titulo,
                                        showConfirmButton: true,
                                        timer: 2000
                                    });
                                }
                                else{
                                    Swal.fire({
                                        icon: "error",
                                        title: "Sin cambios.",
                                        html: "<b class='color-red'>Algo ha ocurrido, verifique he intentelo nuevamente.</b>",
                                        showConfirmButton: true,
                                        timer: 4000
                                    });
                                }
                            },
                            complete: function(){
                                search(false);
                            }
                        });
                    }
                });
    }

    function clean(){
        $("#formProyecto")[0].reset();
        $("#proyecto").val("");
        $("#cliente").val("");
    }
</script>