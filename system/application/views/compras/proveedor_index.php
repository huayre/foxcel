<script type="text/javascript" src="<?php echo base_url();?>js/jquery.js?=<?=JS;?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.js?=<?=JS;?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/compras/proveedor.js?=<?=JS;?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/funciones.js?=<?=JS;?>"></script>		
<script type="text/javascript" src="<?php echo base_url();?>js/fancybox/jquery.mousewheel-3.0.4.pack.js?=<?=JS;?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/fancybox/jquery.fancybox-1.3.4.pack.js?=<?=JS;?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>js/fancybox/jquery.fancybox-1.3.4.css?=<?=CSS;?>" media="screen" />			
<script type="text/javascript">
		$(document).ready(function(){
			$("a#linkVerMarca").fancybox({
				'width'          : 700,
				'height'         : 450,
				'autoScale'	 : false,
				'transitionIn'   : 'none',
				'transitionOut'  : 'none',
				'showCloseButton': false,
				'modal'          : true,
				'type'	     : 'iframe'
			});
			
			$("a#linkVerTipo").fancybox({
				'width'          : 700,
				'height'         : 450,
				'autoScale'	 : false,
				'transitionIn'   : 'none',
				'transitionOut'  : 'none',
				'showCloseButton': false,
				'modal'          : true,
				'type'	     : 'iframe'
			}); 
		});
		
		function cargar_familia(familia,nombre,codfamilia){
				a    = "tipoCodigo";
				b    = "tipoNombre";
				document.getElementById(a).value = familia;
				document.getElementById(b).value = nombre;
		}
		
		function seleccionar_marca(codigo,nombre){
			a    = "marcaCodigo";
			b    = "marcaNombre";
			document.getElementById(a).value = codigo;
			document.getElementById(b).value = nombre;
		}
		
		function buscar_marca(){
			base_url = $("#base_url").val();
			$('a#linkVerMarca').click();
		}
		
		function buscar_tipo(){
			base_url = $("#base_url").val();
			$('#linkVerTipo').click();
		}

</script>				
<div id="pagina">
    <div id="zonaContenido">
                <div align="center">
                        <div id="tituloForm" class="header">Buscar PROVEEDOR </div>
                        <div id="frmBusqueda">
                        <form id="form_busqueda" name="form_busqueda" method="post" action="<?php echo $action;?>">
                            <table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
                                    <tr>
                                            <td width="16%">N. de Documento </td>
                                            <td width="68%">
                                                <input id="txtNumDoc" type="text" list="list_grupo1" class="cajaPequena" NAME="txtNumDoc" maxlength="11" onkeypress="return numbersonly(this,event,'.');" value="<?php echo $numdoc; ?>">
                                            <td width="5%">&nbsp;</td>
                                            <td width="5%">&nbsp;</td>
                                            <td width="6%" align="right"></td>
                                    </tr>
                                    <tr>
                                            <td>Nombre o Raz&oacute;n Social</td>
                                            <td><input id="txtNombre" name="txtNombre" type="text" list="list_grupo"  class="cajaGrande" maxlength="45" value="<?php echo $nombre; ?>"></td>
                                            
                                            <!--                                    se puso el datalist -->
                                    <td><datalist id="list_grupo">
                                    <?php
                                    $grupos=$this->Global_model->get('cji_empresa');
                                    if ($grupos) {
                                        foreach ($grupos as $grupo) :
                                            ?>
                                            <option value="<?php echo $grupo->EMPRC_RazonSocial ?>"></option>
                                            <?php
                                        endforeach;
                                    }
                                    ?>
                                        </datalist></td>
<!--                                        termina el datalist-->
                                            
                                            
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td>Tel&eacute;fono/Celular</td>
                                      <td><input id="txtTelefono" type="text" class="cajaPequena" NAME="txtTelefono" maxlength="15" value="<?php echo $telefono; ?>"></td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      <td>&nbsp;</td>
                                      </tr>
                                      <!--  <tr>
                                        <td>Tipo Persona</td>
                                        <td>
                                                <select id="cboTipoProveedor" name="cboTipoProveedor" class="comboMedio">
                                                        <option value="" selected>::Seleccionar::</option>
                                                        <option value="N" <?php if($tipo=='N') echo 'selected="selected"'; ?> >P.Natural</option>
                                                        <option value="J" <?php if($tipo=='J') echo 'selected="selected"'; ?>>P.Juridica</option>
                                                </select>
                                        </td>
                                        </tr>
                                        <tr>
											<td>Tipo Proveedor</td>
											<td>
												<a href="<?php echo base_url(); ?>index.php/almacen/tipoproveedor/nueva_familia" id='linkVerTipo'></a>
												<input type="hidden" value="<?php echo $codtipoproveedor; ?>" name="tipoCodigo" id="tipoCodigo" class="cajaMedia">
												<input type="text" name="tipoNombre" value="<?php echo $nombre_tipoproveedor; ?>" id="tipoNombre" readonly="" class="cajaMedia">
												<a href="#" onclick="buscar_tipo();">
												<img src="<?php echo base_url();?>images/ver.png?=<?=IMG;?>" border="0"></a>
											</td>
                                        </tr>
                                        <tr>
											<td>Marca</td>
											<td>
												<a href="<?php echo base_url();?>index.php/almacen/marca/ventana_busqueda_marca/" id='linkVerMarca'></a>
												<input type="hidden" name="marcaCodigo" value="<?php echo $codmarca; ?>" id="marcaCodigo" class="cajaMedia">
												<input type="text" name="marcaNombre" readonly="" id="marcaNombre" value="<?php echo $nombre_marcaproveedor; ?>" class="cajaMedia">
												<a href="#" onclick="buscar_marca();">
												<img src="<?php echo base_url();?>images/ver.png?=<?=IMG;?>" border="0"></a>
											</td>

                                        </tr>
                                         <tr>
                                            <td>Dirección</td>
                                             <td>
                                             <input id="txtDireccion" name="txtDireccion" type="text"    maxlength="45" value="<?php echo $nombre; ?>"></td>
                                         </tr>-->
                            </table>
                        </form>
                  </div>
				  <div class="acciones">
                    <div id="botonBusqueda">
                            <ul id="imprimirProveedor" class="lista_botones"><li id="imprimir">Imprimir</li></ul>
                            <ul id="nuevoProveedor" class="lista_botones"><li id="nuevo">Nuevo Proveedor</li></ul>
                            <ul id="limpiarProveedor" class="lista_botones"><li id="limpiar">Limpiar</li></ul>
                            <ul id="buscarProveedor" class="lista_botones"><li id="buscar">Buscar</li></ul>   
                    </div>
					  <div id="lineaResultado">
						  <table class="fuente7" width="100%" cellspacing=0 cellpadding=3 border="0">
								<tr>
								<td width="100%" align="left">N de proveedores encontrados:&nbsp;<?php echo $registros;?> </td>
								<td width="50%" align="right">&nbsp;</td>
						  </table>
					  </div>
				  </div>
                        <div id="cabeceraResultado" class="header">
                                <?php echo $titulo_tabla; ?> </div>
                        <div id="frmResultado">
                        <table class="fuente8" width="100%" cellspacing="0" cellpadding="3" border="0" ID="Table1">
                                        <tr class="cabeceraTabla">
                                                <td width="8%">ITEM</td>
                                                <td width="5%">RUC</td>
                                                <td width="5%">DNI</td>
                                                <td width="36%">NOMBRE O RAZ&Oacute;N SOCIAL </td>
                                                <td width="5%">TIPO PROVEEDOR </td>
                                                <td width="21%">DIRECCI&Oacute;N</td>
                                               <!--<td width="19%">M&Oacute;VIL</td>-->
                                                <td colspan="3">&nbsp;</td>
                                        </tr>
                                        <?php
                                        $i=1;
                                        if(count($lista)>0){
                                        foreach($lista as $indice=>$valor){
                                                $class = $indice%2==0?'itemParTabla':'itemImparTabla';
                                                ?>
                                                <tr class="<?php echo $class;?>">
                                                        <td>
                                                   <div align="center"><?php echo $valor[0];?></div>
                                                        </td>
                                                        <td>
                                                    <div align="center"><?php echo $valor[1];?></div>
                                                        </td>

                                                        <!--DNI-->
                                                    <td><div align="center"><?php echo $valor[2];?></div></td>

                                                        <!--RaZON SOCIAS-->
                                                    <td><div align="left"><?php echo $valor[3];?></div></td>

                                                        <!--direccion-->
                                                    <td><div align="center"><?php echo $valor[4];?></div></td>

                                                        <!--tipo proveedor-->
                                                         <td>
                                                    <div align="center"><?php echo $valor[5];?></div>
                                                         </td>
                                                        <td>
                                                        <div align="center"><?php echo $valor[6];?></div>
                                                        </td>


                                                        <td><div align="center"><?php echo $valor[7];?></div></td>
                                                        <td><div align="center"><?php echo $valor[8];?></div></td>

                                                       <!-- <td><div align="center"><?php echo $valor[9];?></div></td>-->
                                                </tr>
                                                <?php
                                                $i++;
                                                }
                                        }
                                        else{
                                        ?>
                                        <table width="100%" cellspacing="0" cellpadding="3" border="0" class="fuente8">
                                                <tbody>
                                                        <tr>
                                                                <td width="100%" class="mensaje">No hay ning&uacute;n proveedor que cumpla con los criterios de b&uacute;squeda</td>
                                                        </tr>
                                                </tbody>
                                        </table>
                                        <?php
                                        }
                                        ?>
                        </table>
                        <input type="hidden" id="iniciopagina" name="iniciopagina">
                        <input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
                        
                </div>
            <div style="margin-top: 15px;"><?php echo $paginacion;?></div>
            <input type="text" style="visibility:hidden" name="base_url" id="base_url" value="<?php echo base_url();?>">
        </div>
    </div>
</div>