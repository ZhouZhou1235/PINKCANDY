<?php
    include "./lib/outClass.php";
    include "./lib/examineClass.php";
    $homePage = new homePage;
    $userState = new userState;
    $gallery = new gallery;
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <?php echo"<title>粉糖粒子 画廊</title>"; $homePage->headPart(); ?>
</head>
<body>
    <!-- ZZWW -->
    <?php
        $num = $userState->checkLogin();
        $homePage->menuFull();
        if($num<1){$homePage->entry();}
        $galleryId = $_GET['galleryId'];
        $gallery->showGallery($galleryId);
        $gallery->showComments($galleryId);
    ?>
</body>
</html>