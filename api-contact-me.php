<?php
//header('Access-Control-Allow-Methods:*');
//header('Access-Control-Allow-Origin:*');
//header('Content-type: application/json');
//header("Access-Control-Allow-Methods: POST,OPTIONS");
//
//include 'config.php';
//
//$input = json_decode(file_get_contents('php://input'),true);
//$name = $input['name'];
//$email = $input['email'];
//$message = $input['message'];
//if (empty($name) || empty($email) || empty($message)) {
//    echo json_encode(['status'=>'error' , "message" => "All fields are required."]);
//}
//
//
//
//if (!empty($conn)) {
//    try {
//
//    $query = $conn->prepare("INSERT INTO contact (name, email, message) VALUES (?,?,?)");
//    $query->execute([$name, $email, $message]);
//    echo json_encode(['status'=>'success']);
//    }catch (PDOException $e){
//        echo json_encode(['status'=>'error' , "message" => $e->getMessage()]);
//    }
//
//}


header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');



include 'config.php';

$input = json_decode(file_get_contents('php://input'), true);
$name = $input['name'] ?? '';
$email = $input['email'] ?? '';
$message = $input['message'] ?? '';

if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.',"s"=>$input]);
    exit();
}

if (!empty($conn)) {
    try {
        $query = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
        $query->execute([$name, $email, $message]);
        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
