<?php
    include "./lib/outClass.php";
    $homePage = new homePage;
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <?php echo"<title>粉糖粒子 关于</title>"; $homePage->headPart(); ?>
</head>
<body>
    <!-- ZZWW -->
    <?php
        $homePage->menu();
        $homePage->about();
    ?>
</body>
</html>