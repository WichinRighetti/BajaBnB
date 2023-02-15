<?php
    //Alow acces from outside the server
    header('Access-Control-Allow-origin: *');
    //Allow methods
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); //Read, inster, update, delete

    require_once($_SERVER['DOCUMENT_ROOT'].'/BAJABN/models/City.php');

    //GET reaad
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        //Parameter
        if(isset($_GET['id'])){
            try{
                $s = new City($_GET['id']);
                //Display
                echo json_encode(array(
                    'status' => 0,
                    'site' => json_decode($s->toJson())
                ));
            }catch(RecordNotFoundException $ex){
                echo json_encode(array(
                    'status' => 1,
                    'errorMessage' => $ex->get_message()
                ));
            }
        }else{
            //Display
            echo json_encode(array(
                'status' => 0,
                'site' => json_decode(City::getAllByJson())
            ));
        }
    }
?>