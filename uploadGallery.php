<?php
    include "./lib/outClass.php";
    include "./lib/galleryClass.php";
    include "./lib/examineClass.php";
    $gallery = new gallery;
    $homePage = new homePage;
    $userState = new userState;
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <?php echo"<title>粉糖粒子 上传作品</title>";$homePage->headPart(); ?>
</head>
<body>
    <!-- ZZWW -->
    <?php
        $num = $userState->checkLogin();
        $homePage->menu();
        if($num<1){$homePage->entry();}
        $gallery->uploadForm();
    ?>
</body>
</html>