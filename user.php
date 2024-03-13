<?php
    include "./lib/outClass.php";
    include "./lib/examineClass.php";
    $userZone = new userZone;
    $homePage = new homePage;
    $userState = new userState;
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <?php echo"<title>粉糖粒子 个兽空间</title>";$homePage->headPart(); ?>
</head>
<body>
    <!-- ZZWW -->
    <?php
        $num=$userState->checkLogin();
        $homePage->menu();
        if($num<1){$homePage->entry();exit;}
        $furryUser = $_SESSION['username'];
        $look = $_GET['username'];
        if(!$look){
            $userZone->myPage();
            $userZone->showGalleryUser($furryUser);
        }
        else{
            $userZone->lookUser($look);
            $userZone->showGalleryUser($look);
        }
    ?>
</body>
</html>