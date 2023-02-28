<?php
    //use files
    require_once('mysqlConnection.php');
    require_once('UserType.php');
    require_once('exceptions/recordNotFoundException.php');
    require_once('exceptions/invalidUserException.php');

    class User{
        //attributes
        private $id_user;
        private $name;
        private $lastname;
        private $phone;
        private $email;
        private $userType;
        private $password;
        private $active;

        //setters & getters
        public function setId_user($value){$this->id_user = $value;}
        public function getId_user(){return $this->id_user;}
        public function setName($value){$this->name = $value;}
        public function getName(){return $this->name;}
        public function setLastname($value){$this->lastname = $value;}
        public function getLastname(){return $this->lastname;}
        public function setPhone($value){$this->phone = $value;}
        public function getPhone(){return $this->phone;}
        public function setEmail($value){$this->email = $value;}
        public function getEmail(){return $this->email;}
        public function setUserType($value){$this->userType = $value;}
        public function getUserType(){return $this->userType;}
        public function setPassword($value){$this->password = $value;}
        public function getPassword(){return $this->password;}
        public function setActive($value){$this->active = $value;}
        public function getActive(){return $this->active;}

        //constructor
        public function __construct(){
            //empty constructor
            if(func_num_args() == 0){
                $this->id_user = 0;
                $this->name = '';
                $this->lastname = '';
                $this->phone = '';
                $this->email = '';
                $this->userType = new UserType();
                $this->password = '';
                $this->active = 1;
            }
            //constructor with data from database
            if(func_num_args() == 1){
                // get id_user
                $id_user = func_get_arg(0);
                //get connection
                $connection = MysqlConnection::getConnection();
                //query
                $query = "Select u.id_user, u.name, u.lastName, u.phone, u.email, ut.id_userType, ut.userType, ut.active userTypeActive, u.password, u.active
                from User u left Join UserType ut ON u.id_UserType = ut.id_userType where u.id_user = ?";
                //command
                $command = $connection->prepare($query);
                //bind parameter
                $command->bind_param('i', $id_user);
                //execute
                $command->execute();
                //bind results
                $command->bind_result($id_user, $name, $lastname, $phone, $email, $id_userType, $userType, $userTypeActive, $password, $UserActive);
                //reconrd was found
                if($command->fetch()){
                    //pass values to the attributes
                    $this->id_user = $id_user;
                    $this->name = $name;
                    $this->lastname = $lastname;
                    $this->phone = $phone;
                    $this->email = $email;
                    $this->userType = new UserType($id_userType, $userType, $userTypeActive);
                    $this->password = $password;
                    $this->active = $UserActive;
                }else{
                    // throw exception if record not found
                    throw new RecordNotFoundException($id_user);
                }
                //close command
                mysqli_stmt_close($command);
                //close connection
                $connection->close();
            }

            //constructor with password and email
            if(func_num_args() == 2){
                // get id

                $email = func_get_arg(0);
                $password = func_get_arg(1);
                //get connection
                $connection = MysqlConnection::getConnection();
                //query
                $query = "Select u.id_user, u.name, u.lastName, u.phone, u.email, ut.id_userType, ut.userType, ut.active userTypeActive, u.password, u.active

                from User u left Join UserType ut ON u.id_UserType = ut.id_userType 
                Where Email = ? AND Password = sha(?);";

                //command
                $command = $connection->prepare($query);
                //bind parameter
                $command->bind_param('ss', $email, $password);
                //execute
                $command->execute();
                //bind results
                $command->bind_result($id_user, $name, $lastname, $phone, $email, $id_userType, $userType, $userTypeActive, $password, $UserActive);
                //reconrd was found
                if($command->fetch()){
                    //pass values to the attributes
                    $this->id_user = $id_user;
                    $this->name = $name;
                    $this->lastname = $lastname;
                    $this->phone = $phone;
                    $this->email = $email;
                    $this->userType = new UserType($id_userType, $userType, $userTypeActive);
                    $this->password = $password;
                    $this->active = $UserActive;
                }else{
                    // throw exception if record not found

                    throw new InvalidUserException($email);

                }
                //close command
                mysqli_stmt_close($command);
                //close connection
                $connection->close();
            }
            //constructor with data from database
            if(func_num_args()==8){
                //get arguments
                $arguments = func_get_args();
                //pass arguments to attibutes
                $this->id_user = $arguments[0];
                $this->name = $arguments[1];
                $this->lastname = $arguments[2];
                $this->phone = $arguments[3];
                $this->email = $arguments[4];
                $this->userType = $arguments[5];
                $this->password = $arguments[6];
                $this->active = $arguments[7];

            }
        }

        //represent the object in JSON format
        public function toJson(){
            return json_encode(array(
                'id_user'=>$this->id_user,
                'name'=>$this->name,
                'lastname'=>$this->lastname,
                'phone'=>$this->phone,
                'email'=>$this->email,
                'userType'=>json_decode($this->userType->toJson()),
                'password'=>$this->password,
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
            $query = "Select u.id_user, u.name, u.lastName, u.phone, u.email, ut.id_userType, ut.userType, ut.active userTypeActive, u.password, u.active
            from User u left Join UserType ut ON u.id_UserType = ut.id_userType;";
            //command
            $command = $connection->prepare($query);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($id_user, $name, $lastname, $phone, $email, $id_userType, $userType, $userTypeActive, $password, $userActive);
            //fetch data
            while($command->fetch()){
                $userType = new UserType($id_userType, $userType, $userTypeActive);
                array_push($list, new User($id_user, $name, $lastname, $phone, $email, $userType, $password, $userActive));
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