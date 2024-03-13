<?php
// enter todo 1注册 2登录 3注销
include "../lib/userClass.php";
$todo = $_POST['todo'];
if($todo==1){
    $access = new access;
    $out = $access->register();
    if($out==1){header("location:../index.php");}
    else{echo"---===ZZWW 注册失败===---\n可能的触发原因：填写不完整";}
}
else if($todo==2){
    $access = new access;
    $out = $access->login();
    if($out==1){echo"---===ZZWW 登录成功===---";header("location:../index.php");}
    else{echo"---===ZZWW 登录失败===---\n可能的触发原因：账号或密码不正确";exit;}
}
else if($todo==3){
    $access = new access;
    $out = $access->logout();
    if($out==1){echo"---===ZZWW 注销成功===---";header("location:../index.php");}
    else{echo"---===ZZWW 注销失败===---";exit;}
}
else{echo"ZZWW 非法操作";exit;}