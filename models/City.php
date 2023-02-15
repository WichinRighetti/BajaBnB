<?php
    //user files
    require_once('mysqlConnection.php');
    require_once('exceptions/recordNotFoundException.php');

    //Calse name
    Class City{
        //attributes
        private $id_city;
        private $cityName;
        private $id_state;
        private $active;

        //Setters and getters
        public function setIdProperty($value){$this->id_city = $value; }
        public function getIdProperty(){ return $this->id_city; }
        public function setPropertyName($value){$this->cityName = $value; }
        public function getPropertyName(){ return $this->cityName; }
        public function setActive($value){$this->active = $value; }
        public function getActive(){ return $this->active; }
        
        //Constructors
        public function __construct(){
            //Empty construtor
            if(func_num_args() == 0){
                $this->id_city = 0;
                $this->cityName = '';
                $this->id_state = 0;
                $this->active = 0;
            }
            //Constructor with data from database
            if(func_num_args() == 1){
                //get id
                $id = func_get_arg(0);
                //get connection
                $connection = MysqlConnection::getConnection(); // "::" Para llamar la funcion estatica
                //query
                $query = "Select id_city, cityName, id_state, active From City Where id_city = ?";
                //command
                $command = $connection->prepare($query);
                //bind parameter
                $command->bind_param('i', $id);
                //execute
                $command->execute();
                //bind results
                $command->bind_result($id_city, $cityName, $id_state, $active);
                //Record was found
                if($command->fetch()){
                    //pass values to the attributes
                    $this->id_city = $id_city;
                    $this->cityName = $cityName;
                    $this->id_state = $id_state;
                    $this->active = $active;
                }else{
                    //throw exception if record not found
                    throw new RecordNotFoundException($id);
                }
                //close command
                mysqli_stmt_close($command);
                //close connection
                $connection->close();
            }
            //Constructor with data from arguments
            if(func_num_args() == 4){
                //get arguments
                $arguments = func_get_args();
                //pass arguments to attributes
                $this->id_city = $arguments[0];
                $this->cityName = $arguments[1];
                $this->id_state = $arguments[2];
                $this->active = $arguments[3];
            }
        }
        
        //represent the object in JSON format
        public function toJson(){
            return json_encode(array(
                'id_city' => $this->id_city,
                'cityName' => $this->cityName,
                'id_state' => $this->id_state,
                'active' => $this->active
            ));
        }

        //get all
        public static function getAll(){
            //list
            $list = array();
            //get connection
            $connection = MysqlConnection::getConnection(); // "::" Para llamar la funcion estatica
            //query
            $query = "Select id_city, cityName, id_state, active From City";
            //command
            $command = $connection->prepare($query);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($id_city, $cityName, $id_state, $active);
            //fetch data
            while($command->fetch()){
                array_push($list, new City($id_city, $cityName, $id_state, $active));
            }
            //close command
            mysqli_stmt_close($command);
            //close connection
            $connection->close();
            //return list
            return $list;
        }

        //get all in JSON format
        public static function getAllByJson(){
            //list
            $list = array();
            //get all
            foreach(self::getAll() as $item){
                array_push($list, json_decode($item->toJson()));
            }
            //return list
            return json_encode($list);
        }
    }

    //attributtes
    //setter and getters
?>