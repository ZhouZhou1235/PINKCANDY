<?php
// send todo 1留言板发送 2画廊评论 3添加和贴上标签 4搜索标签
include "../lib/userClass.php";
include "../lib/examineClass.php";
$userState = new userState;
$userAction = new userAction;
$num1 = $userState->checkLogin();
$todo = $_POST['todo'];if(!$todo){$todo=$_GET['todo'];}
if($todo==1){
    $num2 = $userAction->inputBoard();
    if($num1+$num2<2){echo"---===ZZWW 发送失败===---";}
    else{header("location:../board.php");}
}
else if($todo==2){
    $comment = $_POST['comment'];
    $galleryId = $_POST['galleryId'];
    $num2 = $userAction->sendComments($comment,$galleryId);
    if($num1+$num2<2){echo"---===ZZWW 发送失败===---";}
    else{header("location:../gallery.php?galleryId=$galleryId");}
}
else if($todo==3){
    $galleryId = $_POST['galleryId'];
    $tags = $_POST['tags'];
    $dataExist = new dataExist;
    $num2 = $dataExist->checkGalleryExist($galleryId);
    if($num2<1){echo"---===ZZWW 找不到ID为 $galleryId 画廊===---";exit;}
    $num3 = $userAction->addTagsAndConnect($tags,$galleryId);
    if($num1+$num2+$num3<3){echo"---===ZZWW 添加失败===---";}
    else{header("location:../tags.php");}
}
else if($todo==4){
    $search = $_POST['search'];
    $userAction->searchTagsAndShow($search);
}
else if(!$todo){
    $preSearch = $_POST['preSearch'];
    $userAction->preSearchTags($preSearch);
}