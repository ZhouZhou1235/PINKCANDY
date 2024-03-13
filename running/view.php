<?php
// view todo
include "../lib/userClass.php";
include "../lib/examineClass.php";
$userState = new userState;
$userAction = new userAction;
$num = $userState->checkLogin();
if($num<1){echo"---===ZZWW 请先登录===---";exit;}
$todo = $_POST['todo'];if(!$todo){$todo=$_GET['todo'];}
if($todo==1){
    
}