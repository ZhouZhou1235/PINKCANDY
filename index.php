<?php
    include "./lib/examineClass.php";
    include "./lib/outClass.php";
    include "./lib/adminClass.php";
    $userState = new userState;
    $homePage = new homePage;
    $gallery = new gallery;
    $chiefAdmin = new chiefAdmin;
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <?php $homePage->headPart(); ?>
</head>
<body>
    <!-- ZZWW -->
    <?php
        $num1 = $userState->checkLogin();
        $homePage->menu();
        if($num1<1){$homePage->entry();}else{$homePage->furryUser();}
        $num2 = $chiefAdmin->checkIdentity();
        if($num2==1){$chiefAdmin->entry();}
        $gallery->showGalleryIndex();
    ?>
</body>
</html>