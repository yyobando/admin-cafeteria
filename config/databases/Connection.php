<?php
require_once '../../ext/rest/rest.php';
require_once '../../ext/rest/http_status.php';

class Connection extends Rest 
{
    private $port;
    private $server;
    private $controller;
    private $database;
    private $user;
    private $password;


    function __construct ()
    {
        $settingsFile = dirname(__FILE__) . '\config.ini';
        
        if (!file_exists ($settingsFile)) 
        {
            $this->response(400, $this::RESOURCE_NOT_EXIST, array ('reason' => 'No se encontro un archivo de configuracíon'));
        }

        if (!$parameters = parse_ini_file($settingsFile, true) )
        {
            $this->response(400, $this::RESOURCE_NOT_EXIST, array ('reason' => 'No se encontro un archivo de configuracíon'));
        }

        $this->server = $parameters['Database']['server'];
        $this->port = $parameters['Database']['port'];
        $this->controller = $parameters['Database']['controller'];
        $this->user = $parameters['Database']['user'];
        $this->password = $parameters['Database']['password'];
        $this->database = $parameters['Database']['bd'];

    }

    private function connect($bd) {
        try {
            if ($bd == null) $bd = $this->database;
            $options = array();
            $this->connection = new PDO("$this->controller:host=$this->server;port=$this->port;dbname=$bd", $this->user, $this->password, $options);
        } catch (Exception $exc) {
            $this->connection = null;
            $this->response(400, $this::RESOURCE_NOT_EXIST, array ('reason' => 'Error en la conexion con la bd' . $exc->getMessage()));            

        }
    }

    private function disconnect(){
        $this->connection = null;
    }

    private function convertUTF8($array){
        array_walk_recursive($array, function(&$item,$key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
            }
        });
        return $array;
    }


    public static function executeQuery($query , $bd){
        $connector = new Connection();
        $connector->connect($bd);
        $statement = $connector->connection->prepare($query);
        try {
            if (!$statement->execute()){
                // print_r($statement->errorInfo());
                // echo "Error al ejecutar $query en $bd";
                $connector->disconnect();
                return(false);
            } else {
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                $statement->closeCursor();
                $connector->disconnect();            
                $result = $connector->convertUTF8($result);
                if(count($result)>0) {
                    return $result;
                } else { 
                    return true;
                }    
            }
        } catch (\Throwable $th) {
            //throw $th;
            echo "Error al ejecutar el query $query";
            echo "</br> $th";
            return false;
        }
        
    }
}


