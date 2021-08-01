<?php

class Configuracion_model extends Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('date');
        $this->load->model('maestros/compania_model');
        $this->somevar ['compania'] = $this->session->userdata('compania');
        $this->somevar ['usuario'] = $this->session->userdata('usuario');
        $this->somevar['hoy'] = mdate("%Y-%m-%d %h:%i:%s", time());
    }

    public function obtener_configuracion($compania) {
        $where = array("COMPP_Codigo" => $compania, "CONFIC_FlagEstado" => "1");
        $query = $this->db->where($where)->get('cji_configuracion');
        if ($query->num_rows > 0) {
            foreach ($query->result() as $fila) {
                $data[] = $fila;
            }
            return $data;
        }
    }

    public function update_numero_presupuesto($numero, $compania) {
        $where = array("COMPP_Codigo" => $compania, "DOCUP_Codigo" => 13);
        $data['CONFIC_Numero']=$numero;
        $this->db->where($where);
        $this->db->update('cji_configuracion', $data);
    }
    public function update_numero_pedido($numero, $compania) {
        $where = array("COMPP_Codigo" => $compania, "DOCUP_Codigo" => 1);
        $data['CONFIC_Numero']=$numero;
        $this->db->where($where);
        $this->db->update('cji_configuracion', $data);
    }

    /*function obtener_numero_documento($compania, $tipo_doc) {//jjjjjj
        $where = array("COMPP_Codigo" => $compania, "DOCUP_Codigo" => $tipo_doc);
        $query = $this->db->where($where)->get('cji_configuracion');
        if ($query->num_rows > 0) {
            foreach ($query->result() as $fila) {
                $data[] = $fila;
            }
            return $data;
        }
    }*/

    function obtener_numero_documento($compania, $tipo_doc, $documento = "F") {
        $where = array("COMPP_Codigo" => $compania, "DOCUP_Codigo" => $tipo_doc);
        $query = $this->db->where($where)->get('cji_configuracion');
        if ($query->num_rows > 0) {
            foreach ($query->result() as $fila) {
                $data[] = $fila;
            }

            $ultNumero = $this->correlativoSiguiente($compania, $tipo_doc, $documento);
            #var_dump($ultNumero);
            if ($ultNumero >= 0){
                if ( $tipo_doc == 11 || $tipo_doc == 12 )
                    $data[0]->CONFIC_Numero = $ultNumero;
                else
                    if ( $ultNumero > $data[0]->CONFIC_Numero )
                        $data[0]->CONFIC_Numero = $ultNumero;
            }

            return $data;
        }
    }

    public function correlativoSiguiente($compania, $tipo, $documento = "F"){

        switch ($tipo) {
            case 8: # FACTURA
                $tipo_doc = 'F';
                break;
            case 9: # BOLETA
                $tipo_doc = 'B';
                break;
            case 11: # NOTA CREDITO
                $tipo_doc = 'C';
                break;
            case 12: # NOTA DEBITO
                $tipo_doc = 'D';
                break;
            case 14: # COMPROBANTE
                $tipo_doc = 'N';
                break;
            case 20: # PRODUCCION
                $tipo_doc = 'PR';
                break;
            case 21: # DESPACHO
                $tipo_doc = 'DP';
                break;
            case 18: # COTIZACION DE VENTA
                $tipo_doc = 'CT';
                break;
            case 3: # ORDEN DE COMPRA
                $tipo_doc = 'OC';
                break;                
        }

        if ($tipo == 8 || $tipo == 9 || $tipo == 14){
            $sql = "SET @CPP_Ult = (SELECT max(CPP_Codigo) FROM cji_comprobante WHERE COMPP_Codigo = '$compania' AND CPC_TipoDocumento = '$tipo_doc' AND CPC_TipoOperacion = 'V'); ";
            $this->db->query($sql);
            
            $sql = "SELECT CPC_Numero as Numero FROM cji_comprobante WHERE CPP_Codigo = @CPP_Ult;";
            $query = $this->db->query($sql);
        }
        else
            if ($tipo == 11 || $tipo == 12){
                $sql = "SET @CPP_Ult = (SELECT max(CRED_Codigo) FROM cji_nota WHERE COMPP_Codigo = '$compania' AND CRED_TipoNota = '$tipo_doc' AND CRED_TipoOperacion = 'V' AND CRED_TipoDocumento_inicio LIKE '$documento'); ";
                $this->db->query($sql);
                
                $sql = "SELECT CRED_Numero as Numero FROM cji_nota WHERE CRED_Codigo = @CPP_Ult;";
                $query = $this->db->query($sql);
            }
        else
            if ($tipo == 20){
                $sql = "SET @CPP_Ult = (SELECT max(PR_Codigo) FROM cji_produccion); ";
                $this->db->query($sql);
                
                $sql = "SELECT PR_Numero as Numero FROM cji_produccion WHERE PR_Codigo = @CPP_Ult;";
                $query = $this->db->query($sql);
            }
        else
            if ($tipo == 21){
                $sql = "SET @CPP_Ult = (SELECT max(OCOMP_Codigo) FROM cji_pedido where OCOMC_TipoOperacion='V'); ";
                $this->db->query($sql);
                
                $sql = "SELECT OCOMC_Numero as Numero FROM cji_pedido WHERE OCOMP_Codigo = @CPP_Ult;";
                $query = $this->db->query($sql);
            }
         else
            if ($tipo == 10){
                $sql = "SET @CPP_Ult = (SELECT max(GUIAREMP_Codigo) FROM cji_guiarem where GUIAREMC_TipoOperacion='$documento'); ";
                $this->db->query($sql);
                
                $sql = "SELECT GUIAREMC_Numero as Numero FROM cji_guiarem WHERE GUIAREMP_Codigo = @CPP_Ult;";

                $query = $this->db->query($sql);

            }
          else
            if ($tipo == 15){
                $sql = "SET @CPP_Ult = (SELECT max(GTRANP_Codigo) FROM cji_guiatrans where COMPP_Codigo='$compania'); ";
                $this->db->query($sql);
                
                $sql = "SELECT GTRANC_Numero as Numero FROM cji_guiatrans WHERE GTRANP_Codigo = @CPP_Ult;";

                $query = $this->db->query($sql);

            }
           else
            if ($tipo == 18){
                $sql = "SET @CPP_Ult = (SELECT max(OCOMP_Codigo) FROM cji_ordencompra where OCOMC_TipoOperacion='V'); ";
                $this->db->query($sql);
                
                $sql = "SELECT OCOMC_Numero as Numero FROM cji_ordencompra WHERE OCOMP_Codigo = @CPP_Ult;";

                $query = $this->db->query($sql);

            }
           else
            if ($tipo == 3){
                $sql = "SET @CPP_Ult = (SELECT max(OCOMP_Codigo) FROM cji_ordencompra where OCOMC_TipoOperacion='C'); ";
                $this->db->query($sql);
                
                $sql = "SELECT OCOMC_Numero as Numero FROM cji_ordencompra WHERE OCOMP_Codigo = @CPP_Ult;";

                $query = $this->db->query($sql);

            }

        $data = NULL;
        if ($query->num_rows > 0) {
            foreach ($query->result() as $fila) {
                $data = $fila->Numero;
            }
        }
        else
            $data = 0;
        
        return $data;
    }

    
       function obtener_numero_documento_oc($compania) {
        $where = array("COMPP_Codigo" => $compania, "DOCUP_Codigo" => 3);
        $query = $this->db->where($where)->get('cji_configuracion');
        if ($query->num_rows > 0) {
            foreach ($query->result() as $fila) {
                $data[] = $fila;
            }
            return $data;
        }
    }

    function obtener_numero_documento_os($compania) {
        $where = array("COMPP_Codigo" => $compania, "DOCUP_Codigo" => 17);
        $query = $this->db->where($where)->get('cji_configuracion');
        if ($query->num_rows > 0) {
            foreach ($query->result() as $fila) {
                $data[] = $fila;
            }
            return $data;
        }
    }
    
     public function modificar_configuracion2($compania, $documento, $numero, $serie = null) {
        $data['CONFIC_Numero'] = $numero;
        if ($serie != null)
            $data['CONFIC_Serie'] = $serie;
        $where = array("COMPP_Codigo" => $compania, "DOCUP_Codigo" => $documento);
        $this->db->where($where);
        $this->db->update('cji_configuracion', $data);
    }
    
    
    
    public function modificar_configuracion($compania, $documento, $numero, $serie = null) {
        $data['CONFIC_Numero'] = $numero;
        if ($serie != null)
            $data['CONFIC_Serie'] = $serie;
        $where = array("COMPP_Codigo" => $compania, "DOCUP_Codigo" => $documento);
        $this->db->where($where);
        $this->db->update('cji_configuracion', $data);
    }

    public function modificar_configuracion_total($compania, $logo, $tipo_valorizacion, $datos, $datos_serie) {
        //$this->db->trans_start();
        $this->compania_model->modificar($compania, $logo, $tipo_valorizacion);
        $this->modificar_configuracion($compania, '1', $datos['orden_pedido'], $datos_serie['orden_pedido']);
        $this->modificar_configuracion($compania, '2', $datos['cotizacion'], $datos_serie['cotizacion']);
        $this->modificar_configuracion($compania, '3', $datos['orden_compra'], $datos_serie['orden_compra']);
        $this->modificar_configuracion($compania, '17', $datos['orden_servicio'], $datos_serie['orden_servicio']);
        $this->modificar_configuracion($compania, '4', $datos['inventario'], $datos_serie['inventario']);
        $this->modificar_configuracion($compania, '5', $datos['guia_ingreso'], $datos_serie['guia_ingreso']);
        $this->modificar_configuracion($compania, '6', $datos['guia_salida'], $datos_serie['guia_salida']);
        $this->modificar_configuracion($compania, '7', $datos['vale_salida'], $datos_serie['vale_salida']);
        $this->modificar_configuracion($compania, '8', $datos['factura'], $datos_serie['factura']);
        $this->modificar_configuracion($compania, '9', $datos['boleta'], $datos_serie['boleta']);
        $this->modificar_configuracion($compania, '10', $datos['guia_remision'], $datos_serie['guia_remision']);
        $this->modificar_configuracion($compania, '11', $datos['nota_credito'], $datos_serie['nota_credito']);
        $this->modificar_configuracion($compania, '12', $datos['nota_debito'], $datos_serie['nota_debito']);
        $this->modificar_configuracion($compania, '13', $datos['presupuesto'], $datos_serie['presupuesto']);
        $this->modificar_configuracion($compania, '14', $datos['comprobante_general'], $datos_serie['comprobante_general']);
        $this->modificar_configuracion($compania, '15', $datos['importacion'], $datos_serie['importacion']);
        $this->modificar_configuracion($compania, '18', $datos['ordenventa'], $datos_serie["ordenventa_serie"]);
        $this->modificar_configuracion($compania, '20', $datos['cobrarnumero'], $datos_serie["cobrarserie"]);
        $this->modificar_configuracion($compania, '21', $datos['pagarnumero'], $datos_serie["pagarserie"]);
        //$this->db->trans_complete();
    }

}

?>