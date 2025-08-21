<?php
header('content-type:application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,OPTIONS");
header("Access-Control-Allow-Headers: *");
include "config.php";

$id = file_get_contents("php://input");
$id = json_decode($id,true);
$id = $id["id"];

if (!empty($conn)) {
    $query =$conn->prepare("SELECT * FROM projects WHERE id = ? ");
    $query->execute([$id]);
    $result =$query->fetchAll(PDO::FETCH_ASSOC);
    if ($result){
        echo json_encode($result);
    }
    else{
        echo json_encode(["message" => "Project not found $id"]);
    }
}

