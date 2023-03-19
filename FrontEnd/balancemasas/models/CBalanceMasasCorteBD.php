<?php
require_once('CBD.php');

class CBalanceMasasCorteBD extends CBD
{
    public $TIEMPO_INI;
    public $NOMBRE_OT;
    public $HORA_INICIO;
    public $KG_ENT_COR;
    public $KG_SAL_COR_TROZOS;
    public $KG_SAL_COR_NOCONFORME;
    public $KG_SAL_COR_LAM;
    public $peso_actual;

    public $filas;

    public function seleccionarCort()
    {
        $sql ="SELECT NOMBRE_OT, HORA_INICIO, KG_OBJETIVO, 
        KG_ENT_COR, KG_SAL_COR_TROZOS, KG_SAL_COR_NOCONFORME, KG_SAL_COR_LAM 
        FROM pyl_trazability.vw_pesadas_clientes_ot_cor 
        WHERE ID_LINEA = 2 AND TIEMPO_FIN IS NULL 
        ORDER BY ID_OT ASC LIMIT 1;";

        $this->filas = $this->_consultar($sql);

        if ($this->filas == null)
        return false;
        else
        {
            $this->NOMBRE_OT = $this->filas[0]->NOMBRE_OT;
            $this->HORA_INICIO = $this->filas[0]->HORA_INICIO;
            $this->KG_OBJETIVO = $this->filas[0]->KG_OBJETIVO;
            $this->KG_ENT_COR = $this->filas[0]->KG_ENT_COR;
            $this->KG_SAL_COR_TROZOS = $this->filas[0]->KG_SAL_COR_TROZOS;
            $this->KG_SAL_COR_NOCONFORME = $this->filas[0]->KG_SAL_COR_NOCONFORME;
            $this->KG_SAL_COR_LAM = $this->filas[0]->KG_SAL_COR_LAM;            
        }
        return true;
    }

    public function seleccionarCaudalCorteEntrada()
    {     
        $sql ="SELECT peso_actual
        FROM pyl_trazability.vw_caudales
        WHERE tipo_medida = 'CAUDAL_' AND id_linea = 2 AND tipo_lote = 'ENTRADA'"; 
        //FROM pyl_trazability.vw_estado_basculas 
        //WHERE tipo_medida = 'CAUDAL' AND id_linea = 2
        //ORDER BY peso_actual DESC LIMIT 1";

        $this->filas = $this->_consultar($sql);
   
        if ($this->filas == null)
        return false;
        else
        {
            $this->peso_actual = $this->filas[0]->peso_actual;
        }
        return true;
    }


    public function seleccionarPesadasCorte($tipo_lote)
    {     
        $sql ="SELECT tiempo, peso, operacion FROM pyl_trazability.vw_pesadasauto
        where id_linea = 2 and tipo_lote = '$tipo_lote' AND tiempo > NOW()-INTERVAL 24 hour
        ORDER BY tiempo DESC"; 

        $this->filas = $this->_consultar($sql);
   
        return $this->filas;
    }
}
