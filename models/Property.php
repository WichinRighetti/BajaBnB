<?php
    //user files
    require_once('mysqlConnection.php');
    require_once('exceptions/recordNotFoundException.php');

    //Calse name
    Class Property{
        //attributes
        private $id_property;
        private $propertyName;
        private $propertyDescription;
        private $id_propertyType;
        private $id_city;
        private $id_user;
        private $longitude;
        private $latitude;
        private $price;
        private $active;

        //Setters and getters
        public function setIdProperty($value){$this->id_property = $value; }
        public function getIdProperty(){ return $this->id_property; }
        public function setPropertyName($value){$this->propertyName = $value; }
        public function getPropertyName(){ return $this->propertyName; }
        public function setPropertyDescription($value){$this->propertyDescription = $value; }
        public function getPropertuDescription(){ return $this->propertyDescription; }
        public function setIdPropertyType($value){$this->id_propertyType = $value; }
        public function getIdPropertyType(){ return $this->id_propertyType; }
        public function setIdCity($value){$this->id_city = $value; }
        public function getIdCity(){ return $this->id_city; }
        public function setIdUser($value){$this->id_user = $value; }
        public function getIdUser(){ return $this->id_user; }
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
                $this->id_propertyType = 1;
                $this->id_city = 0;
                $this->id_user = 0;
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
                $query = "Select id_property, propertyName, propertyDescription, id_propertyType, id_city, id_user, 
                longitude, latitude, price, active From Property Where id_property = ?";
                //command
                $command = $connection->prepare($query);
                //bind parameter
                $command->bind_param('i', $id);
                //execute
                $command->execute();
                //bind results
                $command->bind_result($id_property, $propertyName, $propertyDescription, $id_propertyType, $id_city, $id_user, 
                $longitude, $latitude, $price, $active);
                //Record was found
                if($command->fetch()){
                    //pass values to the attributes
                    $this->id_property = $id_property;
                    $this->propertyName = $propertyName;
                    $this->propertyDescription = $propertyDescription;
                    $this->id_propertyType = $id_propertyType;
                    $this->id_city = $id_city;
                    $this->id_user = $id_user;
                    $this->longitude = $longitude;
                    $this->latitude = $latitude;
                    $this->price = $price;
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
            if(func_num_args() == 10){
                //get arguments
                $arguments = func_get_args();
                //pass arguments to attributes
                $this->id_property = $arguments[0];
                $this->propertyName = $arguments[1];
                $this->propertyDescription = $arguments[2];
                $this->id_propertyType = $arguments[3];
                $this->id_city = $arguments[4];
                $this->id_user = $arguments[5];
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
                'idpropertyType' => $this->id_propertyType,
                'id_city' => $this->id_city,
                'id_user' => $this->id_user,
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
            $query = "Select id_property, propertyName, propertyDescription, id_propertyType, id_city, id_user, 
            longitude, latitude, price, active From Property";
            //command
            $command = $connection->prepare($query);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($id_property, $propertyName, $propertyDescription, $id_propertyType, $id_city, $id_user, 
            $longitude, $latitude, $price, $active);
            //fetch data
            while($command->fetch()){
                array_push($list, new Property($id_property, $propertyName, $propertyDescription, $id_propertyType, $id_city, 
                $id_user, $longitude, $latitude, $price, $active));
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