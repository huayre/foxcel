<!DOCTYPE html>
<html>
    <head>
        <title><?php echo TITULO; ?></title>
        <meta charset="utf-8"/>

        <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js?=<?=JS;?>"></script>
        
            <!-- BOOTSTRAP HERE -->
            <!-- BOOTSTRAP -->
        <link rel="shortcut icon" href="<?=base_url()?>images/favicon.png">
        <link href="<?=base_url();?>css/calendarioDespacho.css?=<?=CSS;?>" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?=base_url();?>js/datatables/datatables.css">
        
        <link rel="stylesheet" href="<?=base_url();?>css/calendario/calendar-win2k-2.css?=<?=CSS;?>" type="text/css" media="all" title="win2k-cold-1" />
        
        <link rel="stylesheet" href="<?=base_url();?>css/nav.css?=<?=CSS;?>" type="text/css"/>
        <link rel="stylesheet" href="<?=base_url();?>css/ui-lightness/jquery-ui-1.8.18.custom.css?=<?=CSS;?>" type="text/css"/>

        <link rel="stylesheet" href="<?=base_url();?>css/estilos.css?=<?=CSS;?>" type="text/css"/>
        <link href="<?=base_url();?>css/others.css?=<?=CSS;?>" rel="stylesheet">
        
        <script type="text/javascript" charset="utf8" src="<?=base_url();?>js/datatables/datatables.js"></script>
        <script type="text/javascript" src="<?=base_url();?>js/sweetalert2/sweetalert2.js?=<?=JS;?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        
        <script type="text/javascript" src="<?=base_url();?>js/funciones.js?=<?=JS;?>"></script>
        <script type="text/javascript" src="<?=base_url();?>js/jquery-ui.custom.min.js?=<?=JS;?>"></script>
        
        <script type="text/javascript" src="<?=base_url();?>js/jarch.io.js?=<?=JS;?>"></script>
        
        <!--<script src="<?=base_url();?>bootstrap/js/bootstrap.min.js?=<?=JS;?>"></script>-->
        <script src="<?=base_url();?>bootstrap/js/bootstrap.js?=<?=JS;?>"></script>
        

        <!-- BOOTSTRAP HERE -->
            <link href="<?=base_url();?>bootstrap/css/bootstrap.css?=<?=CSS;?>" rel="stylesheet">
            <link href="<?=base_url();?>bootstrap/css/bootstrap-theme.css?=<?=CSS;?>" rel="stylesheet">
        <!-- BOOTSTRAP -->

        <!-- Calendario -->
        <script type="text/javascript" src="<?=base_url();?>js/calendario/responsive-calendar.js?=<?=JS;?>"></script>
        <script type="text/javascript" src="<?=base_url();?>js/calendario/calendar.js?=<?=JS;?>"></script>
        <script type="text/javascript" src="<?=base_url();?>js/calendario/calendar-es.js?=<?=JS;?>"></script>
        <script type="text/javascript" src="<?=base_url();?>js/calendario/calendar-setup.js?=<?=JS;?>"></script>
        

        <!-- Calendario -->
        <script language="javascript">
            var cursor;
            if (document.all) {
                // Est utilizando EXPLORER
                cursor='hand';
            } else {
                // Est utilizando MOZILLA/NETSCAPE
                cursor='pointer';
            }
        </script>
        <script language="javascript">
            $(document).ready(function(){  
                obtener_demora();
                $("ul.subnav").parent().append("<span></span>"); //Only shows drop down trigger when js is enabled (Adds empty span tag after ul.subnav*)  
                $("ul.topnav li span").click(function() { //When trigger is clicked...  
                    //Following events are applied to the subnav itself (moving subnav up and down)  
                    $(this).parent().find("ul.subnav").slideDown('fast').show(); //Drop down the subnav on click 
                    $(this).parent().hover(function() {  
                    }, function(){  
                        $(this).parent().find("ul.subnav").slideUp('slow'); //When the mouse hovers out of the subnav, move it back up  
                    });  
                          //Following events are applied to the trigger (Hover events for the trigger)  
                }).hover(function() {  
                    $(this).addClass("subhover"); //On hover over, add class "subhover"  
                }, function(){  //On Hover Out  
                    $(this).removeClass("subhover"); //On hover out, remove class "subhover"  
                }); 

            });  

        </script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6 pall-0">
            	    <div id="idDivLogo" class="divWF30">
            	    	<img src="<?php echo base_url();?>images/logo.png?=<?=IMG;?>" alt="logo" style="height: 100%; margin-left: 1em; margin-top: 0.3em;" />
            	    </div>
                </div>
                    
                <div class="col-sm-6 col-md-6 col-lg-6 pall-0">
                    <div class="backgroundMenu select-empresa">
        	        	<select name="cboCompania" id="cboCompania" onchange="cambiar_sesion();"> <?php $j = 0;
                            foreach ($lista_compania as $valor) {
                                if ($valor['tipo'] == 1){
                                    if ($j != 0) ?>
                                        </optgroup>

                                    <optgroup label="<?=$valor['nombre'];?>"> <?php
                                }
                                else{ ?>
                                    <option value="<?=$valor['compania'];?>" <?=($valor['compania'] == $_SESSION['compania']) ? 'selected' : '';?>> <?=$valor['nombre'];?> </option> <?php
                                }
                                $j++;
                            } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 pall-0 backgroundMenu">
                    <?php include "menu.php"; ?>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-2 col-md-2 col-lg-2 pall-0">
                    <?php include "menuIzquierdo.php"; ?>  
                </div>
                <div class="col-sm-9 col-md-9 col-lg-9 pall-0">
                    <div class="container-fluid">
                        <div class="row header">
                            <div class="col-sm-2 col-md-2 col-lg-2 pall-0">
                                <span> <b>ROL:</b> <?=$desc_rol;?> </span>
                            </div>
                            <div class="col-sm-3 col-md-3 col-lg-3 pall-0">
                                <span> <b>USUARIO: <?=$nom_user;?></b> </span>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6 pall-0">
                                <span class="pull-right"> <b>EMPRESA:</b> <?=$nombre_empresa;?> </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 pall-0"><?=$content_for_layout;?></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-1 col-md-1 col-lg-1 pall-0">
                    <?php include "menuDerecho.php"; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-1 col-md-1 col-lg-1 pall-0"></div>
                <footer class="col-sm-10 col-md-10 col-lg-10 pall-0" style="text-align: center">
                    <p> <a href="http://www.osa-erp.com"> www.osa-erp.com </a> </p>
                    <p> Resolución optima 1152 x 864 pixeles </p>
                    <p> <a href="http://www.ccapasistemas.com">www.ccapasistemas.com</a> </p>
                </footer>
                <div class="col-sm-1 col-md-1 col-lg-1 pall-0"></div>
            </div>
        </div>    
    </body>
</html>