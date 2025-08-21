<?php


header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST,OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');


include 'config.php';

$input = json_decode(file_get_contents('php://input'), true);


$name = $_POST['title'];
$mainImage = $_FILES['mainImage'];
$desc = $_POST['desc'];
$projectUrl = $_POST['projectUrl']??"";
$sectionImage = $_FILES['sectionImage']??"";
$select = $_POST['select'];
$urls = $_POST['urlCode'];
$select = $select==='public'?1:0;



$token = getenv('TOKEN');
$repo = getenv('REPO');


$branch = 'main';
try{

$projectName = $name?$name.time():"Project".time() ;

if(!isset($_FILES["mainImage"])  ){
    echo json_encode(["message" => "Please upload an image"]);
    exit;
}

$filesToUpload[] = $mainImage;

$response1 =[];
if(isset($_FILES['sectionImage'])){

foreach($sectionImage['tmp_name'] as $index => $tmpName) {
    $filesToUpload[] =[
        'name' => $sectionImage['name'][$index],
        'tmp_name' => $tmpName
    ];
}
}

    $i=0;
foreach($filesToUpload as $file) {
    $newName = "Image-$i";
    $i++;


$newImage = compressImage($file['tmp_name']);
$fileContents = file_get_contents($newImage);
$base64Image = base64_encode($fileContents);

$filename = $file['name'];

$pathInRepo = "$projectName/$newName".time().".jpeg";
$pathInRepo = preg_replace('/\s+/', '', $pathInRepo);

$data =[
    "message" => "Upload $pathInRepo via API",
    "content" => $base64Image,
    "branch" => $branch
];

$ch = curl_init("https://api.github.com/repos/$repo/contents/images/$pathInRepo");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_USERAGENT,'PHP');
curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"PUT");
curl_setopt($ch,CURLOPT_HTTPHEADER,
    [
        "Authorization: token $token",
        "Content-Type: application/json",
        "User-Agent: PHP"
    ]);
curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($data));
$result = curl_exec($ch);
if($result === false){
    die("cURL Error: " . curl_error($ch));

}

$resData = json_decode($result,true);
    if(isset($resData['content']['path'])){
        $commitSha = $resData['commit']['sha'];
        $cdnUrl = "https://cdn.jsdelivr.net/gh/$repo@$commitSha/".$resData['content']['path'];
        $response1[]= $cdnUrl;
    }
    else{
        echo json_encode(["message" => $result]);
        exit();
    }

curl_close($ch);

}


$urls = json_decode($urls);


if(isset($_FILES['sectionImage'])) {
    $sections = packData($response1);
}else{
    $sections = [];
}
}catch (Exception $e){
    echo json_encode(["message" => $e->getMessage()]);
    exit();
}


try {
    $query = $conn->prepare("INSERT INTO projects (name, description,sections,urls,mainImage,visibility,projectUrl) VALUES (?,?,?,?,?,?,?)");
    $query->execute([$name, $desc, json_encode($sections), json_encode($urls), $response1[0], $select,$projectUrl]);
    echo json_encode(["success" => true]);
    exit();
}
catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);

}

function compressImage($sourceImage,$quality = 75){
    $info = getimagesize($sourceImage);
    $mime = $info['mime'];

    switch($mime){
        case 'image/gif':
            $image = imagecreatefromgif($sourceImage);
            break;
        case 'image/jpeg':
        case 'image/jpg':
            $image = imagecreatefromjpeg($sourceImage);
            break;
        case 'image/png':
            $image = imagecreatefrompng($sourceImage);
            break;
        default:
            return $sourceImage;

    }
    imagejpeg($image,$sourceImage,$quality);
    imagedestroy($image);
    return $sourceImage;

}

function packData($response): array
{
    $SectionTitle = $_POST['SectionTitle'];
    $SectionDesc = $_POST['sectionDesc'];
    $result = [];
    foreach($SectionTitle as $key => $value) {
        $result[]=[
            $value,
            $SectionDesc[$key],
            $response[$key+1]
        ];
    }
    return $result;
}




