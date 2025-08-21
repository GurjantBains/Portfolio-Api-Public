<?php

header('content-type: application/json');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,OPTIONS');
header('Access-Control-Allow-Headers:content-type');

include 'config.php';

$messages = $conn->prepare("SELECT * FROM `messages` ORDER BY `id` DESC");
$messages->execute();
$result = $messages->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['status'=> 'success','messages' => $result]);
