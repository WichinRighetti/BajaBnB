<?php
    //allow access
    header('Access-Control-Allow-Origin: *');
    //allow methos
    header('Access-Control-Methods: GET, POST, PUT, DELETE');
    //allow headers
    header('Access-Control-Allow-Headers: user, password');
    //read headers
    $headers = getallheaders();
    //use class User
    require_once($_SERVER['DOCUMENT_ROOT'].'/BajaBnB/models/user.php');
    //use class security
    require_once($_SERVER['DOCUMENT_ROOT'].'/BajaBnB/security/security.php');

    //check if headers were recived
    if(isset($headers['user'],$headers['password'])){
        //authenticate user
        try{
            // new user object
            $u = new user($headers['user'],$headers['password']);
            //display 
            echo json_encode(array(
                'status'=>0,
                'user' => json_decode($u->toJson()),
                'token' => Security::generateToken($headers['user'])
            ));
        }catch(InvalidUserException $ex){
            echo json_encode(array(
                'status'=>2,
                'errorMessage' => $ex->get_message()
            ));
        }
    }else{
        echo json_encode(array(
            'status' => 1,
            'errorMessage' => 'missing Headers'
        ));
    }
?>