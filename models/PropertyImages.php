<?php
    //user files
    require_once('mysqlConnection.php');
    require_once('exceptions/recordNotFoundException.php');
    require_once('PropertyType.php');
    require_once('City.php');
    require_once('State.php');
    require_once('User.php');
    require_once('UserType.php');
    require_once('Property.php');

    //Calse name
    Class PropertyImage{
        //attributes
        private $id_propertyImage;
        private $property;
        private $url;
        private $active;

        //Setters and getters
        public function setIdPropertyImage($value){$this->id_propertyImage = $value; }
        public function getIdPropertyImage(){ return $this->id_propertyImage; }
        public function setProperty($value){$this->property = $value; }
        public function getProperty(){ return $this->property; }
        public function setUrl($value){$this->url = $value; }
        public function getUrl(){ return $this->url; }
        public function setActive($value){$this->active = $value; }
        public function getActive(){ return $this->active; }
        
        //Constructors
        public function __construct(){
            //Empty construtor
            if(func_num_args() == 0){
                $this->id_propertyImage = 0;
                $this->property = 0;
                $this->url = '';
                $this->active = 0;
            }
            //Constructor with data from database
            if(func_num_args() == 1){
                //get id
                $id = func_get_arg(0);
                //get connection
                $connection = MysqlConnection::getConnection(); // "::" Para llamar la funcion estatica
                //query
                $query = "Select pi.id_propertyImage, pi.url, pi.active propertyImageActive, 
                    p.id_property, p.propertyName, p.propertyDescription, p.longitude, p.latitude, p.price, p.active propertyActive,
                    pt.id_propertyType, pt.propertyType, pt.active propertyTypeActive,
                    c.id_city, c.cityName, c.active cityActive, s.id_state, s.stateName, s.active stateActive,
                    u.id_user, u.name, u.lastName, u.phone, u.email, ut.id_userType, ut.userType, ut.active userTypeActive, u.password, u.active userActive
                    from PropertyImages pi Left JOIN Property p ON pi.id_property = p.id_property
                    left Join PropertyType pt ON p.id_propertyType = pt.id_propertyType
                    Left JOIN City c ON p.id_city = c.id_city
                    Left JOIN State s ON c.id_state = s.id_state
                    Left JOIN User u ON p.id_user = u.id_user
                    Left JOIN UserType ut ON u.id_userType = ut.id_userType Where pi.id_propertyImage = ?;";
                //command
                $command = $connection->prepare($query);
                //bind parameter
                $command->bind_param('i', $id);
                //execute
                $command->execute();
                //bind results
                $command->bind_result($id_propertyImage, $url, $propertyImageActive,
                    $id_property, $propertyName, $propertyDescription, $longitude, $latitude, $price, $propertyActive,
                    $id_propertyType, $propertyType, $propertyActive,
                    $id_city, $cityName, $activeCity, $id_state, $stateName, $activeState,
                    $id_user, $name, $lastname, $phone, $email, $id_userType, $userType, $userTypeActive, $password, $userActive);
                //Record was found
                if($command->fetch()){
                    $state = new State($id_state, $stateName, $activeState);
                    $userType = new UserType($id_userType, $userType, $userTypeActive);
                    $propertyType = new PropertyType($id_propertyType, $propertyType, $propertyActive);
                    $city = new City($id_city, $cityName, $state, $activeCity);
                    $user = new User($id_user, $name, $lastname, $phone, $email, $userType, $password, $userActive);
                    $property = new Property($id_property, $propertyName, $propertyDescription, $propertyType, $city, $user, $longitude, $latitude, $price, $propertyActive);
                    //pass values to the attributes
                    $this->id_propertyImage = $id_propertyImage;
                    $this->property = $property;
                    $this->url = $url;
                    $this->active = $propertyImageActive;
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
                $this->id_propertyImage = $arguments[0];
                $this->property = $arguments[1];
                $this->url = $arguments[2];
                $this->active = $arguments[3];
            }
        }
        
        //represent the object in JSON format
        public function toJson(){
            return json_encode(array(
                'id_propertyImage' => $this->id_propertyImage,
                'property' => json_decode($this->property->toJson()),
                'url' => $this->url,
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
            $query = "Select pi.id_propertyImage, pi.url, pi.active propertyImageActive, 
                p.id_property, p.propertyName, p.propertyDescription, p.longitude, p.latitude, p.price, p.active propertyActive,
                pt.id_propertyType, pt.propertyType, pt.active propertyTypeActive,
                c.id_city, c.cityName, c.active cityActive, s.id_state, s.stateName, s.active stateActive,
                u.id_user, u.name, u.lastName, u.phone, u.email, ut.id_userType, ut.userType, ut.active userTypeActive, u.password, u.active userActive
                from PropertyImages pi Left JOIN Property p ON pi.id_property = p.id_property
                left Join PropertyType pt ON p.id_propertyType = pt.id_propertyType
                Left JOIN City c ON p.id_city = c.id_city
                Left JOIN State s ON c.id_state = s.id_state
                Left JOIN User u ON p.id_user = u.id_user
                Left JOIN UserType ut ON u.id_userType = ut.id_userType;";
            //command
            $command = $connection->prepare($query);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($id_propertyImage, $url, $propertyImageActive,
                $id_property, $propertyName, $propertyDescription, $longitude, $latitude, $price, $propertyActive,
                $id_propertyType, $propertyType, $propertyTypeActive,
                $id_city, $cityName, $activeCity, $id_state, $stateName, $activeState,
                $id_user, $name, $lastname, $phone, $email, $id_userType, $userType, $userTypeActive, $password, $userActive);
            //fetch data
            while($command->fetch()){
                    $state = new State($id_state, $stateName, $activeState);
                    $userType = new UserType($id_userType, $userType, $userTypeActive);
                    $propertyType = new PropertyType($id_propertyType, $propertyType, $propertyTypeActive);
                    $city = new City($id_city, $cityName, $state, $activeCity);
                    $user = new User($id_user, $name, $lastname, $phone, $email, $userType, $password, $userActive);
                    $property = new Property($id_property, $propertyName, $propertyDescription, $propertyType, $city, $user, $longitude, $latitude, $price, $propertyActive);
                array_push($list, new PropertyImage($id_propertyImage, $property, $url, $propertyImageActive));
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