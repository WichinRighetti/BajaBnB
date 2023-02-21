<?php
    //user files
    require_once('mysqlConnection.php');
    require_once('exceptions/recordNotFoundException.php');

    //Calse name
    Class State{
        //attributes
        private $id_state;
        private $stateName;
        private $active;

        //Setters and getters
        public function setIdState($value){$this->id_state = $value; }
        public function getIdState(){ return $this->id_state; }
        public function setStateName($value){$this->stateName = $value; }
        public function getStateName(){ return $this->stateName; }
        public function setActive($value){$this->active = $value; }
        public function getActive(){ return $this->active; }
        
        //Constructors
        public function __construct(){
            //Empty construtor
            if(func_num_args() == 0){
                $this->id_state = 0;
                $this->stateName = "";
                $this->active = 0;
            }
            //Constructor with data from database
            if(func_num_args() == 1){
                //get id
                $id = func_get_arg(0);
                //get connection
                $connection = MysqlConnection::getConnection(); // "::" Para llamar la funcion estatica
                //query
                $query = "Select id_state, stateName, active From State Where id_state = ?";
                //command
                $command = $connection->prepare($query);
                //bind parameter
                $command->bind_param('i', $id);
                //execute
                $command->execute();
                //bind results
                $command->bind_result($id_state, $stateName, $active);
                //Record was found
                if($command->fetch()){
                    //pass values to the attributes
                    $this->id_state = $id_state;
                    $this->stateName = $stateName;
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
            if(func_num_args() == 3){
                //get arguments
                $arguments = func_get_args();
                //pass arguments to attributes
                $this->id_state = $arguments[0];
                $this->stateName = $arguments[1];
                $this->active = $arguments[2];
            }
        }
        
        //represent the object in JSON format
        public function toJson(){
            return json_encode(array(
                'id_state' => $this->id_state,
                'stateName' => $this->stateName,
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
            $query = "Select id_state, stateName, active From State";
            //command
            $command = $connection->prepare($query);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($id_state, $stateName, $active);
            //fetch data
            while($command->fetch()){
                array_push($list, new State($id_state, $stateName, $active));
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