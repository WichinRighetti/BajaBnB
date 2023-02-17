<?php
    //use files
    require_once('mysqlConnection.php');
    require_once('exceptions/recordNotFoundException.php');

    class UserType{
        //attributes
        private $id_userType;
        private $userType;
        private $active;

        //setters & getters
        public function setId_UserType($value){$this->id_userType = $value;}
        public function getId_UserType(){return $this->id_userType;}
        public function setUserType($value){$this->userType = $value;}
        public function getUserType(){return $this->userType;}
        public function setActive($value){$this->active = $value;}
        public function getActive(){return $this->active;}

        //constructor
        public function __construct(){
            if(func_num_args() == 0){
                $this->id_userType=0;
                $this->userType = '';
                $this->active=1;
            }
            //constructor with data from database
            if(func_num_args() == 1){
                // get id
                $id = func_get_arg(0);
                //get connection
                $connection = MysqlConnection::getConnection();
                //query
                $query = "Select Id_userType, userType, active 
                from UserType where id_userType=?";
                //command
                $command = $connection->prepare($query);
                //bind parameter
                $command->bind_param('i', $id);
                //execute
                $command->execute();
                //bind results
                $command->bind_result($id_userType, $userType, $active);
                //record was found
                if($command->fetch()){
                    $this->id_userType = $id_userType;
                    $this->userType = $userType;
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
                $this->id_userType = $arguments[0];
                $this->userType = $arguments[1];
                $this->active = $arguments[2];
            }
        }

        //represent the object in JSON format
        public function toJson(){
            return json_encode(array(
                'id_userType'=>$this->id_userType,
                'userType'=>$this->userType,
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
            $query = "Select Id_userType, userType, active 
            from UserType";
            //command
            $command = $connection->prepare($query);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($id_userType, $userType, $active);
            //fetch data
            while($command->fetch()){
                array_push($list, new UserType($id_userType, $userType, $active));
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
