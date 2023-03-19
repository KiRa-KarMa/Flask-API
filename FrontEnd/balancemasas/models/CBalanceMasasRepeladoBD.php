<?php
/*https://www.w3schools.com/php/php_ajax_database.asp*/
require_once('CBD.php');

class CBalanceMasasRepeladoBD extends CBD
{
    public $NOMBRE_OT;
    public $KG_ENT_REP;
    public $KG_SAL_REP_RECHAZO;
    public $KG_SAL_REP_MEDIAS;
    public $KG_SAL_REP_TROZOS;
    public $KG_SAL_REP_FINAL;
    PUBLIC $KG_OBJETIVO;
    public $HORA_INICIO;

    public $filas;

    public function seleccionarRep()
    {
        $sql ="SELECT NOMBRE_OT, HORA_INICIO, KG_OBJETIVO, KG_ENT_REP, KG_SAL_REP_RECHAZO, KG_SAL_REP_TROZOS, KG_SAL_REP_MEDIAS, KG_SAL_REP_FINAL
		FROM pyl_trazability.vw_pesadas_clientes_ot_rep 
        WHERE ID_LINEA = 1 AND TIEMPO_FIN IS NULL 
        ORDER BY ID_OT ASC LIMIT 1";

        $this->filas = $this->_consultar($sql);

        if ($this->filas == null)
        return false;
        else
        {
            $this->ID_OT = $this->filas[0]->ID_OT;
            $this->NOMBRE_OT = $this->filas[0]->NOMBRE_OT;
            $this->FECHA_INICIO = $this->filas[0]->FECHA_INICIO;
            $this->HORA_INICIO = $this->filas[0]->HORA_INICIO;
            $this->TIEMPO_FIN = $this->filas[0]->TIEMPO_FIN; 
            $this->KG_OBJETIVO = $this->filas[0]->KG_OBJETIVO;        
            $this->KG_ENT_REP = $this->filas[0]->KG_ENT_REP;
            $this->KG_SAL_REP_RECHAZO = $this->filas[0]->KG_SAL_REP_RECHAZO;
            $this->KG_SAL_REP_MEDIAS = $this->filas[0]->KG_SAL_REP_MEDIAS;
            $this->KG_SAL_REP_TROZOS = $this->filas[0]->KG_SAL_REP_TROZOS;
            $this->KG_SAL_REP_FINAL = $this->filas[0]->KG_SAL_REP_FINAL;
        }
        return true;
    }
    
  
    public function seleccionarCaudalRepEntrada()
    {     
        $sql ="SELECT peso_actual
        FROM pyl_trazability.vw_caudales 
        WHERE tipo_medida = 'CAUDAL_' AND id_linea = 1 AND tipo_lote = 'ENTRADA'";
        //FROM pyl_trazability.vw_estado_basculas
        //WHERE tipo_medida = 'CAUDAL' AND id_linea = 1";
       
        $this->filas = $this->_consultar($sql);
   
        if ($this->filas == null)
        return false;
        else
        {
            $this->peso_actual = $this->filas[0]->peso_actual;
        }
        return true;
    }   


    public function seleccionarPesadasRepelado($tipo_lote)
    {     
        $sql ="SELECT tiempo, peso, operacion FROM pyl_trazability.vw_pesadasauto
        where id_linea = 1 and tipo_lote = '$tipo_lote' AND tiempo > NOW()-INTERVAL 24 hour
        ORDER BY tiempo DESC"; 

        $this->filas = $this->_consultar($sql);
   
        return $this->filas;
    }
}
