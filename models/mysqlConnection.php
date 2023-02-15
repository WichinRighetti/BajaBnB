<?php
    // class
    class MysqlConnection{
        // return a MYSQL connection object
        public static function getConnection(){ //Para un metodo estatico no es necesario delcarar la clase
            //Open Config file //$_Server toma la ruta del servidor el punto '.' es para concatenar 2 rutas ej. ruta1.ruta2 da como resultado ruta1ruta2
            $configPath = $_SERVER['DOCUMENT_ROOT'].'/BAJABN/config/mysqlConnection.json';
            $configData = json_decode(file_get_contents($configPath), true); //Convierte en texto plano
            //Check parameters
            if(isset($configData['server'])){//isset revisa si la variable esta asignada
                $server = $configData['server'];
            }else{
                echo 'Configuration error, server not found'; die;
            }
            if(isset($configData['database'])){
                $database = $configData['database'];
            }else{
                echo 'Configuration error, database not found'; die;
            }
            if(isset($configData['user'])){
                $user = $configData['user'];
            }else{
                echo 'Configuration error, user not found'; die;
            }
            if(isset($configData['password'])){
                $password = $configData['password'];
            }else{
                echo 'Configuration error, password not found'; die;
            }
            //Create connection
            $connection = mysqli_connect($server, $user, $password, $database); //(servidor, usuario, password, base de datos)
            //Character set
            $connection->set_charset('utf8'); //uto8 es en ingles, no recibe la ñ. Set de caracteres, usa el utf8, en idioma en ingles, en español es otro set
            //Check connection
            if(!$connection){
                echo 'Could not connect to MYSQL'; die;
            }
            //Return connection
            return $connection;
        }
    }
?>