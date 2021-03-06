<?php

include("system/application/libraries/cezpdf.php");
include("system/application/libraries/class.backgroundpdf.php");

class Ventas extends Controller {

    private $nombre_persona;
    private $compania;

    public function __construct() {
        parent::Controller();
        $this->load->helper('form');
        $this->load->helper('date');
        $this->load->helper('util');
        $this->load->library('lib_props');
        $this->load->model('reportes/ventas_model');
        $this->load->model('almacen/producto_model');
        $this->load->model('ventas/comprobantedetalle_model');
        $this->load->model('maestros/directivo_model');
        $this->load->model('maestros/moneda_model');
        $this->load->model('tesoreria/cuentas_model');
        $this->load->model('tesoreria/pago_model');
        $this->load->model('tesoreria/cuentaspago_model');
        $this->load->library('Excel');

        $this->somevar['user'] = $this->session->userdata('user');
        $this->somevar['rol'] = $this->session->userdata('rol');
        $this->somevar['empresa'] = $this->session->userdata('empresa');
        $this->nombre_persona = $this->session->userdata("nombre_persona");
        $this->compania = $this->session->userdata('compania');
    }
    public function excel_producto_por_vendedor($vendedor = "", $fechai = NULL, $fechaf = NULL)
    {
            $fechaI = explode("-", $fechai);
        $fechaF = explode("-", $fechaf);
        $f_ini = ($fechai == NULL) ? date("Y-").date("m-")."-01" : "$fechai";
        $f_fin = ($fechaF == NULL) ? date('Y-m-d') : "$fechaf";
        if ($vendedor=='0') {
            $vendedor="";
        }
        $hoja=0;
        $vendedores = $this->directivo_model->listarVendedores($vendedor);
        foreach ($vendedores as $key => $value) {
            $vendedor = $value->PERSP_Codigo;
        
        

            ###########################################
            ######### ESTILOS
            ###########################################
                $estiloTitulo = array(
                                                'font' => array(
                                                    'name'      => 'Calibri',
                                                    'bold'      => true,
                                                    'color'     => array(
                                                        'rgb' => '000000'
                                                    ),
                                                    'size' => 14
                                                ),
                                                'alignment' =>  array(
                                                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                        'wrap'          => TRUE
                                                )
                                            );

                $estiloColumnasTitulo = array(
                                                'font' => array(
                                                    'name'      => 'Calibri',
                                                    'bold'      => true,
                                                    'color'     => array(
                                                        'rgb' => '000000'
                                                    ),
                                                    'size' => 11
                                                ),
                                                'fill'  => array(
                                                    'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                    'color' => array('argb' => 'ECF0F1')
                                                ),
                                                'alignment' =>  array(
                                                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                        'wrap'          => TRUE
                                                ),
                                                'borders' => array(
                                                    'allborders' => array(
                                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                        'color' => array( 'rgb' => "000000")
                                                    )
                                                )
                                            );

                $estiloColumnasPar = array(
                                                'font' => array(
                                                    'name'      => 'Calibri',
                                                    'bold'      => false,
                                                    'color'     => array(
                                                        'rgb' => '000000'
                                                    )
                                                ),
                                                'fill'  => array(
                                                    'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                    'color' => array('argb' => 'FFFFFFFF')
                                                ),
                                                'alignment' =>  array(
                                                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                        'wrap'          => TRUE
                                                ),
                                                'borders' => array(
                                                    'allborders' => array(
                                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                        'color' => array( 'rgb' => "000000")
                                                    )
                                                )
                                            );

                $estiloColumnasImpar = array(
                                                'font' => array(
                                                    'name'      => 'Calibri',
                                                    'bold'      => false,
                                                    'color'     => array(
                                                        'rgb' => '000000'
                                                    )
                                                ),
                                                'fill'  => array(
                                                    'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                    'color' => array('argb' => 'DCDCDCDC')
                                                ),
                                                'alignment' =>  array(
                                                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                        'wrap'          => TRUE
                                                ),
                                                'borders' => array(
                                                    'allborders' => array(
                                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                        'color' => array( 'rgb' => "000000")
                                                    )
                                                )
                                            );
                $estiloBold = array(
                                                'font' => array(
                                                    'name'      => 'Calibri',
                                                    'bold'      => true,
                                                    'color'     => array(
                                                        'rgb' => '000000'
                                                    ),
                                                    'size' => 11
                                                )
                                            );
            

            $fechai = mysql_to_human($f_ini);
            $fechaf = mysql_to_human($f_fin);
            $this->excel->setActiveSheetIndex($hoja);
            #$this->excel->getActiveSheet()->setTitle('Ventas por vendedor');
            $this->excel->createSheet($hoja);
            $this->excel->setActiveSheetIndex($hoja); //Seleccionar la pesta??a deseada
            
            $this->excel->getActiveSheet()->setTitle($value->PERSC_Nombre); //Establecer nombre
            $this->excel->getActiveSheet()->getStyle('A1:H2')->applyFromArray($estiloTitulo);
            $this->excel->getActiveSheet()->getStyle('A4:H4')->applyFromArray($estiloColumnasTitulo);
            $this->excel->getActiveSheet()->getStyle('A5:H5')->applyFromArray($estiloTitulo);

            $this->excel->setActiveSheetIndex($hoja)->mergeCells('A1:H2')->setCellValue('A1', $_SESSION['nombre_empresa']);
            $this->excel->setActiveSheetIndex($hoja)->mergeCells('A3:H3')->setCellValue('A3','USUARIO: '.$this->nombre_persona);
            $this->excel->setActiveSheetIndex($hoja)->mergeCells('A4:H4')->setCellValue("A4", "REPORTE DE VENTAS POR PRODUCTO SEGUN VENDEDOR. DESDE $fechai HASTA $fechaf");
            $this->excel->setActiveSheetIndex($hoja)->mergeCells('A5:H5')->setCellValue('A5', $value->PERSC_Nombre." ".$value->PERSC_ApellidoPaterno." ".$value->PERSC_ApellidoMaterno);

            $lugar = 6;
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
            $this->excel->getActiveSheet()->getStyle('A6:H6')->applyFromArray($estiloColumnasTitulo);
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("A$lugar", "CODIGO");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("B$lugar", "NOMBRE");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("C$lugar", "UNIDAD");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("D$lugar", "MARCA");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("E$lugar", "CANTIDAD");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("F$lugar", "TOTAL S/");

            $productosInfo = $this->ventas_model->excel_reporte_por_producto_vendedor($f_ini, $f_fin, $vendedor);
            
            $fila = 7;
            if($productosInfo>0){
            foreach ($productosInfo as $key => $value) {
                        $unidadMedida = $this->unidadmedida_model->obtener($value['unidad']);
                $medidaDetalle = "";
                $medidaDetalle = ($unidadMedida[0]->UNDMED_Descripcion != "") ? $unidadMedida[0]->UNDMED_Descripcion : "UNIDAD";
                    $this->excel->setActiveSheetIndex($hoja)->setCellValue("A$fila",$value['PROD_CodigoUsuario']);
                    $this->excel->setActiveSheetIndex($hoja)->setCellValue("B$fila",$value['PROD_Nombre']);
                    $this->excel->setActiveSheetIndex($hoja)->setCellValue("C$fila",$medidaDetalle);
                    $this->excel->setActiveSheetIndex($hoja)->setCellValue("D$fila",$value['MARCC_Descripcion']);
                    $this->excel->setActiveSheetIndex($hoja)->setCellValue("E$fila",$value['cantidadTotal']);
                    $this->excel->setActiveSheetIndex($hoja)->setCellValue("F$fila",$value['ventaTotal']);
                    $fila++;
            }
            }
            $hoja++;
        }

            $filename = "Ventas por vendedor ".date('Y-m-d').".xls"; //save our workbook as this file name
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=$filename");
        header("Cache-Control: max-age=0"); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        // Forzamos a la descarga
        $objWriter->save('php://output');
    }
    public function filtroVendedor() {
        $this->load->library('layout', 'layout');
        $data['fecha_inicio'] = '';
        $data['fecha_fin'] = '';
        $data['cboVendedor'] = $this->lib_props->listarVendedores();

        if (isset($_POST['reporte'])) {
            $data['fecha_inicio'] = $_POST['fecha_inicio'];
            $data['fecha_fin'] = $_POST['fecha_fin'];
            $data['resumen'] = $this->ventas_model->ventas_por_vendedor_resumen($data['fecha_inicio'], $data['fecha_fin']);
            $data['mensual'] = $this->ventas_model->ventas_por_vendedor_mensual($data['fecha_inicio'], $data['fecha_fin']);
            $data['anual'] = $this->ventas_model->ventas_por_vendedor_anual($data['fecha_inicio'], $data['fecha_fin']);
        }
        $this->layout->view('reportes/ventas_por_vendedor', $data);
    }

    public function filtroVendedorExcel($fechai = NULL, $fechaf = NULL) {
        $fechaI = explode("-", $fechai);
        $fechaF = explode("-", $fechaf);
        $f_ini = ($fechai == NULL) ? date("Y-").date("m-")."-01" : "$fechai";
        $f_fin = ($fechaF == NULL) ? date('Y-m-d') : "$fechaf";

            $resumen = $this->ventas_model->ventas_por_vendedor_resumen($f_ini, $f_fin);
            $mensual = $this->ventas_model->ventas_por_vendedor_mensual($f_ini, $f_fin);
            $anual = $this->ventas_model->ventas_por_vendedor_anual($f_ini, $f_fin);
        
        $this->load->library('Excel');
        
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Ventas Por Vendedor');
        
        $TipoFont = array( 'font'  => array( 'bold'  => false, 'color' => array('rgb' => '000000'), 'size'  => 14, 'name'  => 'Calibri'));
        $TipoFont2 = array( 'font'  => array( 'bold'  => false, 'color' => array('rgb' => '000000'), 'size'  => 12, 'name'  => 'Calibri'));
        $style = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));
        $style2 = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));

        $this->excel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($TipoFont);
        $this->excel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($style);

        $this->excel->getActiveSheet()->getStyle('A3:N3')->applyFromArray($TipoFont);
        $this->excel->getActiveSheet()->getStyle("A3:N3")->applyFromArray($style);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth('5');
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth('40');
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth('18');
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth('18');
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth('18');
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth('18');
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth('18');

        $this->excel->setActiveSheetIndex(0)->mergeCells('A1:E2')->setCellValue('A1', $_SESSION['nombre_empresa']);
        
        $this->excel->getActiveSheet()->getStyle("A3:E3")->applyFromArray($TipoFont2);
        $this->excel->getActiveSheet()->getStyle("A3:E3")->applyFromArray($style2);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A3:E3")->setCellValue("A3", "REPORTE DESDE $f_ini HASTA $f_fin");
        
        $this->excel->setActiveSheetIndex(0)->setCellValue('A4', 'N');
        $this->excel->setActiveSheetIndex(0)->setCellValue('B4', 'VENDEDOR');
        $this->excel->setActiveSheetIndex(0)->setCellValue('C4', 'FECHA DESDE');
        $this->excel->setActiveSheetIndex(0)->setCellValue('D4', 'FECHA HASTA');
        $this->excel->setActiveSheetIndex(0)->setCellValue('E4', 'VENTA');
    
        #$this->excel->setActiveSheetIndex(0);
        $numeroS = 0;
        $lugar = 5;

        foreach($resumen as $col)
            $keys = array_keys($col);
        
        foreach($resumen as $indice => $valor){
            $numeroS+=1;
            $ventas=$valor[$keys[0]];
            $nombre=$valor[$keys[1]];
            $paterno=$valor[$keys[2]];

            $this->excel->setActiveSheetIndex(0)
            ->setCellValue('A'.$lugar, $numeroS)
            ->setCellValue('B'.$lugar, "$nombre $paterno")
            ->setCellValue('C'.$lugar, $f_ini)
            ->setCellValue('D'.$lugar, $f_fin)
            ->setCellValue('E'.$lugar, $ventas);
            $lugar+=1;    
        }

        $numeroS = 0;
        $lugar += 4;
        $this->excel->getActiveSheet()->getStyle("A$lugar:E$lugar")->applyFromArray($TipoFont2);
        $this->excel->getActiveSheet()->getStyle("A$lugar:E$lugar")->applyFromArray($style2);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A$lugar:E$lugar")->setCellValue("A$lugar", "REPORTE MENSUAL");
        $lugar++;
        $this->excel->setActiveSheetIndex(0)->setCellValue("A$lugar", "N");
        $this->excel->setActiveSheetIndex(0)->setCellValue("B$lugar", "NOMBRE");
        #$this->excel->setActiveSheetIndex(0)->setCellValue("C$lugar", "VENTAS");

        foreach($mensual as $col)
            $keys = array_keys($col);

        $size = count($keys);
        $lcol = $lugar;
        $lugar++;

        foreach($mensual as $indice => $valor){ // listo todos los meses seleccionados
            for ($x = 2; $x < $size; $x++){
                $mes = substr($keys[$x], -1); // obtengo el mes
                $this->excel->setActiveSheetIndex(0)->setCellValue($this->lib_props->colExcel($x+1)."$lcol", $this->lib_props->mesesEs($mes));
            }
        }
        

        foreach($mensual as $indice => $valor){
            $numeroS+=1;
            $nombre=$valor[$keys[0]];
            $paterno=$valor[$keys[1]];

            for ($x = 2; $x < $size; $x++){ // 2 posicion de array donde inician ventas
                if ( $valor[$keys[$x]] != "" ){
                    $ventas = $valor[$keys[$x]];
                    $this->excel->setActiveSheetIndex(0)->setCellValue($this->lib_props->colExcel($x+1)."$lugar", $ventas); // x + 2 posision donde inician ventas + iniciar en columna D
                    #break;
                }
            }

            $this->excel->setActiveSheetIndex(0)
            ->setCellValue('A'.$lugar, $numeroS)
            ->setCellValue('B'.$lugar, "$nombre $paterno");
            $lugar+=1;
        }

        $numeroS = 0;
        $lugar += 4;
        $this->excel->getActiveSheet()->getStyle("A$lugar:E$lugar")->applyFromArray($TipoFont2);
        $this->excel->getActiveSheet()->getStyle("A$lugar:E$lugar")->applyFromArray($style2);
        $this->excel->setActiveSheetIndex(0)->setCellValue("A$lugar", "REPORTE ANUAL");
        $lugar++;
        $this->excel->setActiveSheetIndex(0)->setCellValue("A$lugar", "N");
        $this->excel->setActiveSheetIndex(0)->setCellValue("B$lugar", "NOMBRE");
        
        foreach($anual as $col)
            $keys = array_keys($col); // obtengo las llaves

        $size = count($keys);
        $lcol = $lugar;
        $lugar++;

        foreach($anual as $indice => $valor){ // listo todos los a??os seleccionados
            for ($x = 2; $x < $size; $x++){
                $anio = substr($keys[$x], 1); // obtengo el a??o
                $this->excel->setActiveSheetIndex(0)->setCellValue($this->lib_props->colExcel($x+1)."$lcol",$anio);
            }
        }
        

        foreach($anual as $indice => $valor){
            $numeroS+=1;
            $nombre=$valor[$keys[0]];
            $paterno=$valor[$keys[1]];

            for ($x = 2; $x < $size; $x++){ // 2 posicion de array donde inician ventas
                if ( $valor[$keys[$x]] != "" ){
                    $ventas = $valor[$keys[$x]];
                    $this->excel->setActiveSheetIndex(0)->setCellValue($this->lib_props->colExcel($x+1)."$lugar", $ventas); // x + 2 posision donde inician ventas + iniciar en columna D
                    #break;
                }
            }

            $this->excel->setActiveSheetIndex(0)
            ->setCellValue('A'.$lugar, $numeroS)
            ->setCellValue('B'.$lugar, "$nombre $paterno");
            $lugar+=1;
        }

        $filename = "Ventas De Vendedorre ".date('Y-m-d').".xls"; //save our workbook as this file name

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=$filename");
        header("Cache-Control: max-age=0"); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        // Forzamos a la descarga
        $objWriter->save('php://output');
        #$this->layout->view('reportes/ventas_por_vendedor', $data);
    }

    public function filtroVendedorExcelDet($vendedor = "0", $fechai = NULL, $fechaf = NULL) {
        $fechaI = explode("-", $fechai);
        $fechaF = explode("-", $fechaf);
        $f_ini = ($fechai == NULL) ? date("Y-").date("m-")."-01" : "$fechai";
        $f_fin = ($fechaF == NULL) ? date('Y-m-d') : "$fechaf";

        
        $this->load->library('Excel');
        $hoja = 0;
        $this->excel->setActiveSheetIndex($hoja);
        $this->excel->getActiveSheet()->setTitle('Ventas por vendedor');
        
        ###########################################
        ######### ESTILOS
        ###########################################
            $estiloTitulo = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 14
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasTitulo = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 11
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'ECF0F1')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            ),
                                            'borders' => array(
                                                'allborders' => array(
                                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                    'color' => array( 'rgb' => "000000")
                                                )
                                            )
                                        );

            $estiloColumnasPar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                )
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'FFFFFFFF')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            ),
                                            'borders' => array(
                                                'allborders' => array(
                                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                    'color' => array( 'rgb' => "000000")
                                                )
                                            )
                                        );

            $estiloColumnasImpar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                )
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'DCDCDCDC')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            ),
                                            'borders' => array(
                                                'allborders' => array(
                                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                    'color' => array( 'rgb' => "000000")
                                                )
                                            )
                                        );
            $estiloBold = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 11
                                            )
                                        );

        ###########################################################################
        ###### HOJA 0 VENTAS POR VENDEDOR
        ###########################################################################
            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth('5');
            $this->excel->getActiveSheet()->getStyle("A1:K2")->applyFromArray($estiloTitulo);
            $this->excel->getActiveSheet()->getStyle("A3:K3")->applyFromArray($estiloColumnasTitulo);

            $this->excel->setActiveSheetIndex($hoja)->mergeCells('A1:K2')->setCellValue('A1', $_SESSION['nombre_empresa']);        
            $this->excel->setActiveSheetIndex($hoja)->mergeCells("A3:K3")->setCellValue("A3", "VENTAS POR VENDEDOR DESDE $f_ini HASTA $f_fin");
            
            $lugar = 4;
            $vendedor = ($vendedor == 0) ? "" : $vendedor;
            $listaVendedores = $this->directivo_model->listarVendedores($vendedor);
            
            foreach ($listaVendedores as $indice => $data) {
                $numeroS = 0;
                $fpago = NULL;

                $resumen = $this->ventas_model->ventas_por_vendedor_general_suma($data->PERSP_Codigo, $f_ini, $f_fin);
                $detalle = $this->ventas_model->ventas_por_vendedor_detallado($data->PERSP_Codigo, $f_ini, $f_fin);

                if ($resumen != NULL){
                    foreach($resumen as $indice => $valor){
                        $numeroS += 1;

                        if ($numeroS == 1){
                            $lugarN = $lugar + 1;
                            foreach($detalle as $i => $val){
                                $this->excel->getActiveSheet()->getStyle("A$lugar:C$lugarN")->applyFromArray($estiloBold);
                                $this->excel->setActiveSheetIndex($hoja)
                                ->setCellValue("A$lugar", "DNI: $val->PERSC_NumeroDocIdentidad")
                                ->setCellValue("A$lugarN", "VENDEDOR: $val->PERSC_Nombre $val->PERSC_ApellidoPaterno $val->PERSC_ApellidoMaterno");
                                break;
                            }
                            $lugar += 1;
                        }
                    }

                    $lugar++;
                    $numeroS = 0;

                    foreach($detalle as $indice => $valor){
                        $numeroS += 1;

                        if ($numeroS == 1){
                            $this->excel->setActiveSheetIndex($hoja)->setCellValue("A$lugar", 'N');
                            $this->excel->setActiveSheetIndex($hoja)->setCellValue("B$lugar", 'FORMA DE PAGO');
                            $this->excel->setActiveSheetIndex($hoja)->setCellValue("C$lugar", 'C??DIGO');
                            $this->excel->setActiveSheetIndex($hoja)->setCellValue("D$lugar", 'RUC Y RAZON SOCIAL');
                            $this->excel->setActiveSheetIndex($hoja)->setCellValue("E$lugar", 'SERIE');
                            $this->excel->setActiveSheetIndex($hoja)->setCellValue("F$lugar", 'N??MERO');
                            $this->excel->setActiveSheetIndex($hoja)->setCellValue("G$lugar", 'TOTAL');
                            $this->excel->setActiveSheetIndex($hoja)->setCellValue("H$lugar", 'FECHA');
                            $this->excel->setActiveSheetIndex($hoja)->setCellValue("I$lugar", 'NOTA CREDITO RELACIONADA');
                            $this->excel->setActiveSheetIndex($hoja)->setCellValue("J$lugar", 'TOTAL');
                            $this->excel->setActiveSheetIndex($hoja)->setCellValue("K$lugar", 'FECHA');

                            $this->excel->getActiveSheet()->getStyle("A$lugar:K$lugar")->applyFromArray($estiloColumnasTitulo);
                            $lugar++;
                        }
                        
                        $this->excel->setActiveSheetIndex($hoja)
                        ->setCellValue("A$lugar", $numeroS)
                        ->setCellValue("B$lugar", $valor->FORPAC_Descripcion)
                        ->setCellValue("C$lugar", $valor->CLIC_CodigoUsuario)
                        ->setCellValue("D$lugar", $valor->nombre_cliente)
                        ->setCellValue("E$lugar", $valor->CPC_Serie)
                        ->setCellValue("F$lugar", $valor->CPC_Numero)
                        ->setCellValue("G$lugar", number_format($valor->CPC_Total,2))
                        ->setCellValue("H$lugar", $valor->CPC_Fecha)
                        ->setCellValue("I$lugar", $valor->CRED_Serie."-".$valor->CRED_Numero)
                        ->setCellValue("J$lugar", $valor->CRED_Total)
                        ->setCellValue("K$lugar", $valor->CRED_Fecha);

                        if ($indice % 2 == 0)
                            $this->excel->getActiveSheet()->getStyle("A$lugar:K$lugar")->applyFromArray($estiloColumnasPar);
                        else
                            $this->excel->getActiveSheet()->getStyle("A$lugar:K$lugar")->applyFromArray($estiloColumnasImpar);

                        $lugar+=1;
                        $fpago = $valor->FORPAC_Descripcion;
                    }
                    $lugar++;
                }
            }

            for($i = 'B'; $i <= 'K'; $i++){
                $this->excel->setActiveSheetIndex($hoja)->getColumnDimension($i)->setAutoSize(true);
            }

        ###########################################################################
        ###### HOJA 1 VENTAS POR PRODUCTO SEGUN VENDEDOR
        ###########################################################################
            $productosInfo = $this->ventas_model->ventas_por_producto_de_vendedor($f_ini, $f_fin);
            $col = count($productosInfo[0]);
            $split = $col - intval( $col / 2 ) + 7;
            $colE = $this->lib_props->colExcel( $split );
            $size = count($productosInfo);
            
            $hoja++;
            $this->excel->createSheet($hoja);
            $this->excel->setActiveSheetIndex($hoja); //Seleccionar la pesta??a deseada
            $this->excel->getActiveSheet()->setTitle('Ventas por producto'); //Establecer nombre

            $this->excel->getActiveSheet()->getStyle('A1:'.$colE.'2')->applyFromArray($estiloTitulo);
            $this->excel->getActiveSheet()->getStyle('A3:'.$colE.'3')->applyFromArray($estiloColumnasTitulo);

            $this->excel->setActiveSheetIndex($hoja)->mergeCells('A1:'.$colE.'2')->setCellValue('A1', $_SESSION['nombre_empresa']);
            $this->excel->setActiveSheetIndex($hoja)->mergeCells('A3:'.$colE.'3')->setCellValue("A3", "REPORTE DE VENTAS POR PRODUCTO SEGUN VENDEDOR. DESDE $f_ini HASTA $f_fin");

            $numeroS = 0;
            $lugar = 5;
            
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("A$lugar", "CODIGO");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("B$lugar", "NOMBRE");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("C$lugar", "MARCA");


            $lugarTU = 4;
            $lugarT = $lugar;


            $this->excel->getActiveSheet()->getStyle("A$lugarTU:$colE$lugarT")->applyFromArray($estiloColumnasTitulo);
            $lugar++;

            for ($i = "D"; $i <= $colE; $i++){
                $this->excel->setActiveSheetIndex($hoja)->setCellValue("$i$lugarT", "CANTIDAD" );
                $i++;
                $this->excel->setActiveSheetIndex($hoja)->setCellValue("$i$lugarT", "TOTAL S/ " );
            }

            foreach($productosInfo as $nCol)
                $keys = array_keys($nCol);

            $merge = true;

            for ($x = 0; $x < $size; $x++){
                $vendedor = 0;
                $it = 4;
                for ($j = 0; $j < $col; $j++) {

                    if ( $keys[$j] != "vendedor".$vendedor ){
                            $this->excel->setActiveSheetIndex($hoja)->setCellValue($this->lib_props->colExcel( $j + 1 - $vendedor)."$lugar", $productosInfo[$x][ $keys[$j] ] );
                    }
                    
                    if ( $keys[$j] == "vendedor".$vendedor )
                        $vendedor++;

                    if ( $x == 0 ){

                        $c1 = $this->lib_props->colExcel( $it );
                        $c2 = $this->lib_props->colExcel( $it + 1 );
                        $cols = "$c1$lugarTU:$c2$lugarTU";

                        if ( $c2 > $colE)
                            $merge = false;

                        if ($merge == true){
                            #$this->excel->setActiveSheetIndex($hoja)->mergeCells($cols)->setCellValue($this->lib_props->colExcel($it).$lugarTU, $productosInfo[$x]["vendedor$j"] );
                            $this->excel->setActiveSheetIndex($hoja)->setCellValue($this->lib_props->colExcel($it).$lugarTU, $productosInfo[$x]["vendedor$j"] );
                            $it += 2;
                        }
                    }
                }

                #$this->excel->setActiveSheetIndex($hoja)->setCellValue("$colE$lugarT", "TOTAL" );

                if ($x % 2 == 0)
                    $this->excel->getActiveSheet()->getStyle("A$lugar:$colE$lugar")->applyFromArray($estiloColumnasPar);
                else
                    $this->excel->getActiveSheet()->getStyle("A$lugar:$colE$lugar")->applyFromArray($estiloColumnasImpar);

                $lugar++;
            }

            $this->excel->getActiveSheet()->getColumnDimension("A")->setWidth('18');
            $this->excel->getActiveSheet()->getColumnDimension("B")->setWidth('30');
            $this->excel->getActiveSheet()->getColumnDimension("B")->setWidth('20');
            for ($i = "D"; $i <= $colE; $i++)
                $this->excel->getActiveSheet()->getColumnDimension($i)->setWidth('11');

        ###########################################################################
        ###### HOJA 2 VENTAS POR MARCA SEGUN VENDEDOR
        ###########################################################################
            $marcasInfo = $this->ventas_model->ventas_por_marca_de_vendedor($f_ini, $f_fin);
            $col = count($marcasInfo[0]);
            $split = $col - intval( $col / 2 ) + 2;
            $colE = $this->lib_props->colExcel( $split );
            $size = count($marcasInfo);
            
            $hoja++;
            $this->excel->createSheet($hoja);
            $this->excel->setActiveSheetIndex($hoja); //Seleccionar la pesta??a deseada
            $this->excel->getActiveSheet()->setTitle('Ventas por marca segun vendedor'); //Establecer nombre

            $this->excel->getActiveSheet()->getStyle('A1:'.$colE.'2')->applyFromArray($estiloTitulo);
            $this->excel->getActiveSheet()->getStyle('A3:'.$colE.'3')->applyFromArray($estiloColumnasTitulo);

            $this->excel->setActiveSheetIndex($hoja)->mergeCells('A1:'.$colE.'2')->setCellValue('A1', $_SESSION['nombre_empresa']);
            $this->excel->setActiveSheetIndex($hoja)->mergeCells('A3:'.$colE.'3')->setCellValue("A3", "REPORTE DE VENTAS POR MARCA SEGUN VENDEDOR. DESDE $f_ini HASTA $f_fin");

            $numeroS = 0;
            $lugar = 4;
            
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("A$lugar", "N");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("B$lugar", "MARCA");

            $lugarT = $lugar;
            
            $this->excel->getActiveSheet()->getStyle("A$lugar:$colE$lugar")->applyFromArray($estiloColumnasTitulo);
            $lugar++;

            foreach($marcasInfo as $nCol)
                $keys = array_keys($nCol); // obtengo las llaves

            for ($x = 0; $x < $size; $x++){
                $this->excel->setActiveSheetIndex($hoja)->setCellValue("A$lugar", $x + 1);
                $vendedor = 0;
                for ($j = 0; $j < $col; $j++) {

                    if ( $keys[$j] != "vendedor".$vendedor ){
                            $this->excel->setActiveSheetIndex($hoja)->setCellValue($this->lib_props->colExcel( $j + 2 - $vendedor)."$lugar", $marcasInfo[$x][ $keys[$j] ] );
                    }
                    
                    if ( $keys[$j] == "vendedor".$vendedor )
                        $vendedor++;

                    if ( $x == 0 )
                        $this->excel->setActiveSheetIndex($hoja)->setCellValue($this->lib_props->colExcel( $j + 3 )."$lugarT", $marcasInfo[$x]["vendedor$j"] );

                }

                $this->excel->setActiveSheetIndex($hoja)->setCellValue("$colE$lugarT", "TOTAL" );

                if ($x % 2 == 0)
                    $this->excel->getActiveSheet()->getStyle("A$lugar:$colE$lugar")->applyFromArray($estiloColumnasPar);
                else
                    $this->excel->getActiveSheet()->getStyle("A$lugar:$colE$lugar")->applyFromArray($estiloColumnasImpar);

                $lugar++;
            }

            for ($i = "B"; $i <= $colE; $i++)
                $this->excel->getActiveSheet()->getColumnDimension($i)->setWidth('20');
        
        $filename = "Ventas por vendedor ".date('Y-m-d').".xls"; //save our workbook as this file name
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=$filename");
        header("Cache-Control: max-age=0"); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        // Forzamos a la descarga
        $objWriter->save('php://output');
    }

    public function filtroVendedorExcelGeneral($fechai = NULL, $fechaf = NULL) {
        $fechaI = explode("-", $fechai);
        $fechaF = explode("-", $fechaf);
        $f_ini = ($fechai == NULL) ? date("Y-").date("m-")."-01" : "$fechai";
        $f_fin = ($fechaF == NULL) ? date('Y-m-d') : "$fechaf";

        
        $this->load->library('Excel');
        
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Ventas Por Vendedor');
        
        ###########################################
        ######### ESTILOS
        ###########################################
            $estiloTitulo = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 11
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasTitulo = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 11
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'ECF0F1')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasPar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                )
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasImpar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                )
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'DCDCDCDC')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );
            $estiloBold = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 11
                                            )
                                        );

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth('5');
        $this->excel->getActiveSheet()->getStyle("A1:H2")->applyFromArray($estiloTitulo);
        $this->excel->getActiveSheet()->getStyle("A4:H4")->applyFromArray($estiloColumnasTitulo);

        $this->excel->setActiveSheetIndex(0)->mergeCells('A1:H2')->setCellValue('A1', $_SESSION['nombre_empresa']);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A3:H3")->setCellValue("A3", "USUARIO: $this->nombre_persona");
        $this->excel->setActiveSheetIndex(0)->mergeCells("A4:H4")->setCellValue("A4", "VENTAS POR VENDEDOR DESDE $f_ini HASTA $f_fin");
        
        $listaVendedores = $this->directivo_model->listarVendedores();

        $lugar = 5;
        
        foreach ($listaVendedores as $indice => $data) {
            $numeroS = 0;
            $fpago = NULL;

            $detalle = $this->ventas_model->ventas_por_vendedor_general($data->PERSP_Codigo, $f_ini, $f_fin);

            if ($detalle != NULL){
                foreach($detalle as $indice => $valor){
                    $numeroS += 1;

                    if ($numeroS == 1){
                        foreach($detalle as $i => $val){
                            $this->excel->getActiveSheet()->getStyle("A$lugar:H$lugar")->applyFromArray($estiloBold);
                            $this->excel->setActiveSheetIndex(0)->mergeCells("A$lugar:H$lugar")->setCellValue("A$lugar", "VENDEDOR: $val->vendedor");
                            break;
                        }
                        $lugar += 1;

                        $this->excel->getActiveSheet()->getStyle("A$lugar:H$lugar")->applyFromArray($estiloColumnasTitulo);
                        $this->excel->setActiveSheetIndex(0)->setCellValue("A$lugar", 'N');
                        $this->excel->setActiveSheetIndex(0)->setCellValue("B$lugar", 'FORMA DE PAGO');
                        $this->excel->setActiveSheetIndex(0)->setCellValue("C$lugar", 'FACTURAS');
                        $this->excel->setActiveSheetIndex(0)->setCellValue("D$lugar", 'BOLETAS');
                        $this->excel->setActiveSheetIndex(0)->setCellValue("E$lugar", 'COMPROBANTES');
                        $this->excel->setActiveSheetIndex(0)->setCellValue("F$lugar", 'TOTAL');
                        $this->excel->setActiveSheetIndex(0)->setCellValue("G$lugar", 'NOTAS DE CREDITO');
                        $this->excel->setActiveSheetIndex(0)->setCellValue("H$lugar", 'VENTAS - NOTAS DE CREDITO');
                        $lugar++;
                    }
                    
                    $this->excel->setActiveSheetIndex(0)
                    ->setCellValue("A$lugar", $numeroS)
                    ->setCellValue("B$lugar", $valor->FORPAC_Descripcion)
                    ->setCellValue("C$lugar", number_format($valor->totalFacturas,2))
                    ->setCellValue("D$lugar", number_format($valor->totalBoletas,2))
                    ->setCellValue("E$lugar", number_format($valor->totalComprobantes,2))
                    ->setCellValue("F$lugar", number_format($valor->total,2))
                    ->setCellValue("G$lugar", number_format($valor->totalNotas,2))
                    ->setCellValue("H$lugar", number_format($valor->total - $valor->totalNotas,2));

                    if ($indice % 2 == 0)
                        $this->excel->getActiveSheet()->getStyle("A$lugar:H$lugar")->applyFromArray($estiloColumnasPar);
                    else
                        $this->excel->getActiveSheet()->getStyle("A$lugar:H$lugar")->applyFromArray($estiloColumnasImpar);
                    $lugar++;
                }
                $lugar++;
                $numeroS = 0;
            }
        }
        
        for($i = 'A'; $i <= 'C'; $i++){
            $this->excel->setActiveSheetIndex(0)            
                ->getColumnDimension($i)->setAutoSize(true);
        }

        
        $filename = "Ventas por Vendedor General ".date('Y-m-d').".xls"; //save our workbook as this file name
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=$filename");
        header("Cache-Control: max-age=0"); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        // Forzamos a la descarga
        $objWriter->save('php://output');
    }

    public function filtroCliente() {
        $this->load->library('layout', 'layout');
        $data['fecha_inicio'] = date("Y-m-d");
        $data['fecha_fin'] = date("Y-m-d");
        
        $data['cliente'] = "";
        $data['nombre_cliente'] = "";
        $data['buscar_cliente'] = "";
    
        if ($_POST['reporteT'] == 'cliente') {
            $data['cliente'] = $_POST['cliente'];
            $data['nombre_cliente'] = $_POST['nombre_cliente'];
            $data['buscar_cliente'] = $_POST['buscar_cliente'];
            $data['fecha_inicio'] = $_POST['fecha_inicio'];
            $data['fecha_fin'] = $_POST['fecha_fin'];
            $data['resumen'] = $this->ventas_model->ventas_por_cliente_resumen($data['fecha_inicio'], $data['fecha_fin'],$data['cliente']);
            $data['mensual'] = $this->ventas_model->ventas_por_cliente_mensual($data['fecha_inicio'], $data['fecha_fin'],$data['cliente']);
            $data['anual'] = $this->ventas_model->ventas_por_cliente_anual($data['fecha_inicio'], $data['fecha_fin'],$data['cliente']);
        }
        else
            if ($_POST['reporteT'] == 'general') {
                $data['fecha_inicio'] = $_POST['fecha_inicio'];
                $data['fecha_fin'] = $_POST['fecha_fin'];
                $data['resumen'] = $this->ventas_model->ventas_por_cliente_resumen_general($data['fecha_inicio'], $data['fecha_fin']);
                $data['mensual'] = $this->ventas_model->ventas_por_cliente_mensual_general($data['fecha_inicio'], $data['fecha_fin']);
                $data['anual'] = $this->ventas_model->ventas_por_cliente_anual_general($data['fecha_inicio'], $data['fecha_fin']);
            }
        $this->layout->view('reportes/ventas_por_cliente', $data);
    }

    public function filtroProveedor() {
        $this->load->library('layout', 'layout');
        $data['fecha_inicio'] = '';
        $data['fecha_fin'] = '';
    
        if ($_POST['reporteT'] == 'cliente') {
            $data['cliente'] = $_POST['cliente'];
            $data['nombre_cliente'] = $_POST['nombre_cliente'];
            $data['buscar_cliente'] = $_POST['buscar_cliente'];
            $data['fecha_inicio'] = $_POST['fecha_inicio'];
            $data['fecha_fin'] = $_POST['fecha_fin'];
            $data['resumen'] = $this->ventas_model->ventas_por_cliente_resumen($data['fecha_inicio'], $data['fecha_fin'],$data['cliente']);
            $data['mensual'] = $this->ventas_model->ventas_por_cliente_mensual($data['fecha_inicio'], $data['fecha_fin'],$data['cliente']);
            $data['anual'] = $this->ventas_model->ventas_por_cliente_anual($data['fecha_inicio'], $data['fecha_fin'],$data['cliente']);
        }
        else
            if ($_POST['reporteT'] == 'general') {
                $data['fecha_inicio'] = $_POST['fecha_inicio'];
                $data['fecha_fin'] = $_POST['fecha_fin'];
                $data['resumen'] = $this->ventas_model->ventas_por_proveedor_resumen_general($data['fecha_inicio'], $data['fecha_fin']);
                $data['mensual'] = $this->ventas_model->ventas_por_proveedor_mensual_general($data['fecha_inicio'], $data['fecha_fin']);
                $data['anual'] = $this->ventas_model->ventas_por_proveedor_anual_general($data['fecha_inicio'], $data['fecha_fin']);
            }
        $this->layout->view('reportes/compras_por_proveedor', $data);
    }

    public function resumen_ventas_detallado($fechai = NULL, $fechaf = NULL) {
        $fechaI = explode("-", $fechai);
        $fechaF = explode("-", $fechaf);
        $f_ini = ($fechai == NULL) ? date("Y-").date("m-")."-01" : "$fechai";
        $f_fin = ($fechaF == NULL) ? date('Y-m-d') : "$fechaf";

        
        $this->load->library('Excel');
        
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Resumen Detallado de Ventas');
        
        ###########################################
        ######### ESTILOS
        ###########################################
            $estiloTitulo = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 11
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasTitulo = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 10
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'ECF0F1')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasPar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 9
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasImpar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 9
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'DCDCDCDC')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );
            $estiloBold = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 9
                                            )
                                        );

        $this->excel->getActiveSheet()->getStyle("A1:O2")->applyFromArray($estiloTitulo);
        $this->excel->getActiveSheet()->getStyle("A3:O3")->applyFromArray($estiloColumnasTitulo);

        $this->excel->setActiveSheetIndex(0)->mergeCells('A1:O2')->setCellValue('A1', $_SESSION['nombre_empresa']);        
        $this->excel->setActiveSheetIndex(0)->mergeCells("A3:O3")->setCellValue("A3", "DETALLE DE VENTAS DESDE $f_ini HASTA $f_fin");
        
        $lugar = 4;
        $numeroS = 0;

        $resumen = $this->ventas_model->resumen_ventas_detallado($f_ini, $f_fin);

        $this->excel->setActiveSheetIndex(0)->setCellValue("A$lugar", "FECHA DOC.");
        $this->excel->setActiveSheetIndex(0)->setCellValue("B$lugar", "FECHA REG.");
        $this->excel->setActiveSheetIndex(0)->setCellValue("C$lugar", "SERIE/NUMERO");
        $this->excel->setActiveSheetIndex(0)->setCellValue("D$lugar", "CLIENTE");
        $this->excel->setActiveSheetIndex(0)->setCellValue("E$lugar", "NOMBRE DE PRODUCTO");
        $this->excel->setActiveSheetIndex(0)->setCellValue("F$lugar", "MARCA");
        $this->excel->setActiveSheetIndex(0)->setCellValue("G$lugar", "LOTE");
        $this->excel->setActiveSheetIndex(0)->setCellValue("H$lugar", "FECHA VCTO");
        $this->excel->setActiveSheetIndex(0)->setCellValue("I$lugar", "CANTIDAD");
        $this->excel->setActiveSheetIndex(0)->setCellValue("J$lugar", "P/U");
        $this->excel->setActiveSheetIndex(0)->setCellValue("K$lugar", "TOTAL");
        $this->excel->setActiveSheetIndex(0)->setCellValue("L$lugar", "NOTA DE CREDITO");
        $this->excel->setActiveSheetIndex(0)->setCellValue("M$lugar", "CANTIDAD");
        $this->excel->setActiveSheetIndex(0)->setCellValue("N$lugar", "P/U");
        $this->excel->setActiveSheetIndex(0)->setCellValue("O$lugar", "TOTAL");
        $this->excel->getActiveSheet()->getStyle("A$lugar:O$lugar")->applyFromArray($estiloColumnasTitulo);

        if ($resumen != NULL){
            $lugar++;
            foreach($resumen as $indice => $valor){
                $fRegistro = explode(" ", $valor->CPC_FechaRegistro);
                $this->excel->setActiveSheetIndex(0)
                ->setCellValue("A$lugar", $valor->CPC_Fecha)
                ->setCellValue("B$lugar", $fRegistro[0])
                ->setCellValue("C$lugar", $valor->CPC_Serie." - ".$valor->CPC_Numero)
                ->setCellValue("D$lugar", $valor->clienteEmpresa.$valor->clientePersona)
                ->setCellValue("E$lugar", $valor->PROD_Nombre)
                ->setCellValue("F$lugar", $valor->MARCC_CodigoUsuario)
                ->setCellValue("G$lugar", $valor->LOTC_Numero)
                ->setCellValue("H$lugar", $valor->LOTC_FechaVencimiento)
                ->setCellValue("I$lugar", $valor->CPDEC_Cantidad)
                ->setCellValue("J$lugar", $valor->CPDEC_Pu_ConIgv)
                ->setCellValue("K$lugar", $valor->CPDEC_Total)
                ->setCellValue("L$lugar", $valor->CRED_Serie."-".$valor->CRED_Numero)
                ->setCellValue("M$lugar", $valor->CREDET_Cantidad)
                ->setCellValue("N$lugar", $valor->CREDET_Pu_ConIgv)
                ->setCellValue("O$lugar", $valor->CREDET_Total);
                if ($indice % 2 == 0)
                    $this->excel->getActiveSheet()->getStyle("A$lugar:O$lugar")->applyFromArray($estiloColumnasPar);
                else
                    $this->excel->getActiveSheet()->getStyle("A$lugar:O$lugar")->applyFromArray($estiloColumnasImpar);
                $lugar++;
            }
            $lugar++;
        }

        $this->excel->getActiveSheet()->getColumnDimension("D")->setWidth("25");
        $this->excel->getActiveSheet()->getColumnDimension("E")->setWidth("25");

        for($i = 'A'; $i <= 'O'; $i++){
            if ($i != 'D' && $i != 'E')
            $this->excel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(true);
        }

        
        $filename = "Reporte de ventas ".date('Y-m-d').".xls"; //save our workbook as this file name
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=$filename");
        header("Cache-Control: max-age=0"); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        // Forzamos a la descarga
        $objWriter->save('php://output');
    }

    public function resumen_compras_detallado($fechai = NULL, $fechaf = NULL) {
        $fechaI = explode("-", $fechai);
        $fechaF = explode("-", $fechaf);
        $f_ini = ($fechai == NULL) ? date("Y-").date("m-")."-01" : "$fechai";
        $f_fin = ($fechaF == NULL) ? date('Y-m-d') : "$fechaf";

        
        $this->load->library('Excel');
        
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Resumen Detallado de Compras');
        
        ###########################################
        ######### ESTILOS
        ###########################################
            $estiloTitulo = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 11
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasTitulo = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 10
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'ECF0F1')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasPar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 9
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasImpar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 9
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'DCDCDCDC')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );
            $estiloBold = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 9
                                            )
                                        );

        $this->excel->getActiveSheet()->getStyle("A1:I2")->applyFromArray($estiloTitulo);
        $this->excel->getActiveSheet()->getStyle("A3:I3")->applyFromArray($estiloColumnasTitulo);

        $this->excel->setActiveSheetIndex(0)->mergeCells('A1:I2')->setCellValue('A1', $_SESSION['nombre_empresa']);        
        $this->excel->setActiveSheetIndex(0)->mergeCells("A3:I3")->setCellValue("A3", "DETALLE DE COMPRAS DESDE $f_ini HASTA $f_fin");
        
        $lugar = 4;
        $numeroS = 0;

        $resumen = $this->ventas_model->resumen_compras_detallado($f_ini, $f_fin);

        $this->excel->setActiveSheetIndex(0)->setCellValue("A$lugar", "FECHA DOC.");
        $this->excel->setActiveSheetIndex(0)->setCellValue("B$lugar", "FECHA ING.");
        $this->excel->setActiveSheetIndex(0)->setCellValue("C$lugar", "SERIE/NUMERO");
        $this->excel->setActiveSheetIndex(0)->setCellValue("D$lugar", "PROVEEDOR");
        $this->excel->setActiveSheetIndex(0)->setCellValue("E$lugar", "NOMBRE DE PRODUCTO");
        $this->excel->setActiveSheetIndex(0)->setCellValue("F$lugar", "MARCA");
        $this->excel->setActiveSheetIndex(0)->setCellValue("G$lugar", "LOTE");
        $this->excel->setActiveSheetIndex(0)->setCellValue("H$lugar", "FECHA VCTO");
        $this->excel->setActiveSheetIndex(0)->setCellValue("I$lugar", "CANTIDAD");
        $this->excel->getActiveSheet()->getStyle("A$lugar:I$lugar")->applyFromArray($estiloColumnasTitulo);

        if ($resumen != NULL){
            $lugar++;
            foreach($resumen as $indice => $valor){
                $fRegistro = explode(" ", $valor->CPC_FechaRegistro);
                $this->excel->setActiveSheetIndex(0)
                ->setCellValue("A$lugar", $valor->CPC_Fecha)
                ->setCellValue("B$lugar", $fRegistro[0])
                ->setCellValue("C$lugar", $valor->CPC_Serie." - ".$valor->CPC_Numero)
                ->setCellValue("D$lugar", $valor->proveedorEmpresa.$valor->proveedorPersona)
                ->setCellValue("E$lugar", $valor->PROD_Nombre)
                ->setCellValue("F$lugar", $valor->MARCC_CodigoUsuario)
                ->setCellValue("G$lugar", $valor->LOTC_Numero)
                ->setCellValue("H$lugar", $valor->LOTC_FechaVencimiento)
                ->setCellValue("I$lugar", $valor->CPDEC_Cantidad);
                if ($indice % 2 == 0)
                    $this->excel->getActiveSheet()->getStyle("A$lugar:I$lugar")->applyFromArray($estiloColumnasPar);
                else
                    $this->excel->getActiveSheet()->getStyle("A$lugar:I$lugar")->applyFromArray($estiloColumnasImpar);
                $lugar++;
            }
            $lugar++;
        }

        $this->excel->getActiveSheet()->getColumnDimension("D")->setWidth("25");
        $this->excel->getActiveSheet()->getColumnDimension("E")->setWidth("25");

        for($i = 'A'; $i <= 'I'; $i++){
            if ($i != 'D' && $i != 'E')
            $this->excel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(true);
        }

        
        $filename = "Reporte de compras ".date('Y-m-d').".xls"; //save our workbook as this file name
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=$filename");
        header("Cache-Control: max-age=0"); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        // Forzamos a la descarga
        $objWriter->save('php://output');
    }
    
    public function filtroTienda() {
        $monthf = date('m');
        $yearf = date('Y');
        $monthi = date('m');
        $yeari = date('Y');
        //date('Y-m-d', mktime(0,0,0, $monthf, $dayf, $yearf))
        
        $this->load->library('layout', 'layout');
        $data['fecha_inicio'] = '';
        $data['fecha_fin'] = '';

        if (isset($_POST['reporte'])) {
            $data['fecha_inicio'] = $_POST['fecha_inicio'];
            $data['fecha_fin'] = $_POST['fecha_fin'];
            $data['resumen'] = $this->ventas_model->ventas_por_tienda_resumen($data['fecha_inicio'], $data['fecha_fin']);
            $data['mensual'] = $this->ventas_model->ventas_por_tienda_mensual($data['fecha_inicio'], $data['fecha_fin']);
            $data['anual'] = $this->ventas_model->ventas_por_tienda_anual($data['fecha_inicio'], $data['fecha_fin']);
        }
         
        $this->layout->view('reportes/ventas_por_tienda', $data);
    }

    public function filtroMarca() {
        $this->load->library('layout', 'layout');
        $data['fecha_inicio'] = '';
        $data['fecha_fin'] = '';

        if (isset($_POST['reporte'])) {
            $data['fecha_inicio'] = $_POST['fecha_inicio'];
            $data['fecha_fin'] = $_POST['fecha_fin'];
            $data['resumen'] = $this->ventas_model->ventas_por_marca_resumen($data['fecha_inicio'], $data['fecha_fin']);
            $data['mensual'] = $this->ventas_model->ventas_por_marca_mensual($data['fecha_inicio'], $data['fecha_fin']);
            $data['anual'] = $this->ventas_model->ventas_por_marca_anual($data['fecha_inicio'], $data['fecha_fin']);
        }
        $this->layout->view('reportes/ventas_por_marca', $data);
    }

    public function filtroMarcaExcel($fechai = NULL, $fechaf = NULL) {
        $fechaI = explode("-", $fechai);
        $fechaF = explode("-", $fechaf);
        $f_ini = ($fechai == NULL) ? date("Y-").date("m-")."-01" : "$fechai";
        $f_fin = ($fechaF == NULL) ? date('Y-m-d') : "$fechaf";

            $resumen = $this->ventas_model->ventas_por_marca_resumen($f_ini, $f_fin);
            $mensual = $this->ventas_model->ventas_por_marca_mensual($f_ini, $f_fin);
            $anual = $this->ventas_model->ventas_por_marca_anual($f_ini, $f_fin);

        $this->load->library('Excel');
        $hoja = 0;
        $this->excel->setActiveSheetIndex($hoja);
        $this->excel->getActiveSheet()->setTitle('Ventas Por MARCA');
        
        ###########################################
        ######### ESTILOS
        ###########################################
            $estiloTitulo = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 14
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasTitulo = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 11
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'ECF0F1')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasPar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                )
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'FFFFFFFF')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            ),
                                            'borders' => array(
                                                'allborders' => array(
                                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                    'color' => array( 'rgb' => "000000")
                                                )
                                            )
                                        );

            $estiloColumnasImpar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                )
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'DCDCDCDC')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            ),
                                            'borders' => array(
                                                'allborders' => array(
                                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                    'color' => array( 'rgb' => "000000")
                                                )
                                            )
                                        );
            $estiloBold = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 11
                                            )
                                        );

        ###########################################################################
        ###### HOJA 0 VENTAS POR MARCA
        ###########################################################################
            
            $this->excel->getActiveSheet()->getStyle("A1:E2")->applyFromArray($estiloTitulo);
            $this->excel->getActiveSheet()->getStyle("A3:E3")->applyFromArray($estiloColumnasTitulo);

            $this->excel->setActiveSheetIndex($hoja)->mergeCells('A1:E2')->setCellValue('A1', $_SESSION['nombre_empresa']);

            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth('5');
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth('40');
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth('18');
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth('18');
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth('18');
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth('18');
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth('18');

            $this->excel->setActiveSheetIndex($hoja)->mergeCells('A1:E2')->setCellValue('A1', $_SESSION['nombre_empresa']);
            $this->excel->setActiveSheetIndex($hoja)->mergeCells("A3:E3")->setCellValue("A3", "REPORTE DE VENTAS POR MARCA DESDE $f_ini HASTA $f_fin");
            
            $this->excel->setActiveSheetIndex($hoja)->setCellValue('A4', 'N');
            $this->excel->setActiveSheetIndex($hoja)->setCellValue('B4', 'MARCA');
            $this->excel->setActiveSheetIndex($hoja)->setCellValue('C4', 'FECHA DESDE');
            $this->excel->setActiveSheetIndex($hoja)->setCellValue('D4', 'FECHA HASTA');
            $this->excel->setActiveSheetIndex($hoja)->setCellValue('E4', 'VENTA');
            
            $lugar = 4;
            $this->excel->getActiveSheet()->getStyle("A$lugar:E$lugar")->applyFromArray($estiloColumnasTitulo);
            
            $numeroS = 0;
            $lugar++;

            foreach($resumen as $col)
                $keys = array_keys($col);
            
            foreach($resumen as $indice => $valor){
                $numeroS += 1;
                $nombre = $valor[$keys[0]];
                $ventas = $valor[$keys[1]];

                $this->excel->setActiveSheetIndex($hoja)
                ->setCellValue('A'.$lugar, $numeroS)
                ->setCellValue('B'.$lugar, "$nombre")
                ->setCellValue('C'.$lugar, $f_ini)
                ->setCellValue('D'.$lugar, $f_fin)
                ->setCellValue('E'.$lugar, $ventas);

                if ($indice % 2 == 0)
                    $this->excel->getActiveSheet()->getStyle("A$lugar:E$lugar")->applyFromArray($estiloColumnasPar);
                else
                    $this->excel->getActiveSheet()->getStyle("A$lugar:E$lugar")->applyFromArray($estiloColumnasImpar);

                $lugar+=1;
            }

            $numeroS = 0;
            $lugar += 4;

            $this->excel->setActiveSheetIndex($hoja)->mergeCells("A$lugar:B$lugar")->setCellValue("A$lugar", "REPORTE MENSUAL");
            $this->excel->getActiveSheet()->getStyle("A$lugar:B$lugar")->applyFromArray($estiloColumnasTitulo);
            $ltituloMensual = $lugar;
            $lugar++;
            
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("A$lugar", "N");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("B$lugar", "MARCA");
            #$this->excel->setActiveSheetIndex(0)->setCellValue("C$lugar", "VENTAS");
            $this->excel->getActiveSheet()->getStyle("A$lugar:B$lugar")->applyFromArray($estiloColumnasTitulo);

            foreach($mensual as $col)
                $keys = array_keys($col);

            $size = count($keys);
            $lcol = $lugar;
            $lugar++;

            foreach($mensual as $indice => $valor){ // listo todos los meses seleccionados
                for ($x = 1; $x < $size; $x++){
                    if ( strlen($keys[$x]) == 7  ) // Entre Octubre y diciembre son 7 caracteres por ello descuento del array $keys 2 caracteres y ese es el mes.
                        $mes = substr($keys[$x], -2);
                    else 
                        $mes = substr($keys[$x], -1);
                    
                    $this->excel->setActiveSheetIndex($hoja)->setCellValue($this->lib_props->colExcel($x+2)."$lcol", $this->lib_props->mesesEs($mes));
                    $this->excel->getActiveSheet()->getStyle($this->lib_props->colExcel($x+2)."$lcol")->applyFromArray($estiloColumnasTitulo);
                    $this->excel->getActiveSheet()->getStyle($this->lib_props->colExcel($x+2)."$ltituloMensual")->applyFromArray($estiloColumnasTitulo);
                }
            }
            
            foreach($mensual as $indice => $valor){
                $numeroS += 1;
                $nombre = $valor[$keys[0]];
                
                if ($indice % 2 == 0)
                    $this->excel->getActiveSheet()->getStyle("A$lugar:B$lugar")->applyFromArray($estiloColumnasPar);
                else
                    $this->excel->getActiveSheet()->getStyle("A$lugar:B$lugar")->applyFromArray($estiloColumnasImpar);


                for ($x = 1; $x < $size; $x++){ // 1 posicion de array donde inician ventas
                    if ( $valor[$keys[$x]] != "" ){
                        $ventas = $valor[$keys[$x]];
                        $this->excel->setActiveSheetIndex($hoja)->setCellValue($this->lib_props->colExcel($x+2)."$lugar", $ventas); // x + 2 posision donde inician ventas + iniciar en columna C
                        #break;

                        if ($indice % 2 == 0)
                            $this->excel->getActiveSheet()->getStyle($this->lib_props->colExcel($x+2)."$lugar")->applyFromArray($estiloColumnasPar);
                        else
                            $this->excel->getActiveSheet()->getStyle($this->lib_props->colExcel($x+2)."$lugar")->applyFromArray($estiloColumnasImpar);
                    }
                }

                $this->excel->setActiveSheetIndex($hoja)->setCellValue('A'.$lugar, $numeroS)->setCellValue('B'.$lugar, "$nombre");
                $lugar+=1;
            }

            $numeroS = 0;
            $lugar += 4;
            $this->excel->getActiveSheet()->getStyle("A$lugar:B$lugar")->applyFromArray($estiloColumnasTitulo);
            $this->excel->setActiveSheetIndex($hoja)->mergeCells("A$lugar:C$lugar")->setCellValue("A$lugar", "REPORTE ANUAL");
            $lugar++;

            $this->excel->setActiveSheetIndex($hoja)->setCellValue("A$lugar", "N");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("B$lugar", "MARCA");
            $this->excel->getActiveSheet()->getStyle("A$lugar:B$lugar")->applyFromArray($estiloColumnasTitulo);
            $ltituloMensual = $lugar;
            
            foreach($anual as $col)
                $keys = array_keys($col); // obtengo las llaves

            $size = count($keys);
            $lcol = $lugar;
            $lugar++;

            foreach($anual as $indice => $valor){ // listo todos los a??os seleccionados
                for ($x = 1; $x < $size; $x++){
                    $anio = substr($keys[$x], 1); // obtengo el a??o
                    $this->excel->setActiveSheetIndex($hoja)->setCellValue($this->lib_props->colExcel($x+2)."$lcol",$anio);
                    $this->excel->getActiveSheet()->getStyle($this->lib_props->colExcel($x+2)."$lcol")->applyFromArray($estiloColumnasTitulo);
                    $this->excel->getActiveSheet()->getStyle($this->lib_props->colExcel($x+2)."$ltituloMensual")->applyFromArray($estiloColumnasTitulo);
                }
            }
            
            foreach($anual as $indice => $valor){
                $numeroS += 1;
                $nombre = $valor[$keys[0]];

                if ($indice % 2 == 0)
                    $this->excel->getActiveSheet()->getStyle("A$lugar:B$lugar")->applyFromArray($estiloColumnasPar);
                else
                    $this->excel->getActiveSheet()->getStyle("A$lugar:B$lugar")->applyFromArray($estiloColumnasImpar);

                for ($x = 1; $x < $size; $x++){ // 1 posicion de array donde inician ventas
                    if ( $valor[$keys[$x]] != "" ){
                        $ventas = $valor[$keys[$x]];
                        $this->excel->setActiveSheetIndex($hoja)->setCellValue($this->lib_props->colExcel($x+2)."$lugar", $ventas); // x + 2 posision donde inician ventas + iniciar en columna C
                        #break;
                        if ($indice % 2 == 0)
                            $this->excel->getActiveSheet()->getStyle($this->lib_props->colExcel($x+2)."$lugar")->applyFromArray($estiloColumnasPar);
                        else
                            $this->excel->getActiveSheet()->getStyle($this->lib_props->colExcel($x+2)."$lugar")->applyFromArray($estiloColumnasImpar);
                    }
                }

                $this->excel->setActiveSheetIndex($hoja)->setCellValue('A'.$lugar, $numeroS)->setCellValue('B'.$lugar, "$nombre");
                $lugar+=1;
            }

        ###########################################################################
        ###### HOJA 1 VENTAS POR MARCA SEGUN VENDEDOR
        ###########################################################################
            $marcasInfo = $this->ventas_model->ventas_por_marca_de_vendedor($f_ini, $f_fin);
            $col = count($marcasInfo[0]);
            $split = $col - intval( $col / 2 ) + 2;
            $colE = $this->lib_props->colExcel( $split );
            $size = count($marcasInfo);
            
            $hoja++;
            $this->excel->createSheet($hoja);
            $this->excel->setActiveSheetIndex($hoja); //Seleccionar la pesta??a deseada
            $this->excel->getActiveSheet()->setTitle('Ventas por marca Segun Vendedor'); //Establecer nombre

            $this->excel->getActiveSheet()->getStyle('A1:'.$colE.'2')->applyFromArray($estiloTitulo);
            $this->excel->getActiveSheet()->getStyle('A3:'.$colE.'3')->applyFromArray($estiloColumnasTitulo);

            $this->excel->setActiveSheetIndex($hoja)->mergeCells('A1:'.$colE.'2')->setCellValue('A1', $_SESSION['nombre_empresa']);
            $this->excel->setActiveSheetIndex($hoja)->mergeCells('A3:'.$colE.'3')->setCellValue("A3", "REPORTE DE VENTAS POR MARCA SEGUN VENDEDOR. DESDE $f_ini HASTA $f_fin");

            $numeroS = 0;
            $lugar = 4;
            
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("A$lugar", "N");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("B$lugar", "MARCA");

            $lugarT = $lugar;
            
            $this->excel->getActiveSheet()->getStyle("A$lugar:$colE$lugar")->applyFromArray($estiloColumnasTitulo);
            $lugar++;

            foreach($marcasInfo as $nCol)
                $keys = array_keys($nCol); // obtengo las llaves

            for ($x = 0; $x < $size; $x++){
                $this->excel->setActiveSheetIndex($hoja)->setCellValue("A$lugar", $x + 1);
                $vendedor = 0;
                for ($j = 0; $j < $col; $j++) {

                    if ( $keys[$j] != "vendedor".$vendedor ){
                            $this->excel->setActiveSheetIndex($hoja)->setCellValue($this->lib_props->colExcel( $j + 2 - $vendedor)."$lugar", $marcasInfo[$x][ $keys[$j] ] );
                    }
                    
                    if ( $keys[$j] == "vendedor".$vendedor )
                        $vendedor++;

                    if ( $x == 0 )
                        $this->excel->setActiveSheetIndex($hoja)->setCellValue($this->lib_props->colExcel( $j + 3 )."$lugarT", $marcasInfo[$x]["vendedor$j"] );

                }

                $this->excel->setActiveSheetIndex($hoja)->setCellValue("$colE$lugarT", "TOTAL" );

                if ($x % 2 == 0)
                    $this->excel->getActiveSheet()->getStyle("A$lugar:$colE$lugar")->applyFromArray($estiloColumnasPar);
                else
                    $this->excel->getActiveSheet()->getStyle("A$lugar:$colE$lugar")->applyFromArray($estiloColumnasImpar);

                $lugar++;
            }

            for ($i = "B"; $i <= $colE; $i++)
                $this->excel->getActiveSheet()->getColumnDimension($i)->setWidth('20');


        $filename = "Ventas por MARCA desde ".$f_ini." hasta ".$f_fin.".xls"; //save our workbook as this file name

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=$filename");
        header("Cache-Control: max-age=0"); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        // Forzamos a la descarga
        $objWriter->save('php://output');
        #$this->layout->view('reportes/ventas_por_vendedor', $data);
    }

    public function filtroFamilia() {
        $this->load->library('layout', 'layout');
        $data['fecha_inicio'] = '';
        $data['fecha_fin'] = '';

        if (isset($_POST['reporte'])) {
            $data['fecha_inicio'] = $_POST['fecha_inicio'];
            $data['fecha_fin'] = $_POST['fecha_fin'];
            $data['resumen'] = $this->ventas_model->ventas_por_familia_resumen($data['fecha_inicio'], $data['fecha_fin']);
            $data['mensual'] = $this->ventas_model->ventas_por_familia_mensual($data['fecha_inicio'], $data['fecha_fin']);
            $data['anual'] = $this->ventas_model->ventas_por_familia_anual($data['fecha_inicio'], $data['fecha_fin']);
        }
        $this->layout->view('reportes/ventas_por_familia', $data);
    }

    public function filtroFamiliaExcel($fechai = NULL, $fechaf = NULL) {
        #$this->load->library('layout', 'layout');
        $fechaI = explode("-", $fechai);
        $fechaF = explode("-", $fechaf);
        #$f_ini = ($fechaI == NULL) ? date("Y-").date("m-")."-01" : "$fechaI[2]-$fechaI[1]-$fechaI[0]";
        $f_ini = ($fechai == NULL) ? date("Y-").date("m-")."-01" : "$fechai";
        #$f_fin = ($fechaF == NULL) ? date('Y-m-d') : "$fechaF[5]-$fechaF[4]-$fechaF[3]";
        $f_fin = ($fechaF == NULL) ? date('Y-m-d') : "$fechaf";

            $resumen = $this->ventas_model->ventas_por_familia_resumen($f_ini, $f_fin);
            $mensual = $this->ventas_model->ventas_por_familia_mensual($f_ini, $f_fin);
            $anual = $this->ventas_model->ventas_por_familia_anual($f_ini, $f_fin);
        
        $this->load->library('Excel');
        
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Ventas Por Familia');
        
        $TipoFont = array( 'font'  => array( 'bold'  => false, 'color' => array('rgb' => '000000'), 'size'  => 14, 'name'  => 'Calibri'));
        $TipoFont2 = array( 'font'  => array( 'bold'  => false, 'color' => array('rgb' => '000000'), 'size'  => 12, 'name'  => 'Calibri'));
        $style = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));
        $style2 = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));

        $this->excel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($TipoFont);
        $this->excel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($style);

        $this->excel->getActiveSheet()->getStyle('A3:N3')->applyFromArray($TipoFont);
        $this->excel->getActiveSheet()->getStyle("A3:N3")->applyFromArray($style);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth('5');
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth('40');
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth('18');
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth('18');
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth('18');
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth('18');
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth('18');

        $this->excel->setActiveSheetIndex(0)->mergeCells('A1:E2')->setCellValue('A1', $_SESSION['nombre_empresa']);
        
        $this->excel->getActiveSheet()->getStyle("A3:E3")->applyFromArray($TipoFont2);
        $this->excel->getActiveSheet()->getStyle("A3:E3")->applyFromArray($style2);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A3:E3")->setCellValue("A3", "REPORTE DE VENTAS POR FAMILIA DESDE $f_ini HASTA $f_fin");
        $this->excel->setActiveSheetIndex(0)->mergeCells("A4:E4")->setCellValue("A4", "USUARIO: $this->nombre_persona");
        
        $this->excel->setActiveSheetIndex(0)->setCellValue('A5', 'N');
        $this->excel->setActiveSheetIndex(0)->setCellValue('B5', 'FAMILIA');
        $this->excel->setActiveSheetIndex(0)->setCellValue('C5', 'FECHA DESDE');
        $this->excel->setActiveSheetIndex(0)->setCellValue('D5', 'FECHA HASTA');
        $this->excel->setActiveSheetIndex(0)->setCellValue('E5', 'VENTA');
    
        #$this->excel->setActiveSheetIndex(0);
        $numeroS = 0;
        $lugar = 5;

        foreach($resumen as $col)
            $keys = array_keys($col);
        
        foreach($resumen as $indice => $valor){
            $numeroS += 1;
            $nombre = $valor[$keys[0]];
            $ventas = $valor[$keys[1]];

            $this->excel->setActiveSheetIndex(0)
            ->setCellValue('A'.$lugar, $numeroS)
            ->setCellValue('B'.$lugar, "$nombre")
            ->setCellValue('C'.$lugar, $f_ini)
            ->setCellValue('D'.$lugar, $f_fin)
            ->setCellValue('E'.$lugar, $ventas);
            $lugar+=1;    
        }

        $numeroS = 0;
        $lugar += 4;
        $this->excel->getActiveSheet()->getStyle("A$lugar:E$lugar")->applyFromArray($TipoFont2);
        $this->excel->getActiveSheet()->getStyle("A$lugar:E$lugar")->applyFromArray($style2);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A$lugar:E$lugar")->setCellValue("A$lugar", "REPORTE MENSUAL");
        $lugar++;
        $this->excel->setActiveSheetIndex(0)->setCellValue("A$lugar", "N");
        $this->excel->setActiveSheetIndex(0)->setCellValue("B$lugar", "FAMILIA");
        #$this->excel->setActiveSheetIndex(0)->setCellValue("C$lugar", "VENTAS");

        foreach($mensual as $col)
            $keys = array_keys($col);

        $size = count($keys);
        $lcol = $lugar;
        $lugar++;

        foreach($mensual as $indice => $valor){ // listo todos los meses seleccionados
            for ($x = 1; $x < $size; $x++){
                if ( strlen($keys[$x]) == 7  ) // Entre Octubre y diciembre son 7 caracteres por ello descuento del array $keys 2 caracteres y ese es el mes.
                    $mes = substr($keys[$x], -2);
                else 
                    $mes = substr($keys[$x], -1);
                
                $this->excel->setActiveSheetIndex(0)->setCellValue($this->lib_props->colExcel($x+2)."$lcol", $this->lib_props->mesesEs($mes));
            }
        }
        
        foreach($mensual as $indice => $valor){
            $numeroS += 1;
            $nombre = $valor[$keys[0]];

            for ($x = 1; $x < $size; $x++){ // 1 posicion de array donde inician ventas
                if ( $valor[$keys[$x]] != "" ){
                    $ventas = $valor[$keys[$x]];
                    $this->excel->setActiveSheetIndex(0)->setCellValue($this->lib_props->colExcel($x+2)."$lugar", $ventas); // x + 2 posision donde inician ventas + iniciar en columna C
                    #break;
                }
            }

            $this->excel->setActiveSheetIndex(0)
            ->setCellValue('A'.$lugar, $numeroS)
            ->setCellValue('B'.$lugar, "$nombre");
            $lugar+=1;
        }

        $numeroS = 0;
        $lugar += 4;
        $this->excel->getActiveSheet()->getStyle("A$lugar:E$lugar")->applyFromArray($TipoFont2);
        $this->excel->getActiveSheet()->getStyle("A$lugar:E$lugar")->applyFromArray($style2);
        $this->excel->setActiveSheetIndex(0)->setCellValue("A$lugar", "REPORTE ANUAL");
        $lugar++;
        $this->excel->setActiveSheetIndex(0)->setCellValue("A$lugar", "N");
        $this->excel->setActiveSheetIndex(0)->setCellValue("B$lugar", "FAMILIA");
        
        foreach($anual as $col)
            $keys = array_keys($col); // obtengo las llaves

        $size = count($keys);
        $lcol = $lugar;
        $lugar++;

        foreach($anual as $indice => $valor){ // listo todos los a??os seleccionados
            for ($x = 1; $x < $size; $x++){
                $anio = substr($keys[$x], 1); // obtengo el a??o
                $this->excel->setActiveSheetIndex(0)->setCellValue($this->lib_props->colExcel($x+2)."$lcol",$anio);
            }
        }
        

        foreach($anual as $indice => $valor){
            $numeroS += 1;
            $nombre = $valor[$keys[0]];

            for ($x = 1; $x < $size; $x++){ // 1 posicion de array donde inician ventas
                if ( $valor[$keys[$x]] != "" ){
                    $ventas = $valor[$keys[$x]];
                    $this->excel->setActiveSheetIndex(0)->setCellValue($this->lib_props->colExcel($x+2)."$lugar", $ventas); // x + 2 posision donde inician ventas + iniciar en columna C
                    #break;
                }
            }

            $this->excel->setActiveSheetIndex(0)
            ->setCellValue('A'.$lugar, $numeroS)
            ->setCellValue('B'.$lugar, "$nombre");
            $lugar+=1;
        }

        $filename = "Ventas por Familia desde ".$f_ini." hasta ".$f_fin.".xls"; //save our workbook as this file name

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=$filename");
        header("Cache-Control: max-age=0"); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        // Forzamos a la descarga
        $objWriter->save('php://output');
        #$this->layout->view('reportes/ventas_por_vendedor', $data);
    }

    public function filtroProducto() {
        $this->load->library('layout', 'layout');
        $data['fecha_inicio'] = '';
        $data['fecha_fin'] = '';

        if (isset($_POST['reporte'])) {
            $data['fecha_inicio'] = $_POST['fecha_inicio'];
            $data['fecha_fin'] = $_POST['fecha_fin'];
            $data['resumen'] = $this->ventas_model->ventas_por_producto_resumen($data['fecha_inicio'], $data['fecha_fin']);
            $data['mensual'] = $this->ventas_model->ventas_por_producto_mensual($data['fecha_inicio'], $data['fecha_fin']);
            $data['anual'] = $this->ventas_model->ventas_por_producto_anual($data['fecha_inicio'], $data['fecha_fin']);
        }
        $this->layout->view('reportes/ventas_por_producto', $data);
    }
    
    public function Producto_stock() {
        $this->load->library('layout', 'layout');
     
        $listado_productos = $this->ventas_model->producto_stock();
        
        if(count($listado_productos)>0){
            foreach($listado_productos as $indice=>$valor){
                $nombre = $valor->PROD_Nombre;
                $fecha = $valor->fecha;
                $dias = $valor->dias;
                $lista[] = array($nombre,$fecha,$dias);
            }
        }
        $data['lista'] = $lista;
        $this->layout->view('reportes/producto_stock', $data);
    }
    
    public function filtroDiario() {
        $this->load->library('layout', 'layout');
        $data['fecha_inicio'] = '';
        $data['fecha_fin'] = '';

        if (isset($_POST['reporte'])) {
            $data['fecha_inicio'] = $_POST['fecha_inicio'];
            $data['fecha_fin'] = $_POST['fecha_fin'];
            $data['resumen'] = $this->ventas_model->ventas_por_dia($data['fecha_inicio'], $data['fecha_fin']);
        }
        $data['oculto'] = form_hidden(array('base_url' => base_url()));
        $this->layout->view('reportes/ventas_por_dia', $data);
    }

    public function ventasdiario($tipo = 'F') {

        $this->load->library('layout', 'layout');
        $hoy = date('Y-m-d');
        $data['titulo'] = "Ventas Diarias";
        $data['tipo_docu'] = $tipo;
        $data['titulo_tabla'] = "Ventas del dia";
        $data['lista'] = $this->ventas_model->ventas_diarios($tipo, $hoy);
        $data['fecha'] = $hoy;

        $data['oculto'] = form_hidden(array('base_url' => base_url()));
        $this->layout->view('reportes/ventas_diarios', $data);
    }

    public function ejecutarAjax(){
        $tipo_oper = $this->input->post('tipo_oper');
        $tipo = $this->input->post('tipo_doc');
        $mes = $this->input->post('mes');
        $anio = $this->input->post('anio');
        
        $lista = $this->ventas_model->registro_ventas($tipo_oper, $tipo, $mes, $anio); 
        $RetornarTable = "";
        $RetornarTable .= '<table class="fuente8 tableReporte" width="100%" cellspacing="0" cellpadding="3" border="0" ID="Table1">
                            <tr class="cabeceraTabla ">
                            <td width="10%">FEHCA DE EMISION</td>
                            <td width="7%">TIPO</td>
                            <td width="5%">SERIE</td>
                            <td width="5%">NUMERO</td>
                            <td width="10%">NOMBRE Y/O RAZON SOCIAL</td>
                            <td width="5%">RUC</td>
                            <td width="5%">VALOR VENTA</td>
                            <td width="5%">I.G.V</td>
                            <td width="5%">TOTAL IMPORTE</td>
                            </tr>';

        if(count($lista)>0){
            $valor_ventaS = 0;
            $valor_igvS = 0;
            $valor_totalS =0;
            $valor_ventaD = 0;
            $valor_igvD = 0;
            $valor_totalD =0;

            foreach ($lista as $indice => $valor) {
                $fecha = $valor->CPC_Fecha;
                $tipo = $valor->CPC_TipoDocumento;
                $serie = $valor->CPC_Serie;
                $numero = $valor->CPC_Numero;
                $flag = $valor->CPC_FlagEstado;
                $tipo_persona = $valor->CLIC_TipoPersona;
                $tipo_proveedor = $valor->PROVC_TipoPersona;
                $tipo_Moneda=$valor->MONED_Simbolo;
                $cod_Moneda=$valor->MONED_Codigo;
                if ($flag == 1) {
                    $venta = $valor->CPC_subtotal;
                    $igv = $valor->CPC_igv;
                    $total = $valor->CPC_total;

                   if($cod_Moneda==1){
                    $valor_ventaS += $venta;
                    $valor_igvS += $igv;
                    $valor_totalS +=$total;}
                    if($cod_Moneda==2){
                    $valor_ventaD += $venta;
                    $valor_igvD += $igv;
                    $valor_totalD +=$total;}    
                    
                    
                    if ($tipo_oper == 'V') {
                        if ($tipo_persona == '0') {
                            $nombre = $valor->PERSC_Nombre . " " . $valor->PERSC_ApellidoPaterno . " " . $valor->PERSC_ApellidoMaterno;
                            $ruc = $valor->PERSC_Ruc;
                        } else {
                            $nombre = $valor->EMPRC_RazonSocial;
                            $ruc = $valor->EMPRC_Ruc;
                        }
                    } else {
                        if ($tipo_proveedor == '0') {
                            $nombre = $valor->PERSC_Nombre . " " . $valor->PERSC_ApellidoPaterno . " " . $valor->PERSC_ApellidoMaterno;
                            $ruc = $valor->PERSC_Ruc;
                        } else {
                            $nombre = $valor->EMPRC_RazonSocial;
                            $ruc = $valor->EMPRC_Ruc;
                        }
                    }
                }
                else {

                    $nombre = "ANULADO";
                    $ruc = "";
                    $venta = "";
                    $igv = "";
                    $total = "";
                }

                $RetornarTable.='<tr>
                <td><div align="center">'.$fecha.'</div></td>
                <td><div align="left">';
                
                if ($tipo == 'F')
                    $RetornarTable .= "Factura";
                else
                    if($tipo == 'B')
                        $RetornarTable.="Boleta";
                else
                    if($tipo == 'N')
                        $RetornarTable.="Comprobante";

                $RetornarTable .= '</div></td>';
                $RetornarTable .= '<td><div align="center">'.$serie.'</div></td>
                    <td><div align="center">'.$numero.'</div></td>
                    <td><div align="center">'.$nombre.'</div></td>
                    <td><div align="center">'.$ruc.'</div></td>';
                $RetornarTable .= '<td><div align="center">'.$valor_ventaS.'</div></td><td><div align="center">'.$valor_igvS.'</div></td>
                                    <td><div align="center">S/.'.number_format($valor_totalS, 2).'</div></td> ';
            }
        }
        else {
            $RetornarTable .= '<table width="100%" cellspacing="0" cellpadding="3" border="0" class="fuente8">
                                    <tbody>
                                        <tr>
                                            <td width="100%" class="mensaje">No hay ning&uacute;n registro que cumpla con los criterios de b&uacute;squeda</td>
                                        </tr>
                                    </tbody>
                                </table>';
        }

        echo $RetornarTable;
    }

    public function ventasdiario_fecha($tipo = 'F', $hoy) {
        $this->load->library('layout', 'layout');
        $data['titulo'] = "Ventas Diarias";
        $data['tipo_docu'] = $tipo;
        $data['titulo_tabla'] = "Ventas del dia";
        $data['lista'] = $this->ventas_model->ventas_diarios($tipo, $hoy);
        $data['fecha'] = $hoy;

        $data['oculto'] = form_hidden(array('base_url' => base_url()));
        $this->layout->view('reportes/ventas_diarios', $data);
    }

    public function ventas_pdf($tipo_doc = "F", $hoy) {

        if ($tipo_doc == "F")
            $titulo = "REPORTE FACTURAS";
        if ($tipo_doc == "B")
            $titulo = "REPORTE BOLETAS";
        if ($tipo_doc == "N")
            $titulo = "REPORTE COMPROBANTES";
        $lista = $this->ventas_model->ventas_diarios($tipo_doc, $hoy);
        $this->cezpdf = new Cezpdf('a4', 'landscape');
        $this->cezpdf->ezText(($titulo . "  DIARIO  "), 11, array("left" => 180));

        $this->cezpdf->ezText('', '');
        /* Listado de detalles */
        $db_data = array();
        $valor_venta = 0;
        $valor_igv = 0;
        $valor_total = 0;
        foreach ($lista as $indice => $valor) {
            $tipo = $valor->CPC_TipoDocumento;
            $tipo_persona = $valor->CLIC_TipoPersona;
            $flag = $valor->CPC_FlagEstado;
            $nombre = '';
            if ($flag == 1) {

                if ($tipo_doc != "F") {
                    $subtotal = number_format($valor->CPC_total / 1.18, 2);
                    $igv = number_format($subtotal * 0.18, 2);
                } else {
                    $igv = $valor->CPC_igv;
                    $subtotal = $valor->CPC_subtotal;
                }
                $total = $valor->CPC_total;
                $valor_venta +=$subtotal;
                $valor_igv +=$igv;
                $valor_total +=$total;


                if ($tipo_doc == 'F')
                    $nombre = 'Factura';
                else
                    $nombre = 'Boleta';

                if ($tipo_persona == '0') {
                    $nombre_cliente = $valor->PERSC_Nombre . " " . $valor->PERSC_ApellidoPaterno . " " . $valor->PERSC_ApellidoMaterno;
                    $ruc = $valor->PERSC_Ruc;
                } else {
                    $nombre_cliente = $valor->EMPRC_RazonSocial;
                    $ruc = $valor->EMPRC_Ruc;
                }
            } else {
                $nombre_cliente = "ANULADO";
                $ruc = "";
                $subtotal = "";
                $igv = "";
                $total = "";
            }

            $db_data[] = array(
                'cols1' => $valor->CPC_Fecha,
                'cols2' => $nombre,
                'cols3' => $valor->CPC_Serie,
                'cols4' => $valor->CPC_Numero,
                'cols5' => $nombre_cliente,
                'cols6' => $ruc,
                'cols7' => $subtotal,
                'cols8' => $igv,
                'cols9' => $total,
            );
        }
        $col_names = array(
            'cols1' => 'Fecha',
            'cols2' => 'Tipo',
            'cols3' => 'Serie',
            'cols4' => 'Numero',
            'cols5' => 'Cliente',
            'cols6' => 'Ruc',
            'cols7' => 'Valor Venta',
            'cols8' => '   I.G.V      ',
            'cols9' => 'Importe Total',
        );

        $this->cezpdf->ezTable($db_data, $col_names, '', array(
            'width' => 450,
            'showLines' => 2,
            'shaded' => 0,
            'Leading' => 10,
            'showHeadings' => 1,
            'xPos' => 300,
            'fontSize' => 8,
            'cols' => array(
                'cols1' => array('width' => 58, 'justification' => 'center'),
                'cols2' => array('width' => 42, 'justification' => 'left'),
                'cols3' => array('width' => 35, 'justification' => 'left'),
                'cols4' => array('width' => 45, 'justification' => 'left'),
                'cols5' => array('width' => 155, 'justification' => 'center'),
                'cols6' => array('width' => 66, 'justification' => 'left'),
                'cols7' => array('width' => 54, 'justification' => 'left'),
                'cols9' => array('width' => 48, 'justification' => 'left'),
                'cols9' => array('width' => 48, 'justification' => 'left')
            )
        ));

        $db_data = array(
            array(
                'cols1' => '',
                'cols2' => '',
                'cols3' => '',
                'cols4' => '',
                'cols5' => '',
                'cols6' => number_format($valor_venta, 2)
                , 'cols7' => number_format($valor_igv, 2),
                'cols8' => number_format($valor_total, 2)),
        );



        $this->cezpdf->ezText('', '');
        $this->cezpdf->ezTable($db_data, "", "", array(
            'width' => 505,
            'showLines' => 0,
            'shaded' => 20,
            'showHeadings' => 0,
            'xPos' => 'center',
            'fontSize' => 9,
            'cols' => array(
                'cols1' => array('width' => 10, 'justification' => 'left'),
                'cols2' => array('width' => 10, 'justification' => 'left'),
                'cols3' => array('width' => 40, 'justification' => 'left'),
                'cols4' => array('width' => 45, 'justification' => 'left'),
                'cols5' => array('width' => 50, 'justification' => 'left'),
                'cols6' => array('width' => 55, 'justification' => 'left'),
                'cols7' => array('width' => 45, 'justification' => 'left'),
                'cols8' => array('width' => 55, 'justification' => 'left'),
            )
        ));




        $this->cezpdf->ezText('', 8);
        $cabecera = array('Content-Type' => 'application/pdf', 'Content-Disposition' => $tipo_doc . '.pdf', 'Expires' => '0', 'Pragma' => 'cache', 'Cache-Control' => 'private');
        $this->cezpdf->ezStream($cabecera);
    }

    public function registro_ventas_pdf($tipo_oper, $tipo_doc = "F", $fecha1, $fecha2) {
        if ($tipo_oper == 'V') {
            $titulo_personal = 'Cliente';
            if ($tipo_doc == "F")
                $titulo = "REGISTRO DE  VENTAS FACTURAS";
            if ($tipo_doc == "B")
                $titulo = "REPORTE DE  VENTAS BOLETAS";
            if ($tipo_doc == "N")
                $titulo = "REPORTE DE  VENTAS COMPROBANTES";
        }

        else {

            $titulo_personal = 'Proveedor';

            if ($tipo_doc == "F")
                $titulo = "REGISTRO DE  COMPRAS FACTURAS";
            if ($tipo_doc == "B")
                $titulo = "REPORTE DE  COMPRAS BOLETAS";
            if ($tipo_doc == "N")
                $titulo = "REPORTE DE  COMPRAS COMPROBANTES";
        }
        $lista = $this->ventas_model->registro_ventas($tipo_oper, $tipo_doc, $fecha1, $fecha2);
        $this->cezpdf = new Cezpdf('a4', 'landscape');
        $this->cezpdf->ezText(($titulo), 11, array("left" => 180));

        $this->cezpdf->ezText('', '');
        /* Listado de detalles */
        $db_data = array();
        $valor_venta = 0;
        $valor_igv = 0;
        $valor_total = 0;
        foreach ($lista as $indice => $valor) {
            $tipo = $valor->CPC_TipoDocumento;
            $tipo_persona = $valor->CLIC_TipoPersona;
            $flag = $valor->CPC_FlagEstado;
            $nombre = '';
            if ($flag == 1) {

                if ($tipo_doc != "F") {
                    $subtotal = number_format($valor->CPC_total / 1.18, 2);
                    $igv = number_format($subtotal * 0.18, 2);
                } else {
                    $igv = $valor->CPC_igv;
                    $subtotal = $valor->CPC_subtotal;
                }
                $total = $valor->CPC_total;
                $valor_venta +=$subtotal;
                $valor_igv +=$igv;
                $valor_total +=$total;


                if ($tipo_doc == 'F')
                    $nombre = 'Factura';
                else
                    $nombre = 'Boleta';
                if ($tipo_persona == '0') {
                    $nombre_cliente = $valor->PERSC_Nombre . " " . $valor->PERSC_ApellidoPaterno . " " . $valor->PERSC_ApellidoMaterno;
                    $ruc = $valor->PERSC_Ruc;
                } else {
                    $nombre_cliente = $valor->EMPRC_RazonSocial;
                    $ruc = $valor->EMPRC_Ruc;
                }
            } else {
                $nombre_cliente = "ANULADO";
                $ruc = "";
                $subtotal = "";
                $igv = "";
                $total = "";
                if ($tipo_doc == 'F')
                    $nombre = 'Factura';
                else
                    $nombre = 'Boleta';
            }

            $db_data[] = array(
                'cols1' => $valor->CPC_Fecha,
                'cols2' => $nombre,
                'cols3' => $valor->CPC_Serie,
                'cols4' => $valor->CPC_Numero,
                'cols5' => $nombre_cliente,
                'cols6' => $ruc,
                'cols7' => $subtotal,
                'cols8' => $igv,
                'cols9' => $total,
            );
        }
        $col_names = array(
            'cols1' => 'Fecha',
            'cols2' => 'Tipo',
            'cols3' => 'Serie',
            'cols4' => 'Numero',
            'cols5' => $titulo_personal,
            'cols6' => 'Ruc',
            'cols7' => 'Valor Venta',
            'cols8' => '   I.G.V      ',
            'cols9' => 'Importe Total',
        );

        $this->cezpdf->ezTable($db_data, $col_names, '', array(
            'width' => 450,
            'showLines' => 1,
            'shaded' => 1,
            'Leading' => 10,
            'showHeadings' => 1,
            'xPos' => 300,
            'fontSize' => 8,
            'cols' => array(
                'cols1' => array('width' => 58, 'justification' => 'center'),
                'cols2' => array('width' => 42, 'justification' => 'left'),
                'cols3' => array('width' => 35, 'justification' => 'left'),
                'cols4' => array('width' => 45, 'justification' => 'left'),
                'cols5' => array('width' => 155, 'justification' => 'center'),
                'cols6' => array('width' => 66, 'justification' => 'left'),
                'cols7' => array('width' => 54, 'justification' => 'left'),
                'cols9' => array('width' => 48, 'justification' => 'left'),
                'cols9' => array('width' => 48, 'justification' => 'left')
            )
        ));

        $db_data = array(
            array(
                'cols1' => '',
                'cols2' => '',
                'cols3' => '',
                'cols4' => '',
                'cols5' => '',
                'cols6' => number_format($valor_venta, 2)
                , 'cols7' => number_format($valor_igv, 2),
                'cols8' => number_format($valor_total, 2)),
        );



        $this->cezpdf->ezText('', '');
        $this->cezpdf->ezTable($db_data, "", "", array(
            'width' => 505,
            'showLines' => 0,
            'shaded' => 20,
            'showHeadings' => 0,
            'xPos' => 'center',
            'fontSize' => 9,
            'cols' => array(
                'cols1' => array('width' => 10, 'justification' => 'left'),
                'cols2' => array('width' => 10, 'justification' => 'left'),
                'cols3' => array('width' => 40, 'justification' => 'left'),
                'cols4' => array('width' => 45, 'justification' => 'left'),
                'cols5' => array('width' => 50, 'justification' => 'left'),
                'cols6' => array('width' => 55, 'justification' => 'left'),
                'cols7' => array('width' => 45, 'justification' => 'left'),
                'cols8' => array('width' => 55, 'justification' => 'left'),
            )
        ));




        $this->cezpdf->ezText('', 8);
        $cabecera = array('Content-Type' => 'application/pdf', 'Content-Disposition' => $tipo_doc . '.pdf', 'Expires' => '0', 'Pragma' => 'cache', 'Cache-Control' => 'private');
        $this->cezpdf->ezStream($cabecera);
    }

     //AQUI EXCEL NUEVO
    public function resumen_ventas_mensual($tipo_oper = "V", $tipo = "", $fecha1 = "", $fecha2 = "", $forma_pago = "", $vendedor = "", $moneda = "", $consolidado="") {
                
        if (isset($tipo) && $tipo!="" && $tipo!="-") {
            $tipo = $tipo;
        }else{
            $tipo = "";
        }
        if (isset($forma_pago) && $forma_pago!="" && $forma_pago!="-") {
            $forma_pago = $forma_pago;
        }else{
            $forma_pago = "";
        }

        if (isset($vendedor) && $vendedor!="" && $vendedor!="-") {
            $vendedor = $vendedor;
        }else{
            $vendedor = "";
        }
        if (isset($moneda) && $moneda!="" && $moneda!="-") {
            $moneda = $moneda;
        }else{
            $moneda = "";
        }
        if (isset($fecha1) && $fecha1!="" && $fecha1!=1) {
            $fecha1 = $fecha1;
        }else{
            $fecha1 = date('Y-m-d');
        }
        if (isset($fecha2) && $fecha2!="" && $fecha2!=1) {
            $fecha2 = $fecha2;
        }else{
            $fecha2 = date('Y-m-d');
        }    
        switch ($tipo_oper) {
            case 'C':
                    $operacion = "COMPRA";
                break;
            case 'V':
                    $operacion = "VENTA";
                break;
            
            default:
                    $operacion = "";
                break;
        }

        $this->load->library('Excel');
        
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle("Resumen De $operacion");
        
        ###########################################
        ######### ESTILOS #########################
        ###########################################
            $estiloTitulo = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 11
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasTitulo = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 10
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'ECF0F1')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );
            $estiloColumnasAnuladoNota = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 10
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'D20505')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasPar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 9
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasImpar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 9
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'DCDCDCDC')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );
            $estiloBold = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 9
                                            )
                                        );

        $fecha_ini = explode("-", $fecha1);
        $fecha_fin = explode("-", $fecha2);
        $fecha_inicio = $fecha_ini[2]."/".$fecha_ini[1]."/".$fecha_ini[0];
        $fecha_final = $fecha_fin[2]."/".$fecha_fin[1]."/".$fecha_fin[0];

        $this->excel->getActiveSheet()->getStyle("A1:O2")->applyFromArray($estiloTitulo);
        $this->excel->getActiveSheet()->getStyle("A3:O3")->applyFromArray($estiloColumnasTitulo);

        $this->excel->setActiveSheetIndex(0)->mergeCells('A1:O2')->setCellValue('A1', $_SESSION['nombre_empresa']);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A3:O3")->setCellValue("A3", "REPORTE DE $operacion del ".$fecha_inicio." hasta el ".$fecha_final);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A4:O4")->setCellValue("A4","USUARIO: $this->nombre_persona");
        $lugar = 5;
        $numeroS = 0;

        $filter = new stdClass();
        $filter->tipo_oper      = $tipo_oper;
        $filter->tipo           = $tipo;
        $filter->fecha1         = $fecha1;
        $filter->fecha2         = $fecha2;
        $filter->forma_pago     = $forma_pago;
        $filter->vendedor       = $vendedor;
        $filter->moneda         = $moneda;
        $filter->consolidado    = $consolidado;

        $resumen = $this->ventas_model->resumen_ventas_mensual($filter);
        
        $this->excel->setActiveSheetIndex(0)->setCellValue("A$lugar", "FECHA EMISION.");
        $this->excel->setActiveSheetIndex(0)->setCellValue("B$lugar", "FECHA VENCIMIENTO");
        $this->excel->setActiveSheetIndex(0)->setCellValue("C$lugar", "TIPO DOC. (01: FACTURA. 03: BOLETA. 07: NOTA CREDITO. 12: TICKET, ETC)");
        $this->excel->setActiveSheetIndex(0)->setCellValue("D$lugar", "SERIE");
        $this->excel->setActiveSheetIndex(0)->setCellValue("E$lugar", "NUMERO");
        $this->excel->setActiveSheetIndex(0)->setCellValue("F$lugar", "TIPO ENTIDAD");
        $this->excel->setActiveSheetIndex(0)->setCellValue("G$lugar", "NUMERO DE DOC. DE ENTIDAD");
        $this->excel->setActiveSheetIndex(0)->setCellValue("H$lugar", "RAZON SOCIAL / APELLIDOS Y NOMBRES");
        $this->excel->setActiveSheetIndex(0)->setCellValue("I$lugar", "MONEDA");
        $this->excel->setActiveSheetIndex(0)->setCellValue("J$lugar", "T/C");
        $this->excel->setActiveSheetIndex(0)->setCellValue("K$lugar", "GRAVADA");
        $this->excel->setActiveSheetIndex(0)->setCellValue("L$lugar", "EXONERADA");
        $this->excel->setActiveSheetIndex(0)->setCellValue("M$lugar", "INAFECTA");
        $this->excel->setActiveSheetIndex(0)->setCellValue("N$lugar", "IGV");
        $this->excel->setActiveSheetIndex(0)->setCellValue("O$lugar", "TOTAL");
        $this->excel->setActiveSheetIndex(0)->setCellValue("P$lugar", "ESTADO");
        $this->excel->getActiveSheet()->getStyle("A$lugar:P$lugar")->applyFromArray($estiloColumnasTitulo);

        if ($resumen != NULL){
            $lugar++;
            foreach($resumen as $indice => $valor){
                $fEmision = explode("-", $valor->CPC_Fecha);
                switch ($valor->CPC_TipoDocumento) {
                    case 'F':
                        $tipoDoc = "01";
                        break;
                    case 'B':
                        $tipoDoc = "03";
                        break;
                    case 'N':
                        $tipoDoc = "00";
                        break;
                    case 'C':
                        $tipoDoc = "07";
                        break;
                    
                    default:
                        $tipoDoc = "00";
                        break;
                }

                if ( $valor->numero_documento_cliente != NULL ){
                    switch ( strlen($valor->numero_documento_cliente) ) {
                        case 11:
                            $tipoDocEntidad = "6";
                            break;
                        case 8:
                            $tipoDocEntidad = "1";
                            break;
                        default:
                            $tipoDocEntidad = "";
                            break;
                    }
                }
                else{
                    switch ( strlen($valor->numero_documento_proveedor) ) {
                        case 11:
                            $tipoDocEntidad = "6";
                            break;
                        case 8:
                            $tipoDocEntidad = "1";
                            break;
                        default:
                            $tipoDocEntidad = "";
                            break;
                    }
                }

                if ( $valor->numero_documento_cliente == "00000009" ){
                    $tipoDocEntidad = "0";
                }

                if($valor->CPC_FlagEstado=="0"){

                    $estado             = "ANULADO";
                    $valor->gravada     = 0;
                    $valor->exonerada   = 0;
                    $valor->inafecta    = 0;
                    $valor->CPC_igv     = 0;
                    $valor->CPC_total   = 0;
                }else{
                    $estado             = "APROBADO";
                }
                $resultado = str_replace("indefinida", "", $valor->razon_social_cliente);
                $resultado2 = str_replace("indefinida", "", $valor->razon_social_proveedor);
                $valor->razon_social_cliente = $resultado;
                $valor->razon_social_proveedor = $resultado2;
                $this->excel->setActiveSheetIndex(0)
                ->setCellValue("A$lugar", $fEmision[2]."/".$fEmision[1]."/".$fEmision[0])
                ->setCellValue("B$lugar", "")
                ->setCellValue("C$lugar", $tipoDoc)
                ->setCellValue("D$lugar", $valor->CPC_Serie)
                ->setCellValue("E$lugar", $valor->CPC_Numero)
                ->setCellValue("F$lugar", $tipoDocEntidad)
                ->setCellValue("G$lugar", $valor->numero_documento_cliente.$valor->numero_documento_proveedor)
                ->setCellValue("H$lugar", $valor->razon_social_cliente.$valor->razon_social_proveedor)
                ->setCellValue("I$lugar", $valor->MONED_Descripcion)
                ->setCellValue("J$lugar", $valor->CPC_TDC)
                ->setCellValue("K$lugar", number_format( $valor->gravada, 2) )
                ->setCellValue("L$lugar", number_format( $valor->exonerada, 2) )
                ->setCellValue("M$lugar", number_format( $valor->inafecta, 2) )
                ->setCellValue("N$lugar", number_format( $valor->CPC_igv, 2) )
                ->setCellValue("O$lugar", number_format( $valor->CPC_total, 2) )
                ->setCellValue("P$lugar", $estado);
                if ($indice % 2 == 0)
                    $this->excel->getActiveSheet()->getStyle("A$lugar:P$lugar")->applyFromArray($estiloColumnasPar);
                else
                    $this->excel->getActiveSheet()->getStyle("A$lugar:P$lugar")->applyFromArray($estiloColumnasImpar);
                
                $lugar++;
            }
            $lugar++;
        }

        $this->excel->getActiveSheet()->getColumnDimension("A")->setWidth("12");
        $this->excel->getActiveSheet()->getColumnDimension("B")->setWidth("12");
        $this->excel->getActiveSheet()->getColumnDimension("C")->setWidth("12");
        $this->excel->getActiveSheet()->getColumnDimension("D")->setWidth("10");
        $this->excel->getActiveSheet()->getColumnDimension("E")->setWidth("10");
        $this->excel->getActiveSheet()->getColumnDimension("F")->setWidth("10");
        $this->excel->getActiveSheet()->getColumnDimension("G")->setWidth("12");
        $this->excel->getActiveSheet()->getColumnDimension("H")->setWidth("40");
        $this->excel->getActiveSheet()->getColumnDimension("I")->setWidth("10");
        $this->excel->getActiveSheet()->getColumnDimension("J")->setWidth("10");
        $this->excel->getActiveSheet()->getColumnDimension("K")->setWidth("10");
        $this->excel->getActiveSheet()->getColumnDimension("L")->setWidth("12");
        $this->excel->getActiveSheet()->getColumnDimension("M")->setWidth("10");
        $this->excel->getActiveSheet()->getColumnDimension("N")->setWidth("10");
        $this->excel->getActiveSheet()->getColumnDimension("O")->setWidth("10");
        $this->excel->getActiveSheet()->getColumnDimension("P")->setWidth("10");

      
        
        $filename = "Reporte de $operacion de ".$valor->CPC_Fecha.".xls"; //save our workbook as this file name
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=$filename");
        header("Cache-Control: max-age=0"); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        // Forzamos a la descarga
        $objWriter->save('php://output');
    }

    /*public function registro_ventas_excel($tipo_oper, $tipo_doc = "F", $fecha1, $fecha2) {
        if ($tipo_oper == 'V') {
            $titulo_personal = 'Cliente';
            if ($tipo_doc == "F")
                $titulo = "REGISTRO DE  VENTAS FACTURAS";
            if ($tipo_doc == "B")
                $titulo = "REPORTE DE  VENTAS BOLETAS";
            if ($tipo_doc == "N")
                $titulo = "REPORTE DE  VENTAS COMPROBANTES";
        }

        else {
            $titulo_personal = 'Proveedor';
            if ($tipo_doc == "F")
                $titulo = "REGISTRO DE  COMPRAS FACTURAS";
            if ($tipo_doc == "B")
                $titulo = "REPORTE DE  COMPRAS BOLETAS";
            if ($tipo_doc == "N")
                $titulo = "REPORTE DE  COMPRAS COMPROBANTES";
        }
        $this->load->library("PHPExcel");

        $phpExcel = new PHPExcel();
        $prestasi = $phpExcel->setActiveSheetIndex(0);
        //merger
        $phpExcel->getActiveSheet()->mergeCells('A1:J1');
        //manage row hight
        $phpExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
        //style alignment
        $styleArray = array(
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
        );
        $phpExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleArray);
        //border
        $styleArray1 = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        //background
        $styleArray12 = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                    'rgb' => 'FFEC8B',
                ),
            ),
        );
        //freeepane
        $phpExcel->getActiveSheet()->freezePane('A5');
        //coloum width
        $phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $phpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $prestasi->setCellValue('A1', $titulo);
        if ($tipo_oper == 'V') {
            $phpExcel->getActiveSheet()->getStyle('A2:V4')->applyFromArray($styleArray12);


            $phpExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('A2:A4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('A2:A4');
            $prestasi->setCellValue('A2', 'N??mero Correlativo del Registro o C??digo unico de la operaci??n');

            $phpExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('B2:B4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('B2:B4');
            $prestasi->setCellValue('B2', 'Fecha de emisi??n del comprobante de pago o documento.');

            $phpExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('C2:C4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('C2:C4');
            $prestasi->setCellValue('C2', 'Fecha de vencimiento y/o pago.');


            $phpExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('D2:F2')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('D2:F2');
            $prestasi->setCellValue('D2', 'Comprobante de Pago o Documento');

            $phpExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('D3:D4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('D3:D4');
            $prestasi->setCellValue('D3', 'Tipo (Tabla 10)');

            $phpExcel->getActiveSheet()->getStyle('E3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('E3:E4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('E3:E4');
            $prestasi->setCellValue('E3', 'N?? de serie o N?? de serie de la maquina registradora');

            $phpExcel->getActiveSheet()->getStyle('F3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('F3:F4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('F3:F4');
            $prestasi->setCellValue('F3', 'N??mero');


            $phpExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('G2:I2')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('G2:I2');
            $prestasi->setCellValue('G2', 'Informaci??n del Cliente');

            $phpExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('G3:H3')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('G3:H3');
            $prestasi->setCellValue('G3', 'Documento de Identidad');

            $phpExcel->getActiveSheet()->getStyle('G4')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('G4')->applyFromArray($styleArray1);
            $prestasi->setCellValue('G4', 'Tipo (Tabla 2)');

            $phpExcel->getActiveSheet()->getStyle('H4')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('H4')->applyFromArray($styleArray1);
            $prestasi->setCellValue('H4', 'N??mero');

            $phpExcel->getActiveSheet()->getStyle('I3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('I3:I4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('I3:I4');
            $prestasi->setCellValue('I3', 'Apellidos y Nombres, Denominaci??n o Raz??n Social');


            $phpExcel->getActiveSheet()->getStyle('J2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('J2:J4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('J2:J4');
            $prestasi->setCellValue('J2', 'Valor Facturado de la Exportaci??n');

            $phpExcel->getActiveSheet()->getStyle('K2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('K2:K4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('K2:K4');
            $prestasi->setCellValue('K2', 'Base imponible de la operaci??n grabada');

            $phpExcel->getActiveSheet()->getStyle('L2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('L2:N2')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('L2:N2');
            $prestasi->setCellValue('L2', 'Importe Total de la Operaci??n Exonerada o Inafecta');

            $phpExcel->getActiveSheet()->getStyle('L3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('L3:L4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('L3:L4');
            $prestasi->setCellValue('L3', 'Exonerada');

            $phpExcel->getActiveSheet()->getStyle('M3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('M3:M4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('M3:M4');
            $prestasi->setCellValue('M3', 'Inafecta');

            $phpExcel->getActiveSheet()->getStyle('N3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('N3:N4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('N3:N4');
            $prestasi->setCellValue('N3', 'ISC');

            $phpExcel->getActiveSheet()->getStyle('O2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('O2:O4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('O2:O4');
            $prestasi->setCellValue('O2', 'IGV Y/O IPM');

            $phpExcel->getActiveSheet()->getStyle('P2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('P2:P4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('P2:P4');
            $prestasi->setCellValue('P2', 'OTROS TRIBUTOS Y CARGOS QUE NO FORMAN PARTE DE LA BASE IMPONIBLE');

            $phpExcel->getActiveSheet()->getStyle('Q2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('Q2:Q4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('Q2:Q4');
            $prestasi->setCellValue('Q2', 'IMPORTE TOTAL DEL COMPROBANTE DE PAGO');

            $phpExcel->getActiveSheet()->getStyle('R2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('R2:R4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('R2:R4');
            $prestasi->setCellValue('R2', 'TIPO DE CAMBIO');

            $phpExcel->getActiveSheet()->getStyle('S2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('S2:V2')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('S2:V2');
            $prestasi->setCellValue('S2', 'REFERENCIA DEL COMPROBANTE DE PAGO O DOCUMENTO ORIGINAL QUE SE MODIFICA');

            $phpExcel->getActiveSheet()->getStyle('S3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('S3:S4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('S3:S4');
            $prestasi->setCellValue('S3', 'FECHA');

            $phpExcel->getActiveSheet()->getStyle('T3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('T3:T4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('T3:T4');
            $prestasi->setCellValue('T3', 'TIPO TABLA(10)');

            $phpExcel->getActiveSheet()->getStyle('U3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('U3:U4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('U3:U4');
            $prestasi->setCellValue('U3', 'SERIE');

            $phpExcel->getActiveSheet()->getStyle('V3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('V3:V4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('V3:V4');
            $prestasi->setCellValue('V3', 'N?? DEL COMPROBATE DE PAGO O DOCUMENTO');
        } else {
            $phpExcel->getActiveSheet()->getStyle('A2:AB4')->applyFromArray($styleArray12);


            $phpExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('A2:A4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('A2:A4');
            $prestasi->setCellValue('A2', 'N??mero Correlativo del Registro o C??digo unico de la operaci??n');

            $phpExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('B2:B4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('B2:B4');
            $prestasi->setCellValue('B2', 'Fecha de emisi??n del comprobante de pago o documento.');

            $phpExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('C2:C4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('C2:C4');
            $prestasi->setCellValue('C2', 'Fecha de vencimiento y/o pago.');


            $phpExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('D2:F2')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('D2:F2');
            $prestasi->setCellValue('D2', 'Comprobante de Pago o Documento');

            $phpExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('D3:D4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('D3:D4');
            $prestasi->setCellValue('D3', 'Tipo (Tabla 10)');

            $phpExcel->getActiveSheet()->getStyle('E3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('E3:E4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('E3:E4');
            $prestasi->setCellValue('E3', 'SERIE O CODIGO DE LA DEPENDENCIA ADUANERA (TABLA11)');

            $phpExcel->getActiveSheet()->getStyle('F3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('F3:F4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('F3:F4');
            $prestasi->setCellValue('F3', 'A??O DE EMISION DE LA DUA O DSI');

            $phpExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('G2:G4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('G2:G4');
            $prestasi->setCellValue('G2', ' N?? DEL COMPROBANTE DE PAGO,
                                            DOCUMENTO, N?? DE ORDEN DEL
                                           FORMULARIO F?SICO O VIRTUAL, 
                                          N?? DE DUA, DSI O LIQUIDACI??N DE 
                                         COBRANZA U OTROS DOCUMENTOS 
                                      EMITIDOS POR SUNAT PARA ACREDITAR 
                                      EL CR??DITO FISCAL EN LA IMPORTACI??N
                                     ');

            $phpExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('H2:J2')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('H2:J2');
            $prestasi->setCellValue('H2', 'INFORMACI??N DEL PROVEEDOR');

            $phpExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('H3:I3')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('H3:I3');
            $prestasi->setCellValue('H3', 'Documento de Identidad');

            $phpExcel->getActiveSheet()->getStyle('H4')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('H4')->applyFromArray($styleArray1);
            $prestasi->setCellValue('H4', 'TIPO (TABLA 2)');

            $phpExcel->getActiveSheet()->getStyle('I4')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('I4')->applyFromArray($styleArray1);
            $prestasi->setCellValue('I4', 'N??MERO');

            $phpExcel->getActiveSheet()->getStyle('J3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('J3:J4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('J3:J4');
            $prestasi->setCellValue('J3', 'APELLIDOS Y NOMBRES, DENOMINACION SOCIAL O RAZON SOCIAL');

            $phpExcel->getActiveSheet()->getStyle('K2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('K2:L2')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('K2:L2');
            $prestasi->setCellValue('K2', ' ADQUISICIONES GRAVADAS DESTINADAS A OPERACIONES 
             GRAVADAS Y/O DE EXPORTACI??N');

            $phpExcel->getActiveSheet()->getStyle('K3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('K3:K4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('K3:K4');
            $prestasi->setCellValue('K3', 'BASE IMPONIBLE');

            $phpExcel->getActiveSheet()->getStyle('L3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('L3:L4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('L3:L4');
            $prestasi->setCellValue('L3', 'IGV');


            $phpExcel->getActiveSheet()->getStyle('M2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('M2:N2')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('M2:N2');
            $prestasi->setCellValue('M2', ' ADQUISICIONES GRAVADAS DESTINADAS A OPERACIONES 
            GRAVADAS Y/O DE EXPORTACI??N Y A OPERACIONES NO GRAVADAS');

            $phpExcel->getActiveSheet()->getStyle('M3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('M3:M4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('M3:M4');
            $prestasi->setCellValue('M3', 'BASE IMPONIBLE');

            $phpExcel->getActiveSheet()->getStyle('N3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('N3:N4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('N3:N4');
            $prestasi->setCellValue('N3', 'IGV');


            $phpExcel->getActiveSheet()->getStyle('O2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('O2:P2')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('O2:P2');
            $prestasi->setCellValue('O2', ' ADQUISICIONES GRAVADAS DESTINADAS A OPERACIONES NO GRAVADAS');

            $phpExcel->getActiveSheet()->getStyle('O3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('O3:O4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('O3:O4');
            $prestasi->setCellValue('O3', 'BASE IMPONIBLE');

            $phpExcel->getActiveSheet()->getStyle('P3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('P3:P4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('P3:P4');
            $prestasi->setCellValue('P3', 'IGV');

            $phpExcel->getActiveSheet()->getStyle('Q2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('Q2:Q4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('Q2:Q4');
            $prestasi->setCellValue('Q2', 'VALOR DE LAS ADQUISICIONES NO GRAVADAS');

            $phpExcel->getActiveSheet()->getStyle('R2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('R2:R4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('R2:R4');
            $prestasi->setCellValue('R2', 'ISC');

            $phpExcel->getActiveSheet()->getStyle('S2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('S2:S4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('S2:S4');
            $prestasi->setCellValue('S2', 'OTROS TRIBUTOS Y CARGOS');

            $phpExcel->getActiveSheet()->getStyle('T2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('T2:T4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('T2:T4');
            $prestasi->setCellValue('T2', 'IMPORTE TOTAL');

            $phpExcel->getActiveSheet()->getStyle('U2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('U2:U4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('U2:U4');
            $prestasi->setCellValue('U2', 'N?? DE COMPROBANTE DE PAGO EMITIDO POR SUJETO NO DOMICILIADO (2)');

            $phpExcel->getActiveSheet()->getStyle('V2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('V2:W2')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('V2:W2');
            $prestasi->setCellValue('V2', 'CONSTANCIA DE DEP??SITO DE DETRACCI??N (3)');

            $phpExcel->getActiveSheet()->getStyle('V3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('V3:V4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('V3:V4');
            $prestasi->setCellValue('V3', 'NUMERO');

            $phpExcel->getActiveSheet()->getStyle('W3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('W3:W4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('W3:W4');
            $prestasi->setCellValue('W3', 'FECHA DE EMISION');

            $phpExcel->getActiveSheet()->getStyle('X2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('X2:X4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('X2:X4');
            $prestasi->setCellValue('X2', 'TIPO DE CAMBIO');

            $phpExcel->getActiveSheet()->getStyle('Y2')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('Y2:AB2')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('Y2:AB2');
            $prestasi->setCellValue('Y2', 'REFERENCIA DEL COMPROBANTE DE PAGO O DOCUMENTO ORIGINAL QUE SE MODIFICA');

            $phpExcel->getActiveSheet()->getStyle('Y3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('Y3:Y4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('Y3:Y4');
            $prestasi->setCellValue('Y3', 'FECHA');

            $phpExcel->getActiveSheet()->getStyle('Z3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('Z3:Z4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('Z3:Z4');
            $prestasi->setCellValue('Z3', 'TIPO (TABLA 10)');

            $phpExcel->getActiveSheet()->getStyle('AA3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('AA3:AA4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('AA3:AA4');
            $prestasi->setCellValue('AA3', 'SERIE');

            $phpExcel->getActiveSheet()->getStyle('AB3')->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle('AB3:AB4')->applyFromArray($styleArray1);
            $phpExcel->getActiveSheet()->mergeCells('AB3:AB4');
            $prestasi->setCellValue('AB3', 'N?? DEL COMPROBANTE DE PAGO O DOCUMENTO');
        }
        $lista = $this->ventas_model->registro_ventas($tipo_oper, $tipo_doc, $fecha1, $fecha2);

        $no = 0;
        $rowexcel = 4;
        $valor_venta = 0;
        $valor_igv = 0;
        $valor_total = 0;
        foreach ($lista as $indice => $valor) {
            $tipo = $valor->CPC_TipoDocumento;
            $tipo_persona = $valor->CLIC_TipoPersona;
            $flag = $valor->CPC_FlagEstado;
            $nombre = '';
            if ($flag == 1) {

                if ($tipo_doc != "F") {
                    $subtotal = number_format($valor->CPC_total / 1.18, 2);
                    $igv = number_format($subtotal * 0.18, 2);
                } else {
                    $igv = $valor->CPC_igv;
                    $subtotal = $valor->CPC_subtotal;
                }
                $total = $valor->CPC_total;
                $valor_venta +=$subtotal;
                $valor_igv +=$igv;
                $valor_total +=$total;


                if ($tipo_doc == 'F')
                    $nombre = 'Factura';
                else
                    $nombre = 'Boleta';
                if ($tipo_persona == '0') {
                    $doc = 'DNI';
                    $nombre_cliente = $valor->PERSC_Nombre . " " . $valor->PERSC_ApellidoPaterno . " " . $valor->PERSC_ApellidoMaterno;
                    $ruc = $valor->PERSC_Ruc;
                } else {
                    $doc = 'RUC';
                    $nombre_cliente = $valor->EMPRC_RazonSocial;
                    $ruc = $valor->EMPRC_Ruc;
                }
            } else {
                $nombre_cliente = "ANULADO";
                $ruc = "";
                $subtotal = "";
                $igv = "";
                $total = "";
                if ($tipo_doc == 'F')
                    $nombre = 'Factura';
                else
                    $nombre = 'Boleta';
            }

            $no++;
            $rowexcel++;

            if ($tipo_oper == 'V') {
                $prestasi->setCellValue('A' . $rowexcel, $no);
                $prestasi->setCellValue('B' . $rowexcel, $valor->CPC_Fecha);
                $prestasi->setCellValue('D' . $rowexcel, $nombre);
                $prestasi->setCellValue('E' . $rowexcel, (int) $valor->CPC_Serie);
                $prestasi->setCellValue('F' . $rowexcel, (int) $valor->CPC_Numero);
                $prestasi->setCellValue('G' . $rowexcel, $doc);
                $prestasi->setCellValue('H' . $rowexcel, $ruc);
                $prestasi->setCellValue('I' . $rowexcel, $nombre_cliente);
                $prestasi->setCellValue('O' . $rowexcel, $igv);
                $prestasi->setCellValue('Q' . $rowexcel, $total);
            } else {
                $prestasi->setCellValue('A' . $rowexcel, $no);
                $prestasi->setCellValue('B' . $rowexcel, $valor->CPC_Fecha);
                $prestasi->setCellValue('D' . $rowexcel, $nombre);
                $prestasi->setCellValue('E' . $rowexcel, (int) $valor->CPC_Serie);
                $prestasi->setCellValue('G' . $rowexcel, (int) $valor->CPC_Numero);
                $prestasi->setCellValue('H' . $rowexcel, $doc);
                $prestasi->setCellValue('I' . $rowexcel, $ruc);
                $prestasi->setCellValue('J' . $rowexcel, $nombre_cliente);
                $prestasi->setCellValue('P' . $rowexcel, $igv);
                $prestasi->setCellValue('T' . $rowexcel, $total);
            }
        }

        $prestasi->setTitle('ReportE');
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"Report.xls\"");
        header("Cache-Control: max-age=0");
        $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
        $objWriter->save("php://output");
    }*/

    public function registro_ventas_excel2($tipo_oper, $tipo_doc = "F", $fecha1, $fecha2) {
        if ($tipo_oper == 'V') {
            $titulo_personal = 'Cliente';
            if ($tipo_doc == "F")
                $titulo = "REGISTRO DE  VENTAS FACTURAS";
            if ($tipo_doc == "B")
                $titulo = "REPORTE DE  VENTAS BOLETAS";
            if ($tipo_doc == "N")
                $titulo = "REPORTE DE  VENTAS COMPROBANTES";
        }

        else {
            $titulo_personal = 'Proveedor';
            if ($tipo_doc == "F")
                $titulo = "REGISTRO DE  COMPRAS FACTURAS";
            if ($tipo_doc == "B")
                $titulo = "REPORTE DE  COMPRAS BOLETAS";
            if ($tipo_doc == "N")
                $titulo = "REPORTE DE  COMPRAS COMPROBANTES";
        }
        $this->load->library("PHPExcel");

        $phpExcel = new PHPExcel();
        $prestasi = $phpExcel->setActiveSheetIndex(0);
        //merger
        $phpExcel->getActiveSheet()->mergeCells('A1:J1');
        //manage row hight
        $phpExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
        //style alignment
        $styleArray = array(
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
        );
        $phpExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleArray);
        //border
        $styleArray1 = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        //background
        $styleArray12 = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                    'rgb' => 'FFEC8B',
                ),
            ),
        );
        //freeepane
        $phpExcel->getActiveSheet()->freezePane('A3');
        //coloum width
        $phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6.1);
        $phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $phpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $prestasi->setCellValue('A1', $titulo);
        $phpExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($styleArray);
        $phpExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($styleArray1);
        $phpExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($styleArray12);
        $prestasi->setCellValue('A2', 'No');
        $prestasi->setCellValue('B2', 'Fecha');
        $prestasi->setCellValue('C2', 'Tipo');
        $prestasi->setCellValue('D2', 'Serie');
        $prestasi->setCellValue('E2', 'Numero');
        $prestasi->setCellValue('F2', $titulo_personal);
        $prestasi->setCellValue('G2', 'Ruc');
        $prestasi->setCellValue('H2', 'Valor Venta');
        $prestasi->setCellValue('I2', 'I.G.V');
        $prestasi->setCellValue('J2', 'Importe Total');



        $lista = $this->ventas_model->registro_ventas($tipo_oper, $tipo_doc, $fecha1, $fecha2);

        $no = 0;
        $rowexcel = 2;
        $valor_venta = 0;
        $valor_igv = 0;
        $valor_total = 0;
        foreach ($lista as $indice => $valor) {
            $tipo = $valor->CPC_TipoDocumento;
            $tipo_persona = $valor->CLIC_TipoPersona;
            $flag = $valor->CPC_FlagEstado;
            $nombre = '';
            if ($flag == 1) {

                if ($tipo_doc != "F") {
                    $subtotal = number_format($valor->CPC_total / 1.18, 2);
                    $igv = number_format($subtotal * 0.18, 2);
                } else {
                    $igv = $valor->CPC_igv;
                    $subtotal = $valor->CPC_subtotal;
                }
                $total = $valor->CPC_total;
                $valor_venta +=$subtotal;
                $valor_igv +=$igv;
                $valor_total +=$total;


                if ($tipo_doc == 'F')
                    $nombre = 'Factura';
                else
                    $nombre = 'Boleta';
                if ($tipo_persona == '0') {
                    $nombre_cliente = $valor->PERSC_Nombre . " " . $valor->PERSC_ApellidoPaterno . " " . $valor->PERSC_ApellidoMaterno;
                    $ruc = $valor->PERSC_Ruc;
                } else {
                    $nombre_cliente = $valor->EMPRC_RazonSocial;
                    $ruc = $valor->EMPRC_Ruc;
                }
            } else {
                $nombre_cliente = "ANULADO";
                $ruc = "";
                $subtotal = "";
                $igv = "";
                $total = "";
                if ($tipo_doc == 'F')
                    $nombre = 'Factura';
                else
                    $nombre = 'Boleta';
            }

            $no++;
            $rowexcel++;

            $prestasi->setCellValue('A' . $rowexcel, $no);
            $prestasi->setCellValue('B' . $rowexcel, $valor->CPC_Fecha);
            $prestasi->setCellValue('C' . $rowexcel, $nombre);
            $prestasi->setCellValue('D' . $rowexcel, $valor->CPC_Serie);
            $prestasi->setCellValue('E' . $rowexcel, $valor->CPC_Numero);
            $prestasi->setCellValue('F' . $rowexcel, $nombre_cliente);
            $prestasi->setCellValue('G' . $rowexcel, $ruc);
            $prestasi->setCellValue('H' . $rowexcel, $subtotal);
            $prestasi->setCellValue('I' . $rowexcel, $igv);
            $prestasi->setCellValue('J' . $rowexcel, $total);
        }

        $prestasi->setTitle('ReportE');
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"Report.xls\"");
        header("Cache-Control: max-age=0");
        $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
        $objWriter->save("php://output");
    }

    public function ganancia($value='')
    {
        $this->load->library('layout', 'layout');
        $data['titulo_tabla'] = "REPORTE DE GANANCIAS";
        $lista_companias = $this->compania_model->listar_establecimiento($this->somevar['empresa']);
        foreach ($lista_companias as $key => $compania) {
            if (count($_POST) > 0) {
                if ($this->input->post('COMPANIA_' . $compania->COMPP_Codigo) > 0) {
                    $comp_select[] = $compania->COMPP_Codigo;
                    $lista_companias[$key]->checked = true;
                }
                else
                    $lista_companias[$key]->checked = false;
            }else {
                $comp_select[] = $compania->COMPP_Codigo;
                $lista_companias[$key]->checked = true;
            }
        }
        $data['TODOS'] = $this->input->post('TODOS') == '1' ? true : false;
        $data['lista_companias'] = $lista_companias;
        $data['moneda'] = $this->moneda_model->listar();
        $data['oculto'] = form_hidden(array('base_url' => base_url()));
        
        $this->layout->view('reportes/ganancia', $data);
    }

    public function datatable_ganancia(){

        $columnas = array(
            0 => "",
            1 => "",
            2 => "",
            3 => "",
            4 => "",
            5 => "",
            6 => "",
            7 => ""
        );
            
        $filter = new stdClass();
        $filter->start  = $this->input->post("start");
        $filter->length = $this->input->post("length");
        $filter->search = $this->input->post("search")["value"];

        $ordenar = $this->input->post("order")[0]["column"];
        if ($ordenar != ""){
            $filter->order  = $columnas[$ordenar];
            $filter->dir    = $this->input->post("order")[0]["dir"];
        }

        $item = ($this->input->post("start") != "") ? $this->input->post("start") : 0;

        $filter->moneda     = $this->input->post('moneda');
        $filter->fechai     = ($this->input->post('fechai') != "") ? $this->input->post('fechai') : date('y-m-d');
        $filter->fechaf     = ($this->input->post('fechaf') != "") ? $this->input->post('fechaf') : date('y-m-d');
        $filter->producto   = $this->input->post('producto');
        $filter->compania   = $this->input->post('companias');


        $lista_companias = $this->compania_model->listar_establecimiento($this->somevar['empresa']);
        $lista_ganancia = $this->comprobantedetalle_model->reporte_ganancia($filter);
        
        $lista = array();
        $resumen_compania = array();
        foreach ($lista_ganancia as $value) {
            $fecha = mysql_to_human($value->CPC_Fecha);
            $establec = $value->EESTABC_Descripcion;
            $nombre_producto = $value->PROD_Nombre;
            $cantidad = $value->CPDEC_Cantidad;
            $simbolo_moneda = $value->MONED_Simbolo;
            $pcosto = $value->ALMALOTC_Costo;
            $pventa = $value->CPDEC_Pu_ConIgv;
            $costo = $pcosto * $value->CPDEC_Cantidad;
            $venta = $pventa * $value->CPDEC_Cantidad;
            $total_costo+=$costo;
            $total_venta+=$venta;
            $utilidad = $venta - $costo;
            $porc_util = $costo != 0 ? ($utilidad / $costo) * 100 : 100;
            $resumen_compania[$value->COMPP_Codigo] = array('costo' => isset($resumen_compania[$value->COMPP_Codigo]['costo']) ? $resumen_compania[$value->COMPP_Codigo]['costo'] + $costo : $costo,
            'venta' => isset($resumen_compania[$value->COMPP_Codigo]['venta']) ? $resumen_compania[$value->COMPP_Codigo]['venta'] + $venta : $venta
            );

            $lote_numero = $value->LOTC_Numero;
            $lote_fv = mysql_to_human($value->LOTC_FechaVencimiento);
           // $lista[] = array($fecha, $establec, $nombre_producto, $lote_numero, $lote_fv, $cantidad, $simbolo_moneda, number_format($pcosto, 2), number_format($pventa, 2), number_format($costo, 2), number_format($venta, 2), number_format($utilidad, 2), round($porc_util));

            $posDT=-1;
            $lista[] = array(
                    ++$posDT => $fecha,
                    ++$posDT => $establec,
                    ++$posDT => $nombre_producto,
                    ++$posDT => $cantidad,
                    ++$posDT => $simbolo_moneda,
                    ++$posDT => number_format($pcosto, 2),
                    ++$posDT => number_format($pventa, 2),
                    ++$posDT => number_format($costo, 2),
                    ++$posDT => number_format($venta, 2),
                    ++$posDT => number_format($utilidad, 2),
                    ++$posDT => round($porc_util)."%"
                );
        }
        $total_util = $total_venta - $total_costo;
        $total_porc_util = $total_costo != 0 ? ($total_util / $total_costo) * 100 : 0;

        /* Resumen por compania */
        $t_resumen_costo = 0;
        $t_resumen_venta = 0;
        foreach ($lista_companias as $key => $compania) {
            if (isset($resumen_compania[$compania->COMPP_Codigo])) {
                $st_costo = $resumen_compania[$compania->COMPP_Codigo]['costo'];
                $st_venta = $resumen_compania[$compania->COMPP_Codigo]['venta'];
                $resumen_compania[$compania->COMPP_Codigo]['costo'] = $resumen_compania[$compania->COMPP_Codigo]['costo'] > 0 ? number_format($resumen_compania[$compania->COMPP_Codigo]['costo'], 2) : 0;
                $resumen_compania[$compania->COMPP_Codigo]['venta'] = $resumen_compania[$compania->COMPP_Codigo]['venta'] > 0 ? number_format($resumen_compania[$compania->COMPP_Codigo]['venta'], 2) : 0;
            } else {
                $resumen_compania[$compania->COMPP_Codigo]['costo'] = $st_costo = 0;
                $resumen_compania[$compania->COMPP_Codigo]['venta'] = $st_venta = 0;
            }
            $resumen_compania[$compania->COMPP_Codigo]['util'] = $st_venta - $st_costo;
            $resumen_compania[$compania->COMPP_Codigo]['porc'] = round($st_costo != 0 ? (($st_venta - $st_costo) / $st_costo) * 100 : 0, 2);
            $t_resumen_costo+=$st_costo;
            $t_resumen_venta+=$st_venta;
        }
        $t_resumen_util = $t_resumen_venta - $t_resumen_costo;
        $t_resumen_porc = $t_resumen_costo != 0 ? ($t_resumen_util / $t_resumen_costo) * 100 : 0;
        $detalles =array();
        foreach($lista_companias as $indice => $value){
            $detalles[$indice]=array('compania'=>$value->EESTABC_Descripcion,'costototal'=>$resumen_compania[$value->COMPP_Codigo]['costo'],
                'ventatotal'=>$resumen_compania[$value->COMPP_Codigo]['venta'],
                'utilidad'=>$resumen_compania[$value->COMPP_Codigo]['util'],
                'proc_utilidad'=>$resumen_compania[$value->COMPP_Codigo]['porc']
                );

        }




        unset($filter->start);
        unset($filter->length);

        $json = array(
                "draw"            => intval( $this->input->post('draw') ),
                "recordsTotal"    => count($this->comprobantedetalle_model->reporte_ganancia()),
                "recordsFiltered" => intval( count($this->comprobantedetalle_model->reporte_ganancia($filter)) ),
                "data"            => $lista,
                "detalles"        => $detalles
        );

        echo json_encode($json);
    }



    public function ganancia_old() {
        $this->load->library('layout', 'layout');
        $lista = '';
        $total_soles = '';
        $total_dolares = '';
        $resumen_suma = '';
        $resumen_suma_d = '';
        $resumen_cantidad = '';
        $resumen_fpago = '';
        $producto = $this->input->post('producto');
        $f_ini = $this->input->post('fecha_inicio') != '' ? $this->input->post('fecha_inicio') : '01/' . date('m/Y');
        $f_fin = $this->input->post('fecha_fin') != '' ? $this->input->post('fecha_fin') : date('d/m/Y');
        $moneda = $this->input->post('moneda') != '' ? $this->input->post('moneda') : '1';

        $comp_select = array();
        $lista_companias = $this->compania_model->listar_establecimiento($this->somevar['empresa']);
        foreach ($lista_companias as $key => $compania) {
            if (count($_POST) > 0) {
                if ($this->input->post('COMPANIA_' . $compania->COMPP_Codigo) > 0) {
                    $comp_select[] = $compania->COMPP_Codigo;
                    $lista_companias[$key]->checked = true;
                }
                else
                    $lista_companias[$key]->checked = false;
            }else {
                $comp_select[] = $compania->COMPP_Codigo;
                $lista_companias[$key]->checked = true;
            }
        }

        $total_costo = 0;
        $total_venta = 0;
        $total_util  = 0;
        $total_porc_util = 0;
        $lista_ganancia = $this->comprobantedetalle_model->reporte_ganancia($producto, human_to_mysql($f_ini), human_to_mysql($f_fin), $comp_select);
        $lista = array();
        $resumen_compania = array();
        foreach ($lista_ganancia as $value) {
            $fecha = mysql_to_human($value->CPC_Fecha);
            $establec = $value->EESTABC_Descripcion;
            $nombre_producto = $value->PROD_Nombre;
            $cantidad = $value->CPDEC_Cantidad;
            $simbolo_moneda = $value->MONED_Simbolo;
            $pcosto = $value->ALMALOTC_Costo;
            $pventa = $value->CPDEC_Pu_ConIgv;
            $costo = $pcosto * $value->CPDEC_Cantidad;
            $venta = $pventa * $value->CPDEC_Cantidad;
            $total_costo+=$costo;
            $total_venta+=$venta;
            $utilidad = $venta - $costo;
            $porc_util = $costo != 0 ? ($utilidad / $costo) * 100 : 0;
            $resumen_compania[$value->COMPP_Codigo] = array('costo' => isset($resumen_compania[$value->COMPP_Codigo]['costo']) ? $resumen_compania[$value->COMPP_Codigo]['costo'] + $costo : $costo,
                'venta' => isset($resumen_compania[$value->COMPP_Codigo]['venta']) ? $resumen_compania[$value->COMPP_Codigo]['venta'] + $venta : $venta
            );

            $lote_numero = $value->LOTC_Numero;
            $lote_fv = mysql_to_human($value->LOTC_FechaVencimiento);
            $lista[] = array($fecha, $establec, $nombre_producto, $lote_numero, $lote_fv, $cantidad, $simbolo_moneda, number_format($pcosto, 2), number_format($pventa, 2), number_format($costo, 2), number_format($venta, 2), number_format($utilidad, 2), round($porc_util));
        }

        $total_util = $total_venta - $total_costo;
        $total_porc_util = $total_costo != 0 ? ($total_util / $total_costo) * 100 : 0;

        /* Resumen por compania */
        $t_resumen_costo = 0;
        $t_resumen_venta = 0;
        foreach ($lista_companias as $key => $compania) {
            if (isset($resumen_compania[$compania->COMPP_Codigo])) {
                $st_costo = $resumen_compania[$compania->COMPP_Codigo]['costo'];
                $st_venta = $resumen_compania[$compania->COMPP_Codigo]['venta'];
                $resumen_compania[$compania->COMPP_Codigo]['costo'] = $resumen_compania[$compania->COMPP_Codigo]['costo'] > 0 ? number_format($resumen_compania[$compania->COMPP_Codigo]['costo'], 2) : 0;
                $resumen_compania[$compania->COMPP_Codigo]['venta'] = $resumen_compania[$compania->COMPP_Codigo]['venta'] > 0 ? number_format($resumen_compania[$compania->COMPP_Codigo]['venta'], 2) : 0;
            } else {
                $resumen_compania[$compania->COMPP_Codigo]['costo'] = $st_costo = 0;
                $resumen_compania[$compania->COMPP_Codigo]['venta'] = $st_venta = 0;
            }
            $resumen_compania[$compania->COMPP_Codigo]['util'] = $st_venta - $st_costo;
            $resumen_compania[$compania->COMPP_Codigo]['porc'] = round($st_costo != 0 ? (($st_venta - $st_costo) / $st_costo) * 100 : 0, 2);
            $t_resumen_costo+=$st_costo;
            $t_resumen_venta+=$st_venta;
        }
        $t_resumen_util = $t_resumen_venta - $t_resumen_costo;
        $t_resumen_porc = $t_resumen_costo != 0 ? ($t_resumen_util / $t_resumen_costo) * 100 : 0;

        $data['producto'] = $producto;
        $data['codproducto'] = $this->input->post('codproducto');
        $data['nombre_producto'] = $this->input->post('nombre_producto');
        $data['f_ini'] = $f_ini;
        $data['f_fin'] = $f_fin;
        $data['TODOS'] = $this->input->post('TODOS') == '1' ? true : false;
        $data['lista_companias'] = $lista_companias;
        $data['cboMoneda'] = form_dropdown("moneda", $this->moneda_model->seleccionar(), $moneda, " class='comboMedio cajaSoloLectura' disabled id='moneda' style='width:150px'");
        $data['lista'] = $lista;
        $data['total_costo'] = number_format($total_costo, 2);
        $data['total_venta'] = number_format($total_venta, 2);
        $data['total_util'] = number_format($total_util, 2);
        $data['total_porc_util'] = round($total_porc_util, 2);
        $data['resumen_compania'] = $resumen_compania;
        $data['t_resumen_costo'] = number_format($t_resumen_costo, 2);
        $data['t_resumen_venta'] = number_format($t_resumen_venta, 2);
        $data['t_resumen_util'] = number_format($t_resumen_util, 2);
        $data['t_resumen_porc'] = round($t_resumen_porc, 2);
        $data['oculto'] = form_hidden(array('base_url' => base_url()));
        $this->layout->view('reportes/ganancia', $data);
    }

    public function gananciaPDF($codigo = 'ALL', $companias = '', $fecha = NULL) {

        $comp_select = explode("-", $companias);

        $lista = '';
        $total_soles = '';
        $total_dolares = '';
        $resumen_suma = '';
        $resumen_suma_d = '';
        $resumen_cantidad = '';
        $resumen_fpago = '';

        $producto = ($codigo == "ALL") ? "" : $codigo;

        $fechaIF = explode("-", $fecha);

        $f_ini = ($fecha == NULL) ? "01/".date("m/").date("Y") : "$fechaIF[0]/$fechaIF[1]/$fechaIF[2]";
        $f_fin = ($fecha == NULL) ? date('d/m/Y') : "$fechaIF[3]/$fechaIF[4]/$fechaIF[5]";

        $lista_companias = $this->compania_model->listar_establecimiento($this->somevar['empresa']);

        $total_costo = 0;
        $total_venta = 0;
        $total_util = 0;
        $total_porc_util = 0;
        $lista_ganancia = $this->comprobantedetalle_model->reporte_ganancia($producto, human_to_mysql($f_ini), human_to_mysql($f_fin), $comp_select);
        $lista = array();
        $resumen_compania = array();
        foreach ($lista_ganancia as $value) {
            $fecha = mysql_to_human($value->CPC_Fecha);
            $establec = $value->EESTABC_Descripcion;
            $nombre_producto = $value->PROD_Nombre;
            $cantidad = $value->CPDEC_Cantidad;
            $simbolo_moneda = $value->MONED_Simbolo;
            $pcosto = $value->ALMALOTC_Costo;
            $pventa = $value->CPDEC_Pu_ConIgv;
            $costo = $pcosto * $value->CPDEC_Cantidad;
            $venta = $pventa * $value->CPDEC_Cantidad;
            $total_costo+=$costo;
            $total_venta+=$venta;
            $utilidad = $venta - $costo;
            $porc_util = $costo != 0 ? ($utilidad / $costo) * 100 : 0;
            $resumen_compania[$value->COMPP_Codigo] = array('costo' => isset($resumen_compania[$value->COMPP_Codigo]['costo']) ? $resumen_compania[$value->COMPP_Codigo]['costo'] + $costo : $costo,
                'venta' => isset($resumen_compania[$value->COMPP_Codigo]['venta']) ? $resumen_compania[$value->COMPP_Codigo]['venta'] + $venta : $venta
            );

            $lote_numero = $value->LOTC_Numero;
            $lote_fv = mysql_to_human($value->LOTC_FechaVencimiento);
            $lista[] = array($fecha, $establec, $nombre_producto, $lote_numero, $lote_fv, $cantidad, $simbolo_moneda, number_format($pcosto, 2), number_format($pventa, 2), number_format($costo, 2), number_format($venta, 2), number_format($utilidad, 2), round($porc_util));
        }

        $total_util = $total_venta - $total_costo;
        $total_porc_util = $total_costo != 0 ? ($total_util / $total_costo) * 100 : 0;

        /* Resumen por compania */
        $t_resumen_costo = 0;
        $t_resumen_venta = 0;
        foreach ($lista_companias as $key => $compania) {
            if (isset($resumen_compania[$compania->COMPP_Codigo])) {
                $st_costo = $resumen_compania[$compania->COMPP_Codigo]['costo'];
                $st_venta = $resumen_compania[$compania->COMPP_Codigo]['venta'];
                $resumen_compania[$compania->COMPP_Codigo]['costo'] = $resumen_compania[$compania->COMPP_Codigo]['costo'] > 0 ? number_format($resumen_compania[$compania->COMPP_Codigo]['costo'], 2) : 0;
                $resumen_compania[$compania->COMPP_Codigo]['venta'] = $resumen_compania[$compania->COMPP_Codigo]['venta'] > 0 ? number_format($resumen_compania[$compania->COMPP_Codigo]['venta'], 2) : 0;
            } else {
                $resumen_compania[$compania->COMPP_Codigo]['costo'] = $st_costo = 0;
                $resumen_compania[$compania->COMPP_Codigo]['venta'] = $st_venta = 0;
            }
            $resumen_compania[$compania->COMPP_Codigo]['util'] = $st_venta - $st_costo;
            $resumen_compania[$compania->COMPP_Codigo]['porc'] = round($st_costo != 0 ? (($st_venta - $st_costo) / $st_costo) * 100 : 0, 2);
            $t_resumen_costo+=$st_costo;
            $t_resumen_venta+=$st_venta;
        }
        $t_resumen_util = $t_resumen_venta - $t_resumen_costo;
        $t_resumen_porc = $t_resumen_costo != 0 ? ($t_resumen_util / $t_resumen_costo) * 100 : 0;

        $img = 'images/img_db/menbrete1.jpg';
        $this->cezpdf = new Cezpdf('a4');
        $this->cezpdf = new backgroundPDF('a4', 'portrait', 'image', array('img'=> $img));
        $this->cezpdf->ezSetCmMargins(5.5,4,1.5,1.5);
        $this->cezpdf->ezStartPageNumbers(60, 40, 10, 'left', '', 1);

        $this->cezpdf->ezText("", 8, array("leading" => 2));
        $this->cezpdf->ezText("REPORTE DE GANANCIA", 12, array("leading" => 10, "left" => 0, "justification" => "center"));
        $this->cezpdf->ezText("", 8, array("leading" => 10));
        
        if ( count($lista) > 0 ) {
            $view = array();

            foreach($lista as $indice=>$value){
                $view[] = array(
                            'col1' => $value[0],
                            'col2' => $value[1],
                            'col3' => $value[2],
                            'col4' => $value[3],
                            'col5' => $value[4],
                            'col6' => $value[5],
                            'col7' => $value[6],
                            'col8' => $value[7],
                            'col9' => $value[8],
                            'col10' => $value[9],
                            'col11' => $value[10],
                            'col12' => $value[11],
                            'col13' => $value[12]
                        );
            }

            $col_names = array(
                    'col1' => 'FECHA',
                    'col2' => 'ESTABLECIMIENTO',
                    'col3' => 'PRODUCTO',
                    'col4' => 'NUMETO LOTE',
                    'col5' => 'FECHA V.',
                    'col6' => 'CANT.',
                    'col7' => 'M.',
                    'col8' => 'P/COSTO',
                    'col9' => 'P/VENTA',
                    'col10' => 'COSTO',
                    'col11' => 'VENTA',
                    'col12' => 'UTILIDAD',
                    'col13' => '% UTIL'
            );

                $alignL = "left";
                $alignC = "center";
                $alignR = "right";

                $this->cezpdf->ezTable($view, $col_names, '', array(
                    'width' => 555,
                    'showLines' => 2,
                    'shaded' => 0,
                    'showHeadings' => 1,
                    'xPos' => '300',
                    'fontSize' => 6,
                    'cols' => array(
                        'col1' => array('width' => 45, 'justification' => $alignC), // FECHA
                        'col2' => array('width' => 60, 'justification' => $alignC), // ESTABLECIMIENTO
                        'col3' => array('width' => 70, 'justification' => $alignL),// PRODUCTO
                        'col4' => array('width' => 40, 'justification' => $alignC), // N. LOTE
                        'col5' => array('width' => 45, 'justification' => $alignC), // FECHA V.
                        'col6' => array('width' => 30, 'justification' => $alignR), // CANT.
                        'col7' => array('width' => 20, 'justification' => $alignR), // MONEDA
                        'col8' => array('width' => 40, 'justification' => $alignR), // P/COSTO
                        'col9' => array('width' => 40, 'justification' => $alignR), // P/VENTA
                        'col10' => array('width' => 40, 'justification' => $alignR),// COSTO
                        'col11' => array('width' => 40, 'justification' => $alignR),// VENTA
                        'col12' => array('width' => 40, 'justification' => $alignR),// UTILIDAD
                        'col13' => array('width' => 35, 'justification' => $alignR) // % UTILIDAD
                    )
                ));


        }

        $yPos = $this->cezpdf->y - $this->cezpdf->ez['bottomMargin'];
                
        if ($yPos < 70)
            $this->cezpdf->ezNewPage();

            if(count($lista_companias) > 0){
                $this->cezpdf->ezText("", 8, array("leading" => 15));
                $this->cezpdf->ezText("RESUMEN POR ESTABLECIMIENTO", 10, array("leading" => 10, "left" => 35));
                $this->cezpdf->ezText("", 8, array("leading" => 10));
            
                $col_names = array(
                        'col1' => 'ESTABLECIMIENTO',
                        'col2' => 'COSTO',
                        'col3' => 'VENTA',
                        'col4' => 'UTILIDAD',
                        'col5' => '% UTILIDAD'
                );

                $viewG = array();

                foreach($lista_companias as $indice=>$value){
                    $viewG[] = array(
                            'col1' => $value->EESTABC_Descripcion,
                            'col2' => $resumen_compania[$value->COMPP_Codigo]['costo'],
                            'col3' => $resumen_compania[$value->COMPP_Codigo]['venta'],
                            'col4' => $resumen_compania[$value->COMPP_Codigo]['util'],
                            'col5' => $resumen_compania[$value->COMPP_Codigo]['porc']
                        );
                }

                $alignL = "left";
                $alignC = "center";
                $alignR = "right";

                $this->cezpdf->ezTable($viewG, $col_names, '', array(
                    'width' => 525,
                    'showLines' => 2,
                    'shaded' => 0,
                    'showHeadings' => 1,
                    'xPos' => '295',
                    'fontSize' => 7,
                    'cols' => array(
                        'col1' => array('width' => 140, 'justification' => $alignL),
                        'col2' => array('width' => 70, 'justification' => $alignR),
                        'col3' => array('width' => 70, 'justification' => $alignR),
                        'col4' => array('width' => 70, 'justification' => $alignR),
                        'col5' => array('width' => 70, 'justification' => $alignR)
                    )
                ));
            }

        $cabecera = array('Content-Type' => 'application/pdf', 'Content-Disposition' => 'nama_file.pdf', 'Expires' => '0', 'Pragma' => 'cache', 'Cache-Control' => 'private');
        $this->cezpdf->ezStream($cabecera);
    }

    public function gananciaExcel($codigo, $companias, $fecha,$moneda) {


        $lista = '';
        $total_soles = '';
        $total_dolares = '';
        $resumen_suma = '';
        $resumen_suma_d = '';
        $resumen_cantidad = '';
        $resumen_fpago = '';

        $producto = (trim($codigo) == "noValue") ? "" : $codigo;


        $fechaIF = explode("-", $fecha);

        $f_ini = (trim($fecha) == 'noValue') ? date('d/m/Y') : "$fechaIF[2]/$fechaIF[1]/$fechaIF[0]";
        $f_fin = (trim($fecha) == 'noValue') ? date('d/m/Y') : "$fechaIF[5]/$fechaIF[4]/$fechaIF[3]";

        $lista_companias = $this->compania_model->listar_establecimiento($this->somevar['empresa']);


        $total_costo = 0;
        $total_venta = 0;
        $total_util = 0;
        $total_porc_util = 0;
        $lista_ganancia = $this->comprobantedetalle_model->reporte_ganancia_old($producto, human_to_mysql($f_ini), human_to_mysql($f_fin), $companias,$moneda);

        $lista = array();
        $resumen_compania = array();
        foreach ($lista_ganancia as $value) {
            $fecha = mysql_to_human($value->CPC_Fecha);
            $establec = $value->EESTABC_Descripcion;
            $nombre_producto = $value->PROD_Nombre;
            $cantidad = $value->CPDEC_Cantidad;
            $simbolo_moneda = $value->MONED_Simbolo;
            $pcosto = $value->ALMALOTC_Costo;
            $pventa = $value->CPDEC_Pu_ConIgv;
            $costo = $pcosto * $value->CPDEC_Cantidad;
            $venta = $pventa * $value->CPDEC_Cantidad;
            $total_costo+=$costo;
            $total_venta+=$venta;
            $utilidad = $venta - $costo;
            $porc_util = $costo != 0 ? ($utilidad / $costo) * 100 : 0;
            $resumen_compania[$value->COMPP_Codigo] = array('costo' => isset($resumen_compania[$value->COMPP_Codigo]['costo']) ? $resumen_compania[$value->COMPP_Codigo]['costo'] + $costo : $costo,
                'venta' => isset($resumen_compania[$value->COMPP_Codigo]['venta']) ? $resumen_compania[$value->COMPP_Codigo]['venta'] + $venta : $venta
            );

            $lote_numero = $value->LOTC_Numero;
            $lote_fv = mysql_to_human($value->LOTC_FechaVencimiento);
            $lista[] = array($fecha, $establec, $nombre_producto, $lote_numero, $lote_fv, $cantidad, $simbolo_moneda, number_format($pcosto, 2), number_format($pventa, 2), number_format($costo, 2), number_format($venta, 2), number_format($utilidad, 2), round($porc_util));
        }

        $total_util = $total_venta - $total_costo;
        $total_porc_util = $total_costo != 0 ? ($total_util / $total_costo) * 100 : 0;

        /* Resumen por compania */
        $t_resumen_costo = 0;
        $t_resumen_venta = 0;
        foreach ($lista_companias as $key => $compania) {
            if (isset($resumen_compania[$compania->COMPP_Codigo])) {
                $st_costo = $resumen_compania[$compania->COMPP_Codigo]['costo'];
                $st_venta = $resumen_compania[$compania->COMPP_Codigo]['venta'];
                $resumen_compania[$compania->COMPP_Codigo]['costo'] = $resumen_compania[$compania->COMPP_Codigo]['costo'] > 0 ? number_format($resumen_compania[$compania->COMPP_Codigo]['costo'], 2) : 0;
                $resumen_compania[$compania->COMPP_Codigo]['venta'] = $resumen_compania[$compania->COMPP_Codigo]['venta'] > 0 ? number_format($resumen_compania[$compania->COMPP_Codigo]['venta'], 2) : 0;
            } else {
                $resumen_compania[$compania->COMPP_Codigo]['costo'] = $st_costo = 0;
                $resumen_compania[$compania->COMPP_Codigo]['venta'] = $st_venta = 0;
            }
            $resumen_compania[$compania->COMPP_Codigo]['util'] = $st_venta - $st_costo;
            $resumen_compania[$compania->COMPP_Codigo]['porc'] = round($st_costo != 0 ? (($st_venta - $st_costo) / $st_costo) * 100 : 0, 2);
            $t_resumen_costo+=$st_costo;
            $t_resumen_venta+=$st_venta;
        }
        $t_resumen_util = $t_resumen_venta - $t_resumen_costo;
        $t_resumen_porc = $t_resumen_costo != 0 ? ($t_resumen_util / $t_resumen_costo) * 100 : 0;

        

        
        ###########################################
        ######### TITULO Y ESTILOS
        ###########################################
            $this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle('Reporte de Ganancia');
            $TipoFont = array( 'font'  => array( 'bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 16, 'name'  => 'Calibri'));
            $TipoFont2 = array( 'font'  => array( 'bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 14, 'name'  => 'Calibri'));
            $style = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));
            $style2 = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));

            $this->excel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($TipoFont);
            $this->excel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($style);
            $this->excel->getActiveSheet()->getStyle('A3:N3')->applyFromArray($TipoFont);
            $this->excel->getActiveSheet()->getStyle("A3:N3")->applyFromArray($style);

            $estiloColumnasTitulo = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 11
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'ECF0F1')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasPar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                )
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasImpar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                )
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'ECF0F1')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $this->excel->getActiveSheet()->getStyle("A4:M4")->applyFromArray($estiloColumnasTitulo);

        ###########################################

        $this->excel->setActiveSheetIndex(0)->mergeCells('A1:M2')->setCellValue('A1', $_SESSION['nombre_empresa']);
        
        $this->excel->getActiveSheet()->getStyle("A3:M3")->applyFromArray($TipoFont2);
        $this->excel->getActiveSheet()->getStyle("A3:M3")->applyFromArray($style2);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A3:M3")->setCellValue("A3", "REPORTE DE GANANCIA DESDE El $f_ini HASTA $f_fin");
        
        ###########################################
        ######### TITULO DE COLUMNA RODUCTO
        ###########################################
            $lugar = 4;
            $this->excel->setActiveSheetIndex(0)->setCellValue("A$lugar", "FECHA");
            $this->excel->setActiveSheetIndex(0)->setCellValue("B$lugar", "ESTABLECIMIENTO");
            $this->excel->setActiveSheetIndex(0)->setCellValue("C$lugar", "DESCRIPCI??N");
            $this->excel->setActiveSheetIndex(0)->setCellValue("D$lugar", "N??MERO DE LOTE");
            $this->excel->setActiveSheetIndex(0)->setCellValue("E$lugar", "VENCIMIENTO LOTE");
            $this->excel->setActiveSheetIndex(0)->setCellValue("F$lugar", "CANTIDAD");
            $this->excel->setActiveSheetIndex(0)->setCellValue("G$lugar", "MONEDA");
            $this->excel->setActiveSheetIndex(0)->setCellValue("H$lugar", "P / COSTO");
            $this->excel->setActiveSheetIndex(0)->setCellValue("I$lugar", "P / VENTA");
            $this->excel->setActiveSheetIndex(0)->setCellValue("J$lugar", "COSTO TOTAL");
            $this->excel->setActiveSheetIndex(0)->setCellValue("K$lugar", "VENTA TOTAL");
            $this->excel->setActiveSheetIndex(0)->setCellValue("L$lugar", "UTILIDAD");
            $this->excel->setActiveSheetIndex(0)->setCellValue("M$lugar", "% UTILIDAD");
        ###########################################

        $numeroS = 0;
        $lugar += 1;
        
        foreach($lista as $indice => $valor){
            $numeroS += 1;

            $this->excel->setActiveSheetIndex(0)
            ->setCellValue("A$lugar", $valor[0])
            ->setCellValue("B$lugar", $valor[1])
            ->setCellValue("C$lugar", $valor[2])
            ->setCellValue("D$lugar", $valor[3])
            ->setCellValue("E$lugar", $valor[4])
            ->setCellValue("F$lugar", $valor[5])
            ->setCellValue("G$lugar", $valor[6])
            ->setCellValue("H$lugar", $valor[7])
            ->setCellValue("I$lugar", $valor[8])
            ->setCellValue("J$lugar", $valor[9])
            ->setCellValue("K$lugar", $valor[10])
            ->setCellValue("L$lugar", $valor[11])
            ->setCellValue("M$lugar", $valor[12]);

      ;
            $lugar += 1;
        }

        for($i = 'A'; $i <= 'M'; $i++){
            $this->excel->setActiveSheetIndex(0)            
                ->getColumnDimension($i)->setAutoSize(true);
        }

        if(count($lista_companias) > 0) {
            $lugar += 3;
            $this->excel->getActiveSheet()->getStyle("A$lugar:E$lugar")->applyFromArray($TipoFont2);
            $this->excel->getActiveSheet()->getStyle("A$lugar:E$lugar")->applyFromArray($style2);
            $this->excel->setActiveSheetIndex(0)->mergeCells("A$lugar:E$lugar")->setCellValue("A$lugar", "RESUMEN POR ESTABLECIMIENTO");
            $lugar += 1;

            $this->excel->setActiveSheetIndex(0)->setCellValue("A$lugar", "ESTABLECIMIENTO");
            $this->excel->setActiveSheetIndex(0)->setCellValue("B$lugar", "COSTO TOTAL");
            $this->excel->setActiveSheetIndex(0)->setCellValue("C$lugar", "VENTA TOTAL");
            $this->excel->setActiveSheetIndex(0)->setCellValue("D$lugar", "UTILIDAD");
            $this->excel->setActiveSheetIndex(0)->setCellValue("E$lugar", "% UTILIDAD");
            if ($lugar % 2 == 0)
                $this->excel->getActiveSheet()->getStyle("A$lugar:E$lugar")->applyFromArray($estiloColumnasPar);
            else
                $this->excel->getActiveSheet()->getStyle("A$lugar:E$lugar")->applyFromArray($estiloColumnasImpar);
        }



                foreach($lista_companias as $indice => $value){

                          $lugar += 1;
                          $this->excel->setActiveSheetIndex(0)
                              ->setCellValue("A$lugar", $value->EESTABC_Descripcion)
                              ->setCellValue("B$lugar", $resumen_compania[$value->COMPP_Codigo]['costo'])
                              ->setCellValue("C$lugar", $resumen_compania[$value->COMPP_Codigo]['venta'])
                              ->setCellValue("D$lugar", $resumen_compania[$value->COMPP_Codigo]['util'])
                              ->setCellValue("E$lugar", $resumen_compania[$value->COMPP_Codigo]['porc']);



                }


        $filename = "Reporte de ganancia desde ".$f_ini." hasta ".$f_fin.".xls"; //save our workbook as this file name

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=$filename");
        header("Cache-Control: max-age=0"); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        // Forzamos a la descarga
        $objWriter->save('php://output');
        #$this->layout->view('reportes/ventas_por_vendedor', $data);
    }

    public function promedioVentaExcel($codigo = 'ALL', $fecha = NULL) {

        $lista = '';
        $total_soles = '';
        $total_dolares = '';
        $resumen_suma = '';
        $resumen_suma_d = '';
        $resumen_cantidad = '';
        $resumen_fpago = '';

        $producto = ($codigo == "ALL") ? "" : $codigo;

        $fechaIF = explode("-", $fecha);

        $f_ini = ($fecha == NULL) ? "01/".date("m/").date("Y") : "$fechaIF[0]/$fechaIF[1]/$fechaIF[2]";
        $f_fin = ($fecha == NULL) ? date('d/m/Y') : "$fechaIF[3]/$fechaIF[4]/$fechaIF[5]";

        $total_costo = 0;
        $total_venta = 0;
        $total_util = 0;
        $total_porc_util = 0;

        $lista_promedio = $this->comprobantedetalle_model->promedio_ventas_articulos($producto, human_to_mysql($f_ini), human_to_mysql($f_fin));
        $lista = array();
        foreach ($lista_promedio as $value) {
            $fecha = mysql_to_human($value->CPC_Fecha);
            $nombre_producto = $value->PROD_Nombre;
            $marca = $value->MARCC_Descripcion;
            $cantidad = $value->CPDEC_Cantidad;
            $simbolo_moneda = $value->MONED_Simbolo;
            $pventa_min = $value->pventa_minimo;
            $pventa_max = $value->pventa_maximo;
            $precio_promedio = $value->total / $value->cantidad_operaciones;

            $lista[] = array($fecha, $establec, $nombre_producto, $lote_numero, $lote_fv, $cantidad, $simbolo_moneda, number_format($pcosto, 2), number_format($pventa, 2), number_format($costo, 2), number_format($venta, 2), number_format($utilidad, 2), round($porc_util));
        }
        
        $this->load->library('Excel');
        
        ###########################################
        ######### TITULO Y ESTILOS
        ###########################################
            $this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle('Reporte de Ganancia');
            $TipoFont = array( 'font'  => array( 'bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 16, 'name'  => 'Calibri'));
            $TipoFont2 = array( 'font'  => array( 'bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 14, 'name'  => 'Calibri'));
            $style = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));
            $style2 = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));

            $this->excel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($TipoFont);
            $this->excel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($style);
            $this->excel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($TipoFont);
            $this->excel->getActiveSheet()->getStyle("A3:H3")->applyFromArray($style);

            $estiloColumnasTitulo = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 11
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'ECF0F1')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasPar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                )
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasImpar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                )
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'ECF0F1')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $this->excel->getActiveSheet()->getStyle("A4:H4")->applyFromArray($estiloColumnasTitulo);
        ###########################################

        $this->excel->setActiveSheetIndex(0)->mergeCells('A1:H2')->setCellValue('A1', $_SESSION['nombre_empresa']);
        
        $this->excel->getActiveSheet()->getStyle("A3:H3")->applyFromArray($TipoFont2);
        $this->excel->getActiveSheet()->getStyle("A3:H3")->applyFromArray($style2);
        $this->excel->setActiveSheetIndex(0)->mergeCells("A3:H3")->setCellValue("A3", "REPORTE DE PRECIOS DE VENTA DEL $f_ini HASTA $f_fin");
        
        ###########################################
        ######### TITULO DE COLUMNA RODUCTO
        ###########################################
            $lugar = 4;
            $this->excel->setActiveSheetIndex(0)->setCellValue("A$lugar", "ITEM");
            $this->excel->setActiveSheetIndex(0)->setCellValue("B$lugar", "DESCRIPCI??N");
            $this->excel->setActiveSheetIndex(0)->setCellValue("C$lugar", "MARCA");
            $this->excel->setActiveSheetIndex(0)->setCellValue("D$lugar", "TOTAL P/V.");
            $this->excel->setActiveSheetIndex(0)->setCellValue("E$lugar", "N# OPERACIONES");
            $this->excel->setActiveSheetIndex(0)->setCellValue("F$lugar", "PRECIO MIN.");
            $this->excel->setActiveSheetIndex(0)->setCellValue("G$lugar", "PRECIO MAX.");
            $this->excel->setActiveSheetIndex(0)->setCellValue("H$lugar", "PRECIO PROMEDIO.");
        ###########################################

        $numeroS = 0;
        $lugar += 1;
        
        foreach ($lista_promedio as $value){
            $numeroS += 1;
            $fecha = mysql_to_human($value->CPC_Fecha);
            $nombre_producto = $value->PROD_Nombre;
            $marca = $value->MARCC_Descripcion;
            $cantidad = $value->CPDEC_Cantidad;
            $simbolo_moneda = $value->MONED_Simbolo;
            $pventa_min = $value->pventa_minimo;
            $pventa_max = $value->pventa_maximo;
            $precio_promedio = $value->total / $value->cantidad_operaciones;

            $lista[] = array($fecha, $establec, $nombre_producto, $lote_numero, $lote_fv, $cantidad, $simbolo_moneda, number_format($pcosto, 2), number_format($pventa, 2), number_format($costo, 2), number_format($venta, 2), number_format($utilidad, 2), round($porc_util));


            $this->excel->setActiveSheetIndex(0)
            ->setCellValue("A$lugar", $numeroS)
            ->setCellValue("B$lugar", $value->PROD_Nombre)
            ->setCellValue("C$lugar", $value->MARCC_Descripcion)
            ->setCellValue("D$lugar", $value->total)
            ->setCellValue("E$lugar", $value->cantidad_operaciones)
            ->setCellValue("F$lugar", $value->pventa_minimo)
            ->setCellValue("G$lugar", $value->pventa_maximo)
            ->setCellValue("H$lugar", $value->total / $value->cantidad_operaciones);

            if ($indice % 2 == 0)
                $this->excel->getActiveSheet()->getStyle("A$lugar:H$lugar")->applyFromArray($estiloColumnasPar);
            else
                $this->excel->getActiveSheet()->getStyle("A$lugar:H$lugar")->applyFromArray($estiloColumnasImpar);
            $lugar += 1;
        }

        for($i = 'A'; $i <= 'H'; $i++){
            $this->excel->setActiveSheetIndex(0)            
                ->getColumnDimension($i)->setAutoSize(true);
        }

        $filename = "Reporte de precios de venta desde ".$f_ini." hasta ".$f_fin.".xls"; //save our workbook as this file name

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=$filename");
        header("Cache-Control: max-age=0"); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        // Forzamos a la descarga
        $objWriter->save('php://output');
        #$this->layout->view('reportes/ventas_por_vendedor', $data);
    }

    public function estado_cuenta() {
        $this->load->library('layout', 'layout');

        $total_soles = '';
        $total_dolares = '';
        $resumen_suma = '';
        $resumen_suma_d = '';
        $resumen_cantidad = '';
        $resumen_fpago = '';
        $cliente = $this->input->post('cliente');
        $proveedor = $this->input->post('proveedor');
        $moneda = $this->input->post('moneda') != '' ? $this->input->post('moneda') : '2';
        $f_ini = $this->input->post('fecha_inicio') != '' ? $this->input->post('fecha_inicio') : '01/' . date('m/Y');
        $f_fin = $this->input->post('fecha_fin') != '' ? $this->input->post('fecha_fin') : date('d/m/Y');
        $lista_moneda = $this->moneda_model->obtener($moneda);
        $moneda_simbolo = $lista_moneda[0]->MONED_Simbolo;
        $total_saldo = 0;
        $lista = array();
        $lista_ultimos = array();
        if ($cliente != '' || $proveedor != '') {
            $listado_cuentas = $this->cuentas_model->buscar(($cliente != '' ? '1' : '2'), ($cliente != '' ? $cliente : $proveedor), array('V', 'A', 'C'), human_to_mysql($f_ini), human_to_mysql($f_fin));
            foreach ($listado_cuentas as $value) {
                $fecha = mysql_to_human($value->CUE_FechaOper);
                $tipo_docu = $value->CPC_TipoDocumento == 'F' ? 'FAC' : 'B';
                $numero = $value->CPC_Serie . '-' . $value->CPC_Numero;
                $simbolo_moneda = $value->MONED_Simbolo;
                $monto = $value->CUE_Monto;
                $monto = cambiar_moneda($monto, $value->CPC_TDC, $value->MONED_Codigo, $moneda);

                $listado_pago = $this->cuentaspago_model->listar($value->CUE_Codigo);
                $lista_pago = array();
                if(count($listado_pago)>0){
                    foreach ($listado_pago as $pago){
                        $lista_pago[] = array(mysql_to_human($pago->PAGC_FechaOper), $pago->MONED_Simbolo, number_format($pago->CPAGC_Monto, 2), $this->pago_model->obtener_forma_pago($pago->PAGC_FormaPago), $pago->PAGC_Obs);
                    }
                
                }
                $saldo = $monto - $this->pago_model->sumar_pagos($listado_pago, $moneda);
                $total_saldo+=$saldo;
                $estado = $value->CUE_FlagEstadoPago == 'C' ? 'CANC' : 'ACT';
                $lista[] = array($fecha, $tipo_docu, $numero, $simbolo_moneda, number_format($monto, 2), $lista_pago, number_format($saldo, 2), $estado);
            }
            $listado_pago = $this->pago_model->listar_ultimos(($cliente != '' ? '1' : '2'), ($cliente != '' ? $cliente : $proveedor), 10);
            $lista_utlimos = array();
            foreach ($listado_pago as $pago) {
                $lista_ultimos[] = array(mysql_to_human($pago->PAGC_FechaOper), $pago->MONED_Simbolo, number_format($pago->PAGC_Monto, 2), $this->pago_model->obtener_forma_pago($pago->PAGC_FormaPago), $pago->PAGC_Obs);
            }
        }



        $data['cliente'] = $cliente;
        $data['ruc_cliente'] = $this->input->post('ruc_cliente');
        $data['nombre_cliente'] = $this->input->post('nombre_cliente');
        $data['proveedor'] = $proveedor;
        $data['ruc_proveedor'] = $this->input->post('ruc_proveedor');
        $data['nombre_proveedor'] = $this->input->post('nombre_proveedor');
        $data['moneda_simbolo'] = $moneda_simbolo;
        $data['cboMoneda'] = form_dropdown("moneda", $this->moneda_model->seleccionar(), $moneda, " class='comboMedio' id='moneda' style='width:150px'");
        $data['f_ini'] = $f_ini;
        $data['f_fin'] = $f_fin;
        $data['lista'] = $lista;
        $data['lista_ultimos'] = $lista_ultimos;
        $data['total_saldo'] = number_format($total_saldo, 2);
        $data['oculto'] = form_hidden(array('base_url' => base_url()));
        $this->layout->view('reportes/estado_cuenta', $data);
    }

    public function descargarExcel($fechaini, $fechafin){
        $resultado = $this->ventas_model->ventas_por_dia($fechaini, $fechafin);

        $this->load->library('Excel');
        $hoja = 0;

        ###########################################
        ######### ESTILOS
        ###########################################
            $estiloTitulo = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 14
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasTitulo = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 11
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'ECF0F1')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            )
                                        );

            $estiloColumnasPar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                )
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'FFFFFFFF')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            ),
                                            'borders' => array(
                                                'allborders' => array(
                                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                    'color' => array( 'rgb' => "000000")
                                                )
                                            )
                                        );

            $estiloColumnasImpar = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => false,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                )
                                            ),
                                            'fill'  => array(
                                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('argb' => 'DCDCDCDC')
                                            ),
                                            'alignment' =>  array(
                                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    'wrap'          => TRUE
                                            ),
                                            'borders' => array(
                                                'allborders' => array(
                                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                    'color' => array( 'rgb' => "000000")
                                                )
                                            )
                                        );
            $estiloBold = array(
                                            'font' => array(
                                                'name'      => 'Calibri',
                                                'bold'      => true,
                                                'color'     => array(
                                                    'rgb' => '000000'
                                                ),
                                                'size' => 11
                                            )
                                        );

            # ROJO PARA ANULADOS
            $colorCelda = array(
                                    'font' => array(
                                        'name'      => 'Calibri',
                                        'bold'      => false,
                                        'color'     => array(
                                            'rgb' => '000000'
                                        )
                                    ),
                                    'fill'  => array(
                                        'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                        'color' => array('argb' => "F28A8C")
                                    )
                                );

        ###########################################################################
        ###### HOJA 0 INGRESOS POR DIA
        ###########################################################################

            $tituloReporte = "Reporte de venta por dia";
            $titulosColumnas = array('FECHA DE COMPROBANTE', 'FECHA DE ULTIMO PAGO', 'NRO DOCUMENTO', 'VENTA S/', 'VENTA US$', 'CANCELADO', 'PENDIENTE', 'ESTADO');
            
            $this->excel->setActiveSheetIndex($hoja)->mergeCells('A1:H1');
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth('25');
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth('25');
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth('25');
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth('25');
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth('25');
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth('35');

            $this->excel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($estiloTitulo);
            $this->excel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($estiloColumnasTitulo);
                            
            // Se agregan los titulos del reporte
            $this->excel->setActiveSheetIndex($hoja)
                        ->setCellValue('A1',  $tituloReporte)
                        ->setCellValue('A2',  "USUARIO : $this->nombre_persona")
                        ->setCellValue('A3',  $titulosColumnas[0])
                        ->setCellValue('B3',  $titulosColumnas[1])
                        ->setCellValue('C3',  $titulosColumnas[2])
                        ->setCellValue('D3',  $titulosColumnas[3])
                        ->setCellValue('E3',  $titulosColumnas[4])
                        ->setCellValue('F3',  $titulosColumnas[5])
                        ->setCellValue('G3',  $titulosColumnas[6])
                        ->setCellValue('H3',  $titulosColumnas[7]);
                
            $i = 4;
            $tota_dolares = 0;
            $tota_soles = 0;

            $pago_soles = 0;
            $pago_dolares = 0;

            $pendiente_soles = 0;
            $pendiente_dolares = 0;

            foreach ($resultado as $value) {
                $numero = $value['SERIE'] ."-". $value['NUMERO'];
     
                $pago = ( $value['FORPAP_Codigo'] == 1 ) ? $value['VENTAS'] : $this->ventas_model->total_pagos($value['CUE_Codigo'], $fechaini, $fechafin);
                $pendiente = $value['VENTAS'] - $pago;
     
                if( $value['MONED_Codigo'] == 2 ){
                    $soles = "0.00";
                    $dolares = $value['VENTAS'];
                    
                    $tota_dolares = $tota_dolares + $dolares;
                    $pago_dolares += $pago;
                    $pendiente_dolares += $pendiente;
                }else{
                    $soles = $value['VENTAS'];
                    $dolares = "0.00";   
                    
                    $tota_soles = $tota_soles + $soles;
                    $pago_soles += $pago;
                    $pendiente_soles += $pendiente;
                }

                switch ($value['CPC_FlagEstado']) {
                    case '0':
                        $status = "ANULADO";
                        $color = "F28A8C";
                        break;

                    default:
                        $status = "APROBADO";
                        $color = "FFFFFF";
                        break;
                }

                $this->excel->setActiveSheetIndex($hoja)
                        ->setCellValue("A$i",  $value['FECHA'])
                        ->setCellValue("B$i",  $value['FECHAPAGO'])
                        ->setCellValue("C$i",  $numero)
                        ->setCellValue("D$i",  $soles)
                        ->setCellValue("E$i",  $dolares)
                        ->setCellValue("F$i",  $pago)
                        ->setCellValue("G$i",  $pendiente)
                        ->setCellValue("H$i",  $status);

                if ( $value['CPC_FlagEstado'] == 0 )
                    $this->excel->getActiveSheet()->getStyle("A$i:H$i")->applyFromArray($colorCelda);

                $i++;
            }
                
            $this->excel->setActiveSheetIndex($hoja)
                        ->setCellValue("C$i", "TOTAL S/")
                        ->setCellValue("D$i", $tota_soles)
                        ->setCellValue("E$i", '')
                        ->setCellValue("F$i", $pago_soles)
                        ->setCellValue("G$i", $pendiente_soles);
            $i++;
            $this->excel->setActiveSheetIndex($hoja)
                        ->setCellValue("C$i", "TOTAL US$")
                        ->setCellValue("D$i", '')
                        ->setCellValue("E$i", $tota_dolares)
                        ->setCellValue("F$i", $pago_dolares)
                        ->setCellValue("G$i", $pendiente_dolares);
            
            $i--;
            $this->excel->getActiveSheet()->getStyle("A$i:H$i")->applyFromArray($estiloColumnasTitulo);
            $i++;
            $this->excel->getActiveSheet()->getStyle("A$i:H$i")->applyFromArray($estiloColumnasTitulo);

            for($i = 'A'; $i < 'D'; $i++){
                $this->excel->setActiveSheetIndex($hoja)->getColumnDimension($i)->setAutoSize(true);
            }
            
            # Se asigna el nombre a la hoja
            $this->excel->getActiveSheet()->setTitle('Ingreso Diario');
            # Se activa la hoja para que sea la que se muestre cuando el archivo se abre
            #$this->excel->setActiveSheetIndex($hoja);
            # INMOBILIZAR FILA
            $this->excel->getActiveSheet($hoja)->freezePaneByColumnAndRow(0,4);

        ###########################################################################
        ###### HOJA 1 VENTAS DEL DIA
        ###########################################################################
            $hoja++;
            $this->excel->createSheet($hoja);
            $this->excel->setActiveSheetIndex($hoja); //Seleccionar la pesta??a deseada
            $this->excel->getActiveSheet()->setTitle('Ventas diarias general'); //Establecer nombre

            $this->excel->getActiveSheet()->getStyle("A1:G2")->applyFromArray($estiloTitulo);
            $this->excel->getActiveSheet()->getStyle("A4:G4")->applyFromArray($estiloColumnasTitulo);

            $this->excel->setActiveSheetIndex($hoja)->mergeCells('A1:G2')->setCellValue('A1', $_SESSION['nombre_empresa']);
        $this->excel->setActiveSheetIndex($hoja)->mergeCells('A3:G3')->setCellValue('A3',"USUARIO: $this->nombre_persona");
            $this->excel->setActiveSheetIndex($hoja)->mergeCells("A4:G4")->setCellValue("A4", "DETALLE DE VENTAS DESDE $fechaini HASTA $fechafin");
            
            $lugar = 5;
            $numeroS = 0;

            $resumen = $this->ventas_model->resumen_ventas($fechaini, $fechafin);

            $this->excel->setActiveSheetIndex($hoja)->setCellValue("A$lugar", "FECHA DOC.");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("B$lugar", "FECHA REG.");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("C$lugar", "SERIE/NUMERO");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("D$lugar", "CLIENTE");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("E$lugar", "TOTAL");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("F$lugar", "NOTA DE CREDITO");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("G$lugar", "TOTAL");
            $this->excel->getActiveSheet()->getStyle("A$lugar:G$lugar")->applyFromArray($estiloColumnasTitulo);

            if ($resumen != NULL){
                $lugar++;
                foreach($resumen as $indice => $valor){
                    $fRegistro = explode(" ", $valor->CPC_FechaRegistro);
                    $this->excel->setActiveSheetIndex($hoja)
                    ->setCellValue("A$lugar", $valor->CPC_Fecha)
                    ->setCellValue("B$lugar", $fRegistro[0])
                    ->setCellValue("C$lugar", $valor->CPC_Serie." - ".$valor->CPC_Numero)
                    ->setCellValue("D$lugar", $valor->clienteEmpresa.$valor->clientePersona)
                    ->setCellValue("E$lugar", $valor->CPC_total)
                    ->setCellValue("F$lugar", $valor->CRED_Serie."-".$valor->CRED_Numero)
                    ->setCellValue("G$lugar", $valor->CRED_Total);
                    if ($indice % 2 == 0)
                        $this->excel->getActiveSheet()->getStyle("A$lugar:G$lugar")->applyFromArray($estiloColumnasPar);
                    else
                        $this->excel->getActiveSheet()->getStyle("A$lugar:G$lugar")->applyFromArray($estiloColumnasImpar);
                    $lugar++;
                }
                $lugar++;
            }

            $this->excel->getActiveSheet()->getColumnDimension("D")->setWidth("45");

            for($i = 'A'; $i <= 'G'; $i++){
                if ($i != "D")
                    $this->excel->setActiveSheetIndex($hoja)->getColumnDimension($i)->setAutoSize(true);
            }

        ###########################################################################
        ###### HOJA 2 VENTAS DEL DIA DETALLADO
        ###########################################################################
            $hoja++;
            $this->excel->createSheet($hoja);
            $this->excel->setActiveSheetIndex($hoja); //Seleccionar la pesta??a deseada
            $this->excel->getActiveSheet()->setTitle('Ventas diarias detallado'); //Establecer nombre

            $this->excel->getActiveSheet()->getStyle("A1:O2")->applyFromArray($estiloTitulo);
            $this->excel->getActiveSheet()->getStyle("A4:O4")->applyFromArray($estiloColumnasTitulo);

            $this->excel->setActiveSheetIndex($hoja)->mergeCells('A1:O2')->setCellValue('A1', $_SESSION['nombre_empresa']);
        $this->excel->setActiveSheetIndex($hoja)->mergeCells('A3:O3')->setCellValue('A3',"USUARIO : $this->nombre_persona");
        $this->excel->setActiveSheetIndex($hoja)->mergeCells("A4:O4")->setCellValue("A4", "DETALLE DE VENTAS DESDE $fechaini HASTA $fechafin");
            
            $lugar = 5;
            $numeroS = 0;

            $resumen = $this->ventas_model->resumen_ventas_detallado($fechaini, $fechafin);

            $this->excel->setActiveSheetIndex($hoja)->setCellValue("A$lugar", "FECHA DOC.");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("B$lugar", "FECHA REG.");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("C$lugar", "SERIE/NUMERO");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("D$lugar", "CLIENTE");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("E$lugar", "NOMBRE DE PRODUCTO");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("F$lugar", "MARCA");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("G$lugar", "LOTE");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("H$lugar", "FECHA VCTO");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("I$lugar", "CANTIDAD");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("J$lugar", "P/U");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("K$lugar", "TOTAL");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("L$lugar", "NOTA DE CREDITO");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("M$lugar", "CANTIDAD");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("N$lugar", "P/U");
            $this->excel->setActiveSheetIndex($hoja)->setCellValue("O$lugar", "TOTAL");
            $this->excel->getActiveSheet()->getStyle("A$lugar:O$lugar")->applyFromArray($estiloColumnasTitulo);

            if ($resumen != NULL){
                $lugar++;
                foreach($resumen as $indice => $valor){
                    $fRegistro = explode(" ", $valor->CPC_FechaRegistro);
                    $this->excel->setActiveSheetIndex($hoja)
                    ->setCellValue("A$lugar", $valor->CPC_Fecha)
                    ->setCellValue("B$lugar", $fRegistro[0])
                    ->setCellValue("C$lugar", $valor->CPC_Serie." - ".$valor->CPC_Numero)
                    ->setCellValue("D$lugar", $valor->clienteEmpresa.$valor->clientePersona)
                    ->setCellValue("E$lugar", $valor->PROD_Nombre)
                    ->setCellValue("F$lugar", $valor->MARCC_CodigoUsuario)
                    ->setCellValue("G$lugar", $valor->LOTC_Numero)
                    ->setCellValue("H$lugar", $valor->LOTC_FechaVencimiento)
                    ->setCellValue("I$lugar", $valor->CPDEC_Cantidad)
                    ->setCellValue("J$lugar", $valor->CPDEC_Pu_ConIgv)
                    ->setCellValue("K$lugar", $valor->CPDEC_Total)
                    ->setCellValue("L$lugar", $valor->CRED_Serie."-".$valor->CRED_Numero)
                    ->setCellValue("M$lugar", $valor->CREDET_Cantidad)
                    ->setCellValue("N$lugar", $valor->CREDET_Pu_ConIgv)
                    ->setCellValue("O$lugar", $valor->CREDET_Total);
                    if ($indice % 2 == 0)
                        $this->excel->getActiveSheet()->getStyle("A$lugar:O$lugar")->applyFromArray($estiloColumnasPar);
                    else
                        $this->excel->getActiveSheet()->getStyle("A$lugar:O$lugar")->applyFromArray($estiloColumnasImpar);
                    $lugar++;
                }
                $lugar++;
            }

            $this->excel->getActiveSheet()->getColumnDimension("D")->setWidth("25");
            $this->excel->getActiveSheet()->getColumnDimension("E")->setWidth("25");

            for($i = 'A'; $i <= 'O'; $i++){
                if ($i != 'D' && $i != 'E')
                $this->excel->setActiveSheetIndex($hoja)->getColumnDimension($i)->setAutoSize(true);
            }


        $filename = "Reporte-".date('Y-m-d').".xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=$filename");
        header("Cache-Control: max-age=0");
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function registro_ventas($tipo_oper, $tipo = 'F', $fecha1 = '', $fecha2 = '') {

        $this->load->library('layout', 'layout');

        if ($tipo_oper == 'V')
            $data['titulo'] = "Registro de Ventas Desde " . $fecha1 . " Hasta " . $fecha2;
        else
            $data['titulo'] = "Registro de Compras Desde " . $fecha1 . " Hasta " . $fecha2;
        
        $data['cboFormaPago'] = $this->OPTION_generador($this->formapago_model->listar(), 'FORPAP_Codigo', 'FORPAC_Descripcion', '');
        $data['cboVendedor'] = $this->lib_props->listarVendedores();
        $data['cboMoneda'] = $this->OPTION_generador($this->moneda_model->listar(), 'MONED_Codigo', 'MONED_Descripcion', '');
        $data['tipo_docu'] = $tipo;
        $data['tipo_oper'] = $tipo_oper;
        if ($tipo_oper == 'V')
            $data['titulo_tabla'] = "Registro de Ventas ";
        else
            $data['titulo_tabla'] = "Registro de Compras ";
        //$data['lista'] = $this->ventas_model->registro_ventas($tipo_oper, $tipo, $fecha1, $fecha2,$forma_pago,$vendedor);
        // echo $this->db->last_query();
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['oculto'] = form_hidden(array('base_url' => base_url()));
        $this->layout->view('reportes/registro_ventas', $data);
    }

    public function registro_ventas_old($tipo_oper, $tipo = 'F', $fecha1 = '', $fecha2 = '') {

        $this->load->library('layout', 'layout');

        if ($tipo_oper == 'V')
            $data['titulo'] = "Registro de Ventas Desde " . $fecha1 . " Hasta " . $fecha2;
        else
            $data['titulo'] = "Registro de Compras Desde " . $fecha1 . " Hasta " . $fecha2;

        $data['tipo_docu'] = $tipo;
        $data['tipo_oper'] = $tipo_oper;
        if ($tipo_oper == 'V')
            $data['titulo_tabla'] = "Registro de Ventas ";
        else
            $data['titulo_tabla'] = "Registro de Compras ";

        $data['lista'] = $this->ventas_model->registro_ventas($tipo_oper, $tipo, $fecha1, $fecha2);
        $data['anio'] = $this->ventas_model->getAnioVentas();
        $data['fecha1'] = $fecha1;
        $data['fecha2'] = $fecha2;
        $data['oculto'] = form_hidden(array('base_url' => base_url()));
        $this->layout->view('reportes/registro_ventas', $data);
    }

    public function registro_ventas_table(){

        $tipo_oper      = $this->input->post('tipo_oper');
        $tipo           = $this->input->post('tipo_doc');
        $fecha1         = $this->input->post('fecha1');
        $fecha2         = $this->input->post('fecha2');
        $forma_pago     = $this->input->post('forma_pago');
        $vendedor       = $this->input->post('vendedor');
        $moneda         = $this->input->post('moneda');
        $consolidado    = $this->input->post('consolidado');

        if (isset($fecha1) && $fecha1!="" && $fecha1!='1') {
            $fecha1 = $this->input->post('fecha1');
        }
        else{
            $fecha1=date('Y-m-d');
        }
        
        if (isset($fecha2) && $fecha2!="" && $fecha2!='1') {
            $fecha2=$this->input->post('fecha2');
        }
        else{
            $fecha2=date('Y-m-d');
        }
   
        if($tipo_oper=="V"){
            $operacion="Ventas";
        }
        else{
            $operacion="Compras";
        }

        $columns = array(
                            0 => "mes",
                            1 => "CPC_Fecha",
                            2 => "CPC_subtotal",
                            3 => "CPC_igv",
                            4 => "CPC_total",
                            5 => "CPC_TDC",
                            6 => "COMPP_Codigo",
                            7 => "CPC_Serie",
                            8 => "CPC_Numero",
                            9 => "CPC_TipoDocumento",
                            10 => "CPC_FlagEstado",
                            11 => "MONED_Codigo",
                            12 => "MONED_Simbolo",
                            13 => "MONED_Descripcion",
                            14 => "razon_social_cliente",
                            15 => "numero_documento_cliente",
                            16 => "razon_social_proveedor",
                            17 => "numero_documento_proveedor",
                            18 => "gravada",
                            19 => "exonerada",
                            20 => "inafecta",
                            21 => "gratuita",
                            22 => "FORPAC_Descripcion"
                        );

        $params = new stdClass();
        $params->search = $this->input->post("search")["value"];
        $params->limit = $this->input->post("start") . ", " . $this->input->post("length");
        $params->order .= ( $columns[$this->input->post("order")[0]["column"]] != "" && $columns[$this->input->post("order")[0]["dir"]] != "" ) ? $columns[$this->input->post("order")[0]["column"]] . ", " . $columns[$this->input->post("order")[0]["dir"]] : "$columns[1] ASC";
        
        $filter = new stdClass();
        $filter->tipo_oper      = $tipo_oper;
        $filter->tipo           = $tipo;
        $filter->fecha1         = $fecha1;
        $filter->fecha2         = $fecha2;
        $filter->forma_pago     = $forma_pago;
        $filter->vendedor       = $vendedor;
        $filter->moneda         = $moneda;
        $filter->consolidado    = $consolidado;
        
        $info = $this->ventas_model->resumen_ventas_mensual($filter);

        $cantidad_fac   = 0;
        $total_fac      = 0;
        $total_bol      = 0;
        $total_comp     = 0;
        $total_nota     = 0;
        $total          = 0;
        $total_fac_dolar      = 0;
        $total_bol_dolar      = 0;
        $total_comp_dolar     = 0;
        $total_nota_dolar     = 0;
        $total_dolar          = 0;
        foreach ($info as $row => $col) {
            //LA CONSULTA TRAE UNO DE LOS CAMPOS COMO "INDEFINIDA" => AQUI SE ELIMINA LA PALABRA PARA QUE NO APAREZCA EN LA VISTA
            $resultado = str_replace("indefinida", "", $col->razon_social_cliente);
            $col->razon_social_cliente = $resultado;
            $tachado1="";
            $tachado2="";
            $fecha = explode("-", $col->CPC_Fecha);
            $col->CPC_Fecha = $fecha[2]."/".$fecha[1]."/".$fecha[0];


            if($col->CPC_FlagEstado=='1'){
                
                if ($col->MONED_Codigo==1) {
                        $total += $col->CPC_total;
                }elseif($col->MONED_Codigo==2) {
                        $total_dolar+=$col->CPC_total;
                }
                if($col->CPC_TipoDocumento=="F"){
                    $col->CPC_TipoDocumento="FACTURA";
                    if ($col->MONED_Codigo==1) {
                        $total_fac += $col->CPC_total;
                    }elseif($col->MONED_Codigo==2) {
                        $total_fac_dolar+=$col->CPC_total;
                    }
                }
                if($col->CPC_TipoDocumento=="P"){
                    $col->CPC_TipoDocumento="PEDIDO";
                    
                }if($col->CPC_TipoDocumento=="B"){
                   
                    if ($col->MONED_Codigo==1) {
                        $total_bol += $col->CPC_total;
                    }elseif($col->MONED_Codigo==2) {
                        $total_bol_dolar+=$col->CPC_total;
                    }
                     $col->CPC_TipoDocumento="BOLETA";
                }if($col->CPC_TipoDocumento=="N"){
                   
                    if ($col->MONED_Codigo==1) {
                        $total_comp += $col->CPC_total;
                    }elseif($col->MONED_Codigo==2) {
                        $total_comp_dolar+=$col->CPC_total;
                    }
                     $col->CPC_TipoDocumento="COMPROBANTE";
                }if($col->CPC_TipoDocumento=="C"){
                   
                    if ($col->MONED_Codigo==1) {
                        $total_nota += $col->CPC_total;
                    }elseif($col->MONED_Codigo==2) {
                        $total_nota_dolar+=$col->CPC_total;
                    }
                     $col->CPC_TipoDocumento="NOTA CREDITO";
                     $col->FORPAC_Descripcion="-";
                }

                $col->CPC_FlagEstado='<font color="green">APROBADO</font>';
            }elseif($col->CPC_FlagEstado=='0'){
                if($col->CPC_TipoDocumento=="F"){
                    $col->CPC_TipoDocumento="FACTURA";
                    
                }if($col->CPC_TipoDocumento=="B"){
                   
                     $col->CPC_TipoDocumento="BOLETA";
                }if($col->CPC_TipoDocumento=="N"){
                    
                     $col->CPC_TipoDocumento="COMPROBANTE";
                }if($col->CPC_TipoDocumento=="C"){
                    
                     $col->CPC_TipoDocumento="NOTA CREDITO";
                     $col->FORPAC_Descripcion="-";
                }if($col->CPC_TipoDocumento=="P"){
                    
                     $col->CPC_TipoDocumento="PEDIDO";
                     
                }
                $col->CPC_FlagEstado='<font color="red">ANULADO</font>';
                $tachado1="<strike>";
                $tachado2="</strike>";
            }
            if ($tipo_oper=="V") {
                $denominacion   = $col->razon_social_cliente;
                $num_doc        = $col->numero_documento_cliente;
            }else{
                $denominacion   = $col->razon_social_proveedor;
                $num_doc        = $col->numero_documento_proveedor;

            }


            $item=$row+1;
           
           
            $data[$row] = array(
                                "item"          => $item,//0
                                "fecha"         => $col->CPC_Fecha,//1
                                "subtotal"      => $col->CPC_subtotal,//2
                                "igv"           => $col->CPC_igv,//3
                                "total"         => $col->CPC_total,//4
                                "tdc"           => $col->CPC_TDC,//5
                                "COMPP_Codigo"  => $col->COMPP_Codigo,//6
                                "serie"         => $col->CPC_Serie,//7
                                "numero"        => $col->CPC_Numero,//8
                                "tipo_documento"=> $col->CPC_TipoDocumento,//9
                                "estado"        => $col->CPC_FlagEstado,//10
                                "MONED_Codigo"  => $col->MONED_Codigo,//11
                                "MONED_Simbolo" => $col->MONED_Simbolo,//12
                                "moneda"        => $col->MONED_Descripcion,//13
                                "razon_social"  => $denominacion,//14
                                "num_doc"       => $num_doc,//15
                                "proveedor"     => $col->razon_social_proveedor,//16
                                "ruc_proveedor" => $col->numero_documento_proveedor,//17
                                "gravada"       => $col->gravada,//18
                                "exonerada"     => $col->exonerada,//19
                                "inafecta"      => $col->inafecta,//20
                                "gratuita"      => $col->gratuita,//21
                                "FORPAC_Descripcion" => $col->FORPAC_Descripcion,//22
                                "MONED_Descripcion" => $col->MONED_Descripcion,//23
                                "tachado1" => $tachado1,//24
                                "tachado2" => $tachado2//25
                               
                            );

        }
         $totales = array(
                                "total"             => $total, //0
                                "total_fac"         => $total_fac, //1
                                "total_bol"         => $total_bol, //2
                                "total_comp"        => $total_comp, //3
                                "total_nota"        => $total_nota, //4
                                "total_fac_dolar"   => $total_fac_dolar, //5
                                "total_bol_dolar"   => $total_bol_dolar, //6
                                "total_comp_dolar"  => $total_comp_dolar, //7
                                "total_nota_dolar"  => $total_nota_dolar, //8
                                "total_dolar"       => $total_dolar, //9
                                "cantidad"          => $item //10
                                                               
                            );
        $datos = array('data' =>$data,'totales' =>$totales);
        $json = $datos;

        echo json_encode($json);
    }

    /********************************************************
    * Funcion: CONCAR
    * crea reporte concar
    * Luis Valdes 09/10/2020  
    * Modificaciones ->  
    ********************************************************/

    public function concar($tipo_oper = "V", $tipo = "", $fecha1 = "", $fecha2 = "", $forma_pago = "", $vendedor = "", $moneda = "", $consolidado=""){

        if (isset($tipo) && $tipo!="" && $tipo!="-") {
            $tipo = $tipo;
        }else{
            $tipo = "";
        }
        if (isset($forma_pago) && $forma_pago!="" && $forma_pago!="-") {
            $forma_pago = $forma_pago;
        }else{
            $forma_pago = "";
        }

        if (isset($vendedor) && $vendedor!="" && $vendedor!="-") {
            $vendedor = $vendedor;
        }else{
            $vendedor = "";
        }
        if (isset($moneda) && $moneda!="" && $moneda!="-") {
            $moneda = $moneda;
        }else{
            $moneda = "";
        }
        if (isset($fecha1) && $fecha1!="" && $fecha1!=1) {
            $fecha1 = $fecha1;
        }else{
            $fecha1 = date('Y-m-d');
        }
        if (isset($fecha2) && $fecha2!="" && $fecha2!=1) {
            $fecha2 = $fecha2;
        }else{
            $fecha2 = date('Y-m-d');
        }    
        switch ($tipo_oper) {
            case 'C':
                    $operacion = "COMPRA";
                break;
            case 'V':
                    $operacion = "VENTA";
                break;
            
            default:
                    $operacion = "";
                break;
        }
                $fecha_ini = explode("-", $fecha1);
        $fecha_fin = explode("-", $fecha2);
        $fecha_inicio = $fecha_ini[2]."/".$fecha_ini[1]."/".$fecha_ini[0];
        $fecha_final = $fecha_fin[2]."/".$fecha_fin[1]."/".$fecha_fin[0];
                
                $filter = new stdClass();
        $filter->tipo_oper      = $tipo_oper;
        #$filter->tipo           = $tipo;
        $filter->fecha1         = $fecha1;
        $filter->fecha2         = $fecha2;
        #$filter->forma_pago     = $forma_pago;
        #$filter->vendedor       = $vendedor;
        #$filter->moneda         = $moneda;
        $filter->consolidado    = $consolidado;

        $reporte = $this->ventas_model->concar_model($filter);
        
        #############################################################
        #
        # INICION DE CREACION EXCEL CONCAR
        #
        #############################################################

        $this->load->library("Excel");
            $object = new PHPExcel();
            $object->setActiveSheetIndex(0);

            #INICIO CONFIGURACION DE CABECERAS
            /*INICIO CONFIGURACION DE CABECERAS*/
                $object->setActiveSheetIndex(0)->mergeCells('A1:O1')->setCellValue('A1',"USUARIO : $this->nombre_persona");
                
                $object->setActiveSheetIndex(0)->setCellValue('A2', 'Campo');
                $object->setActiveSheetIndex(0)->setCellValue('B2', 'Sub Diario');
                $object->setActiveSheetIndex(0)->setCellValue('C2', 'N??mero de Comprobante');
                $object->setActiveSheetIndex(0)->setCellValue('D2', 'Fecha de Comprobante');
                $object->setActiveSheetIndex(0)->setCellValue('E2', 'C??digo de Moneda');
                $object->setActiveSheetIndex(0)->setCellValue('F2', 'Glosa Principal');
                $object->setActiveSheetIndex(0)->setCellValue('G2', 'Tipo de Cambio');
                $object->setActiveSheetIndex(0)->setCellValue('H2', 'Tipo de Conversi??n');
                $object->setActiveSheetIndex(0)->setCellValue('I2', 'Flag de Conversi??n de Moneda');
                $object->setActiveSheetIndex(0)->setCellValue('J2', 'Fecha Tipo de Cambio');
                $object->setActiveSheetIndex(0)->setCellValue('K2', 'Cuenta Contable');
                $object->setActiveSheetIndex(0)->setCellValue('L2', 'C??digo de Anexo');
                $object->setActiveSheetIndex(0)->setCellValue('M2', 'C??digo de Centro de Costo');
                $object->setActiveSheetIndex(0)->setCellValue('N2', 'Debe / Haber');
                $object->setActiveSheetIndex(0)->setCellValue('O2', 'Importe Original');
                $object->setActiveSheetIndex(0)->setCellValue('P2', 'Importe en D??lares');
                $object->setActiveSheetIndex(0)->setCellValue('Q2', 'Importe en Soles');
                $object->setActiveSheetIndex(0)->setCellValue('R2', 'Tipo de Documento');
                $object->setActiveSheetIndex(0)->setCellValue('S2', 'N??mero de Documento');
                $object->setActiveSheetIndex(0)->setCellValue('T2', 'Fecha de Documento');
                $object->setActiveSheetIndex(0)->setCellValue('U2', 'Fecha de Vencimiento');
                $object->setActiveSheetIndex(0)->setCellValue('V2', 'C??digo de Area');
                $object->setActiveSheetIndex(0)->setCellValue('W2', 'Glosa Detalle');
                $object->setActiveSheetIndex(0)->setCellValue('X2', 'C??digo de Anexo Auxiliar');
                $object->setActiveSheetIndex(0)->setCellValue('Y2', 'Medio de Pago');
                $object->setActiveSheetIndex(0)->setCellValue('Z2', 'Tipo de Documento de Referencia');
                
                $object->setActiveSheetIndex(0)->setCellValue('AA2', 'N??mero de Documento Referencia');
                $object->setActiveSheetIndex(0)->setCellValue('AB2', 'Fecha Documento Referencia');
                $object->setActiveSheetIndex(0)->setCellValue('AC2', 'Nro M??q. Registradora Tipo Doc. Ref.');
                $object->setActiveSheetIndex(0)->setCellValue('AD2', 'Base Imponible Documento Referencia');
                $object->setActiveSheetIndex(0)->setCellValue('AE2', 'IGV Documento Provisi??n');
                $object->setActiveSheetIndex(0)->setCellValue('AF2', 'Tipo Referencia en estado MQ');
                $object->setActiveSheetIndex(0)->setCellValue('AG2', 'N??mero Serie Caja Registradora');
                $object->setActiveSheetIndex(0)->setCellValue('AH2', 'Fecha de Operaci??n');
                $object->setActiveSheetIndex(0)->setCellValue('AI2', 'Tipo de Tasa');
                $object->setActiveSheetIndex(0)->setCellValue('AJ2', 'Tasa Detracci??n/Percepci??n');
                $object->setActiveSheetIndex(0)->setCellValue('AK2', 'Importe Base Detracci??n/Percepci??n D??lares');
                $object->setActiveSheetIndex(0)->setCellValue('AL2', 'Importe Base Detracci??n/Percepci??n Soles');
                $object->setActiveSheetIndex(0)->setCellValue('AM2', 'Tipo Cambio para \'F\'');
                $object->setActiveSheetIndex(0)->setCellValue('AN2', 'Importe de IGV sin derecho cr??dito fiscal');
            
            
                $object->setActiveSheetIndex(0)->setCellValue('A3', 'Restricciones');
                $object->setActiveSheetIndex(0)->setCellValue('B3', 'Ver T.G. 02');
                $object->setActiveSheetIndex(0)->setCellValue('C3', 'Los dos primeros d??gitos son el mes y los otros 4 siguientes un correlativo');
                $object->setActiveSheetIndex(0)->setCellValue('D3', '');
                $object->setActiveSheetIndex(0)->setCellValue('E3', 'Ver T.G. 03');
                $object->setActiveSheetIndex(0)->setCellValue('F3', '');
                $object->setActiveSheetIndex(0)->setCellValue('G3', 'Llenar  solo si Tipo de Conversi??n es "C". Debe estar entre >=0 y <=9999.999999');
                $object->setActiveSheetIndex(0)->setCellValue('H3', 'Solo: "C"= Especial,"M"=Compra,"V"=Venta , "F" De acuerdo a fecha');
                $object->setActiveSheetIndex(0)->setCellValue('I3', 'Solo: "S" =Si se convierte, "N"= No se convierte');
                $object->setActiveSheetIndex(0)->setCellValue('J3', 'Si Tipo de Conversi??n "F"');
                $object->setActiveSheetIndex(0)->setCellValue('K3', 'Debe existir en la Tabla de Plan de Cuentas');
                $object->setActiveSheetIndex(0)->setCellValue('L3', 'Si Cuenta Contable tiene seleccionado Tipo de Anexo debe existir en la tabla de Anexos');
                $object->setActiveSheetIndex(0)->setCellValue('M3', 'Si Cuenta Contable tiene habilitado C. Costo, Ver T.G. 05');
                $object->setActiveSheetIndex(0)->setCellValue('N3', '"D" ?? "H"');
                $object->setActiveSheetIndex(0)->setCellValue('O3', 'Importe original de la cuenta contable.Si Flag de Conversi??n de Moneda esta en ??S??, debe estar entre >=0 y <=99999999999.99');
                $object->setActiveSheetIndex(0)->setCellValue('P3', 'Importe de la Cuenta Contable en D??lares. Obligatorio si Flag de Conversi??n de Moneda esta en "N", debe estar entre >=0 y <=99999999999.99 ');
                $object->setActiveSheetIndex(0)->setCellValue('Q3', 'Importe de la Cuenta Contable en Soles. Obligatorio si Flag de Conversi??n de Moneda esta en "N", debe estra entre >=0 y <=99999999999.99 ');
                $object->setActiveSheetIndex(0)->setCellValue('R3', 'Si Cuenta Contable tiene habilitado le Documento Referencia Ver. T.G. 06');
                $object->setActiveSheetIndex(0)->setCellValue('S3', 'Si Cuenta Contable tiene habilitado el Documento Referencia Incluye Serie y N??mero');
                $object->setActiveSheetIndex(0)->setCellValue('T3', 'Si Cuenta Contable tiene Habilitado de Documento Referencia');
                $object->setActiveSheetIndex(0)->setCellValue('U3', 'Si Cuenta Contable tiene habilitado la Fecha de Vencimiento');
                $object->setActiveSheetIndex(0)->setCellValue('V3', 'Si Cuenta Contable tiene habilitado Area. Ver T.G. 26');
                $object->setActiveSheetIndex(0)->setCellValue('W3', '');
                $object->setActiveSheetIndex(0)->setCellValue('X3', 'Si Cuenta Contable tiene seleccionado Tipo de Anexo Referencia');
                $object->setActiveSheetIndex(0)->setCellValue('Y3', 'Si Cuenta Contable tiene habilitado Tipo Medio Pago Ver T.G. "S1"');
                $object->setActiveSheetIndex(0)->setCellValue('Z3', 'Si Tipo de Documento es "NA" ?? "ND" Ver T.G. 06');
                
                $object->setActiveSheetIndex(0)->setCellValue('AA3', 'Si Tipo de Documento es "NC", "NA" ?? "ND", incluye Serie y N??mero');
                $object->setActiveSheetIndex(0)->setCellValue('AB3', 'Si tipo de Documento es "NC", "NA" ?? "ND"');
                $object->setActiveSheetIndex(0)->setCellValue('AC3', 'Si tipo de Documento es "NC", "NA" ?? "ND". Solo cuando el Tipo Documento de Referencia "TK"');
                $object->setActiveSheetIndex(0)->setCellValue('AD3', 'Si tipo de Documento es "NC", "NA" ?? "ND"');
                $object->setActiveSheetIndex(0)->setCellValue('AE3', 'Si tipo de Documento es "NC", "NA" ?? "ND"');
                $object->setActiveSheetIndex(0)->setCellValue('AF3', 'Si la Cuenta Contable tiene Habilitado Documents Referencia 2 y tipo de Documento es "TK"');
                $object->setActiveSheetIndex(0)->setCellValue('AG3', 'Si la Cuenta Contable tiene Habilitado Documents Referencia 2 y tipo de Documento es "TK"');
                $object->setActiveSheetIndex(0)->setCellValue('AH3', 'Si la Cuenta Contable tiene Habilitado Documento Referencia 2. Cuando Tipo de Documento es "TK", consignar la fecha de emision del ticket');
                $object->setActiveSheetIndex(0)->setCellValue('AI3', 'Si la Cuenta Contable tiene conf. en tasa: Si es "1" ver T.G 28 y "2" ver T.G. 29.');
                $object->setActiveSheetIndex(0)->setCellValue('AJ3', 'Si la Cuenta Contable tiene configurada la tasa: Si es "1" ver T.G 28 y "2" ver T.G. 29. Debe estar entre >=0 y <=999.99');
                $object->setActiveSheetIndex(0)->setCellValue('AK3', 'Si la Cuenta Contable tiene configurada la Tasa. Debe ser el importe total del documento y estar entre >=0 y <=99999999999.99');
                $object->setActiveSheetIndex(0)->setCellValue('AL3', 'Si la Cuenta Contable tiene configurada la Tasa. Debe ser el importe total del documento y estar entre >=0 y <=99999999999.99');
                $object->setActiveSheetIndex(0)->setCellValue('AM3', 'Especificar solo si Tipo Conversi??n es "F". Se permite "M" Compra y "V" Venta.');
                $object->setActiveSheetIndex(0)->setCellValue('AN3', 'Especificar solo para comprobantes de compras con IGV sin derecho de cr??dito Fiscal. Se detalle solo en la cuenta 42xxxx');
                

                $object->setActiveSheetIndex(0)->setCellValue('A4', 'Tama??o/Formato');
                $object->setActiveSheetIndex(0)->setCellValue('B4', '2 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('C4', '6 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('D4', 'dd/mm/aaaa');
                $object->setActiveSheetIndex(0)->setCellValue('E4', '2 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('F4', '40 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('G4', 'Num??rico 11, 6');
                $object->setActiveSheetIndex(0)->setCellValue('H4', '1 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('I4', '1 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('J4', 'dd/mm/aaaa');
                $object->setActiveSheetIndex(0)->setCellValue('K4', '8 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('L4', '18 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('M4', '6 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('N4', '1 Car??cter');
                $object->setActiveSheetIndex(0)->setCellValue('O4', 'Num??rico 14,2');
                $object->setActiveSheetIndex(0)->setCellValue('P4', 'Num??rico 14,2');
                $object->setActiveSheetIndex(0)->setCellValue('Q4', 'Num??rico 14,2');
                $object->setActiveSheetIndex(0)->setCellValue('R4', '2 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('S4', '20 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('T4', 'dd/mm/aaaa');
                $object->setActiveSheetIndex(0)->setCellValue('U4', 'dd/mm/aaaa');
                $object->setActiveSheetIndex(0)->setCellValue('V4', '3 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('W4', '30 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('X4', '18 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('Y4', '8 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('Z4', '2 Caracteres');
            
                $object->setActiveSheetIndex(0)->setCellValue('AA4', '20 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('AB4', 'dd/mm/aaaa');
                $object->setActiveSheetIndex(0)->setCellValue('AC4', '20 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('AD4', 'N??merico 14, 2');
                $object->setActiveSheetIndex(0)->setCellValue('AE4', 'N??merico 14, 2');
                $object->setActiveSheetIndex(0)->setCellValue('AF4', 'MQ');
                $object->setActiveSheetIndex(0)->setCellValue('AG4', '15 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('AH4', 'dd/mm/aaaa');
                $object->setActiveSheetIndex(0)->setCellValue('AI4', '5 Caracteres');
                $object->setActiveSheetIndex(0)->setCellValue('AJ4', 'N??merico 14, 2');
                $object->setActiveSheetIndex(0)->setCellValue('AK4', 'N??merico 14, 2');
                $object->setActiveSheetIndex(0)->setCellValue('AL4', 'N??merico 14, 2');
                $object->setActiveSheetIndex(0)->setCellValue('AM4', '1 Caracter');
                $object->setActiveSheetIndex(0)->setCellValue('AN4', 'N??merico 14, 2');
                #FIN NOMBRE DE CABECERAS

                            $estilo = array( 
                      'borders' => array(
                        'outline' => array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                      )
                        );
                        /* #INICIO ESTILOS*/
                         $object->getActiveSheet()->getStyle('A2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('B2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('C2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('D2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('E2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('F2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('G2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('H2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('I2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('J2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('K2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('L2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('M2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('N2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('O2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('P2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('Q2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('R2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('S2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('T2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('U2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('V2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('X2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('Y2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('Z2')->applyFromArray($estilo);
                    
                         $object->getActiveSheet()->getStyle('AA2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AB2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AC2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AD2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AE2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AF2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AG2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AH2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AI2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AJ2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AK2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AL2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AM2')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AN2')->applyFromArray($estilo);

                         $object->getActiveSheet()->getStyle('A3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('B3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('C3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('D3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('E3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('F3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('G3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('H3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('I3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('J3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('K3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('L3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('M3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('N3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('O3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('P3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('Q3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('R3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('S3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('T3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('U3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('V3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('X3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('Y3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('Z3')->applyFromArray($estilo);

                         $object->getActiveSheet()->getStyle('AA3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AB3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AC3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AD3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AE3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AF3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AG3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AH3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AI3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AJ3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AK3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AL3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AM3')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AN3')->applyFromArray($estilo);


                         $object->getActiveSheet()->getStyle('A4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('B4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('C4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('D4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('E4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('F4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('G4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('H4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('I4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('J4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('K4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('L4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('M4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('N4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('O4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('P4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('Q4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('R4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('S4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('T4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('U4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('V4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('X4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('Y4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('Z4')->applyFromArray($estilo);

                         $object->getActiveSheet()->getStyle('AA4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AB4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AC4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AD4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AE4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AF4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AG4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AH4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AI4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AJ4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AK4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AL4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AM4')->applyFromArray($estilo);
                         $object->getActiveSheet()->getStyle('AN4')->applyFromArray($estilo);

                             $estiloletra = array(
                        'font'  => array(
                            'bold'  => true,
                            'size'  => 8,
                            'name'  => 'Calibri'
                         ));

                            $object->getActiveSheet()->getStyle('A2:AN4')->applyFromArray($estiloletra);

                            $centrar = array(
                        'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        )
                        );
                            $object->getActiveSheet()->getStyle("A1:AN3")->applyFromArray($centrar);

                            $object->getActiveSheet()->getColumnDimension('A')->setWidth(12); 
                            $object->getActiveSheet()->getColumnDimension('B')->setWidth(12); 
                            $object->getActiveSheet()->getColumnDimension('C')->setWidth(20); 
                            $object->getActiveSheet()->getColumnDimension('D')->setWidth(12); 
                            $object->getActiveSheet()->getColumnDimension('E')->setWidth(15); 
                            $object->getActiveSheet()->getColumnDimension('F')->setWidth(12); 
                            $object->getActiveSheet()->getColumnDimension('G')->setWidth(12); 
                            $object->getActiveSheet()->getColumnDimension('H')->setWidth(12); 
                            $object->getActiveSheet()->getColumnDimension('I')->setWidth(25); 
                            $object->getActiveSheet()->getColumnDimension('J')->setWidth(18); 
                            $object->getActiveSheet()->getColumnDimension('K')->setWidth(12); 
                            $object->getActiveSheet()->getColumnDimension('L')->setWidth(12); 
                            $object->getActiveSheet()->getColumnDimension('M')->setWidth(12); 
                            $object->getActiveSheet()->getColumnDimension('N')->setWidth(12); 
                            $object->getActiveSheet()->getColumnDimension('O')->setWidth(9); 
                            $object->getActiveSheet()->getColumnDimension('P')->setWidth(9); 
                            $object->getActiveSheet()->getColumnDimension('Q')->setWidth(13); 
                            $object->getActiveSheet()->getColumnDimension('R')->setWidth(6); 
                            $object->getActiveSheet()->getColumnDimension('S')->setWidth(15); 
                            $object->getActiveSheet()->getColumnDimension('T')->setWidth(15); 
                            $object->getActiveSheet()->getColumnDimension('U')->setWidth(16); 
                            $object->getActiveSheet()->getColumnDimension('V')->setWidth(11); 
                            $object->getActiveSheet()->getColumnDimension('W')->setWidth(11); 
                            $object->getActiveSheet()->getColumnDimension('X')->setWidth(6); 
                            $object->getActiveSheet()->getColumnDimension('Y')->setWidth(11); 
                            $object->getActiveSheet()->getColumnDimension('Z')->setWidth(11); 
                            $object->getActiveSheet()->getColumnDimension('AA')->setWidth(6); 
                            $object->getActiveSheet()->getColumnDimension('AB')->setWidth(11); 
                            $object->getActiveSheet()->getColumnDimension('AC')->setWidth(11); 
                            $object->getActiveSheet()->getColumnDimension('AD')->setWidth(6); 
                            $object->getActiveSheet()->getColumnDimension('AE')->setWidth(11); 
                            $object->getActiveSheet()->getColumnDimension('AF')->setWidth(11); 
                            $object->getActiveSheet()->getColumnDimension('AG')->setWidth(6); 
                            $object->getActiveSheet()->getColumnDimension('AH')->setWidth(11); 
                            $object->getActiveSheet()->getColumnDimension('AI')->setWidth(11); 
                            $object->getActiveSheet()->getColumnDimension('AJ')->setWidth(6); 
                            $object->getActiveSheet()->getColumnDimension('AK')->setWidth(6); 
                            $object->getActiveSheet()->getColumnDimension('Al')->setWidth(11); 
                            $object->getActiveSheet()->getColumnDimension('AM')->setWidth(11); 
                            $object->getActiveSheet()->getColumnDimension('AN')->setWidth(6); 

            /*#FIN CABECERAS*/

                #LLENADO DE DATOS
              $numCorrelativo = 0;
              $excel_row = 5;
                foreach ($reporte as $key => $row) {
                    $sn_modificado      = "";
                    $fecha_docu_refe    = "";
                    $tipo_doc_modifica  = "";
                    $nota_comprobante   = "";
                    $base_imponible     = "";
                    $codigo             = $row->codigo;
                    $num_doc            = $row->numero_documento_cliente;
                    $serie              = $row->serie;
                    $numero_sin_ceros   = $row->numero;
                    $numero             = str_pad($numero_sin_ceros, 6, "0", STR_PAD_LEFT);
                    $tipo_doc           = $row->tipo_doc;
                    $cliente            = $row->razon_social_cliente;
                    if(strlen($cliente)>15){
                        $cliente = substr($cliente, 0, 15);
                    }
                    $ser_num            = "FT ".$serie."-".$numero;
                    $glosa              = $cliente.",".$ser_num;
                    switch ($tipo_doc){
                        case 'F':
                            $TipoDetalle="FT";
                            $cuenta = "121201";
                            $detalle_comprobante = $this->ventas_model->detalles_concar_comprobantes($codigo);
                            $nota_comprobante       = $this->comprobante_model->validacion_NC($codigo); 
                            break;
                        case 'B':
                            $TipoDetalle="BV";
                            $cuenta = "121203";
                            $detalle_comprobante = $this->ventas_model->detalles_concar_comprobantes($codigo);
                            $nota_comprobante       = $this->comprobante_model->validacion_NC($codigo); 
                            
                            break;
                        case 'NC':
                            $TipoDetalle = "NA";
                            $detalle_comprobante = $this->ventas_model->detalle_concar_nota($codigo);

                            break;
                        
                        default:
                            $TipoDetalle = "NA";
                            break;
                    }           
                    #SE VERIFICA SI TIENE NOTA DE CREDITO O DEBITO ASOCIADO
                    if ($nota_comprobante!=null && $nota_comprobante!="") {
                        $sn_modificado          = $nota_comprobante[0]->CRED_Serie."-".$nota_comprobante[0]->CRED_Numero;
                        $fecha_docu_refe        = mysql_to_human($nota_comprobante[0]->CRED_Fecha);
                        $tipo_doc_nota          = $nota_comprobante[0]->CRED_TipoNota;
                        switch ($tipo_doc_nota) {
                            case 'C':
                                $tipo_doc_modifica  = "NC";
                                break;
                            case 'D':
                                $tipo_doc_modifica  = "ND";
                                break;
                        }   
                        
                        $base_imponible = $nota_comprobante[0]->CRED_total;
                    }

                    $numCorrelativo++;
                    $fecha = explode("-", $row->fecha);
                    $fecha_doc = $fecha[2]."/".$fecha[1]."/".$fecha[0];
                    $numComprobante = $fecha[1];
                    $numCorrelativoceros = str_pad($numCorrelativo, 4, "0", STR_PAD_LEFT); 
                    $numComprobante = $numComprobante . $numCorrelativoceros;
                    
                    $fecha_v = ($row->fecha_venci!="" || $row->fecha_venci!="" ? $row->fecha_venci : $row->fecha);
                    $fechav = explode("-", $fecha_v);
                    $fecha_venci = $fechav[2]."/".$fechav[1]."/".$fechav[0];


                    $producto  = 0;
                    $Dinicial  = 0;
                    $Dfinal    = 0;
                    $producto  = 0;
                    $DH        = "";
                    
                    for ($i=0; $i < 2 ; $i++) { 
                        
                        if ($i==1) {
                            $cuentaTotal = number_format($row->igv,2,'.',''); //IGV TOTAL
                            $cuenta="401111";
                            $DH="H";
                           
                        } else {
                            $cuentaTotal = number_format($row->total,2,'.',''); //TOTALES
                            $DH="D";
                            

                        }

                        $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, "");                                       //A
                        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, "05");                                 //B
                        $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $numComprobante);          //C
                        $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $fecha_doc);                       //D
                        $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, "MN");                                 //E
                        $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $glosa);                         //F
                        $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row,"");                                        //G
                        $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, "M");                                  //H
                        $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, "S");                                  //I
                        $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $fecha_doc);                       //J
                        $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $cuenta);                         //K
                        $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $num_doc);                        //L
                        $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, "");                                  //M
                        $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $DH);                                 //N
                        $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row,$cuentaTotal );                //O
                        $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, "");                                  //P
                        $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, "");                                  //Q
                        $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $TipoDetalle);                //R
                        $object->getActiveSheet()->setCellValueByColumnAndRow(18, $excel_row, $serie.'-'.$numero);//S
                        $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $fecha_doc);                  //T
                        $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $fecha_venci);                  //U
                        $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, "");                                  //V
                        $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $glosa);                         //W
                        $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, "");                                  //X
                        $object->getActiveSheet()->setCellValueByColumnAndRow(24, $excel_row, "");                                  //Y
                        $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $tipo_doc_modifica);  //Z TIPO DOC REFERENCIA(ND O NC)
                        $object->getActiveSheet()->setCellValueByColumnAndRow(26, $excel_row, $sn_modificado);//AA 
                        $object->getActiveSheet()->setCellValueByColumnAndRow(27, $excel_row, $fecha_docu_refe);                                    //AB fecha docu refe
                        $object->getActiveSheet()->setCellValueByColumnAndRow(28, $excel_row, "");                                  //AC
                        $object->getActiveSheet()->setCellValueByColumnAndRow(29, $excel_row, $base_imponible);                                 //AD
                        $object->getActiveSheet()->setCellValueByColumnAndRow(30, $excel_row, "");                                  //AE
                        $object->getActiveSheet()->setCellValueByColumnAndRow(31, $excel_row, "");                                  //AF
                        $object->getActiveSheet()->setCellValueByColumnAndRow(32, $excel_row, "");                                  //AG
                        $object->getActiveSheet()->setCellValueByColumnAndRow(33, $excel_row, "");                                  //AH
                        $object->getActiveSheet()->setCellValueByColumnAndRow(34, $excel_row, "");                                  //AI
                        $object->getActiveSheet()->setCellValueByColumnAndRow(35, $excel_row, "");                                  //AJ
                        $object->getActiveSheet()->setCellValueByColumnAndRow(36, $excel_row, "");                                  //AK
                        $object->getActiveSheet()->setCellValueByColumnAndRow(37, $excel_row, "");                                  //AL
                        $excel_row++;
                    }

                    #DETALLES DE PRODUCTOS
                    #$detalle_comprobante = $this->comprobantedetalle_model->detalles($codigo);
                    $ValorProducto = 0;
                    foreach ($detalle_comprobante as $key => $value) {
                        $ValorProducto  += number_format($value->det_subtotal,2,'.','');
                    }
                        $CuentaProducto = '701111';#$value->PROD_Cuenta;
                        
                        $subtotal_detalle  = number_format($ValorProducto,2,'.','');
                        $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, "");//A
                        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, "05");//B
                        $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $numComprobante);          //C
                        $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $fecha_doc);//D
                        $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, "MN");//E
                        $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $glosa);//F
                        $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row,"");//G
                        $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, "M");//H
                        $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, "S");//I
                        $object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $fecha_doc);//J
                        $object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $CuentaProducto);//K
                        $object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $num_doc);//L
                        $object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, "");//M
                        $object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $DH);//N
                        $object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $subtotal_detalle);//O
                        $object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, "");//P
                        $object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, "");//Q
                        $object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $TipoDetalle);//R
                        $object->getActiveSheet()->setCellValueByColumnAndRow(18, $excel_row, $serie.'-'.$numero);//S
                        $object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $fecha_doc);//T
                        $object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $fecha_venci);//U
                        $object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, "");          //V
                        $object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $glosa);//W
                        $object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, "");//X
                        $object->getActiveSheet()->setCellValueByColumnAndRow(24, $excel_row, "");//Y
                        $object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $tipo_doc_modifica);  //Z TIPO DOC REFERENC
                        $object->getActiveSheet()->setCellValueByColumnAndRow(26, $excel_row, $sn_modificado);
                        $object->getActiveSheet()->setCellValueByColumnAndRow(27, $excel_row, $fecha_docu_refe);                                    //AB fe
                        $object->getActiveSheet()->setCellValueByColumnAndRow(28, $excel_row, "");//AC
                        $object->getActiveSheet()->setCellValueByColumnAndRow(29, $excel_row, $base_imponible);//AD base imponible
                        $object->getActiveSheet()->setCellValueByColumnAndRow(30, $excel_row, "");//AE
                        $object->getActiveSheet()->setCellValueByColumnAndRow(31, $excel_row, "");          //AF
                        $object->getActiveSheet()->setCellValueByColumnAndRow(32, $excel_row, "");//AG
                        $object->getActiveSheet()->setCellValueByColumnAndRow(33, $excel_row, "");//AH
                        $object->getActiveSheet()->setCellValueByColumnAndRow(34, $excel_row, "");//AI
                        $object->getActiveSheet()->setCellValueByColumnAndRow(35, $excel_row, "");//AJ
                        $object->getActiveSheet()->setCellValueByColumnAndRow(36, $excel_row, "");//AK
                        $object->getActiveSheet()->setCellValueByColumnAndRow(37, $excel_row, "");//AL
                        $excel_row++;       

                }   

                #FIN LLENADO DATOS


                $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="REPORTE CONCAR '.date('d-m-y').'.xls"');
                $object_writer->save('php://output');

    }
}
?>