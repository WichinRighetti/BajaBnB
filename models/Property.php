<?php
    //user files
    require_once('mysqlConnection.php');
    require_once('exceptions/recordNotFoundException.php');
    require_once('PropertyType.php');
    require_once('City.php');
    require_once('State.php');
    require_once('User.php');
    require_once('UserType.php');
    require_once('Reservation.php');

    //Calse name
    Class Property{
        //attributes
        private $id_property;
        private $propertyName;
        private $propertyDescription;
        private $PropertyType;
        private $City;
        private $User;
        private $longitude;
        private $latitude;
        private $price;
        private $active;

        //Setters and getters
        public function setId_Property($value){$this->id_property = $value; }
        public function getId_Property(){ return $this->id_property; }
        public function setPropertyName($value){$this->propertyName = $value; }
        public function getPropertyName(){ return $this->propertyName; }
        public function setPropertyDescription($value){$this->propertyDescription = $value; }
        public function getPropertuDescription(){ return $this->propertyDescription; }
        public function setPropertyType($value){$this->PropertyType = $value; }
        public function getPropertyType(){ return $this->PropertyType; }
        public function setCity($value){$this->City = $value; }
        public function getCity(){ return $this->City; }
        public function setUser($value){$this->User = $value; }
        public function getUser(){ return $this->User; }
        public function setLongitude($value){$this->longitude = $value; }
        public function getLongitude(){ return $this->longitude; }
        public function setLatitude($value){$this->latitude = $value; }
        public function getLatitude(){ return $this->latitude; }
        public function setPrice($value){$this->price = $value; }
        public function getPrice(){ return $this->price; }
        public function setActive($value){$this->active = $value; }
        public function getActive(){ return $this->active; }
        
        //Constructors
        public function __construct(){
            //Empty construtor
            if(func_num_args() == 0){
                $this->id_property = 0;
                $this->propertyName = '';
                $this->propertyDescription = '';
                $this->PropertyType = new PropertyType();
                $this->City = new City();
                $this->User = new User();
                $this->longitude = 0;
                $this->latitude = 0;
                $this->price = 0;
                $this->active = 0;
            }
            //Constructor with data from database
            if(func_num_args() == 1){
                //get id
                $id = func_get_arg(0);
                //get connection
                $connection = MysqlConnection::getConnection(); // "::" Para llamar la funcion estatica
                //query
                $query = "Select p.id_property, p.propertyName, p.propertyDescription, p.longitude, p.latitude, p.price, p.active propertyActive,
                    pt.id_propertyType, pt.propertyType, pt.active propertyTypeActive,
                    c.id_city, c.cityName, c.active cityActive, s.id_state, s.stateName, s.active stateActive,
                    u.id_user, u.name, u.lastName, u.phone, u.email, ut.id_userType, ut.userType, ut.active userTypeActive, u.password, u.active userActive
                    from Property p left Join PropertyType pt ON p.id_propertyType = pt.id_propertyType
                    Left JOIN City c on p.id_city = c.id_city
                    Left JOIN State s on c.id_state = s.id_state
                    Left JOIN User u on p.id_user = u.id_user
                    Left JOIN UserType ut on u.id_userType = ut.id_userType Where p.id_property = ?";
                //command
                $command = $connection->prepare($query);
                //bind parameter
                $command->bind_param('i', $id);
                //execute
                $command->execute();
                //bind results
                $command->bind_result($id_property, $propertyName, $propertyDescription, $longitude, $latitude, $price, $propertyActive,
                    $id_propertyType, $propertyType, $propertyTypeActive,
                    $id_city, $cityName, $activeCity, $id_state, $stateName, $activeState,
                    $id_user, $name, $lastname, $phone, $email, $id_userType, $userType, $userTypeActive, $password, $userActive);
                //Record was found
                if($command->fetch()){
                    $state = new State($id_state, $stateName, $activeState);
                    $userType = new UserType($id_userType, $userType, $userTypeActive);
                    //pass values to the attributes
                    $this->id_property = $id_property;
                    $this->propertyName = $propertyName;
                    $this->propertyDescription = $propertyDescription;
                    $this->PropertyType = new PropertyType($id_propertyType, $propertyType, $propertyTypeActive);
                    $this->City = new City($id_city, $cityName, $state, $activeCity);
                    $this->User = new User($id_user, $name, $lastname, $phone, $email, $userType, $password, $userActive);
                    $this->longitude = $longitude;
                    $this->latitude = $latitude;
                    $this->price = $price;
                    $this->active = $propertyActive;
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
            if(func_num_args() == 10){
                //get arguments
                $arguments = func_get_args();
                //pass arguments to attributes
                $this->id_property = $arguments[0];
                $this->propertyName = $arguments[1];
                $this->propertyDescription = $arguments[2];
                $this->PropertyType = $arguments[3];
                $this->City = $arguments[4];
                $this->User = $arguments[5];
                $this->longitude = $arguments[6];
                $this->latitude = $arguments[7];
                $this->price = $arguments[8];
                $this->active = $arguments[9];
            }
        }
        
        //represent the object in JSON format
        public function toJson(){
            return json_encode(array(
                'id_property' => $this->id_property,
                'propertyName' => $this->propertyName,
                'propertyDescription' => $this->propertyDescription,
                'propertyType' => json_decode($this->PropertyType->toJson()),
                'city' => json_decode($this->City->toJson()),
                'user' => json_decode($this->User->toJson()),
                'longitude' => $this->longitude,
                'latitude' => $this->latitude,
                'price' => $this->price,
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
            $query = "Select p.id_property, p.propertyName, p.propertyDescription, p.longitude, p.latitude, p.price, p.active propertyActive,
                pt.id_propertyType, pt.propertyType, pt.active propertyTypeActive,
                c.id_city, c.cityName, c.active cityActive, s.id_state, s.stateName, s.active stateActive,
                u.id_user, u.name, u.lastName, u.phone, u.email, ut.id_userType, ut.userType, ut.active userTypeActive, u.password, u.active userActive
                from Property p left Join PropertyType pt ON p.id_propertyType = pt.id_propertyType
                Left JOIN City c on p.id_city = c.id_city
                Left JOIN State s on c.id_state = s.id_state
                Left JOIN User u on p.id_user = u.id_user
                Left JOIN UserType ut on u.id_userType = ut.id_userType;";
            //command
            $command = $connection->prepare($query);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($id_property, $propertyName, $propertyDescription, $longitude, $latitude, $price, $propertyActive,
                $id_propertyType, $propertyType, $propertyActive,
                $id_city, $cityName, $activeCity, $id_state, $stateName, $activeState,
                $id_user, $name, $lastname, $phone, $email, $id_userType, $userType, $userTypeActive, $password, $userActive);
            //fetch data
            while($command->fetch()){
                $state = new State($id_state, $stateName, $activeState);
                $userType = new UserType($id_userType, $userType, $userTypeActive);
                $propertyType = new PropertyType($id_propertyType, $propertyType, $propertyActive);
                $user = new User($id_user, $name, $lastname, $phone, $email, $userType, $password, $userActive);
                $city = new City($id_city, $cityName, $state, $activeCity);
                array_push($list, new Property($id_property, $propertyName, $propertyDescription, $propertyType, $city, 
                $user, $longitude, $latitude, $price, $propertyActive));
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
        //represent the object in JSON format
        public function toJsonAll(){
            $reservationList = array();

            foreach(Reservation::getAll() as $item){
                array_push($reservationList, json_decode($item->toJson()));
            }

            return json_encode(array(
                'id_property' => $this->id_property,
                'propertyName' => $this->propertyName,
                'propertyDescription' => $this->propertyDescription,
                'propertyType' => json_decode($this->PropertyType->toJson()),
                'city' => json_decode($this->City->toJson()),
                'user' => json_decode($this->User->toJson()),
                'longitude' => $this->longitude,
                'latitude' => $this->latitude,
                'price' => $this->price,
                'active' => $this->active,
                'records' => $reservationList
            ));
        }
    }
    
?>
