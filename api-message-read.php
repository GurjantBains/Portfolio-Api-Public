<?php

header('content-type: application/json');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,OPTIONS');
header('Access-Control-Allow-Headers:content-type');

include 'config.php';

$input = json_decode(file_get_contents('php://input'), true);

$id = $input['id']??"";


if(isset($id)){
    try {

    $query = $conn->prepare('UPDATE `messages` SET `read` = 1 WHERE id=?');
    $query->execute([$id]);
    echo json_encode(['status'=>'success','read'=>1,'id'=>$id]);
    }
    catch(PDOException $e){
        echo json_encode(['status'=>'error',"Error: "=>$e->getMessage()]);
    }
}

