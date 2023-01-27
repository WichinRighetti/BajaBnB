<?php 
    //allow access control from outside the server 
    header('Access-Control-Allow-Origin: *');
    //allow methods
    header('Access-Control-Methods: GET, POST, PUT, DELETE');

    require_once($_SERVER['DOCUMENT_ROOT'].'/BajaBnB/sites/models/site.php');
    
    // GET ( read the data from the database)
    if ($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_GET['Id'])){
            try {
                $s = new Site($_GET['Id']);
                //display 
                echo json_encode(array(
                    'status' => 0,
                    'Site' => json_decode($s->toJson())
                ));
            }
            catch(RecordNotFoundException $ex){
                echo json_encode(array(
                    'status' => 1,
                    'errormessage' => $ex->get_message()
                ));
            }
        }else{
            //display
            echo json_encode(array(
                'status' => 0,
                'Site' => json_decode(Site::getAllByJson())
            ));
        }
    }

?>