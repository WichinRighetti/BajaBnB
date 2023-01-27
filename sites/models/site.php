<?php
    //use files 
    require_once ('mysqlConnection.php');
    require_once ('exceptions/recordNotFoundException.php');
    require_once ('user.php'); 
    
    // class
    class Site{
        //attributes
        private $id;
        private $description;
        private $address;
        private $image;
        private $price;
        private $latitude;
        private $longitude;
        private $user;
        private $status;

        //getters and setters
        public function setId($value){$this->id = $value; }
        public function getId(){return $this->id;}
        public function setDescription($value){$this->description = $value;}
        public function getDescription(){return $this->description;}
        public function setAddress($value){$this->address = $value;}
        public function getAddress(){return $this->address;}
        public function setImage($value){$this->image = $value;}
        public function getImage(){return $this->image;}
        public function setPrice($value){$this->price = $value;}
        public function getPrice(){return $this->price;}
        public function setLatitude($value){$this->latitude = $value;}
        public function getLatitude(){return $this->latitude;}
        public function setLongitude($value){$this->longitude = $value;}
        public function getLongitude(){return $this->longitude;}
        public function setUser($value){$this->user = $value;}
        public function getUser(){return $this->user;}
        public function setStatus($value){$this->status = $value;}
        public function getStatus(){return $this->status;}

        public function __construct(){
            //empty constructor
            if(func_num_args() == 0){
                $this->id=0;
                $this->description = "";
                $this->address = "";
                $this->image = "";
                $this->price = 0;
                $this->latitude = 0;
                $this->longitude = 0;
                $this->user = new User();
                $this->status = 1;
            }
            //constructor con data de la BD
            if(func_num_args() == 1){
                //get id
                $id = func_get_arg(0);
                // get connection
                $connection = MysqlConnection::getConnection();
                //crear query para traer info
                $query = "Select s.Id, s.Description, s.Address, s.Image, s.Price, s.Latitude, s.Longitude, s.UserId, 
                          u.Name, u.LastName, u.Phone, u.Email, u.Password, u.Status,
                          s.Status From Sites s LEFT JOIN Users u ON s.UserId = u.Id  where s.Id = ?";
                //command
                $command = $connection->prepare($query);
                //bind parameters
                $command->bind_param('i', $id);
                //execute command
                $command->execute();
                //return result
                $command->bind_result($id, $description, $address, $image, $price, $latitude, $longitude, $userId, $Name, $LastName, $Phone, $Email, $Password, $userStatus, $status);
                //record was found successfuly
                if($command->fetch()){
                    //pass values to the attributes
                    $this ->id = $id;
                    $this ->description = $description;
                    $this ->address = $address;
                    $this ->image = $image;
                    $this ->price = $price;
                    $this ->latitude = $latitude;
                    $this ->longitude = $longitude;
                    $this ->user = new User($userId, $Name, $LastName, $Phone, $Email, $Password, $userStatus);
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
            if(func_num_args() == 9){
                // get args 
                $arguments = func_get_args();
                //pass arguments to attributes
                $this ->id = $arguments[0];
                $this ->description = $arguments[1];
                $this ->address = $arguments[2];
                $this ->image = $arguments[3];
                $this ->price = $arguments[4];
                $this ->latitude = $arguments[5];
                $this ->longitude = $arguments[6];
                $this ->user = $arguments[7];
                $this ->status = $arguments[8];
            }
        }   
        //represent the object in JSON format 
        public function toJson(){
            return json_encode(array(
                'Id' => $this->id,
                'Description' => $this->description,
                'Address' => $this->address,
                'Image' => $this->image,
                'Price' => $this->price,
                'Lat' => $this->latitude,
                'Long' => $this->longitude,
                'User' => json_decode($this->user->toJson()),
                'Status' => $this->status
            ));
        }
        public static function getAll(){
            $list = array();
            $connection = MysqlConnection::getConnection();
            $query = "Select s.Id, s.Description, s.Address, s.Image, s.Price, s.Latitude, s.Longitude, s.UserId, 
                     u.Name, u.LastName, u.Phone, u.Email, u.Password, u.Status UserStatus,
                     s.Status From Sites s LEFT JOIN Users u ON s.UserId = u.Id";
            $command = $connection->prepare($query);
            $command->execute();
            $command->bind_result($id, $description, $address, $image, $price, $latitude, $longitude, 
                                  $userId, $Name, $LastName, $Phone, $Email, $Password, $userStatus, $status);
            //fetch data 
            while($command->fetch()){
                $u = new User($userId, $Name, $LastName, $Phone, $Email, $Password, $userStatus);
                array_push($list, new Site($id, $description, $address, $image, $price, $latitude, $longitude, $u, $status));
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