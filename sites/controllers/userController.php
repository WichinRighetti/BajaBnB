<?php 
    //allow access control from outside the server 
    header('Access-Control-Allow-Origin: *');
    //allow methods
    header('Access-Control-Methods: GET, POST, PUT, DELETE');

    require_once($_SERVER['DOCUMENT_ROOT'].'/sites/models/user.php');
    
    // GET ( read the data from the database)
    if ($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_GET['Id'])){
            try {
                $u = new User($_GET['Id']);
                //display 
                echo json_encode(array(
                    'status' => 0,
                    'user' => json_decode($u->toJson())
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
                'user' => json_decode(User::getAllByJson())
            ));
        }
    }

?>