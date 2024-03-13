<?php
namespace {
    class chiefAdmin {
        // 总管理兽
        function checkIdentity(){
            // 检查总管理兽身份
            $furryUser = $_SESSION['username'];
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            // 定义总管理兽的粉糖账号为10000 具有总管理兽徽章 徽章号10000
            $sql = "select * from badges_account where code='10000' and username='$furryUser' and approve='10000'";
            $result = mysqli_query($link,$sql);
            if($result->num_rows==0){return 0;}
            return 1;
        }
        function editGalley($galleryId){
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $title=$_POST['title'];$info=$_POST['info'];$type=$_POST['type'];$visit=$_POST['visit'];
            $userInput = new userInput;
            $dataExist = new dataExist;
            $num1 = $this->checkIdentity();
            $num2 = $userInput->checkEditGallery($title,$type,$visit);
            $num3 = $dataExist->checkGalleryExist($galleryId);
            if($num1+$num2+$num3<3){return 0;}
            $sql = "update gallery set title='$title',info='$info',type='$type',visit='$visit' where id='$galleryId'";
            $result = mysqli_query($link,$sql);$result->num_rows;
            return 1;
        }
        function outGalleryControl(){
            // 显示画廊控制表单
            $url = "./resource/template/galleryControl.html";
            $outStr = file_get_contents($url);
            echo $outStr;
            return 1;
        }
        function entry(){
            // 管理界面入口
            echo "<div class='screen'><a href='admin.php'>管理兽</a></div>";
            return 1;
        }
    }
}