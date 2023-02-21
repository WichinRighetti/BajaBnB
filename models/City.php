<?php
    //user files
    require_once('mysqlConnection.php');
    require_once('exceptions/recordNotFoundException.php');
    require_once('State.php');

    //Calse name
    Class City{
        //attributes
        private $id_city;
        private $cityName;
        private $state;
        private $active;

        //Setters and getters
        public function setIdCity($value){$this->id_city = $value; }
        public function getIdCity(){ return $this->id_city; }
        public function setCityName($value){$this->cityName = $value; }
        public function getCityName(){ return $this->cityName; }
        public function setState($value){$this->state = $value; }
        public function getState(){ return $this->state; }
        public function setActive($value){$this->active = $value; }
        public function getActive(){ return $this->active; }
        
        //Constructors
        public function __construct(){
            //Empty construtor
            if(func_num_args() == 0){
                $this->id_city = 0;
                $this->cityName = '';
                $this->state = new State();
                $this->active = 0;
            }
            //Constructor with data from database
            if(func_num_args() == 1){
                //get id
                $id = func_get_arg(0);
                //get connection
                $connection = MysqlConnection::getConnection(); // "::" Para llamar la funcion estatica
                //query
                $query = "Select c.id_city, c.cityName, c.active cityActive, s.id_state, s.stateName, s.active stateActive 
                    From City c Left JOIN State s ON c.id_state = s.id_state Where c.id_city = ?";
                //command
                $command = $connection->prepare($query);
                //bind parameter
                $command->bind_param('i', $id);
                //execute
                $command->execute();
                //bind results
                $command->bind_result($id_city, $cityName, $activeCity, $id_state, $stateName, $activeState);
                //Record was found
                if($command->fetch()){
                    //pass values to the attributes
                    $this->id_city = $id_city;
                    $this->cityName = $cityName;
                    $this->state = new State($id_state, $stateName, $activeState);
                    $this->active = $activeCity;
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
                $this->state = $arguments[2];
                $this->active = $arguments[3];
            }
        }
        
        //represent the object in JSON format
        public function toJson(){
            return json_encode(array(
                'id_city' => $this->id_city,
                'cityName' => $this->cityName,
                'state' => json_decode($this->state->toJson()),
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
            $query = "Select c.id_city, c.cityName, c.active cityActive, s.id_state, s.stateName, s.active stateActive 
                From City c Left JOIN State s ON c.id_state = s.id_state";
            //command
            $command = $connection->prepare($query);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($id_city, $cityName, $activeCity, $id_state, $stateName, $activeState);
            //fetch data
            while($command->fetch()){
                $state = new State($id_state, $stateName, $activeState);
                array_push($list, new City($id_city, $cityName, $state, $activeCity));
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