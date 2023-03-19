<?php
require_once('CBD.php');

class CBalanceMasasGranilloBD extends CBD
{

    public $NOMBRE_OT;
    public $KG_OBJETIVO;
    public $HORA_INICIO;
    public $KG_ENT_GRA;
    public $KG_SAL_GRA;
    public $peso_actual;
    public $fecha;
    public $fecha2;

    public $filas;

    public function seleccionarGra()
    {     
        $sql ="SELECT NOMBRE_OT, HORA_INICIO, KG_OBJETIVO, KG_ENT_GRA, KG_SAL_GRA
        FROM pyl_trazability.vw_pesadas_clientes_ot_gra WHERE ID_LINEA = 3 AND TIEMPO_FIN IS NULL 
        ORDER BY ID_OT ASC LIMIT 1";
       
        $this->filas = $this->_consultar($sql);
   
        if ($this->filas == null)
        return false;
        else
        {
            $this->NOMBRE_OT = $this->filas[0]->NOMBRE_OT;
            $this->HORA_INICIO = $this->filas[0]->HORA_INICIO;
            $this->KG_OBJETIVO = $this->filas[0]->KG_OBJETIVO;
            $this->KG_ENT_GRA = $this->filas[0]->KG_ENT_GRA;
            $this->KG_SAL_GRA = $this->filas[0]->KG_SAL_GRA;
        }
        return true;
    }
    
   public function seleccionarCaudalGraEntrada()
    {     
        //$sql ="SELECT peso_actual FROM pyl_trazability.vw_estado_basculas WHERE tipo_medida = 'CAUDAL' AND id_linea = 3";
        $sql ="SELECT peso_actual FROM pyl_trazability.vw_caudales WHERE tipo_medida = 'CAUDAL_' AND id_linea = 3 AND tipo_lote = 'ENTRADA'";
        $this->filas = $this->_consultar($sql);
   
        if ($this->filas == null)
        return false;
        else
        {
            $this->peso_actual = $this->filas[0]->peso_actual;
        }
        return true;
    }   
    
    public function seleccionarPesadasGranillo($tipo_lote)
    {     
        $sql ="SELECT tiempo, peso, operacion FROM pyl_trazability.vw_pesadasauto
        where id_linea = 3 and tipo_lote = '$tipo_lote' AND tiempo > NOW()-INTERVAL 24 hour
        ORDER BY tiempo DESC"; 

        $this->filas = $this->_consultar($sql);
   
        return $this->filas;
    }
}
