<?php

class Index extends Controller
{

    public function __construct()
    {
        parent::Controller();
        $this->load->library('pagination');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('almacen/producto_model');
        $this->load->model('maestros/empresa_model');
        $this->load->model('maestros/emprestablecimiento_model');
        $this->load->model('maestros/compania_model');
        $this->load->model('maestros/persona_model');
        $this->load->model('maestros/directivo_model');
        $this->load->model('maestros/tipocambio_model');
        $this->load->model('maestros/moneda_model');
        $this->load->model('seguridad/permiso_model');
        $this->load->model('seguridad/menu_model');
        $this->load->model('seguridad/usuario_model');
        $this->load->model('seguridad/usuario_compania_model');
        $this->load->model('seguridad/rol_model');
        $this->load->library('html');
        $this->load->library('layout', 'layout');
        $this->somevar['compania'] = $this->session->userdata('compania');
        date_default_timezone_set("America/Lima");
    }

    public function index( $msg = NULL ){
        #$lblLogin = form_label('USUARIO', 'usuario');
        #$lblClave = form_label('CONTRASENA', 'clave');
        #$txtLogin = form_input(array('name' => 'txtUsuario', 'id' => 'txtUsuario', 'value' => '', 'maxlength' => '20', 'class' => 'cajaMedia'));
        #$txtClave = form_password(array('name' => 'txtClave', 'id' => 'txtClave', 'value' => '', 'maxlength' => '15', 'class' => 'cajaMedia'));

        #$cboEstablecimiento = "<select id='cboEstablecimiento' name='cboEstablecimiento' class='comboMedio'>" . $this->OPTION_generador($this->compania_model->listar_establecimiento(1), 'COMPP_Codigo', 'EESTABC_Descripcion') . '</select>';
        #$data['cboEstablecimiento'] = $cboEstablecimiento;
        #$data['campos'] = array($lblLogin, $lblClave);
        #$data['valores'] = array($txtLogin, $txtClave);
        #$data['onload'] = "onload=\"$('#nombre').focus();\"";

        $data["msg"] = $msg;
        unset($msg);

        $this->load->view("index", $data);
    }

    public function ingresar_sistema(){
        $this->form_validation->set_rules('txtUsuario', 'Nombre Usuario', 'required|max_length[40]');
        $this->form_validation->set_rules('txtClave', 'Clave de Usuario', 'required|max_length[15]|md5');
        //$this->form_validation->set_rules('cboEstablecimiento', 'Empresa', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $txtUsuario = $this->input->post('txtUsuario');
            $txtClave = $this->input->post('txtClave');
            //$establecimiento = $this->input->post('cboEstablecimiento');
            // $empresa = 3; // este campo tiene el codigo de la empresa

            $datos_usuario = $this->usuario_model->obtener_datosUsuarioLogin($txtUsuario, $txtClave);
            if (count($datos_usuario) > 0) {
                //Obtenemos la compañia por defecto

                $empresa = $this->usuario_model->obtener_empresa_usuario($datos_usuario[0]->USUA_Codigo);

                $datos_usu_com = $this->usuario_compania_model->listar($datos_usuario[0]->USUA_Codigo, $empresa[0]->EMPRP_Codigo);


                if (count($datos_usu_com) > 0) {
                    $datos_compania = $this->compania_model->obtener($datos_usu_com[0]->COMPP_Codigo);
                    $datos_empresa = $this->empresa_model->obtener_datosEmpresa($datos_compania[0]->EMPRP_Codigo);
                    $datos_establec = $this->emprestablecimiento_model->obtener($datos_compania[0]->EESTABP_Codigo);
                    $usuario = $datos_usuario[0]->USUA_Codigo;

                    //obtengo rol
                    $obtener_rol = $this->usuario_model->obtener_rolesUsuario($usuario, $empresa/* , $establecimiento */);
                    //----------------
                    if (count($obtener_rol) > 0) {
                        $persona = $datos_usuario[0]->PERSP_Codigo;
                        $rol = $obtener_rol[0]->ROL_Codigo;
                        $desc_rol = $obtener_rol[0]->ROL_Descripcion;
                        $datos_persona = $this->persona_model->obtener_datosPersona($persona);
                        $datos_rol = $this->rol_model->obtener_rol($rol);
                        $nombre_rol = $datos_rol[0]->ROL_Descripcion;
                        $nombre_persona = $datos_persona[0]->PERSC_Nombre . " " . $datos_persona[0]->PERSC_ApellidoPaterno.' '.$datos_persona[0]->PERSC_ApellidoMaterno;
                        $datos_permisos = $this->permiso_model->obtener_permisosMenu($rol);
                        $data2 = array();
                        foreach ($datos_permisos as $valor) {
                            $menu = $valor->MENU_Codigo;
                            $datos_menu = $this->menu_model->obtener_datosMenu($menu);
                            $nombre_menu = $datos_menu[0]->MENU_Descripcion;
                            $url = $datos_menu[0]->MENU_Url;
                            $data2[] = array($menu, $nombre_menu, $url);
                        }
                        $data = array(
                            'user' => $usuario,
                            'persona' => $persona,
                            'nombre_persona' => $nombre_persona,
                            'rol' => $rol,
                            'desc_rol' => $desc_rol,
                            //'establecimiento' => $establecimiento,
                            'nombre_rol' => $nombre_rol,
                            //'compania' =>$datos_compania[0]->COMPP_Codigo,
                            'compania' => $datos_usu_com[0]->COMPP_Codigo,
                            'empresa' => $datos_empresa[0]->EMPRP_Codigo,
                            'nombre_empresa' => $datos_empresa[0]->EMPRC_RazonSocial,
                            'establec' => $datos_establec[0]->EESTABP_Codigo,
                            'nombre_establec' => $datos_establec[0]->EESTABC_Descripcion,
                            'constante' => 0,
                            'menu' => 0,
                            'user_name' => strtolower($txtUsuario),
                            'idcompania' => $datos_compania[0]->COMPP_Codigo,
                            'codUsuario'=> $datos_usuario[0]->PERSP_Codigo,
                        );
                        $this->session->set_userdata($data);
                        //var_dump($this->session->userdata('idcompania'));
                        //var_dump($datos_compania[0]->COMPP_Codigo);
                        $this->session->set_userdata('datos_menu', $data2);
                        //$this->inicio();
                        header("Location:" . base_url() . "index.php/index/inicio");
                        //-----------------------
                    } else {
                        $msgError = "<br><div align='center' class='error'>Su usuario no tiene acceso a la informacion de ninguna empresa.</div>";
                        $this->index($msgError);
                    }
                    //---------------------
                } else {
                    $msgError = "<br><div align='center' class='error'>Su usuario no tiene acceso a la informacion de ninguna empresa.</div>";
                    $this->index($msgError);
                }
            } else {
                $msgError = "<br><div align='center' class='error'>Usuario y/o contraseña no valido.</div>";
                $this->index($msgError);
            }
        }
    }

    public function inicio($j = 0, $k = 0){

        $fecha = date("Y-m-d");
        $data = array();

        ################################
        ##### FALTAN TIPOS DE CAMBIO
        ################################
            $tcInfo = $this->tipocambio_model->getTCday($fecha);
            $faltan = 0; # 0 = NO FALTAN

            if ($tcInfo != NULL){
                foreach ($tcInfo as $key => $value){
                    if ( $value->TIPCAMC_FactorConversion == NULL || $value->TIPCAMC_FactorConversion == 0 )
                        $faltan = 1;
                }

                $data["tcf"] = $faltan;
            }
            else{
                $data["tcf"] = 1;
                echo "aqui";
            }

        $data["compania"] = $compania;
        $data["nombre_empleado"] = $_SESSION['nombre_persona'];

        $this->layout->view("seguridad/inicio", $data);
    }

    public function salir_sistema()
    {
        session_destroy();
        unset($_SESSION);
        $this->index();
    }

    public function seleccionar_compania()
    {
        $array_empresas = $this->compania_model->listar_empresas();
        $arreglo = array();
        foreach ($array_empresas as $indice => $valor) {
            $empresa = $valor->EMPRP_Codigo;
            $datos_empresa = $this->empresa_model->obtener_datosEmpresa($empresa);
            $razon_social = $datos_empresa[0]->EMPRC_RazonSocial;
            $arreglo[] = array('tipo' => '1', 'nombre' => $razon_social, 'empresa' => $empresa);  //'compania'=>'',

            /* $array_establecimiento = $this->compania_model->listar_establecimiento($empresa);
              foreach($array_establecimiento as $indice=>$valor){
              $compania               = $valor->COMPP_Codigo;
              $datos_establecimiento  = $this->emprestablecimiento_model->obtener($valor->EESTABP_Codigo);
              $nombre_establecimiento = $datos_establecimiento[0]->EESTABC_Descripcion;
              $arreglo[]=array('tipo'=>'2', 'nombre'=>$nombre_establecimiento, 'compania'=>$compania);
              } */
        }
        return $arreglo;
    }
    
    /**gcbq:ponemos en session el menu seleccionado**/
    public function sessionMenuSeleccion(){
    	$idMenuSeleccionado = $this->input->post('idMenuSeleccionadoReal');
    	$idMenuSub = $this->input->post('idMenusubReal');
    	
    	if($idMenuSeleccionado!=null && $idMenuSeleccionado!=0)
    		$_SESSION['idMenuSeleccionado']=$idMenuSeleccionado;
    	else
    		unset($_SESSION['idMenuSeleccionado']);
    	
    	if($idMenuSub!=null && $idMenuSub!=0)
    		$_SESSION['idMenuSub']=$idMenuSub;
    	else
    		unset($_SESSION['idMenuSub']);
    		
    } 

}

?>