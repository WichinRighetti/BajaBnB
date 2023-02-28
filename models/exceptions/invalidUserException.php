<?php
    class InvalidUserException extends Exception{
        //attribute
        protected $message;

        //message
        public function get_message(){
            return $this->message;
        }

        //constructor
        public function __construct($user){
            $this->message = 'Access denied for user '.$user;
        }
    }
?>