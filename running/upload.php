<?php
// upload todo 1画廊上传
include "../lib/examineClass.php";
include "../lib/galleryClass.php";
$userState = new userState;
$num1 = $userState->checkLogin();
$todo = $_POST['todo'];
if($todo==1){
    $userInput = new userInput;
    $furryArt = new furryArt;
    $file = $_FILES['file'];
    $title = $_POST['title'];
    $info = $_POST['info'];
    $type  = $_POST['type'];
    $visit = $_POST['visit'];
    $num2 = $userInput->checkUploadGallery($file,$title,$type,$visit);
    if($num1+$num2<2){echo"---===ZZWW 上传失败===---\n可能的触发原因：图片过大（不超过5M） 填写信息不完整";exit;}
    $furryArt->upload($file,$title,$info,$type,$visit);
    header("location:../index.php");
}