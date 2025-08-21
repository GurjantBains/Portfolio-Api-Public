<?php
header('content-type:application/json');
header("Access-Control-Allow-Origin: *");
include "config.php";


if (!empty($conn)) {
    $query =$conn->prepare("SELECT id,name,description,mainImage FROM projects WHERE visibility = 1 ORDER BY id ASC ");
    $query->execute();
    echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));

}

