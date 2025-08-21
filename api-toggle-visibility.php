<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PATCH,OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$input = json_decode(file_get_contents("php://input"),true);
$id = $input['id'];
$visibility = $input['visibility'];

if(!isset($id)){
    echo json_encode(['status'=> 'error','message'=>'Id is required.']);
    exit();
}
if(!isset($visibility)){
    echo json_encode(['status'=> 'error','message'=>'Visibility is required.']);
    exit();
}
include "config.php";
$query = $conn->prepare("UPDATE `projects` SET visibility = ? where id = ?");

if($visibility === 1) $query->execute([0, $id]);
elseif ($visibility === 0) $query->execute([1, $id]);
if($query->rowCount() > 0){
    echo json_encode(['status'=> 'success','visibility'=>$visibility===0?1:0]);
}else{
    echo json_encode(['status'=> 'error','message'=>'Failed to Change visibility.']);
}