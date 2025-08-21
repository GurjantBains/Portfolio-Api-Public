<?php
header('content-type: application/json');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:DELETE,OPTIONS');
header('Access-Control-Allow-Headers:content-type');

$input = json_decode(file_get_contents('php://input'),true);
$id = $input['id'];

if(isset($id)){
    include 'config.php';

    try {
        $query = $conn->prepare("DELETE FROM messages WHERE id = ?");
        $query->execute([$id]);
        if($query->rowCount() > 0){
        echo json_encode(['status'=>'success','id'=>$id]);
        exit();
        }
        else{
            echo json_encode(['status'=>'error']);
            exit();
        }
    }catch (PDOException $e){
        echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
        exit();
    }
}else{
    json_encode(['status'=>'error','error'=>'id is required']);
    exit();
}