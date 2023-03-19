<?php

require_once('../constants/constants.inc.php');//esto enlaza al archivo con CONSTANTES

class CBD
{
//CONEXIÓN A LA BBDD
    private $con = null; //Conexión a la BBDD
    public $error = '';  //Para guardar el error

    //Creamos la clase
    function __construct()
    {
        $this->error = '';

        try
        {
            //Creamos la conexión
            $this->con = new PDO("mysql:host=". BD_SERVIDOR . ";dbname=" . BD_BASEDATOS . ";charset=utf8", BD_USUARIO,BD_CONTRASENYA);

            if ($this->con) //si se logra crear la conexion
            {
                //Ponemos los atributos para gestionar los errores mediante excepciones
                $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                //El juego de caracteres será utf-8
                $this->con->exec("SET CHARACTER SET utf8");
            }
        }
        catch (PDOException $e)
        {
            $this->error = $e->getMessage();
        }
    }

  /*  function __destruct()
    {
        //Cerramos la conexión a la BBDD
        $this->con = null;
    }
*/
    protected function _consultar($query) //consultas del tipo SELECT
    {
        $this->error = '';

        $filas = null; //Filas que nos devolverá la consulta (variable creada por nosostros)

        try
        {
            //stmt = statement
            $stmt = $this->con->prepare($query);    //Preparamos la consulta
            $stmt->execute();                       //Realizamos la consulta

            if ($stmt->rowCount() > 0)
            {
                $filas = array();                   //Creamos un vector para las filas

                while ($registro = $stmt->fetchObject())    //Para cada registro(fila) obtenido en la consulta
                    $filas[] = $registro;                   //Lo guardamos en el vector

            }
        }
        catch (PDOException $e)
        {
            $this->error = $e->getMessage();
        }
        
        $this->con=null;

        return $filas; //Devolvemos las filas obtenidas

    }

 /*   protected function _ejecutar($query)
    {
        $this->error = '';
        $filas = 0;

        try
        {
            $filas = $this->con->exec($query);  //Ejecutamos la sentencia y devolvemos el número de filas afectadas
        }
        catch (PDOException $e)
        {
            $this->error = $e->getMessage();
        }

        return $filas;
    }*/
}

?>