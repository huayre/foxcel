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
            <div class="col-sm-9 col-md-9 col-lg-9"></div>
            <div class="col-sm-2 col-md-2 col-lg-2">
                <input type="text" name="search_descripcion" id="search_descripcion" value="" placeholder="Marca" class="form-control h-1"/>
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
                                <li id="nuevo" data-toggle='modal' data-target='#add_marca'>Marca</li>
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
                    <table class="fuente8 display" id="table-marca">
                        <div id="cargando_datos" class="loading-table">
                            <img src="<?=base_url().'images/loading.gif?='.IMG;?>">
                        </div>
                        <thead>
                            <tr class="cabeceraTabla">
                                <td style="width:10%" data-orderable="false">N°</td>
                                <td style="width:10%" data-orderable="false">CÓDIGO</td>
                                <td style="width:70%" data-orderable="false">MARCA</td>
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

<div id="add_marca" class="modal fade" role="dialog">
    <div class="modal-dialog w-porc-60">
        <div class="modal-content">
            <form id="formMarca" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div style="text-align: center;">
                    <h3><b>REGISTRAR MARCA</b></h3>
                </div>
                <div class="modal-body panel panel-default">
                    <input type="hidden" id="marca" name="marca" value="">

                    <div class="row form-group">
                        <div class="col-sm-5 col-md-5 col-lg-5">
                            <label for="descripcion_marca">DESCRIPCIÓN DE MARCA *</label>
                            <input type="text" id="descripcion_marca" name="descripcion_marca" class="form-control h-2" placeholder="Indique la marca" value="">
                        </div>
                        <div class="col-sm-2 col-md-2 col-lg-2">
                            <label for="codigo_marca">CÓDIGO</label>
                            <input type="text" id="codigo_marca" name="codigo_marca" class="form-control h-2 w-porc-90" placeholder="Indique el codigo" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" accesskey="x" onclick="registrar_marca()">Guardar Registro</button>
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
        $('#table-marca').DataTable({
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax:{
                    url : '<?=base_url();?>index.php/almacen/marca/datatable_marca/',
                    type: "POST",
                    data: { dataString: "" },
                    beforeSend: function(){
                        $("#table-marca .loading-table").show();
                    },
                    error: function(){
                    },
                    complete: function(){
                        $("#table-marca .loading-table").hide();
                    }
            },
            language: spanish,
            columnDefs: [{"className": "dt-center", "targets": 0}],
            order: [[ 1, "asc" ]]
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
    });

    function search( search = true){
        if (search == true){
            search_descripcion = $("#search_descripcion").val();
        }
        else{
            $("#search_descripcion").val("");
            search_descripcion = "";
        }
        
        $('#table-marca').DataTable({
            filter: false,
            destroy: true,
            processing: true,
            serverSide: true,
            ajax:{
                    url : '<?=base_url();?>index.php/almacen/marca/datatable_marca/',
                    type: "POST",
                    data: {
                            descripcion: search_descripcion
                    },
                    beforeSend: function(){
                        $("#table-marca .loading-table").show();
                    },
                    error: function(){
                    },
                    complete: function(){
                        $("#table-marca .loading-table").hide();
                    }
            },
            language: spanish,
            columnDefs: [{"className": "dt-center", "targets": 0}],
            order: [[ 1, "asc" ]]
        });
    }

    function editar(id){
        var url = base_url + "index.php/almacen/marca/getMarca";
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            data:{
                    marca: id
            },
            beforeSend: function(){
                clean();
            },
            success: function(data){
                if (data.match == true) {
                    info = data.info;

                    $("#marca").val(info.marca);
                    $("#codigo_marca").val(info.codigo);
                    $("#descripcion_marca").val(info.descripcion);

                    $("#add_marca").modal("toggle");
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

    function registrar_marca(){
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
                        var marca = $("#marca").val();
                        var descripcion = $("#descripcion_marca").val();
                        validacion = true;

                        if (descripcion == ""){
                            Swal.fire({
                                        icon: "error",
                                        title: "Verifique los datos ingresados.",
                                        html: "<b class='color-red'>Debe ingresar una descripcion.</b>",
                                        showConfirmButton: true,
                                        timer: 4000
                                    });
                            $("#descripcion_marca").focus();
                            validacion = false;
                            return null;
                        }

                        if (validacion == true){
                            var url = base_url + "index.php/almacen/marca/guardar_registro";
                            var info = $("#formMarca").serialize();
                            $.ajax({
                                type: 'POST',
                                url: url,
                                dataType: 'json',
                                data: info,
                                success: function(data){
                                    if (data.result == "success") {
                                        if (marca == "")
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
                                    $("#descripcion_marca").focus();
                                }
                            });
                        }
                    }
                });
    }

    function deshabilitar(marca){
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
                        var url = base_url + "index.php/almacen/marca/deshabilitar_marca";
                        $.ajax({
                            type: 'POST',
                            url: url,
                            dataType: 'json',
                            data: {
                                marca: marca
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
        $("#formMarca")[0].reset();
        $("#marca").val("");
    }
</script>