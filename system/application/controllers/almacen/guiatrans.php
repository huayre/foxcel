<?php

include("system/application/libraries/cezpdf.php");
include("system/application/libraries/class.backgroundpdf.php");

class Guiatrans extends controller
{

    private $_hoy;

    public function __construct()
    {
        parent::Controller();

        $this->load->model('almacen/guiatrans_model');
        $this->load->model('almacen/guiasa_model');
        $this->load->model('almacen/guiain_model');

        $this->load->model('almacen/guiatransdetalle_model');
        $this->load->model('almacen/guiasadetalle_model');
        $this->load->model('almacen/guiaindetalle_model');
        $this->load->model('almacen/almacen_model');
        $this->load->model('almacen/producto_model');
        $this->load->model('maestros/empresa_model');
        $this->load->model('almacen/unidadmedida_model');
        $this->load->model('almacen/tipomovimiento_model');
        $this->load->model('maestros/documento_model');
        $this->load->model('maestros/compania_model');
        $this->load->model('maestros/empresa_model');
        $this->load->model('maestros/companiaconfiguracion_model');
        $this->load->model('maestros/companiaconfidocumento_model');
        $this->load->model('maestros/moneda_model');
        $this->load->model('compras/ocompra_model');
        $this->load->model('seguridad/usuario_model');
        $this->load->model('almacen/marca_model');
        $this->load->model('almacen/seriedocumento_model');
        $this->load->helper('form', 'url');
        $this->load->helper('utf_helper');
        $this->load->helper('util_helper');
        $this->load->helper('my_almacen');
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->library('lib_props');
        $this->somevar['user'] = $this->session->userdata('user');
        $this->somevar['compania'] = $this->session->userdata('compania');
        $this->somevar['establec'] = $this->session->userdata('establec');
        date_default_timezone_set('America/Lima');
        $this->_hoy = mdate("%Y-%m-%d ", time());
    }

    public function listar($j = 0){
        $this->load->library('layout', 'layout');

        $data['fechai'] = '';
        $data['fechaf'] = '';
        $data['serie'] = '';
        $data['numero'] = '';
        $data['producto'] = '';
        $data['codproducto'] = '';
        $data['nombre_producto'] = '';
        $data['movimiento'] = '';

        $data['titulo_busqueda'] = "GUIAS DE TRANSFERENCIA";
        $data['titulo_tabla'] = "GUIAS DE TRANSFERENCIA";
        $data['oculto'] = form_hidden(array('base_url' => base_url()));
        $this->layout->view('almacen/guiatrans_index', $data);
    }

		public function datatable_guias_salida()
		{
				$columnas = array(
				0 => "GTRANC_Fecha",
				1 => "GTRANC_Serie",
				2 => "GTRANC_Numero",
				3 => "EESTABC_DescripcionOri",
				4 => ""
				);
		    
		    $filter = new stdClass();
		    $filter->start = $this->input->post("start");
		    $filter->length = $this->input->post("length");
		    

		    $filter->fechai = $this->input->post("fechai");
		    $filter->fechaf = $this->input->post("fechaf");
		    $filter->serie = $this->input->post("serie");
		    $filter->numero = $this->input->post("numero");
		    $filter->movimiento = $this->input->post("movimiento");
		    
		    if ($filter->fechaf=="" || $filter->fechaf==null) {
		    	$filter->fechaf = date('y-m-d');
		    }
		    #var_dump($filter);
		    $ordenar = $this->input->post("order")[0]["column"];
		    if ($ordenar != ""){
		        $filter->order = $columnas[$ordenar];
		        $filter->dir = $this->input->post("order")[0]["dir"];
		    }

		    $item = ($this->input->post("start") != "") ? $this->input->post("start") : 0;

		    $guias_transito = $this->guiatrans_model->listar_transferencias_salida($filter);
		    $lista = array();

		    if (count($guias_transito) > 0) {
		        foreach ($guias_transito as $indice => $valor) {
		        		$codigo 			= $valor->GTRANP_Codigo;
		        	$estado_mov 	= $valor->GTRANC_EstadoTrans;
		        	$estado_guia 	= $valor->GTRANC_FlagEstado;
		        	$anular 			= "";
		        	$editar 			= "";
		        	
		        	$pdf = "<a href='javascript:;' onclick='guiatrans_ver_pdf($codigo,1)'><img src='" . base_url() . "images/pdf.png' width='16' height='16' border='0' title='Ver PDF'></a>";
							
							switch ($estado_mov) {
								// Devolucion
								case 3:
									$movimiento_actual = "<div style='width:70px; height:17px; background-color: #5baba8; text-align:center'>Devolucion</div>";
								break;
								// Recibido
								case 2:
									$movimiento_actual = "<div style='width:70px; height:17px; background-color: #00D269; text-align:center'>Recibido</div>";
								break;
								// Transito
								case 1:
										if($estado_mov == 1){
											$estado_mov = 2;	
										}
										$movimiento_actual = "<a href='#' id='trans".$codigo."' title='Enviado correctamente, puede cancelar el envio dando un click' onClick='cargarTransferencia(" . $estado_mov . ",".$codigo.");' ><div style='width:70px; height:17px; background-color: orange; text-align:center' >Enviado</div></a>";
									
								break;
								// Pendiente
								case 0:
									if ($estado_guia == 0) {
										$movimiento_actual = "<div style='width:70px; height:17px; background-color: #ab080c; text-align:center; color: #f1f1f1' >Anulado</div>";
									} else {
										$editar = "<a href='javascript:;' onclick='editar_guiatrans(" . $codigo . ")'><img src='" . base_url() . "images/modificar.png' width='16' height='16' border='0' title='Modificar'></a>";
										$anular = "<a title='Cancelar la transferencia' href='#' onClick='anular_guia(".$codigo.");'><img src='" . base_url() . "images/error.png' width='14px' height='14px'></a>";
									
										$movimiento_actual = "<a href='#' id='trans".$codigo."' title='Transferencia pediente, falta confirmar' onClick='cargarTransferencia(" . $estado_mov . ",".$codigo.");' ><div style='width:70px; height:17px; background-color: #FF6464; text-align:center'>Pendiente</div></a>";
									}
								break;
							}
           		$lista[] = array(
                  0 => mysql_to_human($valor->GTRANC_Fecha),
                  1 => $valor->GTRANC_Serie,
                  2 => $this->lib_props->getOrderNumeroSerie($valor->GTRANC_Numero),
                  3 => $valor->EESTABC_DescripcionDest." - ".$valor->ALMAC_DescripcionDes,
                  4 => $movimiento_actual,
                  5 => $anular,
                  6 => $editar,
                  7 => $pdf,
                  8 => ""
              );
		        }
		    }

		    unset($filter->start);
		    unset($filter->length);

		    $filterAll = new stdClass();
		    $filterAll->tipo_oper = $tipo_oper;
		    $filterAll->tipo_docu = $tipo_docu;

		    $json = array(
		                        "draw"            => intval( $this->input->post('draw') ),
		                        "recordsTotal"    => count($this->guiatrans_model->listar_transferencias_salida($filterAll)),
		                        "recordsFiltered" => intval( count($this->guiatrans_model->listar_transferencias_salida($filter)) ),
		                        "data"            => $lista
		                );

		    echo json_encode($json);
		}

		public function datatable_guias_ingreso() {
			$columnas = array(
				0 => "GTRANC_Fecha",
				1 => "GTRANC_Serie",
				2 => "GTRANC_Numero",
				3 => "EESTABC_DescripcionOri",
				4 => ""
			);
			$compania =$this->somevar['compania'];
			$filter = new stdClass();
			$filter->start 	= $this->input->post("start");
			$filter->length = $this->input->post("length");
			$filter->search = $this->input->post("search")["value"];

			$filter->fechai 		= $this->input->post("fechai");
			$filter->fechaf 		= $this->input->post("fechaf");
			$filter->serie 			= $this->input->post("serie");
			$filter->numero 		= $this->input->post("numero");
			$filter->movimiento = $this->input->post("movimiento");
		  if ($filter->fechaf=="" || $filter->fechaf==null) {
	    	$filter->fechaf = date('y-m-d');
	    }
			$ordenar = $this->input->post("order")[0]["column"];
			if ($ordenar != ""){
				$filter->order = $columnas[$ordenar];
				$filter->dir = $this->input->post("order")[0]["dir"];
			}

			$item = ($this->input->post("start") != "") ? $this->input->post("start") : 0;

			$guias_transito = $this->guiatrans_model->listar_transferencias_ingreso($filter);
			$lista = array();

			if (count($guias_transito) > 0) {
					foreach ($guias_transito as $indice => $valor) {

					$codigo 			= $valor->GTRANP_Codigo;
					$estado_mov 	= $valor->GTRANC_EstadoTrans;
					$estado_guia 	= $valor->GTRANC_FlagEstado;
					$anular 			= "";
					$editar 			= "";
					$pdf = "<a href='javascript:;' onclick='guiatrans_ver_pdf($codigo,1)'><img src='" . base_url() . "images/pdf.png' width='16' height='16' border='0' title='Ver PDF'></a>";
					switch ($estado_mov) {
					// Devolucion
					case 3:
						$movimiento_actual = "<div style='width:70px; height:17px; background-color: #5baba8; text-align:center'>Devolucion</div>";
					break;
					// Recibido
					case 2:
						$movimiento_actual = "<div style='width:70px; height:17px; background-color: #00D269; text-align:center'>Recibido</div>";
					break;
					// Transito
					case 1:

						$movimiento_actual = "<a href='#' id='trans".$codigo."' title='Enviado correctamente, puede cancelar el envio dando un click' onClick='cargarTransferencia(" . $estado_mov . ",".$codigo.");' ><div style='width:70px; height:17px; background-color: yellow; text-align:center' >Transito</div></a>";

					break;
					// Pendiente
					case 0:
						if ($estado_guia == 0) {
							$movimiento_actual = "<div style='width:70px; height:17px; background-color: #ab080c; text-align:center; color: #f1f1f1' >Anulado</div>";
						} else {
							$movimiento_actual = "<div style='width:70px; height:17px; background-color: #FF6464; text-align:center'>Pendiente</div>";
						}
					break;
					}



					$lista[] = array(
                0 => mysql_to_human($valor->GTRANC_Fecha),
                1 => $valor->GTRANC_Serie,
                2 => $this->lib_props->getOrderNumeroSerie($valor->GTRANC_Numero),
                3 => $valor->EESTABC_DescripcionOri." - ".$valor->ALMAC_DescripcionOri,
                4 => $movimiento_actual,
                5 => $pdf
                
            );
					}
			}

			unset($filter->start);
			unset($filter->length);

			$filterAll = new stdClass();
			$filterAll->tipo_oper = $tipo_oper;
			$filterAll->tipo_docu = $tipo_docu;

			$json = array(
			"draw"            => intval( $this->input->post('draw') ),
			"recordsTotal"    => count($this->guiatrans_model->listar_transferencias_ingreso($filterAll)),
			"recordsFiltered" => intval( count($this->guiatrans_model->listar_transferencias_ingreso($filter)) ),
			"data"            => $lista
			);

			echo json_encode($json);
		}

    public function datatable_guias_transito(){

        $columnas = array(
                            0 => "GTRANC_Fecha",
                            1 => "GTRANC_Serie",
                            2 => "GTRANC_Numero",
                            3 => "EESTABC_DescripcionOri",
                            4 => ""
                        );
        
        $filter = new stdClass();
        $filter->start = $this->input->post("start");
        $filter->length = $this->input->post("length");
        $filter->search = $this->input->post("search")["value"];

        $ordenar = $this->input->post("order")[0]["column"];
        if ($ordenar != ""){
            $filter->order = $columnas[$ordenar];
            $filter->dir = $this->input->post("order")[0]["dir"];
        }

        $item = ($this->input->post("start") != "") ? $this->input->post("start") : 0;

        $guias_transito = $this->guiatrans_model->listar_transferencias_transito($filter);
        $lista = array();

        if (count($guias_transito) > 0) {
            foreach ($guias_transito as $indice => $valor) {
                $lista[] = array(
                                    0 => $valor->GTRANC_Fecha,
                                    1 => $valor->GTRANC_Serie,
                                    2 => $this->lib_props->getOrderNumeroSerie($valor->GTRANC_Numero),
                                    3 => $valor->EESTABC_DescripcionOri,
                                    4 => "TRANSITO"
                                );
            }
        }

        unset($filter->start);
        unset($filter->length);

        $filterAll = new stdClass();
        $filterAll->tipo_oper = $tipo_oper;
        $filterAll->tipo_docu = $tipo_docu;

        $json = array(
                            "draw"            => intval( $this->input->post('draw') ),
                            "recordsTotal"    => count($this->guiatrans_model->listar_transferencias_transito($filterAll)),
                            "recordsFiltered" => intval( count($this->guiatrans_model->listar_transferencias_transito($filter)) ),
                            "data"            => $lista
                    );

        echo json_encode($json);
    }

    public function seleccionar_destino_general($sel = NULL){
        $almacen = $this->almacen_model->seleccionar_destino_general();

        $option = "";
        $emp = "";
        $j = 0;

        if ( count($almacen) > 0){
            $option .= "<select name='almacen_destino' id='almacen_destino' class='comboGrande'>";
            foreach ($almacen as $indice => $val) {
                if ($val->EMPRP_Codigo != $emp){
                    $emp = $val->EMPRP_Codigo;
                   
                    if ($j > 0)
                        $option .= "</optgroup>";

                    $option .= "<optgroup label='$val->EMPRC_RazonSocial'>";
                }

                $option .= ($sel != NULL AND $sel == $val->ALMAP_Codigo) ? "<option value='$val->ALMAP_Codigo' selected>$val->EESTABC_Descripcion - $val->ALMAC_Descripcion</option>" : "<option value='$val->ALMAP_Codigo'>$val->EESTABC_Descripcion - $val->ALMAC_Descripcion</option>";
                $j++;
            }
            $option .= "</optgroup>";
            $option .= "</select>";
        }
        return $option;
    }

    public function nueva(){

        /* :::: SE CREA LA SESSION :::*/
        $hoy = date('Y-m-d H:i:s');
        $cadena = strtotime($hoy).substr((string)microtime(), 1, 8);
        $tempSession = str_replace('.','',$cadena);
        $data['tempSession']  = $tempSession;
        /* :::::::::::::::::::::::::::*/

        $this->load->library('layout', 'layout');
        unset($_SESSION['serie']);
        unset($_SESSION['serieReal']);
        unset($_SESSION['serieRealBD']);
        $compania = $this->somevar['compania'];
        $data['compania'] = $compania;
        $data_confi = $this->companiaconfiguracion_model->obtener($this->somevar['compania']);
        $tipo = 15;
        $data_confi_docu = $this->companiaconfidocumento_model->obtener($data_confi[0]->COMPCONFIP_Codigo, $tipo);
        $data_confi1 = $this->configuracion_model->obtener_numero_documento($compania, $tipo);
        $data['titulo'] = "NUEVA GUIA DE TRANSFERENCIA";
        $data['codigo'] = "";
        $data['form_open'] = form_open(base_url() . 'index.php/almacen/guiatrans/grabar', array("name" => "frmGuiatrans", "id" => "frmGuiatrans", "onSubmit" => "javascript:return FALSE"));
        $data['form_close'] = form_close();
        $data['oculto'] = form_hidden(array("base_url" => base_url(), "tipo_codificacion" => $data_confi_docu[0]->COMPCONFIDOCP_Tipo, 'codigo' => ''));
        $data['serie'] = "";
        $data['cboEmpresaTrans'] = form_dropdown("empresa_transporte", $this->empresa_model->seleccionar(), "1", " class='comboGrande' id='empresa_transporte' style='width:300px'");
        $data['numero'] = "";
        $data['codigo_usuario'] = "";
        $data['fecha'] = form_input(array("name" => "fecha", "id" => "fecha", "class" => "cajaPequena cajaSoloLectura", "readonly" => "readonly", "maxlength" => "10", "value" => mysql_to_human($this->_hoy)));
        $data['observacion'] = "";
        $data['codguiain'] = "";
        $data['codguiasa'] = "";
        $data['placa'] = "";
        $data['licencia'] = "";
        $data['chofer'] = "";

        $data['tipoguia'] = $tipo;
        $data['detalle'] = array();
        $lista_almacen = $this->almacen_model->cargarAlmacenesPorCompania($compania);
        $lista_almacen_general = $this->seleccionar_destino_general();
        $data['listar_almacen'] = $lista_almacen;
        $data['cboAlmacenDestino'] = $lista_almacen_general;

        $data['estado'] = form_dropdown("estado", array("1" => "Activo", "0" => "Anulado"), "1", " class='comboPequeno' id='estado' style='display:none'");
        
        $data['cboEmpresaTrans'] = form_dropdown("empresa_transporte", $this->empresa_model->seleccionar(), "1", " class='comboGrande' id='empresa_transporte' style='width:300px'");

        $data['tipo_codificacion'] = $data_confi_docu[0]->COMPCONFIDOCP_Tipo;
        $data['serie_suger'] = $data_confi1[0]->CONFIC_Serie;
        $data['numero_suger'] = $data_confi1[0]->CONFIC_Numero + 1;
        $this->layout->view('almacen/guiatrans_nueva', $data);
    }

    public function grabar(){
    		$tipo = 15;
        $data_confi = $this->companiaconfiguracion_model->obtener($this->somevar['compania']);
        $data_confi_docu = $this->companiaconfidocumento_model->obtener($data_confi[0]->COMPCONFIP_Codigo, 15);
        $tipo_codificacion = $data_confi_docu[0]->COMPCONFIDOCP_Tipo;

        switch ($tipo_codificacion) {
            case '2':
                if ($this->input->post('serie') == '')
                    exit('{"result":"error", "campo":"serie"}');
                if ($this->input->post('numero') == '')
                    exit('{"result":"error", "campo":"numero"}');
                break;
            case '3':
                if ($this->input->post('codigo_usuario') == '')
                    exit('{"result":"error", "campo":"codigo_usuario"}');
                break;
        }

        if ($this->input->post('almacen') == '' || $this->input->post('almacen') == '0')
            exit('{"result":"error", "campo":"almacen"}');
        if ($this->input->post('almacen_destino') == '' || $this->input->post('almacen_destino') == '0')
            exit('{"result":"error", "campo":"almacen_destino"}');
        if ($this->input->post('almacen') == $this->input->post('almacen_destino'))
            exit('{"result":"error", "campo":"almacen_destino"}');
        if ($this->input->post('fecha') == '')
            exit('{"result":"error", "campo":"fecha"}');
        if ($this->input->post('estado') == '0' && $this->input->post('observacion') == '')
            exit('{"result":"error", "campo":"observacion"}');

        $codigo = $this->input->post("codigo");
        $compania = $this->input->post("compania");
        $serie = $this->input->post("serie") ? $this->input->post("serie") : NULL;
        
        
        $configuracion_datos = $this->configuracion_model->obtener_numero_documento($compania, $tipo);
        
        $codigo_usuario = $this->input->post("codigo_usuario") ? $this->input->post("codigo_usuario") : NULL;
        $almacen = $this->input->post("almacen");
        $almacen_destino = $this->input->post("almacen_destino");
        $fecha = $this->input->post("fecha");
        $observacion = $this->input->post("observacion") ? $this->input->post("observacion") : NULL;
        $estado = $this->input->post("estado");
        $placa = $this->input->post("placa");
        $licencia = $this->input->post("licencia");
        $chofer = $this->input->post("chofer");
        $transporte = $this->input->post("empresa_transporte");

        $prodcodigo = $this->input->post('prodcodigo');
        $produnidad = $this->input->post('produnidad');
        $prodcantidad = $this->input->post('prodcantidad');
        $prodcosto = $this->input->post('prodcosto');
        $proddescri = $this->input->post('proddescri');
        $detaccion = $this->input->post('detaccion');
        $detacodi = $this->input->post('detacodi');
        $flagGenInd = $this->input->post('flagGenIndDet');
        $almacenProducto = $this->input->post('almacenProducto');
        //gcbq
        $this->configuracion_model->modificar_configuracion($compania, 15, $numero, $serie);


        $filter = new stdClass();
        $filter->GTRANC_Serie = $serie;
        $filter->GTRANC_Numero = $numero;
        $filter->GTRANC_CodigoUsuario = $codigo_usuario;
        $filter->GTRANC_AlmacenOrigen = $almacen;
        $filter->GTRANC_AlmacenDestino = $almacen_destino;
        $filter->GTRANC_Fecha = human_to_mysql($fecha);
        $filter->GTRANC_Observacion = $observacion;
        $filter->GTRANC_Placa = $placa;
        $filter->GTRANC_Licencia = $licencia;
        $filter->GTRANC_Chofer = $chofer;
        $filter->EMPRP_Codigo = $transporte;
        $filter->COMPP_Codigo = $compania;
        $filter->USUA_Codigo = $this->somevar['user'];
        $filter->GTRANC_FlagEstado = $estado;

        $guiatrans_id = 0;

        if (isset($codigo) && $codigo > 0) {
            $numero = $this->input->post("numero") ? $this->input->post("numero") : NULL;
            $filter->GTRANC_Numero = $numero;
            $guiatrans_id = $this->guiatrans_model->actualiza_almacen_destino($codigo, $filter);
            // Para poder guardar los productos registrados correctamente
            if ($guiatrans_id > 0) {
                $this->guiatransdetalle_model->eliminar($guiatrans_id);
            }
                /**eliminamos los detalles de seriedocumento
                 * 15:guiatransferencia
                 * ***/
                $this->seriedocumento_model->eliminarDocumento($guiatrans_id,15);
                /**fin de eliminacionb**/
        } else {
            $numero = $configuracion_datos[0]->CONFIC_Numero + 1;
            $filter->GTRANC_Numero = $numero;
            $guiatrans_id = $this->guiatrans_model->insertar($filter);
        }

        if ($guiatrans_id!=0) {

            if (is_array($prodcodigo)) {
                foreach ($prodcodigo as $indice => $valor) {
                    $producto = $prodcodigo[$indice];
                    $unidad = $produnidad[$indice];
                    $cantidad = $prodcantidad[$indice];
                    $costo = $prodcosto[$indice];
                    $descri = $proddescri[$indice];
                    $accion = $detaccion[$indice];
                    $detflag = $flagGenInd[$indice];

                    $filter2 = new stdClass();
                    $filter2->GTRANP_Codigo = $guiatrans_id;
                    $filter2->PROD_Codigo = $producto;
                    $filter2->UNDMED_Codigo = $unidad;
                    $filter2->GTRANDETC_Cantidad = $cantidad;
                    $filter2->GTRANDETC_Costo = $costo;
                    $filter2->GTRANDETC_GenInd = $detflag;
                    $filter2->GTRANDETC_Descripcion = $descri;
                    $filter2->GTRANDETC_FlagEstado = 1;

                    if ( $detaccion[$indice] != 'e' )
                        $this->guiatransdetalle_model->insertar($filter2);
                    
                    /**verificacion de tipo de producto si es con serie**/
                    if ( $detflag == 'I' ){
                        if ( $valor != null ){
                            /**obtenemos las series de session por producto***/
                            $codigoAlmacenProducto = $almacen;
                            $seriesProducto = $_SESSION['serieReal'];
                            #$seriesProducto=$this->session->userdata('serieReal');

                            if ($seriesProducto!=null && count($seriesProducto) > 0 && $seriesProducto!= "") {
                                if( $accion != 'n' ){
                                    $producto_id=$valor;
                                    /***pongo todos en estado cero de las series asociadas a ese producto**/
                                    $seriesProductoBD = $_SESSION['serieRealBD'];
                                    $serieBD = $seriesProductoBD;
                                    if($serieBD!=null && count($serieBD)>0){
                                        foreach ($serieBD as $almBD => $arrAlmacenBD) {
                                            if($almBD==$codigoAlmacenProducto){
                                                foreach ($arrAlmacenBD as $ind1BD => $arrserieBD) {
                                                    if ($ind1BD == $producto_id) {
                                                        foreach ($arrserieBD as $keyBD => $valueBD) {
                                                            /**cambiamos a estado 0**/
                                                            $filterSerieD = new stdClass();
                                                            $filterSerieD->SERDOC_FlagEstado = '0';
                                                            $this->seriedocumento_model->modificar($valueBD->SERDOC_Codigo,$filterSerieD);
                                                            /**deseleccionamos los registros en estadoSeleccion cero:0:desleccionado**/
                                                            $tcomp = "GT-".$guiatrans_id;
                                                            $this->almacenproductoserie_model->seleccionarSerieBD($valueBD->SERIP_Codigo,0,$tcomp);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                
                                if( $accion != 'e' ){
                                    foreach ($seriesProducto as $alm2 => $arrAlmacen2) {
                                        if($alm2==$codigoAlmacenProducto){
                                            foreach ($arrAlmacen2 as $ind2 => $arrserie2){
                                                if ($ind2 == $valor) {
                                                    $serial = $arrserie2;
                                                    if($serial != null && count($serial) > 0){
                                                        foreach ($serial as $i => $serie) {
                                                            $serieNumero=$serie->serieNumero;
                                                            if($serie->serieDocumentoCodigo!=null && $serie->serieDocumentoCodigo!=0){
                                                                $filterSerie= new stdClass();
                                                                $filterSerie->SERDOC_FlagEstado='1';
                                                                $this->seriedocumento_model->modificar($serie->serieDocumentoCodigo,$filterSerie);
                                                            }else{
                                                                /**insertamso serie documento**/
                                                                /**DOCUMENTO COMPROBANTE**/
                                                                $filterSerieD = new stdClass();
                                                                $filterSerieD->SERDOC_Codigo = NULL;
                                                                $filterSerieD->SERIP_Codigo = $serie->serieCodigo;
                                                                /**guiatransferencia origen :10**/
                                                                $filterSerieD->DOCUP_Codigo = 15;
                                                                $filterSerieD->SERDOC_NumeroRef = $guiatrans_id;
                                                                /**2:salida**/
                                                                $filterSerieD->TIPOMOV_Tipo = 6;
                                                                $filterSerieD->SERDOC_FechaRegistro=date("Y-m-d H:i:s");
                                                                $filterSerieD->SERDOC_FlagEstado='1';
                                                                $this->seriedocumento_model->insertar($filterSerieD);
                                                                /**FIN DE INSERTAR EN SERIE**/
                                                                /**los registros en estadoSeleccion 1:seleccionado**/
                                                            }
                                                            $tcomp = "GT-".$guiatrans_id;
                                                            $this->almacenproductoserie_model->seleccionarSerieBD($serie->serieCodigo,1,$tcomp);
                                                        }
                                                    }
                                                    break;
                                                }
                                            }
                                            break;
                                        }
                                    }
                                }
                                
                                if($accion != 'n'){
                                    /**eliminamos los registros en estado cero solo de serieDocumento**/
                                    $this->seriedocumento_model->eliminarDocumento($guiatrans_id,15);
                                }
                            }
                        }
                    }
                    
                    
                    
                    /**fin de verificacion**/
                }
            }

            exit('{"result":"ok", "codigo":"' . $guiatrans_id . '"}');
        } else {
            exit('{"result":"error", "codigo":"' . $guiatrans_id . '"}');
        }
    }

    public function editar($codigo){
        /* :::: SE CREA LA SESSION :::*/
        $hoy = date('Y-m-d H:i:s');
        $cadena = strtotime($hoy).substr((string)microtime(), 1, 8);
        $tempSession = str_replace('.','',$cadena);
        $data['tempSession']  = $tempSession;
        /* :::::::::::::::::::::::::::*/

        $tipo_oper="V";
        $tipo = 15;
        $this->load->library('layout', 'layout');
        unset($_SESSION['serie']);
        $compania = $this->somevar['compania'];
        $data['compania'] = $compania;
        $data_confi = $this->companiaconfiguracion_model->obtener($compania);
        $data_confi_docu = $this->companiaconfidocumento_model->obtener($data_confi[0]->COMPCONFIP_Codigo, $tipo);
        $data_confi1 = $this->configuracion_model->obtener_numero_documento($compania, $tipo);
        $data['titulo'] = "EDITAR GUIA DE TRANSFERENCIA";
        $data['tipo_docu'] = "GT";

        $datos_guiatrans = $this->guiatrans_model->obtener($codigo);
        $data['codigo'] = $codigo;
        $data['form_open'] = form_open(base_url() . 'index.php/almacen/guiatrans/grabar', array("name" => "frmGuiatrans", "id" => "frmGuiatrans"));
        $data['form_close'] = form_close();
        $data['oculto'] = form_hidden(array("base_url" => base_url(), "codigo" => $codigo, "tipo_codificacion" => $data_confi_docu[0]->COMPCONFIDOCP_Tipo));
        $data['codguiain'] = $datos_guiatrans[0]->GUIAINP_Codigo;
        $data['codguiasa'] = $datos_guiatrans[0]->GUIASAP_Codigo;
        $almacorigen = $datos_guiatrans[0]->GTRANC_AlmacenOrigen;
        $data['almacorigen']=$almacorigen;
        if ($almacorigen == $compania) {
            $tipoguia = "";
        } else {
            $tipoguia = 15;
        }
        $data['tipoguia'] = $tipoguia;


        $data['serie'] = $datos_guiatrans[0]->GTRANC_Serie;
        $data['numero'] = $datos_guiatrans[0]->GTRANC_Numero;
        $data['codigo_usuario'] = $datos_guiatrans[0]->GTRANC_CodigoUsuario;
        $data['fecha'] = form_input(array("name" => "fecha", "id" => "fecha", "class" => "cajaPequena cajaSoloLectura", "readonly" => "readonly", "maxlength" => "10", "value" => mysql_to_human($codigo != '' ? $datos_guiatrans[0]->GTRANC_Fecha : $this->_hoy)));
        $data['observacion'] = $datos_guiatrans[0]->GTRANC_Observacion;
        $data['placa'] = $datos_guiatrans[0]->GTRANC_Placa;
        $data['licencia'] = $datos_guiatrans[0]->GTRANC_Licencia;
        $data['chofer'] = $datos_guiatrans[0]->GTRANC_Chofer;
        $transporte = $datos_guiatrans[0]->EMPRP_Codigo;

        $filterin = new stdClass();
        $filterin->TIPOMOVC_Tipo = 6;
        $lista_almacen = $this->almacen_model->cargarAlmacenesPorCompania($datos_guiatrans[0]->COMPP_Codigo);
        $lista_almacen_general = $this->seleccionar_destino_general($datos_guiatrans[0]->GTRANC_AlmacenDestino); 
        $data['listar_almacen'] = $lista_almacen;
        $data['cboAlmacenDestino'] = $lista_almacen_general; 
        
        $data['estado'] = form_dropdown("estado", array("1" => "Activo", "0" => "Anulado"), ($codigo != '' ? $datos_guiatrans[0]->GTRANC_FlagEstado : '1'), " class='comboPequeno' id='estado'");

        $data['tipo_codificacion'] = $data_confi_docu[0]->COMPCONFIDOCP_Tipo;

        $data['cboEmpresaTrans'] = form_dropdown("empresa_transporte", $this->empresa_model->seleccionar(), $transporte, "1", " class='comboGrande' id='empresa_transporte' style='width:300px'");

        $data['serie_suger'] = $data_confi_docu[0]->COMPCONFIDOCP_Serie;
        $data['numero_suger'] = $this->guiatrans_model->obtener_ultimo_numero($data_confi_docu[0]->COMPCONFIDOCP_Serie);

        
        
        unset($_SESSION['serie']);
        unset($_SESSION['serieReal']);
        unset($_SESSION['serieRealBD']);
        
        
        $this->layout->view('almacen/guiatrans_nueva', $data);
    }

    public function modificar(){
        $data_confi = $this->companiaconfiguracion_model->obtener($this->somevar['compania']);
        $data_confi_docu = $this->companiaconfidocumento_model->obtener($data_confi[0]->COMPCONFIP_Codigo, 15);
        $tipo_codificacion = $data_confi_docu[0]->COMPCONFIDOCP_Tipo;


        $codigo_guiatrans = $this->input->post("codigo_guiatrans");
        $compania = $this->input->post("compania");
        $serie = $this->input->post("serie") ? $this->input->post("serie") : NULL;
        $numero = $this->input->post("numero") ? $this->input->post("numero") : NULL;
        $codigo_usuario = $this->input->post("codigo_usuario") ? $this->input->post("codigo_usuario") : NULL;
        $almacen = $this->input->post("almacen");
        $almacen_destino = $this->input->post("almacen_destino");
        $fecha = $this->input->post("fecha");
        $observacion = $this->input->post("observacion") ? $this->input->post("observacion") : NULL;
        $estado = $this->input->post("estado");
        $placa = $this->input->post("placa");
        $licencia = $this->input->post("licencia");
        $chofer = $this->input->post("chofer");
        $transporte = $this->input->post("empresa_transporte");

        $prodcodigo = $this->input->post('prodcodigo');
        $produnidad = $this->input->post('produnidad');
        $prodcantidad = $this->input->post('prodcantidad');
        $prodcosto = $this->input->post('prodcosto');
        $proddescri = $this->input->post('proddescri');
        $detaccion = $this->input->post('detaccion');
        $detacodi = $this->input->post('detacodi');
        $flagGenInd = $this->input->post('flagGenIndDet');


        $filter = new stdClass();
        $filter->GTRANC_Serie = $serie;
        $filter->GTRANC_Numero = $numero;
        $filter->GTRANC_CodigoUsuario = $codigo_usuario;
        $filter->GTRANC_AlmacenOrigen = $almacen;
        $filter->GTRANC_AlmacenDestino = $almacen_destino;
        $filter->GTRANC_Fecha = human_to_mysql($fecha);

        $filter->GTRANC_Observacion = $observacion;
        $filter->GTRANC_Placa = $placa;
        $filter->GTRANC_Licencia = $licencia;
        $filter->GTRANC_Chofer = $chofer;
        $filter->EMPRP_Codigo = $transporte;
        $filter->COMPP_Codigo = $compania;
        $filter->USUA_Codigo = $this->somevar['user'];
        $filter->GTRANC_FlagEstado = $estado;

        //Datos cabecera de la guiasa.
        $filterGuiasa = new stdClass();
        $filterGuiasa->TIPOMOVP_Codigo = 6;
        $filterGuiasa->ALMAP_Codigo = $almacen;
        $filterGuiasa->DOCUP_Codigo = 15;
        $filterGuiasa->GUIASAC_Fecha = $fecha;
        $filterGuiasa->GUIASAC_Observacion = $observacion;
        $filterGuiasa->USUA_Codigo = $this->somevar['user'];
        $this->guiatrans_model->actualiza_guia($codigo_guiatrans, $filter);
        exit('{"result":"ok", "codigo":""}');
    }

    public function cargarTransferencia(){
        $userCod = $this->input->post('usuario');
        $codTrans = $this->input->post('guiaTrans');
        $estado = $this->input->post('estado');

        $buscarGuiaTransferencia = $this->guiatrans_model->obtener2($codTrans);
        $estadoTransferencia = $buscarGuiaTransferencia->GTRANC_EstadoTrans;

        // Sirve para verificar si el movimiento ya fue ejecutado
        if($estadoTransferencia == 3 || ($estado == 2 && $estadoTransferencia == 2))
        {
            $data = array(
                'movimiento' => 'Movimiento ya realizado por el DESTINO',
            );

            echo json_encode($data);

        }else {

            // Inicio del FLag
            $flagEstado = "-1";
            // Aumento de estado para confirmar su alteracion del estado de la transferencia
            $estadoTrans = $estado + 1;
            $updateGuiaInySa = FALSE;

            //-------------
            if ($estadoTrans == 0) {
                $flagEstado = "0";
                $confirmacionTransferencia = $this->guiatrans_model->actualiza_usuatrans("", $estadoTrans, $codTrans);
                if ($confirmacionTransferencia) {
                    $updateGuiaInySa = TRUE;
                }
            }
            // DE PENDIENTE A ENVIADO PARA TRANSFERENCIA ORIGEN
            // DE PEDIENTE A TRANSITO PARA TRANSFERENCIA DESTINO
            // AFECTA A LA GUIA DE SALIDA (ESTO AUN NO LO REALIZA - SE REALIZA CUANDO SE CREAR LA TRANSFERENCIA "verificar la cji_guiatrans")
            if ($estadoTrans == 1) {
                $flagEstado = "1";
                $confirmacionTransferencia = $this->guiatrans_model->actualiza_usuatrans($userCod, $estadoTrans, $codTrans);
                if ($confirmacionTransferencia) {
                    $updateGuiaInySa = $this->insertar_guiasatrans($codTrans, $userCod, 15, 'origen');
                    #$this->lib_props->sendMail(74, $codTrans); # MENU 74 = GUIA DE TRANSFERENCIA
                }
            }
            // DE TRANSITO A RECIBIDO PARA TRANSFERENCIA ORIGEN Y DESTINO
            // AFECTA A GUIA DE SALIDA Y AL KARDEX
            // Tipo de documento 15 = GUIA DE TRASFERENCIA
            if ($estadoTrans == 2) {
                $flagEstado = "2";
                $confirmacionTransferencia = $this->guiatrans_model->actualiza_receptrans($userCod, $estadoTrans, $codTrans);
                if ($confirmacionTransferencia) {
                    $updateGuiaInySa = $this->insertar_guiaintrans($codTrans, $userCod, 15, 'destino');
                }
            }
            // DE TRANSITO A DEVOLUCION PARA TRANSFERENCIA ORIGEN Y DESTINO
            // SE ESPECIFICA QUE ES UNA GUIA DEVOLUCION
            if ($estadoTrans == 3) {
                $flagEstado = "3";
                $confirmacionTransferencia = $this->guiatrans_model->actualiza_receptrans($userCod, $estadoTrans, $codTrans);
                if ($confirmacionTransferencia) {
                    $updateGuiaInySa = $this->insertar_guiaintrans($codTrans, $userCod, 15, 'origen');
                }
            }

            $data = array(
                'flagEstado' => $flagEstado,
                'usuario_guia' => $userCod,
                'guia_trans' => $codTrans,
                'estado_trans' => $estadoTrans,
                'updateGuiaInySa' => $updateGuiaInySa
            );
            echo json_encode($data);
        }
    }

    public function insertar_guiasatrans($id_guiatrans, $codUsuario, $tipoDocumento, $guiaAlmacen){
        //consulto  a la guia de transferencia
        $fecha = date("d/m/Y");
        $datos_guiatransAll = $this->guiatrans_model->obtener2($id_guiatrans);
        // Tipo de envio de almacen Origen(Devolucion) o destino(Transito)
        if ($guiaAlmacen == "destino") {
            $almacen_destino = $datos_guiatransAll->GTRANC_AlmacenDestino;
        } else if ($guiaAlmacen == "origen") {
            $almacen_destino = $datos_guiatransAll->GTRANC_AlmacenOrigen;
        } else {
            $almacen_destino = $datos_guiatransAll->GTRANC_AlmacenDestino;
        }

        //Datos cabecera de la guiain.
        $filterGuiasa = new stdClass();
        $filterGuiasa->TIPOMOVP_Codigo = 6;
        $filterGuiasa->ALMAP_Codigo = $almacen_destino;
        $filterGuiasa->DOCUP_Codigo = $tipoDocumento;
        $filterGuiasa->GUIASAC_Fecha = human_to_mysql($fecha);
        $filterGuiasa->USUA_Codigo = $codUsuario;
        $guisa_id = $this->guiasa_model->insertar($filterGuiasa);
        //detalle de la guia de transferencia
        //actualizar guiasa en guiatrans
        $datos_guiatrans = $this->guiatrans_model->actualizar_guia_salida($id_guiatrans, $guisa_id);

        if ($datos_guiatrans) {

            $datos_detallegtrans = $this->guiatransdetalle_model->listar($id_guiatrans);

            $totalDetalles = count($datos_detallegtrans);
            $contDetalles = 0;
            //datos del detalles de la guia

            if ($datos_detallegtrans != NULL) {

                foreach ($datos_detallegtrans as $indice => $valor) {
                    $producto = $valor->PROD_Codigo;
                    $unidad = $valor->UNDMED_Codigo;
                    $cantidad = $valor->GTRANDETC_Cantidad;
                    $costo = $valor->GTRANDETC_Costo;
                    $descri = $valor->GTRANDETC_Descripcion;
                    $GenInd = $valor->GTRANDETC_GenInd;
                    // Valores necesarios
                    $filterGuiasaDet = new stdClass();
                    $filterGuiasaDet->GUIASAP_Codigo = $guisa_id;
                    $filterGuiasaDet->PRODCTOP_Codigo = $producto;
                    $filterGuiasaDet->UNDMED_Codigo = $unidad;
                    $filterGuiasaDet->GUIASADETC_Cantidad = $cantidad;
                    $filterGuiasaDet->GUIASADETC_Costo = $costo;
                    $filterGuiasaDet->GUIASADETC_Descripcion = $descri;
                    $filterGuiasaDet->GUIASADETC_GenInd = $GenInd;
                    $filterGuiasaDet->ALMAP_Codigo = $almacen_destino;

                    $insertGuiasa = $this->guiasadetalle_model->insertar_2015($filterGuiasaDet,$id_guiatrans);

                    if ($insertGuiasa) {
                        $contDetalles++;
                    }
                }

                if ($contDetalles == $totalDetalles) {
                    return TRUE;
                } else {
                    return FALSE;
                }

            } else {
                return FALSE;
            }

        } else {
            return FALSE;
        }
        // sirve cuando se quiere hacer un login
        //header("location:" . base_url() . "index.php/seguridad/usuario/ventana_confirmacion_transusuario/1/activo");
    }

    public function insertar_guiaintrans($id_guiatrans, $codUsuario, $tipoDocumento, $guiaAlmacen){

        //consulto  a la guia de transferencia
        $fecha = date("d/m/Y");
        $datos_guiatrans = $this->guiatrans_model->obtener($id_guiatrans);
        $id_guiasa = $datos_guiatrans[0]->GUIASAP_Codigo;
        $almacen_origen = $datos_guiatrans[0]->GTRANC_AlmacenOrigen;
        // Tipo de envio de almacen Origen(Devolucion) o destino(Transito)
        if ($guiaAlmacen == "destino") {
            $almacen_destino = $datos_guiatrans[0]->GTRANC_AlmacenDestino;
        } else if ($guiaAlmacen == "origen") {
            $almacen_destino = $datos_guiatrans[0]->GTRANC_AlmacenOrigen;
        } else {
            $almacen_destino = $datos_guiatrans[0]->GTRANC_AlmacenDestino;
        }

        //Datos cabecera de la guiain.
        $filterGuiain = new stdClass();
        $filterGuiain->TIPOMOVP_Codigo = 6;
        $filterGuiain->ALMAP_Codigo = $almacen_destino;
        $filterGuiain->DOCUP_Codigo = $tipoDocumento;
        $filterGuiain->GUIAINC_Fecha = human_to_mysql($fecha);
        $filterGuiain->USUA_Codigo = $codUsuario;
        $guiin_id = $this->guiain_model->insertar($filterGuiain);
        //detalle de la guia de transferencia 
        //actualizar guiainp en guiatrans 
        $datos_guiatrans = $this->guiatrans_model->actualiza_guia2($id_guiatrans, $guiin_id);
        $datos_detallegtrans = $this->guiatransdetalle_model->listar($id_guiatrans);
        $totalDetalles = count($datos_detallegtrans);
        $contDetalles = 0;
        //datos del detalles de la guia

        if (is_array($datos_detallegtrans)) {

            foreach ($datos_detallegtrans as $indice => $valor) {
                $producto = $datos_detallegtrans[$indice]->PROD_Codigo;
                $unidad = $datos_detallegtrans[$indice]->UNDMED_Codigo;
                $cantidad = $datos_detallegtrans[$indice]->GTRANDETC_Cantidad;
                $costo = $datos_detallegtrans[$indice]->GTRANDETC_Costo;
                $descri = $datos_detallegtrans[$indice]->GTRANDETC_Descripcion;
                $detflag = $datos_detallegtrans[$indice]->GTRANDETC_GenInd;
                /* Insertar detalle de guia de salida o ingreso */

                $filterGuiainDet = new stdClass();
                $filterGuiainDet->GUIAINP_Codigo = $guiin_id;
                $filterGuiainDet->PRODCTOP_Codigo = $producto;
                $filterGuiainDet->UNDMED_Codigo = $unidad;
                $filterGuiainDet->GUIAINDETC_Cantidad = $cantidad;
                $filterGuiainDet->GUIAINDETC_Costo = $costo;
                $filterGuiainDet->GUIIAINDETC_GenInd = $detflag;
                $filterGuiainDet->ALMAP_Codigo=$almacen_destino;

                $insertGuiain = $this->guiaindetalle_model->insertar_2015($filterGuiainDet, 'TRANSFERENCIA',$id_guiatrans,$almacen_origen);
                if ($insertGuiain) {
                    $contDetalles++;
                }
            }
        }

        if ($contDetalles == $totalDetalles) {
            return TRUE;
        } else {
            return FALSE;
        }
        // sirve cuando se quiere hacer un login
        //header("location:" . base_url() . "index.php/seguridad/usuario/ventana_confirmacion_transusuario/1/activo");
    }

    public function guiatrans_ver_pdf($codigo, $format = "print", $img = 0){
        switch ($format) {
            case "print":
                $this->guiatrans_print($codigo, $img);
                break;
            case "pdf":
                $this->guiatrans_pdf($codigo, $img);
                break;
            default:
                $this->guiatrans_pdf($codigo, $img);
                break;
        }
    }

    public function guiatrans_pdf($codigo, $flagPdf = 0, $enviarcorreo = false){
        $this->lib_props->guiatrans_pdf($codigo, $flagPdf, $enviarcorreo);
        return NULL;
    }

    public function anular_trasnferencia(){
    	 $compania = $this->somevar['compania'];
    	 $codigo = $this->input->post('codigo');
    	 $response = $this->guiatrans_model->eliminar($codigo);
    	 return $response;
    }

}#EOF

?>