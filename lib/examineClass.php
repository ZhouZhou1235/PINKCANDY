<?php
namespace {
    class userInput {
        // 输入检查
        function checkRegister($username,$password,$sex,$sort,$email){
            // 检查注册输入
            if(empty($username)||empty($password)||empty($sex)||empty($sort)||empty($email)){echo"ZZWW 任何必填项不能为空";return 0;}
            if(!is_numeric($username)){echo"ZZWW 粉糖账号必须为数字";return 0;}
            if(strlen($username)!=5){echo"ZZWW 粉糖账号必须为五位数字";return 0;}
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){echo"ZZWW 不是正确的邮箱地址";return 0;}
            return 1;
        }
        function avoidRepeat($username,$email){
            // 避免同号
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "select username,email from account where username='$username' or email='$email'";
            $result = mysqli_query($link,$sql);
            if($result->num_rows>0){echo"ZZWW 此粉糖账号已被占用或此邮箱已被绑定";return 0;}
            return 1;
        }
        function checkLogin($username,$pendPassword){
            // 核验用户和密码
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "select username,password from account where username='$username'";
            $result = mysqli_query($link,$sql);$result->num_rows;
            $account = mysqli_fetch_array($result);
            $realUser=$account['username'];$realPass=$account['password'];
            if(!($username==$realUser)||!password_verify($pendPassword,$realPass)){return 0;}
            return 1;
        }
        function checkUploadGallery($file,$title,$type,$visit){
            // 检查上传作品完整性
            if(empty($file)||empty($title)||empty($type)||empty($visit)){return 0;}
            $fileName=$file['name'];$fileSize=$file['size'];
            $fileArray = pathinfo($fileName);$fileType = $fileArray['extension'];
            $allowType = array('jpg','gif','jpeg','png');
            if($fileSize>5*1024*1024){return 0;}
            if(!in_array($fileType,$allowType)){return 0;}
            return 1;
        }
        function checkEditGallery($title,$type,$visit){
            // 检查修改作品完整性
            if(empty($title)||empty($type)||empty($visit)){return 0;}
            return 1;
        }
        function checkEditUser($username,$password,$sex,$sort,$email){
            // 检查粉糖账号编辑内容
            if(empty($username)||empty($password)||empty($sex)||empty($sort)||empty($email)){echo"ZZWW 任何必填项不能为空";return;}
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){echo"ZZWW 不是正确的邮箱地址";return 0;}
            return 1;
        }
        function avoidEmailRepeat($username,$email){
            // 避免用户使用已经绑定的邮箱
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "select username,email from account where email='$email' and username='$username'";
            $result = mysqli_query($link,$sql);
            if($result->num_rows==0){
                $sql = "select username,email from account where email='$email'";
                $result = mysqli_query($link,$sql);
                if($result->num_rows>0){return 0;}
            }
            return 1;
        }
    }
    class userState {
        // 用户状态检查
        function checkLogin(){
            // 检查是否登录
            session_start();
            $username = $_SESSION['username'];
            $password = $_SESSION['password'];
            if(!$username||!$password){return 0;}
            return 1;
        }
        function checkMyself($username){
            // 检查是否是用户本兽操作
            session_start();
            $furryUser = $_SESSION['username'];
            if($username!=$furryUser){return 0;}
            return 1;
        }
        function checkUserAndGallery($galleryId){
            // 检查作品上传者和修改者是否一致
            session_start();
            $furryUser = $_SESSION['username'];
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "select username from gallery where id='$galleryId' and username='$furryUser'";
            $result = mysqli_query($link,$sql);
            if($result->num_rows==0){return 0;}
            return 1;
        }
    }
    class dataExist {
        // 数据存在类
        function checkGalleryExist($galleryId){
            // 检查画廊是否存在
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "select id from gallery where id='$galleryId'";
            $result = mysqli_query($link,$sql);
            if($result->num_rows==0){return 0;}
            return 1;
        }
        function avoidAddTagRepeat($tagId,$galleryId){
            // 避免标签被重复添加到关联表
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "select tagid,galleryid from connect where tagid='$tagId' and galleryid='$galleryId'";
            $result = mysqli_query($link,$sql);
            if($result->num_rows>0){return 0;}
            return 1;
        }
        function checkTagExist($tag){
            // 检查标签是否存在
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "select tag from tags where tag='$tag'";
            $result = mysqli_query($link,$sql);
            if($result->num_rows==0){return 0;}
            return 1;
        }
    }
}