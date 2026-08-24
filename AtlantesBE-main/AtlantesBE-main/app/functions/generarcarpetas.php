<?php
class Carpetas{
    public function procesarCarpeta($idempresa){
        $folders=array(
            array(
                'carpeta' => $idempresa,
                'children' => array(
                    array(
                        'carpeta' => 'empresa',
                        'children' => array()
                    ),
                    array(
                        'carpeta' => 'almacen',
                        'children' => array(
                            array(
                                'carpeta' => 'adjuntos_ingresos',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'adjuntos_salidas',
                                'children' => array()
                            ),
			                array(
                                'carpeta' => 'accesorios_salidas',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'accesorios_salidas_pendientes',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'actualizacion_inventario',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'ate_gas',
                                'children' => array(
                                    array(
                                        'carpeta' => 'masivo',
                                        'children' => array()
                                    ),
                                    array(
                                        'carpeta' => 'masivo_salidas',
                                        'children' => array()
                                    ),
                                    array(
                                        'carpeta' => 'gestion-movimiento',
                                        'children' => array()
                                    ),
                                    array(
                                        'carpeta' => 'gestion-movimiento-main',
                                        'children' => array()
                                    ),
                                    array(
                                        'carpeta' => 'inventario-main',
                                        'children' => array()
                                    ),
                                    array(
                                        'carpeta' => 'inventario-etapa',
                                        'children' => array()
                                    ),
                                )
                            ),

                            array(
                                'carpeta' => 'inventario_fisico',
                                'children' => array(
                                    array(
                                        'carpeta' => 'masivo',
                                        'children' => array()
                                    ),
                                    array(
                                        'carpeta' => 'conteo',
                                        'children' => array(
                                            array(
                                                'carpeta' => 'imagenes',
                                                'children' => array()
                                            ),
                                            array(
                                                'carpeta' => 'archivos',
                                                'children' => array()
                                            )
                                        )
                                    )
                                        
                                )
                            ),
                            array(
                                'carpeta' => 'pedidos',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'salidasMasivas',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'timbrado',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'productos_cliente',
                                'children' => array()
                            )
                        )
                    ),
                    array(
                        'carpeta' => 'comprobantes',
                        'children' => array()
                    ),
                    array(
                        'carpeta' => 'documentos',
                        'children' => array(
                            array(
                                'carpeta' => 'actasingreso',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'actaspedido',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'actassalida',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'constanciaingreso',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'constanciasalida',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'anticipos',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'caratulas',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'cobros',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'cotizaciones',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'devoluciones',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'documentoscierre',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'facturas',
                                'children' => array(
                                    array(
                                        'carpeta' => 'xml',
                                        'children' => array()
                                    )
                                )
                            ),
                            array(
                                'carpeta' => 'inventario_fisico',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'invoices',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'notascobranza',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'ordenespago',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'ordenesservicio',
                                'children' => array(
                                    array(
                                        'carpeta' => 'e',
                                        'children' => array()
                                    ),
                                    array(
                                        'carpeta' => 'i',
                                        'children' => array()
                                    )
                                )
                            ),
                            array(
                                'carpeta' => 'pagos',
                                'children' => array()
                            ),
                            array(
                                'carpeta' => 'planillas',
                                'children' => array()
                            )
                        )
                    ),
                    array(
                        'carpeta' => 'embarques',
                        'children' => array()
                    ),
                    array(
                        'carpeta' => 'ingresos',
                        'children' => array()
                    ),
                    array(
                        'carpeta' => 'respaldos_facturas_anuladas',
                        'children' => array()
                    ),
                    array(
                        'carpeta' => 'respaldos_facturaspago_anuladas',
                        'children' => array()
                    ),
                    array(
                        'carpeta' => 'respaldos_notasdebito_anuladas',
                        'children' => array()
                    ),
                    array(
                        'carpeta' => 'salidas',
                        'children' => array()
                    )
                )
            )
        );
        return $this->crearCarpetas($folders,folder_files);
    }
    
    protected function crearCarpetas($carpetas, $rutaBase) {
        foreach ($carpetas as $carpeta) {
            $nombreCarpeta = $carpeta['carpeta'];
            $rutaCompleta = $rutaBase . $nombreCarpeta;

            if (!file_exists($rutaCompleta)) {
                mkdir($rutaCompleta, 0777, true);
            }

            if (!empty($carpeta['children'])) {
                $this->crearCarpetas($carpeta['children'], $rutaCompleta."/");
            }
        }
    }
}
