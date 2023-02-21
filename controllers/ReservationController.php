<?php
    //ALLOW ACCESS FROM OUTSIDE THE SERVER
    header('Access-Control-Allow-Origin: *');
    //Allow methods
    header('Access-Control-Methods: GET, POST, PUT, DELETE');

    require_once($_SERVER['DOCUMENT_ROOT'].'/BajaBnB/models/Reservation.php');

    //get (read)
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        //parameters
        if(isset($_GET['id'])){
            try{
                $r = new Reservation($_GET['id']);
                //display
                echo json_encode(array(
                    'status'=> 0,
                    'reservation' => json_decode(($pt->toJson()))
                ));
            }catch(RecordNotFOundException $ex){
                echo json_encode(array(
                    'status' => 1,
                    'errorMessage' => $ex->get_message()
                ));
            }
        }else{
            echo json_encode(array(
                'status' => 0,
                'reservation' => json_decode(Reservation::getAllByJson())
            ));
        }
    }
    //post (add)
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['id_user'], $_POST['id_property'], $_POST['startDate'], $_POST['endDate'])){
            //Error
            $error = false;
            //id_user
            try{
                $user = new User($_POST['id_user']);
            }catch(RecordNotFOundException $ex){
                echo json_encode(array(
                    'status'=>2,
                    'errorMessage'=> 'user not found'
                ));
                $error = true;
            }
            //id_user
            try{
                $property = new Property($_POST['id_property']);
            }catch(RecordNotFOundException $ex){
                echo json_encode(array(
                    'status'=>2,
                    'errorMessage'=> 'property not found'
                ));
                $error = true;
            }
            if(!$error){
                //create an empty object
                $r = new Reservation();
                //set values
                $r->setProperty($property);
                $r->setUser($user);
                $r->setStartDate($_POST['startDate']);
                $r->setEndDate($_POST['endDate']);
                if($r->add()){
                    echo json_encode(array(
                        'status' => 0,
                        'message'=>'Reservation added succsessfully'
                    ));
                }else{
                    echo json_encode(array(
                        'status' => 3,
                        'errorMessage'=>'Could not add the reservation'
                    ));
                }
            }
        }
    }
?>