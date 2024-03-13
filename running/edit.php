<?php
// edit todo 1编辑个兽信息 2编辑画廊信息
include "../lib/userClass.php";
include "../lib/galleryClass.php";
$todo = $_POST['todo'];
if($todo==1){
    $userAction = new userAction;
    $num = $userAction->editMyself();
    if($num<1){echo"---===ZZWW 编辑失败===---";}
    else{header("location:../user.php");}
}
else if($todo==2){
    $galleryId = $_POST['galleryId'];
    $furryArt = new furryArt;
    $num = $furryArt->editGallery($galleryId);
    if($num<1){echo"---===ZZWW 编辑失败===---";}
    else{header("location:../user.php");}
}