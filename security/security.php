<?php

    class Security{
        //get token
        public static function generateToken($user){
            //today
            $today = date_create();
            //generate Token
            return sha1($user.date_format($today, 'Ymd'));
            
        }
    }
?>