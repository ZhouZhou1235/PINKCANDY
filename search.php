<?php
    include "./lib/outClass.php";
    $homePage = new homePage;
    $otherPage = new otherPage;
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <?php $homePage->headPart(); ?>
</head>
<body>
    <!-- ZZWW -->
    <?php
        $homePage->menu();
        $otherPage->search();
    ?>
</body>
</html>