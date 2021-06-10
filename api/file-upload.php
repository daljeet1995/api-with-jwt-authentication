<?php

header("Content-Type: application/json");
header("Acess-Control-Allow-Origin: *");
header("Acess-Control-Allow-Methods: POST");
header("Acess-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Methods, Authorization");


//db connection open

$DBhost = "localhost";
$DBuser = "root";
$DBpassword ="";
$DBname="api_db";

$conn = mysqli_connect($DBhost, $DBuser, $DBpassword, $DBname); 

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}


//db connection close

$target_dir = 'upload/'; // set upload folder path 
$res = array();
$extension = array('jpeg', 'jpg', 'png', 'gif'); 

// $fileName  =  $_FILES['filetoupload']['name'];
// $tempPath  =  $_FILES['filetoupload']['tmp_name'];
// $fileSize  =  $_FILES['filetoupload']['size'];
if (!empty($_FILES['filetoupload']['name'] != "")) {


    foreach ($_FILES['filetoupload']['tmp_name'] as $key => $tmp_name) {
         $file_name  =  $_FILES['filetoupload']['name'][$key];
         $file_temp  =  $_FILES['filetoupload']['tmp_name'][$key];
         $ext = pathinfo($file_name,PATHINFO_EXTENSION);

         $target_file = $target_dir . $file_name;

         if (in_array($ext, $extension)) {
             if (!file_exists($target_file)) {
                 move_uploaded_file($file_temp=$_FILES['filetoupload']['tmp_name'][$key],$target_file );
                 $qry2 = mysqli_query($conn,'INSERT into tbl_image (name) VALUES("'.$file_name.'")');
                 $res['data']['image'][] =  $file_name;
                   $errorMSG = json_encode(array("message" => "Sorry, your file is too large, please upload 5 MB size", "status" => false));   
                echo $errorMSG;
             }else{
                $filename = basename($file_name,$ext);
                $newFileName = $file_name.time().".".$ext;
                $target_file = $target_dir . $newFileName;
                move_uploaded_file($file_temp=$_FILES['filetoupload']['tmp_name'][$key],$target_file );
                 $qry2 = mysqli_query($conn,'INSERT into tbl_image (name) VALUES("'.$newFileName.'")');
                 $res['data']['image'][] =  $newFileName;
                   $errorMSG = json_encode(array("message" => "Sorry, your file is too large, please upload 5 MB size", "status" => false));   
                echo $errorMSG;
             }
         }else{
            array_push($res, "$file_name, ");
         }
    }
}else{
    $res['data']    = array();
    $res['status']  = 'failed'; 
    $res['message'] ='Please passed image';
}