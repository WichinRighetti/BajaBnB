<?php
    //use files 
    require_once ('mysqlConnection.php');
    require_once ('exceptions/recordNotFoundException.php');
    
    // class
    class User{
        //attributes
        private $id;
        private $name;
        private $lastName;
        private $phone;
        private $email;
        private $password;
        private $status;

        //getters and setters
        public function setId($value){$this->id = $value; }
        public function getId(){return $this->id;}
        public function setName($value){$this->name = $value;}
        public function getName(){return $this->name;}
        public function setLastName($value){$this->lastName = $value;}
        public function getLastName(){return $this->lastName;}
        public function setPhone($value){$this->phone = $value ;}
        public function getPhone(){return $this->phone;}
        public function setEmail($value){$this->email = $value;}
        public function getEmail(){return $this->email;}
        public function setPassword($value){$this->password = $value;}
        public function getPassword(){return $this->password;}
        public function setStatus($value){$this->status = $value;}
        public function getStatus(){return $this->status;}

        public function __construct(){
            //empty constructor
            if(func_num_args() == 0){
                $this->id=0;
                $this->name = "";
                $this->lastName = "";
                $this->phone = 0;
                $this->email = "";
                $this->password = "";
                $this->status = 1;
            }
            //constructor con data de la BD
            if(func_num_args() == 1){
                //get id
                $id = func_get_arg(0);
                // get connection
                $connection = MysqlConnection::getConnection();
                //crear query para traer info
                $query = "Select Id, Name, LastName, Phone, Email, Password, Status From Users where Id = ?";
                //command
                $command = $connection->prepare($query);
                //bind parameters
                $command->bind_param('i', $id);
                //execute command
                $command->execute();
                //return result
                $command->bind_result($id, $name, $lastName, $phone, $email, $password, $status);
                //record was found successfuly
                if($command->fetch()){
                    //pass values to the attributes
                    $this ->id = $id;
                    $this ->name = $name;
                    $this ->lastName = $lastName;
                    $this ->phone = $phone;
                    $this ->email = $email;
                    $this ->password = $password;
                    $this ->status = $status;
                }else{
                    throw new RecordNotFoundException($id);
                }
                //close connection
                mysqli_stmt_close($command);
                //close database connection
                $connection->close();
            }
            //constructor with all parameters
            if(func_num_args() == 7){
                // get args 
                $arguments = func_get_args();
                //pass arguments to attributes
                $this ->id = $arguments[0];
                $this ->name = $arguments[1];
                $this ->lastName = $arguments[2];
                $this ->phone = $arguments[3];
                $this ->email = $arguments[4];
                $this ->password = $arguments[5];
                $this ->status = $arguments[6];
            }
        }
        //represent the object in JSON format 
        public function toJson(){
            return json_encode(array(
                'Id' => $this->id,
                'Name' => $this->name,
                'Last_Name' => $this->lastName,
                'Phone' => $this->phone,
                'Email' => $this->email,
                'Password' => $this->password,
                'Status' => $this->status
            ));
        }

        public static function getAll(){
            $list = array();
            $connection = MysqlConnection::getConnection();
            $query = "Select Id, Name, LastName, Phone, Email, Password, Status From Users";
            $command = $connection->prepare($query);
            $command->execute();
            $command->bind_result($id, $name, $lastName, $phone, $email, $password, $status);
            //fetch data 
            while($command->fetch()){
                array_push($list, new User($id, $name, $lastName, $phone, $email, $password, $status));
            }
            // close command
            mysqli_stmt_close($command);
            $connection->close();
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