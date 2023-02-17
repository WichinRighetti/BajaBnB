<?php
    //use files
    require_once('mysqlConnection.php');
    require_once('UserType.php');
    require_once('Property.php');
    require_once('exceptions/recordNotFoundException.php');

    class Reservation{
        //attributes
        private $id_reservation;
        private $user;
        private $property;
        private $startDate;
        private $endDate;

        //setters & getters
        public function setId_reservation($value){$this->id_reservation = $value;}
        public function getId_reservation(){return $this->id_reservation;}
        public function setUser($value){$this->user = $value;}
        public function getUser(){return $this->user;}
        public function setProperty($value){$this->property = $value;}
        public function getProperty(){return $this->property;}
        public function setStartDate($value){$this->startDate = $value;}
        public function getStartDate(){return $this->startDate;}
        public function setEndDate($value){$this->endDate = $value;}
        public function getEndDate(){return $this->endDate;}
        //public function setActive($value){$this->active = $value;}
        //public function getActive(){return $this->active;}

        //constructor
        public function __construct(){
            //empty constructor
            if(func_num_args() == 0){
                $this->id_reservation = 0;
                $this->user = new User();
                $this->property = new Property();
                $this->startDate = '';
                $this->endDate = '';
            }
            //constructor with data from database
            if(func_num_args() == 1){
                // get id_reservation
                $id_reservation = func_get_arg(0);
                //get connection
                $connection = MysqlConnection::getConnection();
                //query
                $query = "Select u.id_user, u.name, u.lastName, u.phone, u.email, ut.id_userType, ut.userType, ut.active userTypeActive, u.password, u.active userActive,
                p.id_property, p.propertyName, p.propertyDescription, p.id_propertyType, p.id_city, p.id_user, p.longitude, p.latitude, p.price, p.active propertyActive,
                r.id_reservation, r.startDate, r.endDate
                from reservation r 
                Left JOIN User u ON r.Id_user = u.id_user 
                left Join UserType ut ON u.id_UserType = ut.id_userType 
                Left Join Property p ON r.id_property = p.id_property
                where r.id_reservation = ?;";
                //command
                $command = $connection->prepare($query);
                //bind parameter
                $command->bind_param('i', $id_reservation);
                //execute
                $command->execute();
                //bind results
                $command->bind_result($id_user, $name, $lastname, $phone, $email, $id_userType, $userType, $userTypeActive, $password, $userActive,
                    $id_property, $propertyName, $propertyDescription, $id_propertyType, $id_city, $id_user, $longitude, $latitude, $price, $propertyActive,
                    $id_reservation, $startDate, $endDate);
                //reconrd was found
                if($command->fetch()){
                    //pass values to the attributes
                    $this->id_reservation = $id_reservation;
                    $this->user = new User($id_user, $name, $lastname, $phone, $email, new UserType($id_userType, $userType, $userTypeActive), $password, $userActive);
                    $this->property = new Property($id_property, $propertyName, $propertyDescription, $id_propertyType, $id_city, $id_user, $longitude, $latitude, $price, $propertyActive);
                    $this->startDate = $startDate;
                    $this->endDate = $endDate;
                    //$this->active = $active;
                }else{
                    // throw exception if record not found
                    throw new RecordNotFoundException($id_reservation);
                }
                //close command
                mysqli_stmt_close($command);
                //close connection
                $connection->close();
            }
            //constructor with data from database
            if(func_num_args()==5){
                //get arguments
                $arguments = func_get_args();
                //pass arguments to attibutes
                $this->id_reservation = $arguments[0];
                $this->user = $arguments[1];
                $this->property = $arguments[2];
                $this->startDate = $arguments[3];
                $this->endDate = $arguments[4];
                //$this->active = $arguments[5];

            }
        }

        //represent the object in JSON format
        public function toJson(){
            return json_encode(array(
                'id_reservation'=>$this->id_reservation,
                'user'=>json_decode($this->user->toJson()),
                'property'=>json_decode($this->property->toJson()),
                'startDate'=>$this->startDate,
                'endDate'=>$this->endDate
            ));
        }

        //get all
        public static function getAll(){
            //list
            $list = array();
            //get connection
            $connection = MysqlConnection::getConnection();
            //query
            $query = "Select u.id_user, u.name, u.lastName, u.phone, u.email, ut.id_userType, ut.userType, ut.active userTypeActive, u.password, u.active userActive,
            p.id_property, p.propertyName, p.propertyDescription, p.id_propertyType, p.id_city, p.id_user, p.longitude, p.latitude, p.price, p.active propertyActive,
            r.id_reservation, r.startDate, r.endDate
            from reservation r 
            Left JOIN User u ON r.Id_user = u.id_user 
            left Join UserType ut ON u.id_UserType = ut.id_userType 
            Left Join Property p ON r.id_property = p.id_property;";
            //command
            $command = $connection->prepare($query);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($id_user, $name, $lastname, $phone, $email, $id_userType, $userType, $userTypeActive, $password, $userActive,
                $id_property, $propertyName, $propertyDescription, $id_propertyType, $id_city, $id_user, $longitude, $latitude, $price, $propertyActive,
                $id_reservation, $startDate, $endDate);
            //fetch data
            while($command->fetch()){
                $userType = new UserType($id_userType, $userType, $userTypeActive);
                $user = new User($id_user, $name, $lastname, $phone, $email, $userType, $password, $userActive);
                $property = new Property($id_property, $propertyName, $propertyDescription, $id_propertyType, $id_city, $id_user, $longitude, $latitude, $price, $propertyActive);
                array_push($list, new User($id_reservation, $user, $property, $startDate, $endDate));
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