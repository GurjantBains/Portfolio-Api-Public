<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');



include 'config.php';
$offset = json_decode(file_get_contents("php://input"),true)['offset'];
$limit = json_decode(file_get_contents("php://input"),true)['limit'];
$offset = intval($offset*$limit);

try{

$query =$conn->prepare("SELECT id,name,mainImage,description,createdAt,visibility FROM projects ORDER BY visibility DESC LIMIT :limit OFFSET :offset ");
$query->bindValue(':limit',$limit,PDO::PARAM_INT);
$query->bindValue(':offset',$offset,PDO::PARAM_INT);
$query->execute();
$result=$query->fetchAll(PDO::FETCH_ASSOC);
if($result){
    echo json_encode(['success'=>true,'data'=>$result,"noMore"=>false]);
    exit();
}
else{
    echo json_encode(['success'=>true,"noMore"=>true]);
}
}catch(PDOException $e){
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}

