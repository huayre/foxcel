<?php

class kardex_Model extends Model {

    protected $_name = "cji_kardex";

    public function __construct() {

        parent::__construct();

        $this->load->database();

        $this->somevar['compania'] = $this->session->userdata('compania');
    }

    public function listar_by_codigo_documento($cod_doc, $tipo, $filter) {


        $this->db->join('cji_producto', 'cji_producto.PROD_Codigo=  cji_kardex.PROD_Codigo');

        $this->db->join('cji_documento', 'cji_documento.DOCUP_Codigo=cji_kardex.DOCUP_Codigo');
        if (isset($filter->producto) && $filter->producto != "")
            $this->db->where('cji_producto.PROD_CodigoUsuario', $filter->producto);
        

        /**gcbq agregamos por almacen***/
        $this->db->join('cji_inventario', 'cji_inventario.INVE_Codigo=cji_kardex.KARDC_CodigoDoc');
        if (isset($filter->codigoAlmacen) && $filter->codigoAlmacen != "")
            $this->db->where('cji_inventario.ALMAP_Codigo', $filter->codigoAlmacen);
        
        /*
          if (isset($filter->fechai) && $filter->fechai != "")
          $this->db->where('DATE_FORMAT(cji_kardex.KARD_Fecha,"%Y-%m-%d")  >=', $filter->fechai);

          if (isset($filter->fechaf) && $filter->fechaf != "")
          $this->db->where('DATE_FORMAT(cji_kardex.KARD_Fecha,"%Y-%m-%d") <', $filter->fechaf);
         */
        #$conpania=$this->somevar['compania'];
        #$this->db->where('cji_kardex.COMPP_Codigo',$conpania);
          $this->db->where('cji_kardex.DOCUP_Codigo', $cod_doc);
          $this->db->where('cji_kardex.KARDC_TipoIngreso', $tipo);
          $this->db->order_by('cji_kardex.KARDP_Codigo DESC');
          $query = $this->db->get('cji_kardex');

          if ($query->num_rows > 0) {

            return $query->result();
        }
    }

    public function listar($filter = NULL) {

        #$ultimo_inventario = $this->listar_by_codigo_documento(4, 3, $filter);

        #if (!$ultimo_inventario)
        #    return array();

        if(isset($filter->producto) || $filter->producto != "") {
            $producto_id = $filter->producto;
        }

        $this->db->select('cji_kardex.*,cji_documento.*,sum(cji_kardex.KARDC_Cantidad) as KARDC_Cantidad2');

        $this->db->from('cji_kardex');

        $this->db->join('cji_producto', 'cji_producto.PROD_Codigo=  cji_kardex.PROD_Codigo');

        $this->db->join('cji_documento', 'cji_documento.DOCUP_Codigo=cji_kardex.DOCUP_Codigo');

        $this->db->where('cji_kardex.COMPP_Codigo', $filter->compania);

        if (isset($producto_id) && $producto_id != "") {
            $this->db->where('cji_producto.PROD_CodigoUsuario', $producto_id);
        }


        ////desbloqueado stv
        if (isset($filter->fechai) && $filter->fechai != "")
          $this->db->where('DATE_FORMAT(cji_kardex.KARD_Fecha,"%Y-%m-%d")  >=', $filter->fechai);

      if (isset($filter->fechaf) && $filter->fechaf != "")
          $this->db->where('DATE_FORMAT(cji_kardex.KARD_Fecha,"%Y-%m-%d") <', $filter->fechaf);

        #$this->db->where('cji_kardex.KARDP_Codigo >=', $ultimo_inventario[0]->KARDP_Codigo);

      $this->db->group_by(array('cji_kardex.DOCUP_Codigo', 'cji_kardex.KARDC_CodigoDoc'));

      $this->db->order_by('cji_kardex.KARD_Fecha ASC');

      $query = $this->db->get();

      $wProducto = (isset($producto_id) && $producto_id != "") ? " AND p.PROD_CodigoUsuario LIKE '$producto_id'" : "";
      $wFecha = (isset($filter->fechai) && $filter->fechai != "" && isset($filter->fechaf) && $filter->fechaf != "") ? " AND k.KARD_Fecha BETWEEN '$filter->fechai 00:00:00' AND '$filter->fechaf 23:59:59' " : "";

        #$sql = "SELECT k.*, d.*, SUM(k.KARDC_Cantidad) as KARDC_Cantidad2
        #            FROM cji_kardex k
        #            INNER JOIN cji_producto p ON p.PROD_Codigo = k.PROD_Codigo
        #            INNER JOIN cji_documento d ON d.DOCUP_Codigo = k.DOCUP_Codigo
        #                WHERE k.COMPP_Codigo = $filter->compania $wProducto $wFecha
        #                GROUP BY k.DOCUP_Codigo, 
        #                ORDER BY k.KARD_Fecha ASC
        #        ";
        #$query = $this->db->query($sql);

      if ($query->num_rows > 0) {
        return $query->result();
    }
}

public function listarFIFO(stdClass $filter) {

    $producto_id = $filter->producto;

    $this->db->select('*');

    $this->db->from('cji_kardex');

    $this->db->join('cji_producto', 'cji_producto.PROD_Codigo=cji_kardex.PROD_Codigo');

    $this->db->join('cji_documento', 'cji_documento.DOCUP_Codigo=cji_kardex.DOCUP_Codigo');

    $this->db->where('cji_kardex.COMPP_Codigo', $this->somevar['compania']);

    if (isset($producto_id) && $producto_id != "")
        $this->db->where('cji_producto.PROD_Codigo', $producto_id);

    if (isset($filter->fechai) && $filter->fechai != "")
        $this->db->where('DATE_FORMAT(cji_kardex.KARD_Fecha,"%Y-%m-%d")  >=', $filter->fechai);

    if (isset($filter->fechaf) && $filter->fechaf != "")
        $this->db->where('DATE_FORMAT(cji_kardex.KARD_Fecha,"%Y-%m-%d") <', $filter->fechaf);

    $this->db->order_by('cji_kardex.KARDP_Codigo');

    $query = $this->db->get();

    if ($query->num_rows > 0) {

        return $query->result();
    }
}

public function listarLIFO(stdClass $filter) {

    $producto_id = $filter->producto;

    $this->db->select('cji_kardex.*,cji_documento.*');

    $this->db->from('cji_kardex');

    $this->db->join('cji_producto', 'cji_producto.PROD_Codigo=cji_kardex.PROD_Codigo');

    $this->db->join('cji_documento', 'cji_documento.DOCUP_Codigo=cji_kardex.DOCUP_Codigo');

    $this->db->where('cji_kardex.COMPP_Codigo', $this->somevar['compania']);

    if (isset($producto_id) && $producto_id != "")
        $this->db->where('cji_producto.PROD_Codigo', $producto_id);

    if (isset($filter->fechai) && $filter->fechai != "")
        $this->db->where('DATE_FORMAT(cji_kardex.KARD_Fecha,"%Y-%m-%d") >=', $filter->fechai);

    if (isset($filter->fechaf) && $filter->fechaf != "")
        $this->db->where('DATE_FORMAT(cji_kardex.KARD_Fecha,"%Y-%m-%d") <', $filter->fechaf);

    $this->db->order_by('cji_kardex.KARDP_Codigo');

    $query = $this->db->get();

    if ($query->num_rows > 0) {

        return $query->result();
    }
}

public function obtener($documento_id, $codigo_doc) {

    $where = array("COMPP_Codigo" => $this->somevar['compania'], "DOCUP_Codigo" => $documento_id, "KARDC_CodigoDoc" => $codigo_doc);

    $query = $this->db->where($where)->get('cji_kardex');

    if ($query->num_rows > 0) {

        return $query->result();
    }
}

public function obtener_stock($producto_id) {

    $where = array("COMPP_Codigo" => $this->somevar['compania'], "PROD_Codigo" => $producto_id);

    $query = $this->db->order_by('KARDP_Codigo', 'desc')->where($where)->get('cji_kardex', 1);

    if ($query->num_rows > 0) {

        return $query->result();
    }
}

public function obtener_registros_x_dcto($producto_id, $documento_id, $codigo_doc) {

    $where = array("COMPP_Codigo" => $this->somevar['compania'], "PROD_Codigo" => $producto_id, "DOCUP_Codigo" => $documento_id, "KARDC_CodigoDoc" => $codigo_doc);

    $query = $this->db->where($where)->get('cji_kardex');

    if ($query->num_rows > 0) {

        return $query->result();
    }
}

public function insertar($dcto_id, stdClass $filter = null) {

    $fecha = $filter->KARD_Fecha;
    $cantidad = $filter->KARDC_Cantidad;
    $producto = $filter->PROD_Codigo;
    $costo = $filter->KARDC_Costo;
    $lote = $filter->LOTP_Codigo;
    $codigoAlamcenProducto = $filter->ALMPROD_Codigo;

    if ($dcto_id == 5 || $dcto_id == '5') {

            $tipo = 1; //Ingreso

        } else if ($dcto_id == 6 || $dcto_id == 7 || $dcto_id == '6' || $dcto_id == '7') {

                $tipo = 2; //Salida 

            } else if ($dcto_id == 4 || $dcto_id == '4') {
            $tipo = 3; //Inventario
        }

        $data = array(
            "PROD_Codigo" => $producto,
            "DOCUP_Codigo" => $dcto_id,
            "TIPOMOVP_Codigo" => $filter->TIPOMOVP_Codigo,
            "KARDC_CodigoDoc" => $filter->KARDC_CodigoDoc,
            "KARDC_TipoIngreso" => $tipo,
            "KARD_Fecha" => $fecha,
            "KARDC_Cantidad" => $cantidad,
            "KARDC_Costo" => $costo,
            "COMPP_Codigo" => $this->somevar['compania'],
            "LOTP_Codigo" => $lote,
            "ALMPROD_Codigo"=>$codigoAlamcenProducto,
            "KARDP_FlagEstado"=>1
        );

        $result = $this->db->insert("cji_kardex", $data);
        return $result;
    }

    public function insertar_2015($dcto_id, stdClass $filter = null) {

        $fecha = $filter->KARD_Fecha;
        $cantidad = $filter->KARDC_Cantidad;
        $producto = $filter->PROD_Codigo;
        $costo = $filter->KARDC_Costo;

        //$lote = $filter->LOTP_Codigo;

        if ($dcto_id == 5) {

            $tipo = 1; //Ingreso

        } elseif ($dcto_id == 6 || $dcto_id == 7) {

            $tipo = 2; //Salida

        } elseif ($dcto_id == 4) {
            $tipo = 3; //Inventario
        }

        $data = array(
            "PROD_Codigo" => $producto,
            "DOCUP_Codigo" => $dcto_id,
            "TIPOMOVP_Codigo" => $filter->TIPOMOVP_Codigo,
            "KARDC_CodigoDoc" => $filter->KARDC_CodigoDoc,
            "KARDC_TipoIngreso" => $tipo,
            "KARD_Fecha" => $fecha,
            "KARDC_Cantidad" => $cantidad,
            "KARDC_Costo" => $costo,
            "COMPP_Codigo" => $this->somevar['compania']
        );

        $result = $this->db->insert("cji_kardex", $data);
        return $result;
    }

    public function insertar_dsnto($dcto_id, stdClass $filter = null) {

        $fecha = $filter->KARD_Fecha;

        $cantidad = $filter->KARDC_Cantidad;

        $producto = $filter->PROD_Codigo;

        $costo = $filter->KARDC_Costo;

        $lote = $filter->LOTP_Codigo;

        $tipo = 3; //Inventario
        

        $data = array(
            "PROD_Codigo" => $producto,
            "DOCUP_Codigo" => $dcto_id,
            "TIPOMOVP_Codigo" => $filter->TIPOMOVP_Codigo,
            "KARDC_CodigoDoc" => $filter->KARDC_CodigoDoc,
            "KARDC_TipoIngreso" => $tipo,
            "KARD_Fecha" => $fecha,
            "KARDC_Cantidad" => $cantidad,
            "KARDC_Costo" => $costo,
            "COMPP_Codigo" => $this->somevar['compania'],
            "LOTP_Codigo" => $lote
        );

        $result = $this->db->insert("cji_kardex", $data);
        return $result;
    }
    
    public function eliminar($documento_id, $codigo, $producto_id) {

        $where = array("COMPP_Codigo" => $this->somevar['compania'], "PROD_Codigo" => $producto_id, "DOCUP_Codigo" => $documento_id, "KARDC_CodigoDoc" => $codigo);

        $data = array(
            'KARDC_Cantidad' => 0
        );

        $this->db->where($where);

        $this->db->update('cji_kardex', $data);
    }



    ////aumentado stv

    public function obtener_comprobante_saling($saling,$tipo,$docum_tipo) {

        if($tipo=='S'){

            if($docum_tipo!=10){
                $query = $this->db->where('GUIASAP_Codigo', $saling)->get('cji_comprobante');
            }else{
               $query = $this->db->where('GUIASAP_Codigo', $saling)->get('cji_guiarem');
           }
       }
       if($tipo=='I'){
        if($docum_tipo!=10){
            $query = $this->db->where('GUIAINP_Codigo', $saling)->get('cji_comprobante');
        }else{
            $query = $this->db->where('GUIAINP_Codigo', $saling)->get('cji_guiarem');
        }
    }




    if ($query->num_rows > 0) {
        foreach ($query->result() as $fila) {
            $data[] = $fila;
        }
        return $data;
    }
}



public function obtener_guiatrans_saling($saling,$tipo) {

    if($tipo=='S'){
        $query = $this->db->where('GUIASAP_Codigo', $saling)->get('cji_guiatrans');
    }elseif($tipo=='I'){
        $query = $this->db->where('GUIAINP_Codigo', $saling)->get('cji_guiatrans');
    }
    if ($query->num_rows > 0) {
        foreach ($query->result() as $fila) {
            $data[] = $fila;
        }
        return $data;
    }
}

public function obtener_comprobante_guainp($codigoGUIinp) {
    $query = $this->db->where('GUIAINP_Codigo', $codigoGUIinp)->get('cji_comprobante');
    if ($query->num_rows > 0) {
        foreach ($query->result() as $fila) {
            $data[] = $fila;
        }
        return $data;
    }
}

public function obtener_tipo_cambio($fecha_ingreso_gr) {
    $query = $this->db->where('TIPCAMC_Fecha', $fecha_ingreso_gr)->get('cji_tipocambio');
    if ($query->num_rows > 0) {
        foreach ($query->result() as $fila) {
            $data[] = $fila;
        }
        return $data;
    }
}

public function verificarMovimiento($codKardex, $filter){
    $where = array("KARDP_Codigo" => $codKardex);
    $this->db->where($where);
    return $this->db->update('cji_kardex', $filter);
}

    ############################################################
    # function: obtiene_movimeintos_kardex
    # description: obtiene movimeintos de productos en tablas 
    #              transaccionales
    # author: Luis Valdés      
    ############################################################
public function obtiene_movimeintos_kardex($filter='')
{
		$compania = $this->somevar['compania'];
    $limit 		= ( isset($filter->start) && isset($filter->length) ) ? " LIMIT $filter->start, $filter->length " : "";

    $where      = '';
    $where_2    = '';
    $where_3    = '';

    if (isset($filter->producto) && $filter->producto != ''){

        $where 		.= " AND cd.PROD_Codigo = $filter->producto";
        $where_2 	.= " AND gd.PROD_Codigo = $filter->producto";
        $where_3 	.= " AND id.PROD_Codigo = $filter->producto";
        $where_4    .= " AND nd.PROD_Codigo = $filter->producto";

    }

    if (isset($filter->almacen) && $filter->almacen != ''){

        $where 		.= " AND cd.ALMAP_Codigo = $filter->almacen";
        $where_2 	.= " AND (g.GTRANC_AlmacenOrigen = $filter->almacen OR g.GTRANC_AlmacenDestino = $filter->almacen)";
        $where_3 	.= " AND i.ALMAP_Codigo = $filter->almacen";

    }

    if (isset($filter->fechai) && $filter->fechai != ''){
        $fechaf 	 = (isset($filter->fechaf) && $filter->fechaf != '') ? $filter->fechaf : date("Y-m-d");
        $where 		.= " AND c.CPC_Fecha BETWEEN '$filter->fechai 00:00:00' AND '$fechaf 23:59:59'";
        $where_2 	.= " AND g.GTRANC_Fecha BETWEEN '$filter->fechai 00:00:00' AND '$fechaf 23:59:59'";
        $where_3 	.= " AND id.INVD_FechaRegistro BETWEEN '$filter->fechai 00:00:00' AND '$fechaf 23:59:59'";
        $where_4    .= " AND n.CRED_Fecha BETWEEN '$filter->fechai 00:00:00' AND '$fechaf 23:59:59'";
    }

            
            $sql="DROP TABLE IF EXISTS kardex";
            $query = $this->db->query($sql);

            $sql_comprobantes = "CREATE TEMPORARY TABLE kardex
            SELECT 
            cd.ALMAP_Codigo         AS almacen,
            c.CPC_Fecha             AS fecha, 
            c.CPP_Codigo            AS codigo_docu, 
            cd.PROD_Codigo          AS codigo, 
            cd.CPDEC_Cantidad       AS cantidad, 
            c.CPC_Numero            AS numero, 
            c.CPC_Serie             AS serie, 
            al.ALMAC_Descripcion    AS nombre_almacen,
            cd.CPDEC_Total          AS total, 
            cd.CPDEC_Pu_ConIgv      AS pu_conIgv, 
            cd.CPDEC_Subtotal       AS subtotal, 
            c.CPC_TipoOperacion     AS tipo_oper,
            p.PROD_UltimoCosto      AS costo,
            c.CPC_FlagEstado        AS estado,
            (SELECT CONCAT_WS(' ', e.EMPRC_RazonSocial, p.PERSC_Nombre, p.PERSC_ApellidoPaterno, p.PERSC_ApellidoMaterno)
            FROM cji_cliente cc
            LEFT JOIN cji_empresa e ON e.EMPRP_Codigo = cc.EMPRP_Codigo
            LEFT JOIN cji_persona p ON p.PERSP_Codigo = cc.PERSP_Codigo
            WHERE cc.CLIP_Codigo = c.CLIP_Codigo
            ) as razon_social_cliente,
            (SELECT CONCAT_WS(' ', e.EMPRC_RazonSocial, p.PERSC_Nombre, p.PERSC_ApellidoPaterno, p.PERSC_ApellidoMaterno)
            FROM cji_proveedor pp
            LEFT JOIN cji_empresa e ON e.EMPRP_Codigo = pp.EMPRP_Codigo
            LEFT JOIN cji_persona p ON p.PERSP_Codigo = pp.PERSP_Codigo
            WHERE pp.PROVP_Codigo = c.PROVP_Codigo
            ) as razon_social_proveedor

            FROM cji_comprobantedetalle cd 
            LEFT JOIN cji_comprobante c ON cd.CPP_Codigo = c.CPP_Codigo
            LEFT JOIN cji_almacen al ON cd.ALMAP_Codigo = al.ALMAP_Codigo
            LEFT JOIN cji_producto p ON cd.PROD_Codigo = p.PROD_Codigo
            WHERE cd.CPDEC_FlagEstado!=0 and c.CPC_FlagEstado=1 AND c.COMPP_Codigo = $compania $where 
            
            UNION
            SELECT 
            g.GTRANC_AlmacenOrigen  AS almacen, 
            g.GTRANC_Fecha          AS fecha, 
            g.GTRANP_Codigo         AS codigo_docu, 
            gd.PROD_Codigo          AS codigo, 
            gd.GTRANDETC_Cantidad   AS cantidad, 
            g.GTRANC_Numero         AS numero, 
            g.GTRANC_Serie          AS serie, 
            al.ALMAC_Descripcion    AS nombre_almacen,
            NULL                    AS total, 
            NULL                    AS pu_conIgv, 
            NULL                    AS subtotal, 
            'T'                     AS tipo_oper,
            NULL                    AS costo,
            g.GTRANC_EstadoTrans    AS estado,
            NULL AS razon_social_cliente,
            NULL AS razon_social_proveedor
            FROM cji_guiatransdetalle gd 
            LEFT JOIN cji_guiatrans g ON gd.GTRANP_Codigo = g.GTRANP_Codigo
            LEFT JOIN cji_almacen al ON g.GTRANC_AlmacenOrigen  = al.ALMAP_Codigo
            WHERE gd.GTRANDETC_FlagEstado!=0  AND g.GTRANC_FlagEstado!=0 AND g.GTRANC_EstadoTrans!=0  $where_2
            
            UNION
            SELECT 
            i.ALMAP_Codigo          AS almacen, 
            id.INVD_FechaRegistro   AS fecha, 
            null                    AS codigo_docu, 
            id.PROD_Codigo          AS codigo, 
            id.INVD_Cantidad        AS cantidad, 
            NULL                    AS numero, 
            NULL                    AS serie, 
            al.ALMAC_Descripcion    AS nombre_almacen,
            NULL                    AS total, 
            NULL                    AS pu_conIgv, 
            NULL                    AS subtotal, 
            'I'                     AS tipo_oper,
            NULL                    AS costo,
            NULL                    AS estado,
            NULL AS razon_social_cliente,
            NULL AS razon_social_proveedor
            FROM cji_inventariodetalle id 
            LEFT JOIN cji_inventario i ON id.INVE_Codigo = i.INVE_Codigo
            LEFT JOIN cji_almacen al ON i.ALMAP_Codigo  = al.ALMAP_Codigo
            WHERE id.INVD_FlagActivacion!=0 AND i.COMPP_Codigo = $compania $where_3

            ";
            /*UNION
            SELECT 
            nd.ALMAP_Codigo          AS almacen, 
            n.CRED_Fecha            AS fecha, 
            n.CRED_Codigo           AS codigo_docu, 
            nd.PROD_Codigo          AS codigo, 
            nd.CREDET_Cantidad      AS cantidad, 
            n.CRED_numero           AS numero, 
            n.CRED_Serie            AS serie, 
            al.ALMAC_Descripcion    AS nombre_almacen,
            nd.CREDET_Total         AS total, 
            NULL                    AS pu_conIgv, 
            nd.CREDET_Subtotal      AS subtotal, 
            'N'                     AS tipo_oper,
            NULL                    AS costo,
            CRED_FlagEstado         AS estado,
           (SELECT CONCAT_WS(' ', e.EMPRC_RazonSocial, p.PERSC_Nombre, p.PERSC_ApellidoPaterno, p.PERSC_ApellidoMaterno)
            FROM cji_cliente cc
            LEFT JOIN cji_empresa e ON e.EMPRP_Codigo = cc.EMPRP_Codigo
            LEFT JOIN cji_persona p ON p.PERSP_Codigo = cc.PERSP_Codigo
            WHERE cc.CLIP_Codigo = n.CLIP_Codigo
            ) as razon_social_cliente,
            (SELECT CONCAT_WS(' ', e.EMPRC_RazonSocial, p.PERSC_Nombre, p.PERSC_ApellidoPaterno, p.PERSC_ApellidoMaterno)
            FROM cji_proveedor pp
            LEFT JOIN cji_empresa e ON e.EMPRP_Codigo = pp.EMPRP_Codigo
            LEFT JOIN cji_persona p ON p.PERSP_Codigo = pp.PERSP_Codigo
            WHERE pp.PROVP_Codigo = n.PROVP_Codigo
            ) as razon_social_proveedor
            FROM cji_notadetalle nd 
            LEFT JOIN cji_nota n ON nd.CRED_Codigo = n.CRED_Codigo
            LEFT JOIN cji_almacen al ON nd.ALMAP_Codigo  = al.ALMAP_Codigo
            WHERE nd.CREDET_FlagEstado!=0 AND n.COMPP_Codigo = $compania $where_4*/


            $query_comprobantes = $this->db->query($sql_comprobantes);
            
            $sql_transferencias = "SELECT * FROM kardex order by fecha desc";
            
            $query_transferencias = $this->db->query($sql_transferencias);
            
            $data = array();

        

        if ($query_transferencias->num_rows > 0) {
            foreach ($query_transferencias->result() as $fila) {
                $data[] = $fila;
            }
        }
        
        if ($data) {
            return $data;
            
        } else{
            return array();
        }

    }
}

?>