<?php
    class RecordNotFoundException extends Exception{
        //message
        public function get_message(){
            return 'Record not found';
        }
    }

    class InvalidUserException extends Exception{
        //message
        public function get_message(){
            return 'User not found';
        }
    }
?>