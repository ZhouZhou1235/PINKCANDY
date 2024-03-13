<?php
    include "./lib/examineClass.php";
    include "./lib/userClass.php";
    include "./lib/outClass.php";
    $userZone = new userZone;
    $homePage = new homePage;
    $userState = new userState;
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <?php
        $furryUser=$_GET['username'];
        echo"<title>编辑 $furryUser 的个兽信息</title>";
        $homePage->headPart();
    ?>
</head>
<body>
    <!-- ZZWW -->
    <?php
        $num = $userState->checkLogin();
        $homePage->menuFull();
        if($num<1){$homePage->entry();exit;}
        $userZone->editPage();
    ?>
</body>
</html>