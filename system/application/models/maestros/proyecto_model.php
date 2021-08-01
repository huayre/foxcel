<?php
class Proyecto_model extends model {

    var $somevar;
    private $empresa;
    private $compania;
    private $usuario;
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('date');

        $this->empresa = $this->session->userdata('empresa');
        $this->compania = $this->session->userdata('compania');
        $this->usuario = $this->session->userdata('user');
    }

    #########################
    ###### FUNCTIONS NEWS
    #########################

        public function getProyectos($filter = NULL) {

            $limit = ( isset($filter->start) && isset($filter->length) ) ? " LIMIT $filter->start, $filter->length " : "";
            $order = ( isset($filter->order) && isset($filter->dir) ) ? "ORDER BY $filter->order $filter->dir " : "";

            $where = '';
            if (isset($filter->nombre) && $filter->nombre != '')
                $where .= " AND p.PROYC_Nombre LIKE '%$filter->nombre%'";

            if (isset($filter->cliente) && $filter->cliente != '')
                $where .= " AND p.PROYC_Descripcion = '%$filter->cliente%'";

            $sql = "SELECT p.* FROM cji_proyecto p WHERE p.PROYC_FlagEstado LIKE '1' $where $order $limit";

            $query = $this->db->query($sql);
            if ($query->num_rows > 0)
                return $query->result();
            else
                return array();
        }

        public function getProyecto($codigo) {

            $sql = "SELECT p.*
                        FROM cji_proyecto p
                        INNER JOIN cji_direccion d ON d.PROYP_Codigo = p.PROYP_Codigo
                        WHERE p.PROYP_Codigo = $codigo
                    ";

            $query = $this->db->query($sql);

            if ($query->num_rows > 0)
                return $query->result();
            else
                return array();
        }

        public function insertar_proyecto($filter){
            $this->db->insert("cji_proyecto", (array) $filter);
            return $this->db->insert_id();
        }

        public function actualizar_proyecto($proyecto, $filter){
            $this->db->where('PROYP_Codigo',$proyecto);
            return $this->db->update('cji_proyecto', $filter);
        }

        public function deshabilitar_proyecto($proyecto, $filter){
            $this->db->where('PROYP_Codigo',$proyecto);
            $query = $this->db->update('cji_proyecto', $filter);
            return $query;
        }

    #########################
    ###### FUNCTIONS OLDS
    #########################


	public function listar_proyectos(){
        $where = array("PROYC_FlagEstado"=>1);
        $query = $this->db->order_by('PROYC_Nombre')
                          ->where($where)
                          ->select('PROYP_Codigo,PROYC_Nombre,PROYC_Descripcion,DIREP_Codigo')
                          ->from('cji_proyecto')
                          ->get();
        if($query->num_rows()>0){
            foreach($query->result() as $fila){
                $data[] = $fila;
            }
            return $data;
        }
 }

	public function obtener_datosProyecto($proyecto){
        $query = $this->db->where('PROYP_Codigo',$proyecto)->get('cji_proyecto');
        if($query->num_rows()>0){
            foreach($query->result() as $fila){
                $data[] = $fila;
            }
            return $data;
        }
    }
 public function obtener_NAMEProyecto($proyecto){
    	$query = $this->db->where('PROYP_Codigo',$proyecto)->get('cji_proyecto');
    	if($query->num_rows()>0){
    		foreach($query->result() as $fila){
    			$data[] = $fila;
    		}
    		return $data;
    	}
    }
    
    
    public function obtener_direccion($proyecto){
    	$query = $this->db->where('PROYP_Codigo',$proyecto)->get('cji_direccion');
    	if($query->num_rows>0){
    		foreach($query->result() as $fila){
    			$data[] = $fila;
    		}
    		return $data;
    	}
    }
    
    public function buscarproyecto_cliente($proyecto){
    	$query = $this->db->where('EMPRP_Codigo',$proyecto)->get('cji_proyecto');
    	if($query->num_rows>0){
    		foreach($query->result() as $fila){
    			$data[] = $fila;
    		}
    		return $data;
    	}
    	
    	
    	
    }
    
    
    public function insertar_datosProyecto($nombreProyecto,$descpProyecto,$fechai,$fechaf,$cbo_clientes)
    {
        $usuario =$this->usuario;        
        $data = array(
                    "PROYC_Nombre"       => strtoupper($nombreProyecto),
                    "PROYC_Descripcion"  => strtoupper($descpProyecto),
                    "PROYC_FechaInicio"  => $fechai,
                    "PROYC_FechaFin"     => $fechaf,
                    "EMPRP_Codigo"       => $cbo_clientes,
                    "PROYC_CodigoUsuario"  =>  $usuario
                   );
       $this->db->insert("cji_proyecto",$data);
       return $this->db->insert_id();
    }
    
    public function insertar_direccion($filter){
    	$data = array(
    			"DIRECC_Descrip"       => $filter -> DIRECC_Descrip,
    			"DIRECC_Referen"       => $filter -> DIRECC_Referen,
    			"DIRECC_Mapa" 		   => $filter -> DIRECC_Mapa,
    			"DIRECC_StreetView"    => $filter -> DIRECC_StreetView,
    			"UBIGP_Domicilio"      => $filter -> UBIGP_Domicilio,
    			"PROYP_Codigo"         => $filter -> PROYP_Codigo,
    			"DIRECC_FlagEstado"    => '1'         
    			);
    	$this->db->insert("cji_direccion",$data);
    	return $this->db->insert_id();
    }
    
 	public function modificar_datosProyecto($proyecto,$nombreProyecto,$descpProyecto,$cbo_clientes,$fechai,$fechaf)
             {
      $data = array(
                    "PROYC_Nombre"       =>$nombreProyecto,
                    "PROYC_Descripcion"  =>$descpProyecto,
                    "EMPRP_Codigo"       =>$cbo_clientes,
                    "PROYC_FechaInicio"  =>$fechai,
                    "PROYC_FechaFin"     =>$fechaf
                    );
     $this->db->where("PROYP_Codigo",$proyecto);
     $this->db->update("cji_proyecto",$data);
    }

    
   public function eliminar_proyecto($proyecto)
    {
        $data  = array("PROYC_FlagEstado"=>'0');
        $where = array("PROYP_Codigo"=>$proyecto);
        $this->db->where($where);
        $this->db->update('cji_proyecto',$data);
    }
     public function buscar_proyectos($filter,$number_items='',$offset='')
    {       
       if(isset($filter->PROYC_Nombre) && $filter->PROYC_Nombre!=""){
       $this->db->like('PROYC_Nombre',$filter->PROYC_Nombre);          
       }
        $query = $this->db->order_by('PROYC_Nombre')
                          ->where('PROYC_FlagEstado','1')
                          ->get('cji_proyecto',$number_items='',$offset='');
        if($query->num_rows>0){
            foreach($query->result() as $fila){
                    $data[] = $fila;
            }
            return $data;
        }
    }
    
    public function listar_detalle($proyecto)
    {
    	$where = array("PROYP_Codigo"=>$proyecto , "DIRECC_FlagEstado" => '1' );
    	$query = $this->db->order_by('PROYP_Codigo')->where($where)->get('cji_direccion');
    	if($query->num_rows()>0){
    		foreach($query->result() as $fila){
    			$data[] = $fila;
    		}
    		return $data;
    	}
    }
    
    public function obtener_usuario_terminal($usu){
    	$query = $this->db->where('USUA_Codigo',$usu)->get('cji_usuario_terminal');
    	if($query->num_rows>0){
    		foreach($query->result() as $fila){
    			$data[] = $fila;
    		}
    		return $data;
    	}
    }
    
    public function obtener_terminal($terminal){
    	$query = $this->db->where('TERMINAL_Codigo',$terminal)->get('cji_terminal');
    	if($query->num_rows()>0){
    		foreach($query->result() as $fila){
    			$data[] = $fila;
    		}
    		return $data;
    	}
    }
    
    public function obtener_direccion_proyecto($direccion){
    	$query = $this->db->where('DIRECC_Codigo',$direccion)->get('cji_direccion');
    	if($query->num_rows()>0){
    		foreach($query->result() as $fila){
    			$data[] = $fila;
    		}
    		return $data;
    	}
    }
    
    public function listar_detalle_terminal($direccionCodigo,$total="",$inicio="")
    {
    	$where = array("DIRECC_Codigo"=>$direccionCodigo , "TERMINAL_FlagEstado" => '1' );
    	$query = $this->db->order_by('DIRECC_Codigo')->where($where)->get('cji_terminal',$total='',$inicio='');
    	if($query->num_rows()>0){
    		foreach($query->result() as $fila){
    			$data[] = $fila;
    		}
    		return $data;
    	}
    	
    }
    
    public function eliminar_direccion($valor)
    {
    	$data  = array("DIRECC_FlagEstado"=>'0');
    	$where = array("DIRECC_Codigo"=>$valor);
    	$this->db->where($where);
    	$this->db->update('cji_direccion',$data);
    }
    
    public function modificar_direccion($valor ,$filter)
    	{
    	  $where = array("DIRECC_Codigo"=>$valor);
    	  $this->db->where($where);
    	  $this->db->update('cji_direccion',(array)$filter);
    	}
    	
    	
    	
    	public function seleccionar($codigoproyecto)
    	{
    		 
    		$listado    = $this->obtener_datosProyecto($codigoproyecto);
    		if(count($listado) > 0){
    			foreach($listado as $indice=>$valor){
    				$indice1   = $valor->PROYP_Codigo;
    				$valor1    = $valor->PROYC_Nombre;
    				$arreglo[$indice1] = $valor1;
    			}
    		}
    		return $arreglo;
    	
    	}
    	
    	public function listar_personas($contacto){

    		$sql = "select contacto.ECONC_Persona,PERSC_Nombre from cji_emprcontacto contacto inner JOIN cji_persona persona
					on contacto.ECONC_Persona = persona.PERSP_Codigo where contacto.ECONC_Persona = $contacto";
    		$query = $this->db->query($sql);
    		if($query->num_rows()>0){
    			foreach($query->result() as $fila){
    				$data[] = $fila;
    			}
    			return $data;
    		}
    	}
    	
    	public function seleccionarcontacto($contacto)
    	{
    		 
    		$listado    = $this->listar_personas($contacto);
    		$arreglo='';
    		
    		if(count($listado) > 0){
    			foreach($listado as $indice=>$valor){
    				$indice1   = $valor->ECONC_Persona;
    				$valor1    = $valor->PERSC_Nombre;
    				$arreglo[$indice1] = $valor1;
    			}
    		}
    		return $arreglo;
    		 
    	}
    	
    	public function obtenerContacto($filter){
    		$sql = "select cliente.EMPRP_Codigo,PROYC_Nombre,PROYP_Codigo,EMPRC_RazonSocial,EMPRC_Ruc from cji_proyecto obra 
					inner join cji_cliente cliente on cliente.CLIP_Codigo = obra.EMPRP_Codigo 
					inner join cji_empresa empresa on cliente.EMPRP_Codigo = empresa.EMPRP_Codigo where PROYP_Codigo = $filter ";
    		$query = $this->db->query($sql);
    		if($query->num_rows()>0){
    			foreach($query->result() as $fila){
    				$data[] = $fila;
    			}
    			return $data;
    		}
    	}

        public function obtener_comprobantesxproyecto($idproyecto){

            $sql= "SELECT c.*,e.* FROM cji_comprobante c INNER JOIN cji_cliente cl ON cl.CLIP_Codigo = c.CLIP_Codigo
                   INNER JOIN cji_empresa e ON e.EMPRP_Codigo = cl.EMPRP_Codigo WHERE c.PROYP_Codigo = $idproyecto";
            $query  = $this->db->query($sql);
            if ($query->num_rows()>0) {
                return $query->result();
            }


        }
    	
  	 } 	 

  	 
?>