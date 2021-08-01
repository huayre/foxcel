<?php  

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout
{  
    protected $obj;
    var $layout;

    function Layout($layout = "layout/layout"){
        $this->obj =& get_instance();
        $this->layout = $layout;
        
        $this->obj->load->model('seguridad/permiso_model');
        $this->obj->load->model("almacen/producto_model");
    }

    function setLayout($layout)
    {
      $this->layout = $layout;
    }    

    function view($view, $data=null, $return=false){
        $url = base_url() . "index.php/index/salir_sistema";

        $empresa        = $this->obj->session->userdata('empresa');
        $compania       = $this->obj->session->userdata('compania');
        $nombre_empresa = $this->obj->session->userdata('nombre_empresa');
        $nombre_persona = $this->obj->session->userdata('nombre_persona');
        $persona        = $this->obj->session->userdata('persona');
        $user           = $this->obj->session->userdata('user');
        $nom_user       = $this->obj->session->userdata('user_name');
        $rol            = $this->obj->session->userdata('rol');
        $desc_rol       = $this->obj->session->userdata('desc_rol');

        if ($compania == NULL || $empresa == NULL || $user == NULL || $persona == NULL || $rol == NULL || $compania == "" || $empresa == "" || $user == "" || $persona == "" || $rol == "" )
            header("Location: $url");

        $lista_compania = $this->obj->usuario_compania_model->listar_compania();

        $data["lista_compania"] = $lista_compania;
        $data["url"] = $url;
        $data["empresa"] = $empresa;
        $data["compania"] = $compania;
        $data["nombre_empresa"] = $nombre_empresa;
        $data["nombre_persona"] = $nombre_persona;
        $data["persona"] = $persona;
        $data["user"] = $user;
        $data["nom_user"] = $nom_user;
        $data["desc_rol"] = $desc_rol;
        
        $data["menus_base"] = $this->obj->permiso_model->obtener_permisosMenu($this->obj->session->userdata('rol'));

        $data["subMenu"]    = $this->obj->permiso_model->menuAccesoRapido($rol);
        $data["productos"]  = $this->obj->producto_model->stockMin(false);

        $loadedData = array();
        $loadedData['content_for_layout'] = $this->obj->load->view($view,$data,true);   

        if($return)
        {
            $output = $this->obj->load->view($this->layout, $loadedData, true);
            return $output;

        }
        else
        {
            $this->obj->load->view($this->layout, $loadedData, false);
        }
    }
}

?>