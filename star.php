<?php
    include "./lib/outClass.php";
    include "./lib/examineClass.php";
    $homePage = new homePage;
    $userState = new userState;
    $otherPage = new otherPage;
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <?php echo"<title>粉糖粒子 收藏</title>";$homePage->headPart(); ?>
</head>
<body>
    <!-- ZZWW -->
    <?php
        $num = $userState->checkLogin();
        $homePage->menu();
        if($num<1){$homePage->entry();exit;}
        $username = $_GET['username'];if(!$username){$username=$_SESSION['username'];}
        $otherPage->star($username);
    ?>
</body>
</html>