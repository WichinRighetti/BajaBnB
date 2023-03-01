<?php
    //use files
    require_once('mysqlConnection.php');
    require_once('exceptions/recordNotFoundException.php');
    require_once('PropertyType.php');
    require_once('City.php');
    require_once('State.php');
    require_once('User.php');
    require_once('UserType.php');
    require_once('Property.php');

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
                $query = "Select r.id_reservation, r.startDate, r.endDate, 
                    p.id_property, p.propertyName, p.propertyDescription, p.longitude, p.latitude, p.price, p.active propertyActive,
                    pt.id_propertyType, pt.propertyType, pt.active propertyTypeActive,
                    c.id_city, c.cityName, c.active cityActive, s.id_state, s.stateName, s.active stateActive,
                    u.id_user, u.name, u.lastName, u.phone, u.email, ut.id_userType, ut.userType, ut.active userTypeActive, u.password, u.active userActive,
                    uh.id_user, uh.name, uh.lastName, uh.phone, uh.email, uth.id_userType, uth.userType, uth.active userHTypeActive, uh.password, uh.active userHActive
                    from Reservation r Left JOIN Property p ON r.id_property = p.id_property
                    left Join PropertyType pt ON p.id_propertyType = pt.id_propertyType
                    Left JOIN City c ON p.id_city = c.id_city
                    Left JOIN State s ON c.id_state = s.id_state
                    Left JOIN User u ON r.id_user = u.id_user
                    Left JOIN User uh ON p.id_user = uh.id_user
                    Left JOIN UserType ut ON u.id_userType = ut.id_userType
                    Left JOIN UserType uth ON uh.id_userType = uth.id_userType
                    where r.id_reservation = ?;";
                //command
                $command = $connection->prepare($query);
                //bind parameter
                $command->bind_param('i', $id_reservation);
                //execute
                $command->execute();
                //bind results
                $command->bind_result($id_reservation, $startDate, $endDate,
                $id_property, $propertyName, $propertyDescription, $longitude, $latitude, $price, $propertyActive,
                $id_propertyType, $propertyType, $propertyTypeActive,
                $id_city, $cityName, $activeCity, $id_state, $stateName, $activeState,
                $id_user, $name, $lastname, $phone, $email, $id_userType, $userType, $userTypeActive, $password, $userActive,
                $id_userH, $nameH, $lastnameH, $phoneH, $emailH, $id_userHType, $userHType, $userHTypeActive, $passwordH, $userHActive);
                //reconrd was found
                if($command->fetch()){
                    $state = new State($id_state, $stateName, $activeState);
                    $userType = new UserType($id_userType, $userType, $userTypeActive);
                    $userHType = new UserType($id_userHType, $userHType, $userHTypeActive);
                    $propertyType = new PropertyType($id_propertyType, $propertyType, $propertyTypeActive);
                    $city = new City($id_city, $cityName, $state, $activeCity);
                    $user = new User($id_user, $name, $lastname, $phone, $email, $userType, $password, $userActive);
                    $userH = new User($id_userH, $nameH, $lastnameH, $phoneH, $emailH, $userHType, $passwordH, $userHActive);
                    $property = new Property($id_property, $propertyName, $propertyDescription, $propertyType, $city, $userH, $longitude, $latitude, $price, $propertyActive);
                    //pass values to the attributes
                    $this->id_reservation = $id_reservation;
                    $this->user = $user;
                    $this->property = $property;
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

        //represent the object in JSON format only reservetion
        public function toJsonReservations(){
            return json_encode(array(
                'id_reservation'=>$this->id_reservation,
                'user'=>json_decode($this->user->toJson()),
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
            $query = "Select r.id_reservation, r.startDate, r.endDate, 
                p.id_property, p.propertyName, p.propertyDescription, p.longitude, p.latitude, p.price, p.active propertyActive,
                pt.id_propertyType, pt.propertyType, pt.active propertyTypeActive,
                c.id_city, c.cityName, c.active cityActive, s.id_state, s.stateName, s.active stateActive,
                u.id_user, u.name, u.lastName, u.phone, u.email, ut.id_userType, ut.userType, ut.active userTypeActive, u.password, u.active userActive,
                uh.id_user, uh.name, uh.lastName, uh.phone, uh.email, uth.id_userType, uth.userType, uth.active userHTypeActive, uh.password, uh.active userHActive
                from Reservation r Left JOIN Property p ON r.id_property = p.id_property
                left Join PropertyType pt ON p.id_propertyType = pt.id_propertyType
                Left JOIN City c ON p.id_city = c.id_city
                Left JOIN State s ON c.id_state = s.id_state
                Left JOIN User u ON r.id_user = u.id_user
                Left JOIN User uh ON p.id_user = uh.id_user
                Left JOIN UserType ut ON u.id_userType = ut.id_userType
                Left JOIN UserType uth ON uh.id_userType = uth.id_userType;";
            //command
            $command = $connection->prepare($query);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($id_reservation, $startDate, $endDate,
                $id_property, $propertyName, $propertyDescription, $longitude, $latitude, $price, $propertyActive,
                $id_propertyType, $propertyType, $propertyTypeActive,
                $id_city, $cityName, $activeCity, $id_state, $stateName, $activeState,
                $id_user, $name, $lastname, $phone, $email, $id_userType, $userType, $userTypeActive, $password, $userActive,
                $id_userH, $nameH, $lastnameH, $phoneH, $emailH, $id_userHType, $userHType, $userHTypeActive, $passwordH, $userHActive);
            //fetch data
            while($command->fetch()){
                $state = new State($id_state, $stateName, $activeState);
                $userType = new UserType($id_userType, $userType, $userTypeActive);
                $userHType = new UserType($id_userHType, $userHType, $userHTypeActive);
                $propertyType = new PropertyType($id_propertyType, $propertyType, $propertyTypeActive);
                $city = new City($id_city, $cityName, $state, $activeCity);
                $user = new User($id_user, $name, $lastname, $phone, $email, $userType, $password, $userActive);
                $userH = new User($id_userH, $nameH, $lastnameH, $phoneH, $emailH, $userHType, $passwordH, $userHActive);
                $property = new Property($id_property, $propertyName, $propertyDescription, $propertyType, $city, $userH, $longitude, $latitude, $price, $propertyActive);
                array_push($list, new Reservation($id_reservation, $user, $property, $startDate, $endDate));
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

        //add
    function add(){
        //get connection
        $connection = MysqlConnection::getConnection();
        //query
        $query = 'Insert Into Reservation (id_property, id_user, startDate, endDate) values (?, ?, ?,?)';
        //command
        $command=$connection->prepare($query);
        $id_property = $this->property->getId_property();
        $id_user = $this->user->getId_user();
        //bind params
        $command->bind_param('iiss', $id_property, $id_user, $this->startDate, $this->endDate);
        //execute
        $result = $command->execute();
        //close command
        mysqli_stmt_close($command);
        //Close connection
        $connection->close();
        //return result
        return $result;

    }
    }
?>