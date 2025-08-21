<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE,OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$input = json_decode(file_get_contents("php://input"),true);
$id = $input['id'];

if(!isset($id)){
    echo json_encode(['status'=>'error','message'=>'id is required']);
    exit();
}
include 'config.php';

$query = $conn->prepare("DELETE FROM projects WHERE id = ?");
$query->execute([$id]);
if($query->rowCount() > 0){
    echo json_encode(['status'=>'success','message'=>'Project deleted successfully']);
}
else{
    echo json_encode(['status'=>'error','message'=>'Project not deleted']);
}
