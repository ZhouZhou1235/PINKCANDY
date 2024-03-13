<?php
// control todo 1管理兽修改画廊
include "../lib/examineClass.php";
include "../lib/adminClass.php";
$userState = new userState;
$chiefAdmin = new chiefAdmin;
$num = $userState->checkLogin();
if($num<1){echo"---===ZZWW 非法操作===---\n可能的触发原因：未登录";exit;}
$todo = $_POST['todo'];if(!$todo){$todo=$_GET['todo'];}
if($todo==1){
    $galleryId = $_POST['galleryId'];
    $num1 = $chiefAdmin->editGalley($galleryId);
    if($num1<1){echo"---===ZZWW 非法操作===---\n可能的触发原因：非管理兽 填写不完整 找不到画廊";}
    else{header("location:../admin.php");}
}