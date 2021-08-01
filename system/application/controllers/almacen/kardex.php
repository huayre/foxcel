<?php

class Kardex extends controller
{

  public function __construct(){
    parent::Controller();
    $this->load->model('almacen/kardex_model');
    $this->load->model('almacen/producto_model');
    $this->load->model('almacen/almacenproducto_model');
    $this->load->model('almacen/almacen_model');
    $this->load->model('almacen/guiain_model');
    $this->load->model('almacen/guiasa_model');
    $this->load->model('almacen/unidadmedida_model');
    $this->load->model('maestros/compania_model');
    $this->load->model('compras/proveedor_model');
    $this->load->model('ventas/cliente_model');
    $this->load->model('seguridad/usuario_model');
    $this->load->helper('form', 'url');
    $this->load->library('pagination');
    $this->load->library('form_validation');
    $this->somevar['compania'] = $this->session->userdata('compania');
  }

  public function listar(){
    unset($_SESSION['serieReal']);
    unset($_SESSION['serieRealBD']);
    $this->load->library('layout', 'layout');
    $data['compania'] =$this->somevar['compania'];
    $data['titulo_tabla'] = "KARDEX DE PRODUCTOS";
    $data['form_open'] = form_open($url, array("name" => "frmkardex", "id" => "frmkardex"));
    $data['cboAlmacen'] = form_dropdown("almacen", $this->almacen_model->seleccionar($this->somevar['compania']), $almacen_id, " class='form-control w-porc-90' id='almacen'"); // EN 
    $atributos = array('width' => 700, 'height' => 450, 'scrollbars' => 'yes', 'status' => 'yes', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0');
    $data['oculto'] = form_hidden(array('base_url' => base_url()));
    $data['form_close'] = form_close();
    $this->layout->view('almacen/kardex_index', $data);
  }

  function obtener_nombre_numdoc($tipo, $codigo){

    $nombre = '';
    $numdoc = '';
    if ($tipo == 'CLIENTE') {
      $datos_cliente = $this->cliente_model->obtener($codigo);
      if ($datos_cliente) {
        $nombre = $datos_cliente->nombre;
        $numdoc = $datos_cliente->ruc;
      }
    } else {
      $datos_proveedor = $this->proveedor_model->obtener($codigo);
      if ($datos_proveedor) {
        $nombre = $datos_proveedor->nombre;
        $numdoc = $datos_proveedor->ruc;
      }
    }
    return array('numdoc' => $numdoc, 'nombre' => $nombre);
  }

  function VerificarMovimiento($codKardex){
    $filter = new stdClass();
    $filter->KARDC_FlagValida = '1';
    $filter->USUA_Codigo = $_SESSION['user'];
    $update = $this->kardex_model->verificarMovimiento($codKardex, $filter);

    if($update == true){
      $usuario = $this->usuario_model->obtener2($_SESSION['user']);
      $detaUsuario = $usuario[0]->PERSC_Nombre." ".$usuario[0]->PERSC_ApellidoPaterno." ".$usuario[0]->PERSC_ApellidoMaterno;
      echo  $detaUsuario;
    }else{
      echo 'error';
    }
  }

  public function datatable_kardex($value=''){

    $filter = new stdClass();
    $filter->start = $this->input->post("start");
    $filter->length = $this->input->post("length");
    $filter->search = $this->input->post("search")["value"];
    $filter->producto = $this->input->post('producto');
    $filter->almacen = $this->input->post('almacen');
    $filter->descripcion = $this->input->post('search_descripcion');
    $filter->fechai = $this->input->post('fechai');
    $filter->fechaf = $this->input->post('fechaf');
    $total_reg = 0;
    $kardex = $this->kardex_model->obtiene_movimeintos_kardex($filter);
    if ($kardex) {
      
      $total_reg = count($kardex);
      
      foreach ($kardex as $key => $value) {
        
        $almacen       = $value->almacen;
        $nom_almacen   = $value->nombre_almacen;
        $fecha         = mysql_to_human($value->fecha);
        $num_doc       = $value->serie.'-'.$value->numero;
        $precio_conigv = $value->pu_conIgv;
        $estado        = $value->estado;
        $fontin        = "<font color='blue'>";
        $font_alert    = "<font color='red'>";
        $fontaout      = "<font color='green'>";
        $fontend       = "</fon>";
        
        if($value->tipo_oper=='V'){
          $cliente = $value->razon_social_cliente;
          $tipo_mov=$fontaout."SALIDA";
        }elseif ($value->tipo_oper=='C') {
          $cliente = $value->razon_social_proveedor;
          $tipo_mov=$fontin."ENTRADA";
        }else{
          if($value->tipo_oper=="I"){
            $num_doc="INVENTARIO";
            $tipo_mov = $fontin."ENTRADA";
            $cliente  = "ENTRADA POR MOVIMEINTO DE INVENTARIO"; 
          }
          if($value->tipo_oper=="T"){
            if($value->almacen == $filter->almacen){
              $tipo_mov = $fontaout."SALIDA";
              $cliente  = "TRASLADO DE ALMACEN"; 
            }else{
              $cliente  = "TRASLADO DE ALMACEN"; 
              if ($estado==1) {
                $tipo_mov = $font_alert."EN TRANSITO";
              }else{
                $tipo_mov = $fontin."ENTRADA";
              }
            }
          }
          
        }

        $cantidad = $value->cantidad;
        $subtotal = $value->subtotal;
        $total    = $value->total;


        $lista[] = array(
          0 => $fecha,   
          1 => $num_doc, 
          2 => $cliente, 
          3 => $tipo_mov,
          4 => $cantidad,
          5 => $precio_conigv,
          6 => $subtotal,
          7 => $total,   
          8 => $nom_almacen  

        );

      }
    }else{
      $lista=array();
    }

    $json = array(
      "draw"            => intval( $this->input->post('draw') ),
      "recordsTotal"    => $total_reg,
      "recordsFiltered" => $total_reg,
      "data"            => $lista
    );

    echo json_encode($json);
  }


  ###################################################################################
  #
  # FUNCIONES OBSOLETAS PENDIENTE DE BORRAR
  # verificar antes de borrar
  ###################################################################################
  public function listarFIFO(){

    $this->load->library('layout', 'layout');
    $compania = $this->input->post("compania") != '' ? $this->input->post("compania") : $this->somevar['compania'];
    $data['compania'] = $compania;//GCBQ AGREGADO
    $producto_id = $this->input->post("producto");
    $almacen_id = $this->input->post("almacen");
    $fechai = $this->input->post("fechai");
    $fechaf = $this->input->post("fechaf");
    $interno_producto = $this->input->post("codproducto");
    $nombre_producto = $this->input->post("nombre_producto");
    $tipo_valorizacion = $this->input->post("tipo_valorizacion");
    $filter = new stdClass();
    $filter->compania = $compania;
    $filter->producto = $interno_producto;
    //$filter->fechai = $fechai; ocultado por que el calculo se debe dar desde el inicio-solo se usa para ocultar.
    $filter->fechaf = $fechaf;
    $listado = $this->kardex_model->listarFIFO($filter);
    $item = 1;
    $lista = array();
    $cantidadGuar_comp = array();//agregado gcbq
    $cantidad_ant = 0;
    $costo_ant = 0;
    ////stv
    $doc_comp = '';
    ///////
    //gcbq
    $urlpdf = '';
    $docum_tipo = '';
    $fecha_dedocumento = '';
    //

    if (count($listado) > 0) {

      foreach ($listado as $indice => $valor) {

        $dcto = $valor->DOCUP_Codigo;

        $motivo_mov = $valor->TIPOMOVP_Codigo;

        $dcto_codigo = $valor->KARDC_CodigoDoc;

        $tipo_ingreso = 'E';

        if ($valor->KARDC_TipoIngreso == 2)
          $tipo_ingreso = 'S';

        $tipo_doc = $valor->DOCUC_Inicial;

        $fecha = explode(" ", $valor->KARD_Fecha);

        $cantidad = $valor->KARDC_Cantidad2;

        $costo = $valor->KARDC_Costo;

        if (isset($producto_id) && $producto_id != "") {

          $datos_unidad = $this->producto_model->obtener_producto_unidad($producto_id);

          $unidad_med = $datos_unidad[0]->UNDMED_Codigo;

          $datos_unidad2 = $this->unidadmedida_model->obtener($unidad_med);

          $nombre_und = $datos_unidad2[0]->UNDMED_Simbolo;
        }

            if ($dcto == 5) {//Ingreso
              $docum_tipo = '';
              $datos_dcto = $this->guiain_model->obtener($dcto_codigo);
              $docum_tipo = $datos_dcto[0]->DOCUP_Codigo;
              $urlpdf = '';
              $doc_comp = '';
              $datos_comprobante = $this->kardex_model->obtener_comprobante_saling($dcto_codigo, 'I', $docum_tipo);
              if (count($datos_comprobante) > 0) {

                if ($docum_tipo != 10) {
                  $doc_comp = $datos_comprobante[0]->CPC_Numero;
                  $doc_comp = $datos_comprobante[0]->CPC_TipoDocumento . ' ' . $doc_comp;
                        //gcbq agregado Para que salga en soles
                  $monedCodi = $datos_comprobante[0]->MONED_Codigo;
                  if ($monedCodi == 2) {
                    $Tipo_cambio = $datos_comprobante[0]->CPC_TDC;
                    $costo = $costo * $Tipo_cambio;
                  }
                        //agregar pdf
                  $urlpdf = $datos_comprobante[0]->CPC_TipoOperacion . '/' . $datos_comprobante[0]->CPP_Codigo . '/' . $datos_comprobante[0]->CPC_TipoDocumento . '/O';
                        //para guias de remision
                } else {
                  $doc_comp = 'G.R. :' . $datos_comprobante[0]->GUIAREMC_Serie . '' . $datos_comprobante[0]->GUIAREMC_Numero;
                        //gcbq agregado Para que salga  en soles
                  $monedCodi = $datos_comprobante[0]->MONED_Codigo;
                  $fecha_ingreso_gr = $datos_comprobante[0]->GUIAREMC_Fecha;
                  if ($monedCodi == 2) {

                    $dato_tipo_cambio = $this->kardex_model->obtener_tipo_cambio($fecha_ingreso_gr);
                    $Tipo_cambio = $dato_tipo_cambio[0]->TIPCAMC_FactorConversion;
                    $costo = $costo * $Tipo_cambio;
                  }
                        //agregar pdf
                  $urlpdf = $datos_comprobante[0]->GUIAREMP_Codigo . '/' . $datos_comprobante[0]->GUIAREMC_TipoOperacion;
                        //
                }

              }

                /////
              $numero = $datos_dcto[0]->GUIAINC_Numero;
              $almacen = $datos_dcto[0]->ALMAP_Codigo;
                //


              if ($datos_dcto[0]->TIPOMOVP_Codigo == 6) {
                $nombre = 'INGRESO POR TRASLADO INTERNO';
                $numdoc = '';

                    ////stv
                $doc_comp = '';
                $datos_guiatrans = $this->kardex_model->obtener_guiatrans_saling($dcto_codigo, 'I');
                if (count($datos_guiatrans) > 0) {
                  $doc_comp = $datos_guiatrans[0]->GTRANC_Numero;
                  $doc_comp = 'GT ' . $doc_comp;
                }
                    /////
              } else {

                $datos_proveedor = $this->proveedor_model->obtener($datos_dcto[0]->PROVP_Codigo);

                $nombre = isset($datos_proveedor->nombre) ? $datos_proveedor->nombre : '';

                $numdoc = isset($datos_proveedor->ruc) ? $datos_proveedor->ruc : '';
              }
            } elseif ($dcto == 6 || $dcto == 7) {//Salida
              $docum_tipo = '';
              $datos_dcto = $this->guiasa_model->obtener($dcto_codigo);
              $docum_tipo = $datos_dcto->DOCUP_Codigo;
                ////stv
              $doc_comp = '';
                //gcbq
              $urlpdf = '';
                //
              $datos_comprobante = $this->kardex_model->obtener_comprobante_saling($dcto_codigo, 'S', $docum_tipo);
              if (count($datos_comprobante) > 0) {

                if ($docum_tipo != 10) {
                  $doc_comp = $datos_comprobante[0]->CPC_Numero;
                  $doc_comp = $datos_comprobante[0]->CPC_TipoDocumento . ' ' . $doc_comp;
                  $fecha_dedocumento = '';
                  $fecha_dedocumento = $datos_comprobante[0]->CPC_Fecha . ' ' . $fecha_dedocumento;
                        //gcbq agregado Para que salga en soles
                  $monedCodi = $datos_comprobante[0]->MONED_Codigo;
                  if ($monedCodi == 2) {
                    $Tipo_cambio = $datos_comprobante[0]->CPC_TDC;
                    $costo = $costo * $Tipo_cambio;
                  }
                        //agregar pdf
                  $urlpdf = $datos_comprobante[0]->CPC_TipoOperacion . '/' . $datos_comprobante[0]->CPP_Codigo . '/' . $datos_comprobante[0]->CPC_TipoDocumento . '/O';
                        //para guias de remision
                } else {
                  $doc_comp = 'G.R. :' . $datos_comprobante[0]->GUIAREMC_Serie . '' . $datos_comprobante[0]->GUIAREMC_Numero;
                        //gcbq agregado Para que salga  en soles
                  $monedCodi = $datos_comprobante[0]->MONED_Codigo;
                  $fecha_ingreso_gr = $datos_comprobante[0]->GUIAREMC_Fecha;
                  if ($monedCodi == 2) {

                    $dato_tipo_cambio = $this->kardex_model->obtener_tipo_cambio($fecha_ingreso_gr);
                    $Tipo_cambio = $dato_tipo_cambio[0]->TIPCAMC_FactorConversion;
                    $costo = $costo * $Tipo_cambio;
                  }
                        //agregar pdf
                  $urlpdf = $datos_comprobante[0]->GUIAREMP_Codigo . '/' . $datos_comprobante[0]->GUIAREMC_TipoOperacion;
                        //
                }
              }
                /////


              $numero = $datos_dcto->GUIASAC_Numero;

              $almacen = $datos_dcto->ALMAP_Codigo;

              if ($datos_dcto->TIPOMOVP_Codigo == 6) {

                $nombre = 'SALIDA POR TRASLADO INTERNO';

                $numdoc = '';


                    ////stv
                $doc_comp = '';
                $datos_guiatrans = $this->kardex_model->obtener_guiatrans_saling($dcto_codigo, 'S');
                if (count($datos_guiatrans) > 0) {
                  $doc_comp = $datos_guiatrans[0]->GTRANC_Numero;
                  $doc_comp = 'GT ' . $doc_comp;
                }
                    /////


              } else {

                $datos_cliente = $this->cliente_model->obtener($datos_dcto->CLIP_Codigo);

                $nombre = isset($datos_cliente->nombre) ? $datos_cliente->nombre : '';

                $numdoc = isset($datos_cliente->ruc) ? $datos_cliente->ruc : '';
              }
            } elseif ($dcto == 4) {

              $this->load->model('almacen/inventario_model');

              $filter->cod_inventario = $dcto_codigo;

              $datos_dcto = $this->inventario_model->buscar_inventario($filter);
              if (isset($datos_dcto)) {

                $numero = $datos_dcto[0]->INVE_Numero;

                $almacen = $datos_dcto[0]->ALMAP_Codigo;

                $nombre = 'INGRESO DE INVENTARIO';

                $s_ = str_pad($datos_dcto[0]->INVE_Serie, 3, "0", STR_PAD_LEFT);
                $n_ = str_pad($datos_dcto[0]->INVE_Numero, 6, "0", STR_PAD_LEFT);

                $numdoc = $s_ . $n_;
              }
            }
            if (!isset($almacen)) {
              $almacen = "";
            }
            if ($almacen != "") {

              $datos_almacen = $this->almacen_model->obtener($almacen);

              $compania_almacen = $datos_almacen[0]->COMPP_Codigo;

              $nombre_almacen = $datos_almacen[0]->ALMAC_Descripcion;


              $filtro = false;


              if ($compania_almacen == $compania) {

                if ($almacen_id == "")
                  $filtro = true;

                else
                  $filtro = $almacen == $almacen_id ? true : false;
              }


              if ($tipo_ingreso == "E" && $filtro) {

                $cantidadGuar_comp[] = array($cantidad, $costo);

                $cantidadi = $cantidad;

                $costoi = $costo;

                $cantidads = "";

                $costos = "";

                $cantidadt = $cantidad_ant + $cantidadi;

                if ($cantidad_ant == 0) {

                  $costot = $costoi;
                } else {


                  $costot = ($cantidad_ant * $costo_ant + $cantidadi * $costoi) / $cantidadt;

                }
              } elseif ($tipo_ingreso == "S" && $filtro) {

                $cantidadi = '';
                $costoi = 0;
                    ///gcbq agregado
                $cantidad_saldo = 0;
                $mul = 0;
                $divi = 0;
                $mult = 0;
                $divit = 0;
                    //
                $cantidads = $cantidad;
                $cantidad_Gene_restar = 0;
                    ///  asi estaba   $costos = $costo_ant;

                $costos = $costo;

                    ///gcbq
                foreach ($cantidadGuar_comp as &$valor) {
                  if ($cantidad_saldo == 0) {
                    $cantidad_Gene_restar += $valor[0];

                    if ($cantidad_Gene_restar > $cantidad) {
                      $cantidad_saldo = $cantidad_Gene_restar - $cantidad;
                      $mul = $cantidad_saldo;
                      $divi = $cantidad_saldo;
                      $valor[0] = $cantidad_saldo;
                    } else {

                      $valor[0] = 0;

                    }

                  } else {
                    $mul = $valor[0];
                    $divi = $valor[0];
                  }

                  $mult += $mul * $valor[1];
                  $divit += $divi;

                }
                unset($valor);
                    ///

                $cantidadt = $cantidad_ant - $cantidads;


                if ($cantidadt == 0) {

                  $costot = 0;
                } else {

                        // asi estaba    $costot = $costo_ant;

                        // $costot = ($cantidad_ant * $costo_ant - $cantidads * $costos) / $cantidadt;

                  if ($divit != 0)
                    $costot = $mult / $divit;
                  else
                    $costot = 0;


                }
              }

                ///gcbq


                //////
              if ($filtro) {

                $cantidad_ant = $cantidadt;

                $costo_ant = $costot;

                if ($fechai <= $fecha[0]) {
                        ///stv estaba   $numdoc
                  $lista[] = array($item++, $nombre_almacen, mysql_to_human($fecha[0]), $doc_comp, $nombre, $tipo_ingreso, $cantidadi, $costoi, $cantidadi * $costoi, $cantidads, $costos, $cantidads * $costos, $cantidadt, $costot, $cantidadt * $costot, $urlpdf, $fecha_dedocumento);
                }
              }
            }
          }
        }

    if ($tipo_valorizacion == "0") {//FIFO
      $url = base_url() . 'index.php/almacen/kardex/listarFIFO';
    } elseif ($tipo_valorizacion == "1") {//LIfO
      $url = base_url() . 'index.php/almacen/kardex/listarLIFO';
    } elseif ($tipo_valorizacion == "") {//Costo promedio
      $url = base_url() . 'index.php/almacen/kardex/listar';
    }

    $datos_compania = $this->compania_model->obtener($this->somevar['compania']);

    $tipo_valorizacion_conf = $datos_compania[0]->COMPC_TipoValorizacion;

    if ($producto_id == '')
      $data['habilitado'] = 'Y';

    else
      $data['habilitado'] = 'N';

    $data['registros'] = $item - 1;

    $data['lista'] = $lista;


    $data['titulo_tabla'] = "KARDEX DE PRODUCTOS";

    $data['form_open'] = form_open($url, array("name" => "frmkardex", "id" => "frmkardex"));

    $data['cboAlmacen'] = form_dropdown("almacen", $this->almacen_model->seleccionar($this->somevar['compania']), $almacen_id, " class='comboMedio' id='almacen'"); // EN SELECCIONAR DECIA TODOS, NO ENTENDI EL PORQUE
    //$data['cboProducto']     = form_dropdown("producto",$this->producto_model->seleccionar(),$producto_id," class='comboExtraGrande' id='producto'");

    $atributos = array('width' => 700, 'height' => 450, 'scrollbars' => 'yes', 'status' => 'yes', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0');

    $contenido = "<img height='16' width='16' class='kardex_prod' src='" . base_url() . "images/ver.png' title='Buscar Cliente' border='0'>";

    //$data['cboProducto']     = anchor_popup('almacen/producto/ventana_busqueda_producto',$contenido,$atributos,'linkVerProducto');

    $data['cboProducto'] = '<a id="linkVerProducto" href="' . base_url() . 'index.php/almacen/producto/ventana_busqueda_producto_kardex/">' . $contenido . '</a>';
    $data['interno_producto'] = $interno_producto;
    $data['nombre_producto'] = $nombre_producto;
    $data['producto_id'] = $producto_id;

    $data['tipo_val1'] = form_radio("tipo_valorizacion", $tipo_valorizacion_conf, (($tipo_valorizacion == $tipo_valorizacion_conf) ? true : false), "id='tipo_valorizacion'") . (($tipo_valorizacion_conf == "0") ? "FIFO" : "LIFO");

    $data['tipo_val2'] = form_radio("tipo_valorizacion", "", ($tipo_valorizacion == "") ? true : false, "id='tipo_valorizacion'") . "Costo Promedio";

    $data['fechai'] = form_input(array("name" => "fechai", "id" => "fechai", "class" => "cajaPequena", "readonly" => "readonly", "maxlength" => "10", "value" => $fechai));

    $data['fechaf'] = form_input(array("name" => "fechaf", "id" => "fechaf", "class" => "cajaPequena", "readonly" => "readonly", "maxlength" => "10", "value" => $fechaf));

    $data['oculto'] = form_hidden(array('base_url' => base_url()));

    $data['form_close'] = form_close();

    $this->layout->view('almacen/kardex_index', $data);

    /*echo $compania . "<br>";
    echo $producto_id . "<br>";
    echo $almacen_id . "<br>";
    echo $fechai . "<br>";
    echo $fechaf . "<br>";
    echo $interno_producto . "<br>";
    echo $nombre_producto . "<br>";
    echo $tipo_valorizacion . "<br>";*/
  }

  public function listarLIFO(){

    $this->load->library('layout', 'layout');

    $producto_id = $this->input->post("producto");

    $almacen_id = $this->input->post("almacen");

    $fechai = $this->input->post("fechai");

    $fechaf = $this->input->post("fechaf");

    $tipo_valorizacion = $this->input->post("tipo_valorizacion");

    $filter = new stdClass();

    $filter->producto = $producto_id;

    $filter->fechai = $fechai;

    $filter->fechaf = $fechaf;

    $listado = $this->kardex_model->listarLIFO($filter);

    $item = 1;

    $lista = array();

    $cantidad_ant = 0;

    $costo_ant = 0;

    $lote_ant = 0;

    $tipo_doc_ant = 0;

    $numero_ant = 0;

    $cantidadt_array = array();

    $costot_array = array();

    $tmp = array();

    $zz = 1;

    if (count($listado) > 0) {

      foreach ($listado as $indice => $valor) {

        $dcto = $valor->DOCUP_Codigo;

        $motivo_mov = $valor->TIPOMOVP_Codigo;

        $dcto_codigo = $valor->KARDC_CodigoDoc;

        $tipo_ingreso = $valor->KARDC_TipoIngreso == 1 ? "E" : "S";

        $tipo_doc = $valor->DOCUC_Inicial;

        $lote_id = $valor->LOTP_Codigo;

        $fecha = explode(" ", $valor->KARD_Fecha);

        $cantidad = $valor->KARDC_Cantidad;

        $costo = $valor->KARDC_Costo;

        $datos_unidad = $this->producto_model->obtener_producto_unidad($producto_id);

        $unidad_med = $datos_unidad[0]->UNDMED_Codigo;

        $datos_unidad2 = $this->unidadmedida_model->obtener($unidad_med);

        $nombre_und = $datos_unidad2[0]->UNDMED_Simbolo;

            if ($dcto == 5) {//Ingreso
              $datos_dcto = $this->guiain_model->obtener($dcto_codigo);

              $numero = $datos_dcto[0]->GUIAINC_Numero;

              $almacen = $datos_dcto[0]->ALMAP_Codigo;
            } elseif ($dcto == 6 || $dcto == 7) {//Salida
              $datos_dcto = $this->guiasa_model->obtener($dcto_codigo);

              $numero = $datos_dcto->GUIASAC_Numero;

              $almacen = $datos_dcto->ALMAP_Codigo;
            }

            $filtro2 = false;

            if ($almacen_id == "")
              $filtro = true;

            else
              $filtro = $almacen == $almacen_id ? true : false;

            $datos_almacen = $this->almacen_model->obtener($almacen);

            $nombre_almacen = $datos_almacen[0]->ALMAC_Descripcion;

            if ($tipo_ingreso == "E" && $filtro) {

              if (count($cantidadt_array) > 0) {

                $filtro2 = true;

                $filtro3 = true;

                foreach ($cantidadt_array as $indice3 => $value3) {

                  $cantidadi = $cantidad;

                  $costoi = $costo;

                  $resultadoi = $cantidadi * $costoi;

                  $cantidads = "";

                  $costos = "";

                  $resultados = "";

                  $cantidadt = $cantidadt_array[$indice3];

                  $costot = $costot_array[$indice3];

                  $resultadot = $cantidadt * $costot;

                  if (!$filtro3) {

                    $nombre_almacen = "";

                    $fecha[0] = "";

                    $tipo_doc = "";

                    $numero = "";

                    $tipo_ingreso = "";

                    $cantidadi = "";

                    $costoi = "";

                    $resultadoi = "";

                    $correlativo = "";
                  } else {

                    $correlativo = $item++;
                  }

                  $lista[] = array($correlativo, $nombre_almacen, $fecha[0], $tipo_doc, $numero, $tipo_ingreso, $cantidadi, $costoi, $resultadoi, $cantidads, $costos, $resultados, $cantidadt, $costot, $resultadot);

                  $filtro3 = false;
                }
              }

              $cantidadi = $cantidad;

              $costoi = $costo;

              $resultadoi = $cantidadi * $costoi;

              $cantidads = "";

              $costos = "";

              $resultados = "";

              $cantidadt = $cantidadi;

              $costot = $costoi;

              $resultadot = $cantidadt * $costot;

              $cantidadt_array[$lote_id] = $cantidadi;

              $costot_array[$lote_id] = $costoi;

              $cantidad_ant = $cantidadt;

              $costo_ant = $costot;

              $lote_ant = $lote_id;

              if ($filtro2) {

                $nombre_almacen = "";

                $fecha[0] = "";

                $tipo_doc = "";

                $numero = "";

                $tipo_ingreso = "";

                $cantidadi = "";

                $costoi = "";

                $resultadoi = "";

                $correlativo = "";
              } else {

                $correlativo = $item++;
              }

              $lista[] = array($correlativo, $nombre_almacen, $fecha[0], $tipo_doc, $numero, $tipo_ingreso, $cantidadi, $costoi, $resultadoi, $cantidads, $costos, $resultados, $cantidadt, $costot, $resultadot);
            } elseif ($tipo_ingreso == "S" && $filtro) {

              $cantidadi = "";

              $costoi = "";

              $jj = 1;

              $ww = 1;

              $filtro4 = true;

              $qcantidad = count($cantidadt_array);

              foreach ($cantidadt_array as $indice2 => $value2) {

                $resultados = $cantidad * $costo;

                if ($indice2 == $lote_id) {

                  $cantidad_ant = $cantidadt_array[$indice2];

                  $costo_ant = $costot_array[$indice2];

                  $cantidadt = $cantidad_ant - $cantidad;

                  $costot = $costo;

                  $resultadot = $cantidadt * $costot;

                  $cantidadt_array[$lote_id] = $cantidadt;

                  $costot_array[$lote_id] = $costot;

                  if ($cantidadt == 0) {

                    $costot = "";

                    $resultadot = "";

                    unset($cantidadt_array[$lote_id]);

                    unset($costot_array[$lote_id]);
                  }
                } else {

                  $cantidadt = $cantidadt_array[$indice2];

                  $costot = $costot_array[$indice2];

                  $resultadot = $cantidadt * $costot;
                }

                $cantidad_ant = $cantidadt;

                $costo_ant = $costot;

                $lote_ant = $lote_id;

                /* Cantidad de registros para un producto en un documento */

                $qcant = count($this->kardex_model->obtener_registros_x_dcto($producto_id, $dcto, $dcto_codigo));

                if ($qcant > 1) {

                  /* Guardo lo stocks por lote*** */

                  if ($filtro4) {

                    $cantidadt_array2 = $cantidadt_array;

                    $costot_array2 = $costot_array;
                  }

                  $cantidad_nueva = $cantidadt_array2;

                  $costo_nuevo = $costot_array2;

                  foreach ($cantidadt_array2 as $ind => $val) {

                    if ($ind == $lote_id) {

                      $cantidad_antx = $cantidadt_array2[$ind];

                      $costo_antx = $costot_array2[$ind];

                      $cantidadtx = $cantidad_antx - $cantidad;

                      $costotx = $costo;

                      $resultadotx = $cantidadt * $costot;

                      if ($cantidadtx == 0) {

                        $costotx = "";

                        $resultadotx = "";
                      }
                    } else {

                      $cantidadtx = $cantidadt_array[$ind];

                      $costotx = $costot_array[$ind];

                      $resultadotx = $cantidadt * $costot;
                    }

                    $cantidadt_arrayx[$ind] = $cantidadtx;

                    $costot_arrayx[$ind] = $costotx;
                  }

                  $cantidadt_array2 = $cantidadt_arrayx;

                  $costot_array2 = $costot_arrayx;

                  /*                             * ************************** */

                  if ($ww == $qcantidad) {

                    $tmp[] = array($cantidad, $costo, $resultados, $cantidadt, $costot, $resultadot);

                    if ($zz == $qcant) {

                      $correlativo = $item++;

                      for ($e = 0; $e < $qcant; $e++) {

                        $cantt = isset($cantidad_nueva[$e + 1]) ? $cantidad_nueva[$e + 1] : 0;

                        $costt = isset($costo_nuevo[$e + 1]) ? $costo_nuevo[$e + 1] : 0;

                        $cants = isset($tmp[$e]['0']) ? $tmp[$e]['0'] : "";

                        $costs = isset($tmp[$e]['1']) ? $tmp[$e]['1'] : "";

                        $results = isset($tmp[$e]['2']) ? $tmp[$e]['2'] : "";

                        $resultt = $cantidad_nueva == 0 ? "" : $cantt * $costt;

                        $lista[] = array($correlativo, $nombre_almacen, $fecha[0], $tipo_doc, $numero, $tipo_ingreso, $cantidadi, $costoi, $resultadoi, $cants, $costs, $results, $cantt, $costt, $resultt);

                        $correlativo = "";

                        $nombre_almacen = "";

                        $fecha[0] = "";

                        $tipo_doc = "";

                        $numero = "";

                        $tipo_ingreso = "";
                      }

                      $tmp = array();

                      $cantidad_nueva = array();

                      $costo_nuevo = array();

                      $zz = 1;
                    }

                    $zz++;

                            //break;

                    $cantidadt_arrayx = array();

                    $costot_arrayx = array();
                  }
                } else {

                  if (!$filtro4) {

                    $nombre_almacen = "";

                    $fecha[0] = "";

                    $tipo_doc = "";

                    $numero = "";

                    $tipo_ingreso = "";

                    $cantidadi = "";

                    $costoi = "";

                    $resultadoi = "";

                    $correlativo = "";

                    $cantidads = "";

                    $costos = "";

                    $resultados = "";
                  } else {

                    $cantidads = $cantidad;

                    $costos = $costo;

                    $resultados = $cantidads * $costos;

                    $correlativo = $item++;
                  }

                  if ($numero_ant == $numero) {

                    $nombre_almacen = "";

                    $fecha[0] = "";

                    $tipo_doc2 = "";

                    $numero2 = "";

                    $tipo_ingreso = "";

                    $cantidadi = "";

                    $costoi = "";

                    $resultadoi = "";

                    $correlativo = "";
                  } else {

                    $tipo_doc2 = $tipo_doc;

                    $numero2 = $numero;
                  }

                  $lista[] = array($correlativo, $nombre_almacen, $fecha[0], $tipo_doc2, $numero2, $tipo_ingreso, $cantidadi, $costoi, $resultadoi, $cantidads, $costos, $resultados, $cantidadt, $costot, $resultadot);
                }

                $filtro4 = false;

                $ww++;
              }

              $jj++;
            }

            $tipo_doc_ant = $tipo_doc;

            $numero_ant = $numero;
          }
        }

    if ($tipo_valorizacion == "0") {//FIFO
      $url = base_url() . 'index.php/almacen/kardex/listarFIFO';
    } elseif ($tipo_valorizacion == "1") {//LIfO
      $url = base_url() . 'index.php/almacen/kardex/listarLIFO';
    } elseif ($tipo_valorizacion == "") {//Costo promedio
      $url = base_url() . 'index.php/almacen/kardex/listar';
    }

    $datos_compania = $this->compania_model->obtener($this->somevar['compania']);

    $tipo_valorizacion_conf = $datos_compania[0]->COMPC_TipoValorizacion;

    $data['registros'] = $item - 1;

    $data['lista'] = $lista;

    $data['titulo_tabla'] = "KARDEX DE PRODUCTOS";

    $data['form_open'] = form_open($url, array("name" => "frmkardex", "id" => "frmkardex"));

    $data['cboAlmacen'] = form_dropdown("almacen", $this->almacen_model->seleccionar("TODOS"), $almacen_id, " class='comboMedio' id='almacen'");

    $data['cboProducto'] = form_dropdown("producto", $this->producto_model->seleccionar(), $producto_id, " class='comboExtraGrande' id='producto'");

    $data['tipo_val1'] = form_radio("tipo_valorizacion", $tipo_valorizacion_conf, ($tipo_valorizacion == $tipo_valorizacion_conf) ? true : false, "id='tipo_valorizacion'") . (($tipo_valorizacion_conf == "0") ? "FIFO" : "LIFO");

    $data['tipo_val2'] = form_radio("tipo_valorizacion", "", ($tipo_valorizacion == "") ? true : false, "id='tipo_valorizacion'") . "Costo Promedio";

    $data['fechai'] = form_input(array("name" => "fechai", "id" => "fechai", "class" => "cajaPequena", "readonly" => "readonly", "maxlength" => "10", "value" => $fechai));

    $data['fechaf'] = form_input(array("name" => "fechaf", "id" => "fechaf", "class" => "cajaPequena", "readonly" => "readonly", "maxlength" => "10", "value" => $fechaf));

    $data['oculto'] = form_hidden(array('base_url' => base_url()));

    $data['form_close'] = form_close();

    $this->layout->view('almacen/kardex_index', $data);
  }

}

?>