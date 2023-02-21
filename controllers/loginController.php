<?php
    //allow access 
    header('Access-Control-Allow-Origin: *');
    //allow methods
    header('Access-Control-Methods: GET, POST, PUT, DELETE');
    //allow headers
    header('Access-Control-Allow-Headers: user, password');
    //read headers
    $headers = getallheaders();
    //use class User
    require_once($_SERVER['DOCUMENT_ROOT'].'/BAJABNB/models/User.php');
    //use security class
    require_once($_SERVER['DOCUMENT_ROOT'].'/BAJABNB/security/security.php');
    //check if headers were received
    if(isset($headers['user']) && isset($headers['password'])){
        //authenticate user
        try {
            //create user object
            $u = new User($headers['user'], $headers['password']);
            //display
            echo json_encode(array(
                'status' => 0,
                'user' => json_decode($u->toJson()),
                'token' => Security::generateToken($headers['user'])
            ));
        } catch(InvalidUserException $ex) {
            echo json_encode(array(
                'status' => 2,
                'errorMessage' => $ex->get_message()
            ));
        }
    }else {
        echo json_encode(array(
            'status' => 1,
            'errorMessage' => 'Missing Headers'
        ));
    }
?>