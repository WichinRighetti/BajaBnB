<?php
    //class
    class MysqlConnection{
        //attributes
        //setter and getter
        //constructor
        //METHODS
        public static function getConnection(){
            //open config file
            $configPath = $_SERVER['DOCUMENT_ROOT'].'/BajaBnB/sites/config/mysqlConnection.json';
            $configData = json_decode(file_get_contents($configPath), true);
            //check parameters
            if(isset($configData['server'])){
                $server = $configData['server'];
            }else{
                echo "Server not found"; die;
            }
            if(isset($configData['database'])){ 
                $database = $configData['database'];
            }else{
                echo "Database not found"; die;
            }
            if(isset($configData['user'])){ 
                $user = $configData['user'];
            }else{
                echo "User not found"; die;
            }
            if(isset($configData['password'])){ 
                $password = $configData['password'];
            }else{
                echo "Password incorrect"; die;
            }
            //create connection 
            $connection = mysqli_connect($server,$user,$password,$database );
            //charset check
            $connection -> set_charset('utf8');
            //check connection 
            if(!$connection){
                echo 'Couldnt connect to mysql';
            }
            return $connection;
        }
    } 
?>