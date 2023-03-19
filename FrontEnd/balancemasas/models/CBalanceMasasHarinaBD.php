<?php
require_once('CBD.php');

class CBalanceMasasHarinaBD extends CBD
{
    public $NOMBRE_OT;
    public $KG_OBJETIVO;
    public $HORA_INICIO;
    public $KG_ENT_HAR;
    public $KG_SAL_HAR;
    public $peso_actual;

    public $date;
    public $date2;
    public $filas;
    
    public function seleccionarHar()
    {

        $sql ="SELECT NOMBRE_OT, HORA_INICIO, KG_OBJETIVO, KG_ENT_HAR, KG_SAL_HAR
        FROM pyl_trazability.vw_pesadas_clientes_ot_har WHERE ID_LINEA = 4 AND TIEMPO_FIN IS NULL 
        ORDER BY ID_OT ASC LIMIT 1";

        $this->filas = $this->_consultar($sql);
   
        if ($this->filas == null)
        return false;
        else
        {
            $this->NOMBRE_OT = $this->filas[0]->NOMBRE_OT;
            $this->HORA_INICIO = $this->filas[0]->HORA_INICIO;
            $this->KG_OBJETIVO = $this->filas[0]->KG_OBJETIVO;
            $this->KG_ENT_HAR = $this->filas[0]->KG_ENT_HAR;
            $this->KG_SAL_HAR = $this->filas[0]->KG_SAL_HAR;
        }
        return true;
    }

    public function seleccionarCaudalHarEntrada()
    {     
        //$sql ="SELECT peso_actual FROM pyl_trazability.vw_estado_basculas WHERE tipo_medida = 'CAUDAL' AND id_linea = 4";
        $sql ="SELECT peso_actual FROM pyl_trazability.vw_caudales WHERE tipo_medida = 'CAUDAL_' AND id_linea = 4 AND tipo_lote = 'ENTRADA'";

        $this->filas = $this->_consultar($sql);

        if ($this->filas == null)
        return false;
        else
        {
            $this->peso_actual = $this->filas[0]->peso_actual;
        }
        return true;
    }

    public function seleccionarPesadasHarina($tipo_lote)
    {     
        $sql ="SELECT tiempo, peso, operacion FROM pyl_trazability.vw_pesadasauto
        where id_linea = 4 and tipo_lote = '$tipo_lote' AND tiempo > NOW()-INTERVAL 24 hour
        ORDER BY tiempo DESC"; 

        $this->filas = $this->_consultar($sql);
   
        return $this->filas;
    }
}
