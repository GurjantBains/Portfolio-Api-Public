<?php
namespace controllers;
require_once "BaseController.php";
//require_once "../middleware/middleware.php";
require_once __DIR__ . "/../middleware/middleware.php";
use Database\Database as DB;
use middleware\middleware;

class AdminController extends BaseController
{
    private $logged = false;

    public function project($id){
        $DB=self::loginToken();
        if(!$DB instanceof DB ) return;
        if($this->logged){
            $results =  $DB->query("SELECT * FROM `projects` WHERE `id` = ?",[$id]);
            echo json_encode($results);

        }
        else{
            http_response_code(401);
            echo json_encode(['success'=>false,'message'=>
                'wrong Token.']);
        }
    }

    public function projects(){
        $DB=self::loginToken();
        if(!$DB instanceof DB ) return;
        if($this->logged){
         $results =  $DB->query("SELECT id,name,description,mainImage  FROM projects where visibility = 1");
         echo json_encode($results);
        }
    }
    public function login(){
        $request = json_decode(file_get_contents('php://input'),true);
        $email = $request['email']??"";
        $password = $request['password']??"";
        $token = middleware::Auth(['email'=>$email,'password'=>$password]);
        if($token){
            echo json_encode(['success'=>true,'token'=>$token]);
        }
        else{
            echo json_encode(['success'=>false]);
        }
        exit();
//        return;
    }
    public function loginToken()
    {
        $request = json_decode(file_get_contents('php://input'),true);
        $token = $request['token']??"";
        if($token){
        $verify = middleware::AuthVerifyToken($token);
        $this->logged = $verify[1];
        return $verify[0];
        }
       exit(json_encode(['success'=>false,'error'=>'Enter valid token']));

    }
    public function logout($token){
        $request = json_decode(file_get_contents('php://input'),true);
        $token = $request['token']??"";
        if($token){
            middleware::AuthDeleteToken($token);
        }
    }

}