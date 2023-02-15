<?php
    //user files
    require_once('mysqlConnection.php');
    require_once('exceptions/recordNotFoundException.php');

    //Calse name
    Class PropertyImage{
        //attributes
        private $id_propertyImage;
        private $id_property;
        private $url;
        private $active;

        //Setters and getters
        public function setIdPropertyImage($value){$this->id_propertyImage = $value; }
        public function getIdPropertyImage(){ return $this->id_propertyImage; }
        public function setIdProperty($value){$this->id_property = $value; }
        public function getIdProperty(){ return $this->id_property; }
        public function setUrl($value){$this->url = $value; }
        public function getUrl(){ return $this->url; }
        public function setActive($value){$this->active = $value; }
        public function getActive(){ return $this->active; }
        
        //Constructors
        public function __construct(){
            //Empty construtor
            if(func_num_args() == 0){
                $this->id_propertyImage = 0;
                $this->id_property = 0;
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
                $query = "Select id_propertyImage, id_property, url, active From PropertyImages Where id_propertyImage = ?";
                //command
                $command = $connection->prepare($query);
                //bind parameter
                $command->bind_param('i', $id);
                //execute
                $command->execute();
                //bind results
                $command->bind_result($id_propertyImage, $id_property, $url, $active);
                //Record was found
                if($command->fetch()){
                    //pass values to the attributes
                    $this->id_propertyImage = $id_propertyImage;
                    $this->id_property = $id_property;
                    $this->url = $url;
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
            if(func_num_args() == 4){
                //get arguments
                $arguments = func_get_args();
                //pass arguments to attributes
                $this->id_propertyImage = $arguments[0];
                $this->id_property = $arguments[1];
                $this->url = $arguments[2];
                $this->active = $arguments[3];
            }
        }
        
        //represent the object in JSON format
        public function toJson(){
            return json_encode(array(
                'id_propertyImage' => $this->id_propertyImage,
                'id_property' => $this->id_property,
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
            $query = "Select id_propertyImage, id_property, url, active From PropertyImages";
            //command
            $command = $connection->prepare($query);
            //execute
            $command->execute();
            //bind results
            $command->bind_result($id_propertyImage, $id_property, $url, $active);
            //fetch data
            while($command->fetch()){
                array_push($list, new PropertyImage($id_propertyImage, $id_property, $url, $active));
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