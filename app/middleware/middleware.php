<?php

namespace middleware;
use Database\Database as DB;
use DateTime;


class middleware
{
    public static function Auth($credentials)
    {
        $DB = new DB();
        $DB->connectDB();
        $result = $DB->query('SELECT * FROM `user` WHERE email = ?',[$credentials['email']]);
        if ($result) {

            if(password_verify($credentials['password'], $result[0]['password'])){
               return self::AuthGenerateToken($DB);
            }
            return null;
        }
        return null;

    }
    public static function AuthGenerateToken($DB)
    {
        $token = bin2hex(random_bytes(32));
        $expiry = new DateTime();
        $expiryString = $expiry->format('Y-m-d H:i:s');
        $expiry->setTimestamp(time() + 604800);
        if($token){
             $DB->query('INSERT INTO `tokens`  (token ,expiry) VALUES (?,?) ',[$token,$expiryString]);
        }
        return $token;
    }
    public static function AuthVerifyToken($token):array
    {
        $DB = new DB();
        $DB->connectDB();
        if($token){
         $result = $DB->query('SELECT * FROM tokens WHERE token = ?',[$token]);
         if(count($result)>0){


             if($result[0]['expiry']<time()){
                 self::AuthDeleteToken($token,$DB);
                 echo json_encode(['success'=>false,'message'=>'Token expired']);
                 exit();
             }
        return [$DB,count($result) > 0];
         }
        }
        echo json_encode(['success'=>false,'message'=>'Invalid token']);
    exit();
    }
    public static function AuthDeleteToken($token,$DB=null): void
    {
        if($DB===null){
        $DB = new DB();
        $DB->connectDB();
        }
        if($token){
            $result = $DB->query('DELETE FROM tokens WHERE token = ?',[$token]);
        }
        echo json_encode(['success'=>true,'message'=>'Token deleted']);
        exit();
    }

}