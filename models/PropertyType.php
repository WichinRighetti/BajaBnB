<?php
    //use files
    require_once('mysqlConnection.php');
    require_once('exceptions/recordNotFoundException.php');

    class PropertyType{
        //attributes
        private $id_propertyType;
        private $propertyType;
        private $active;

        //setters & getters
        public function setId_PropertyType($value){$this->id_propertyType = $value;}
        public function getId_PropertyType(){return $this->id_propertyType;}
        public function setPropertyType($value){$this->propertyType = $value;}
        public function getPropertyType(){return $this->propertyType;}
        public function setActive($value){$this->active = $value;}
        public function getActive(){return $this->active;}

        //constructor
        public function __construct(){
            if(func_num_args() == 0){
                $this->id_propertyType=0;
                $this->propertyType = '';
                $this->active=1;
            }
            //constructor with data from database
            if(func_num_args() == 1){
                // get id
                $id = func_get_arg(0);
                //get connection
                $connection = MysqlConnection::getConnection();
                //query
                $query = "Select Id_propertyType, propertyType, active 
                from PropertyType where id_propertyType=?";
                //command
                $command = $connection->prepare($query);
                //bind parameter
                $command->bind_param('i', $id);
                //execute
                $command->execute();
                //bind results
                $command->bind_result($id_propertyType, $propertyType, $active);
                //record was found
                if($command->fetch()){
                    $this->id_propertyType = $id_propertyType;
                    $this->propertyType = $propertyType;
                    $this->active = $active;
                }else{
                    // throw exception if record not found
                    throw new RecordNotFoundException($id);
                }
                //close command
                mysqli_stmt_close($command);
                //close connection
                $connection->close();
            }
            //constructor with data from database
            if(func_num_args() == 3){
                //get arguments
                $arguments = func_get_args();
                //pass arguments to attibutes
                $this->id_propertyType = $arguments[0];
                $this->propertyType = $arguments[1];
                $this->active = $arguments[2];
            }
        }

        //represent the object in JSON format
        public function toJson(){
            return json_encode(array(
                'id_propertyType'=>$this->id_propertyType,
                'propertyType'=>$this->propertyType,
                'active'=>$this->active
            ));
        }

        //get all
        public static function getAll(){
            //list
            $list = array();
            //get connection
            $connection = MysqlConnection::getConnection();
            //query
            $query = "Select Id_propertyType, propertyType, active 
            from PropertyType";
            //command
            $command = $connection->prepare($query);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($id_propertyType, $propertyType, $active);
            //fetch data
            while($command->fetch()){
                array_push($list, new PropertyType($id_propertyType, $propertyType, $active));
            }
            //close command
            mysqli_stmt_close($command);
            //close connection
            $connection->close();
            //return $list
            return $list;
        }

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
?>
