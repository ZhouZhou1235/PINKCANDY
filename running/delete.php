<?php
// delete todo 1删除留言 2删除评论 3取消收藏 4撕下标签 5取消关注
include "../lib/userClass.php";
include "../lib/examineClass.php";
$userAction = new userAction;
$userState = new userState;
$num = $userState->checkLogin();
if($num<1){echo"---===ZZWW 非法操作===---\n可能的触发原因：未登录";exit;}
$todo = $_POST['todo'];if(!$todo){$todo=$_GET['todo'];}
if($todo==1){
    $boardId = $_POST['boardId'];
    $num1 = $userAction->delMessage($boardId);
    if($num1<1){echo"---===ZZWW 删除失败===---\n可能的触发原因：找不到目标数据";}
    else{header("location:../editOther.php");}
}
else if($todo==2){
    $commentId = $_POST['commentId'];
    $num1 = $userAction->delComments($commentId);
    if($num1<1){echo"---===ZZWW 删除失败===---\n可能的触发原因：找不到目标数据";}
    else{header("location:../editOther.php");}
}
else if($todo==3){
    $starId = $_POST['starId'];
    $num1 = $userAction->delStar($starId);
    if($num1<1){echo"---===ZZWW 取消收藏失败===---\n可能的触发原因：找不到目标数据";}
    else{echo"<script>window.history.go(-1);</script>";}
}
else if($todo==4){
    $galleryId = $_POST['galleryId'];
    $tags = $_POST['tags'];
    $dataExist = new dataExist;
    $num1 = $dataExist->checkGalleryExist($galleryId);
    if($num1<1){echo"---===ZZWW 找不到ID为 $galleryId 画廊===---";exit;}
    $num2 = $userAction->delConnect($tags,$galleryId);
    if($num1+$num2<2){echo"---===ZZWW 撕下标签失败===---\n可能的触发原因：找不到目标数据";exit;}
    else{header("location:../tags.php");}
}
else if($todo==5){
    $username = $_GET['username'];
    $num1 = $userAction->unWatch($username);
    if($num1<1){echo"---===ZZWW 取消关注失败===---\n可能的触发原因：未登录 未关注";exit;}
    else{header("location:../user.php?username=$username");}
}