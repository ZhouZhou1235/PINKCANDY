<?php
namespace {
    class access {
        // 用户通道
        function register(){
            // 注册
            require "examineClass.php";
            $username = $_POST['username'];
            $pendPassword = $_POST['password'];
            $name = $_POST['name'];
            $info = $_POST['info'];
            $sex = $_POST['sex'];
            $sort = $_POST['sort'];
            $email = $_POST['email'];
            $userInput = new userInput;
            $num1 = $userInput->checkRegister($username,$pendPassword,$sex,$sort,$email);
            $num2 = $userInput->avoidRepeat($username,$email);
            if($num1+$num2==2){
                $password = password_hash($pendPassword,PASSWORD_DEFAULT);
                $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
                $sql = "insert into account(username,password,name,info,sex,sort,email,grade,coin)
                values('$username','$password','$name','$info','$sex','$sort','$email',1,100)";
                $result = mysqli_query($link,$sql);$result->num_rows;
            }else{return 0;}
            return 1;
        }
        function login(){
            // 登录
            require "examineClass.php";
            $username = $_POST['username'];
            $pendPassword = $_POST['password'];
            $userInput = new userInput;
            $num1 = $userInput->checkLogin($username,$pendPassword);
            if($num1<1){return 0;}
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "select name,password from account where username='$username'";
            $result = mysqli_query($link,$sql);$result->num_rows;
            $account = mysqli_fetch_array($result);
            $name = $account['name'];$password=$account['password'];
            session_start();
            // $lifeTime = 72*3600;setcookie(session_name(),session_id(),time()+$lifeTime);
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            $_SESSION['name'] = $name;
            $_SESSION['view'] = 40;
            return 1;
        }
        function logout(){
            // 注销
            session_start();
            session_unset();
            session_destroy();
            return 1;
        }
    }
    class userAction {
        // 用户操作类
        function editMyself(){
            // 编辑个兽信息
            require "examineClass.php";
            $userInput = new userInput;
            $userState = new userState;
            $userState->checkLogin();
            $furryUser = $_SESSION['username'];
            $pendPassword = $_POST['password'];
            $name = $_POST['name'];
            $info = $_POST['info'];
            $sex = $_POST['sex'];
            $sort = $_POST['sort'];
            $email = $_POST['email'];
            $num1 = $userInput->checkEditUser($furryUser,$pendPassword,$sex,$sort,$email);
            $num2 = $userInput->avoidEmailRepeat($furryUser,$email);
            $num3 = $userState->checkMyself($furryUser);
            if($num1+$num2+$num3==3){
                $password = password_hash($pendPassword,PASSWORD_DEFAULT);
                $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
                $sql = "update account
                set name='$name',password='$password',info='$info',sex='$sex',sort='$sort',email='$email'
                where username='$furryUser'";
                $result = mysqli_query($link,$sql);$result->num_rows;
            }else{return 0;}
            return 1;
        }
        function inputBoard(){
            // 留言板输入
            $furryUser = $_SESSION['username'];
            $message = $_POST['message'];
            if(!$message||!$furryUser){return 0;}
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "insert into board(username,message) values('$furryUser','$message')";
            $result = mysqli_query($link,$sql);$result->num_rows;
            return 1;
        }
        function delMessage($boardId){
            // 删除留言
            $furryUser = $_SESSION['username'];
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            {
                $sql = "select username from board where username='$furryUser' and id='$boardId'";
                $result = mysqli_query($link,$sql);
                if($result->num_rows==0){return 0;}
            }
            {
                $sql = "delete from board where id='$boardId'";
                $result = mysqli_query($link,$sql);$result->num_rows;
            }
            return 1;
        }
        function delComments($commentId){
            // 删除评论
            $furryUser = $_SESSION['username'];
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            {
                $sql = "select username from comments where username='$furryUser' and id='$commentId'";
                $result = mysqli_query($link,$sql);
                if($result->num_rows==0){return 0;}
            }
            {
                $sql = "delete from comments where id='$commentId'";
                $result = mysqli_query($link,$sql);$result->num_rows;
            }
            return 1;
        }
        function sendComments($comment,$point){
            // 向画廊发送评论
            $furryUser = $_SESSION['username'];
            if(!$comment||!$furryUser){return 0;}
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "insert into comments(username,comment,point) values('$furryUser','$comment','$point')";
            $result = mysqli_query($link,$sql);$result->num_rows;
            return 1;
        }
        function addStar($galleryId){
            // 用户收藏作品
            $furryUser = $_SESSION['username'];
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            {
                $sql = "select username,galleryId from star where username='$furryUser' and galleryId='$galleryId'";
                $result = mysqli_query($link,$sql);
                if($result->num_rows>0){return 0;}
            }
            $sql = "insert into star(username,galleryid) values('$furryUser','$galleryId')";
            $result = mysqli_query($link,$sql);$result->num_rows;
            return 1;
        }
        function delStar($starId){
            // 用户取消收藏
            $furryUser = $_SESSION['username'];
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            {
                $sql = "select username from star where username='$furryUser' and id='$starId'";
                $result = mysqli_query($link,$sql);
                if($result->num_rows==0){return 0;}
            }
            {
                $sql = "delete from star where id='$starId'";
                $result = mysqli_query($link,$sql);$result->num_rows;
            }
            return 1;
        }
        function addTagsAndConnect($tags,$galleryId){
            // 用户添加标签并关联到画廊
            $dataExist = new dataExist;
            $userState = new userState;
            $num1 = $userState->checkUserAndGallery($galleryId);
            $num2 = substr_count($tags,"\n");
            if(!$tags || $num2!=0){return 0;}
            if($num1<1){return 0;}
            $tagsArray = explode(" ",$tags);
            for($i=0;$tagsArray[$i];$i++){
                $tag = $tagsArray[$i];
                {
                    $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
                    $num3 = $dataExist->checkTagExist($tag);
                    if($num3==0){
                        $sqlAdd = "insert into tags(tag,type) values('$tag',1)";
                        $resultAdd = mysqli_query($link,$sqlAdd);$resultAdd->num_rows;
                    }
                    {
                        $sql = "select Id from tags where tag='$tag'";
                        $result = mysqli_query($link,$sql);
                        $getTagId = mysqli_fetch_array($result);
                        $tagId = $getTagId['Id'];
                    }
                    $num4 = $dataExist->avoidAddTagRepeat($tagId,$galleryId);
                    if($num4==1){
                        $sql = "insert into connect(tagid,galleryid) values('$tagId','$galleryId')";
                        $result = mysqli_query($link,$sql);$result->num_rows;
                    }
                }
            }
            return 1;
        }
        function delConnect($tags,$galleryId){
            // 用户撕下标签
            $userState = new userState;
            $num1 = $userState->checkUserAndGallery($galleryId);
            $num2 = substr_count($tags,"\n");
            if(!$tags || $num2!=0){return 0;}
            if($num1<1){return 0;}
            $tagsArray = explode(" ",$tags);
            for($i=0;$tagsArray[$i];$i++){
                $tag = $tagsArray[$i];
                {
                    $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
                    {
                        $sql = "select Id from tags where tag='$tag'";
                        $result = mysqli_query($link,$sql);
                        $getTagId = mysqli_fetch_array($result);
                        $tagId = $getTagId['Id'];
                    }
                    {
                        $sql = "delete from connect where tagid='$tagId' and galleryid='$galleryId'";
                        $result = mysqli_query($link,$sql);$result->num_rows;
                    }
                }
            }
            return 1;
        }
        function preSearchTags($preSearch){
            // 用户预搜索标签
            if(!$preSearch){return 0;}
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $tagsArray = explode(" ",$preSearch);
            {
                $sql = "";
                for($i=0;$tagsArray[$i];$i++){
                    $tag = $tagsArray[$i];
                    if($i==0){
                        $sql = $sql."(select * from tags where tag like '%$tag%')";
                    }
                    else if($i<count($tagsArray)){
                        $sql = $sql." union (select * from tags where tag like '%$tag%')";
                    }
                }
                $sql = $sql." order by id DESC";    
            }
            $result = mysqli_query($link,$sql);
            while($tags = mysqli_fetch_array($result)){
                $foundTag = $tags['tag'];
                echo "#$foundTag ";
            }
            return 1;
        }
        function searchTagsAndShow($search){
            // 搜索标签并显示画廊
            if(!$search){return 0;}
            require "galleryClass.php";
            $furryArt = new furryArt;
            $loadNum = 0;
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $galleryIdArray = [];
            $tagsArray = explode(" ",$search);
            echo "<div class='galleryBox'>";
            for($i=0;$tagsArray[$i];$i++){
                $tag = $tagsArray[$i];
                {
                    $sqlTagId = "select * from tags where tag='$tag'";
                    $resultTagId = mysqli_query($link,$sqlTagId);
                    $tags=mysqli_fetch_array($resultTagId);$tagId = $tags['Id'];    
                }
                {
                    $sqlConnect = "select * from connect where tagid='$tagId' order by id DESC";
                    $resultConnect = mysqli_query($link,$sqlConnect);
                    while($connect=mysqli_fetch_array($resultConnect)){
                        $galleryId=$connect['galleryid'];
                        if(!in_array($galleryId,$galleryIdArray)){
                            $sqlGallery = "select * from gallery where id='$galleryId'";
                            $resultGallery = mysqli_query($link,$sqlGallery);
                            $all = mysqli_fetch_array($resultGallery);
                            $username = $all['username'];
                            $file = $all['file'];
                            $title = $all['title'];
                            $info = $all['info'];
                            $type = $all['type'];
                            $visit = $all['visit'];
                            $sqlAcc = "select name,sex,sort,grade from account where username='$username'";
                            $resultAcc = mysqli_query($link,$sqlAcc);
                            $account = mysqli_fetch_array($resultAcc);
                            $name=$account['name'];$sex=$account['sex'];$sort=$account['sort'];$grade=$account['grade'];
                            {
                                $sqlStar = "select galleryid from star where galleryid='$galleryId'";
                                $resultStar = mysqli_query($link,$sqlStar);
                                $starNum = mysqli_num_rows($resultStar);
                            }
                            $imgSrc = $furryArt->identifyVisit($visit,$username,$file);
                            $outType = $furryArt->identifyType($type);
                            echo <<<ZHOU
                                <div class="showGalleryIndex">
                                    <form action='./running/mark.php' method='post' id='starFormBox'>
                                        <input type='hidden' name='galleryId' value='$galleryId'>
                                        <input type='hidden' name='todo' value='1'>
                                        <button type='submit'><h1>⭐</h1></button>
                                    </form>
                                    <a href="gallery.php?galleryId=$galleryId"><img src="$imgSrc" alt="$imgSrc"></a>
                                    <a href="gallery.php?galleryId=$galleryId"><h1>$title</h1></a>
                                    <a href="user.php?username=$username"><h2>$name $sex $sort</h2></a>
                                    <h2>$outType 画廊ID-$galleryId ⭐$starNum</h2>
                                    <p>$info</p>
                                </div>
                            ZHOU;            
                            $galleryIdArray[] = $galleryId;
                        }
                    }
                }
            }
            echo"</div>";
            if($loadNum>40){return;}
            $loadNum++;
        }
        function watchUser($username){
            // 用户关注用户
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $furryUser = $_SESSION['username'];
            {
                $sql = "select * from watch where watcher='$furryUser' and username='$username'";
                $result = mysqli_query($link,$sql);
                if($result->num_rows>0){return 0;}
                else if($username == $furryUser){return 0;}
            }
            $sql = "insert into watch(watcher,username) values('$furryUser','$username')";
            $result = mysqli_query($link,$sql);$result->num_rows;
            return 1;
        }
        function unWatch($username){
            // 用户取消关注
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $furryUser = $_SESSION['username'];
            echo "$username $furryUser";
            if(empty($furryUser) || empty($username)){return 0;}
            $sql = "delete from watch where watcher='$furryUser' and username='$username'";
            $result = mysqli_query($link,$sql);$result->num_rows;
            return 1;
        }
    }
}