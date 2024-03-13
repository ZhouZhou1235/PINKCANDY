<?php
// mark todo 1收藏 2关注
include "../lib/userClass.php";
include "../lib/examineClass.php";
$userState = new userState;
$userAction = new userAction;
$num = $userState->checkLogin();
if($num<1){echo"---===ZZWW 请先登录===---";exit;}
$todo = $_POST['todo'];if(!$todo){$todo=$_GET['todo'];}
if($todo==1){
    $galleryId = $_POST['galleryId'];
    $userAction = new userAction;
    $num1 = $userAction->addStar($galleryId);
    if($num1<1){echo"---===ZZWW 收藏失败===---\n可能的触发原因：已收藏";}
    else{echo"<script>window.history.go(-1);</script>";}
}
else if($todo==2){
    $username = $_GET['username'];
    $num1 = $userAction->watchUser($username);
    if($num1<1){echo"---===ZZWW 关注失败===---\n可能的触发原因：已关注";}
    else{header("location:../user.php?username=$username");}
}