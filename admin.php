<?php
    include "./lib/examineClass.php";
    include "./lib/outClass.php";
    include "./lib/adminClass.php";
    $userState = new userState;
    $homePage = new homePage;
    $chiefAdmin = new chiefAdmin;
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <?php echo"<title>粉糖粒子 管理兽</title>";$homePage->headPart(); ?>
</head>
<body>
    <!-- ZZWW -->
    <?php
        $num1 = $userState->checkLogin();
        $homePage->menu();
        if($num1<1){$homePage->entry();exit;}
        $num2 = $chiefAdmin->checkIdentity();
        if($num2<1){exit;}
        $chiefAdmin->outGalleryControl();
    ?>
</body>
</html>