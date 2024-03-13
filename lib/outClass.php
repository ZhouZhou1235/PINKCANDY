<?php
namespace {
    class homePage {
        // 首页输出
        function entry(){
            // 简易登录入口
            $file_url = "./resource/template/entry.html";
            $zzww = file_get_contents($file_url);
            echo $zzww;
            return 1;
        }
        function menu(){
            // 导航栏
            $file_url = "./resource/template/menu.html";
            $zzww = file_get_contents($file_url);
            echo $zzww;
            return 1;
        }
        function menuFull(){
            // 全宽导航栏
            $file_url = "./resource/template/menuFull.html";
            $zzww = file_get_contents($file_url);
            echo $zzww;
            return 1;
        }
        function headPart(){
            // 头部
            $webTitle = "<title>粉糖粒子</title>";
            $file_url = "./resource/template/head.html";
            $zzww = file_get_contents($file_url);
            echo $webTitle.$zzww;
            return 1;
        }
        function furryUser(){
            // 小兽空间
            $furryUser = $_SESSION['username'];
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "select username,name,sex,sort,grade,coin from account where username='$furryUser'";
            $result = mysqli_query($link,$sql);
            $account = mysqli_fetch_array($result);
            $name=$account['name'];$sex=$account['sex'];$sort=$account['sort'];$grade=$account['grade'];$coin=$account['coin'];
            require "./resource/template/furryUser.html";
            return 1;
        }
        function register(){
            // 注册页面
            $file_url = "./resource/template/register.html";
            $zzww = file_get_contents($file_url);
            echo $zzww;
            return 1;
        }
        function login(){
            // 登录页面
            $file_url = "./resource/template/login.html";
            $zzww = file_get_contents($file_url);
            echo $zzww;
            return 1;
        }
        function about(){
            // 关于
            $file_url = "./resource/template/about.html";
            $zzww = file_get_contents($file_url);
            echo $zzww;
            return 1;
        }
    }
    class gallery {
        // 画廊输出
        function uploadForm(){
            // 作品上传表单
            $file_url = "./resource/template/uploadGalleryForm.html";
            $zzww = file_get_contents($file_url);
            echo $zzww;
            return 1;
        }
        function showGalleryIndex(){
            // 首页展示作品
            require "galleryClass.php";
            $furryArt = new furryArt;
            $furryUser = $_SESSION['username'];
            $viewNum = $_SESSION['view'];
            $loadNum = 0;
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "select * from gallery order by id DESC";
            $result = mysqli_query($link,$sql);
            echo "<div class='galleryBox'>";
            while($all = mysqli_fetch_array($result)){
                $galleryId = $all['Id'];
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
                if(!empty($furryUser) && $loadNum>$viewNum){$_SESSION['view'] = $viewNum+5;return;}
                else if($loadNum>40){return;}
                $loadNum++;
            }
            echo "</div>";
        }
        function showGallery($galleryId){
            // 展示作品
            require "galleryClass.php";
            $furryArt = new furryArt;
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            {
                $sql = "select * from gallery where id='$galleryId'";
                $result = mysqli_query($link,$sql);if($result->num_rows==0){return 0;}
                $gallery = mysqli_fetch_array($result);
                $username = $gallery['username'];$file=$gallery['file'];$title=$gallery['title'];
                $info=$gallery['info'];$type=$gallery['type'];$visit=$gallery['visit'];
                $time=$gallery['time'];
            }
            {
                $sqlAcc = "select name,sex,sort,grade from account where username='$username'";
                $resultAcc = mysqli_query($link,$sqlAcc);
                $account = mysqli_fetch_array($resultAcc);
                $name=$account['name'];$sex=$account['sex'];$sort=$account['sort'];$grade=$account['grade'];    
            }
            {
                $sqlStar = "select galleryid from star where galleryid='$galleryId'";
                $resultStar = mysqli_query($link,$sqlStar);
                $starNum = mysqli_num_rows($resultStar);
            }
            $allTags = $this->galleryTags($galleryId);
            $outType = $furryArt->identifyType($type);
            if($visit==4){echo"---===ZZWW 访问被拒绝===---\n可能的触发原因：此画廊已被隐藏";return;}
            require "./resource/template/showGallery.html";
        }
        function galleryTags($galleryId){
            // 显示画廊标签
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $allTags = "";
            $sql = "select * from connect where galleryid='$galleryId'";
            $result = mysqli_query($link,$sql);
            while($connect = mysqli_fetch_array($result)){
                $tagId = $connect['tagid'];
                $sqlTags = "select * from tags where id='$tagId'";
                $resultTags = mysqli_query($link,$sqlTags);
                $tags = mysqli_fetch_array($resultTags);
                $tag=$tags['tag'];$type=$tags['type'];
                $allTags = $allTags."#".$tag." ";
            }
            return $allTags;
        }
        function showComments($galleryId){
            // 展示画廊评论
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "select * from comments where point='$galleryId' order by id DESC";
            $result = mysqli_query($link,$sql);
            while($comments = mysqli_fetch_array($result)){
                $username=$comments['username'];$comment=$comments['comment'];$time=$comments['time'];
                {
                    $sqlAcc = "select name,sex,sort,grade from account where username='$username'";
                    $resultAcc = mysqli_query($link,$sqlAcc);
                    $account = mysqli_fetch_array($resultAcc);
                    $name=$account['name'];$sex=$account['sex'];$sort=$account['sort'];$grade=$account['grade'];    
                }
                echo "
                    <div class='screenFull'>
                        <div class='txtBox'>
                            <h1>$comment</h1>
                            <h2>$name $sex $sort</h2>
                            <p>$time</p>
                        </div>
                    </div>
                ";
            }
        }
        function editGalleryForm($galleryId){
            // 修改画廊表单
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "select * from gallery where id='$galleryId'";
            $result = mysqli_query($link,$sql);
            $g = mysqli_fetch_array($result);
            $file=$g['file'];$username=$g['username'];$title=$g['title'];$info=$g['info'];$type=$g['type'];$visit=$g['visit'];
            $userState = new userState;
            $num = $userState->checkMyself($username);
            if($num==1){require "./resource/template/editGalleryForm.html";}
            else{echo"<div class='screen'>---===ZZWW 不能编辑他兽的画廊===---</div>";}
            return 1;
        }
    }
    class userZone {
        // 小兽空间页面
        function myPage(){
            // 自己
            $furryUser = $_SESSION['username'];
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "select username,name,info,sex,sort,grade,coin from account where username='$furryUser'";
            $result = mysqli_query($link,$sql);
            $account = mysqli_fetch_array($result);
            $name=$account['name'];$info=$account['info'];$sex=$account['sex'];$sort=$account['sort'];$grade=$account['grade'];$coin=$account['coin'];
            $outStr = $this->watchUser($furryUser);
            require "./resource/template/myself.html";
            return 1;
        }
        function editPage(){
            // 编辑个兽空间页面
            $furryUser = $_SESSION['username'];
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "select username,name,info,sex,sort,email from account where username='$furryUser'";
            $result = mysqli_query($link,$sql);
            $account = mysqli_fetch_array($result);
            $name=$account['name'];$info=$account['info'];$sex=$account['sex'];$sort=$account['sort'];$email=$account['email'];
            require "./resource/template/editUser.html";
            return 1;
        }
        function editOtherPage(){
            // 编辑其它页面
            $furryUser = $_SESSION['username'];
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            {
                $sql = "select * from board where username='$furryUser'";
                $result = mysqli_query($link,$sql);
                echo"<div class='screenFull'><div class='txtBox'><h1>粉糖粒子留言板</h1></div>";
                while($board = mysqli_fetch_array($result)){
                    $boardId=$board['Id'];$message=$board['message'];$time=$board['time'];
                    echo <<<ZHOU
                        <div class='formBox'>
                            <div class='txtBox'>
                                <h2>$message</h2>
                                <p>$time</p>
                            </div>
                            <form action='./running/delete.php' method='post' onsubmit="return confirm('删除操作不可逆！')">
                                <input type='hidden' name='boardId' value='$boardId'>
                                <input type='hidden' name='todo' value='1'>
                                <button type='submit'>删除</button>
                            </form>
                        </div>
                    ZHOU;
                }
                echo"</div>";
            }
            {
                $sql = "select * from comments where username='$furryUser'";
                $result = mysqli_query($link,$sql);
                echo"<div class='screenFull'><div class='txtBox'><h1>画廊评论</h1></div>";
                while($comments = mysqli_fetch_array($result)){
                    $commentId=$comments['Id'];$comment=$comments['comment'];$point=$comments['point'];$time=$comments['time'];
                    echo<<<ZHOU
                        <div class='formBox'>
                            <div class='txtBox'>
                                <h2>$comment</h2>
                                <p>对画廊ID为 $point 的作品评论 $time</p>
                            </div>
                            <form action='./running/delete.php' method='post' onsubmit="return confirm('删除操作不可逆！')">
                                <input type='hidden' name='commentId' value='$commentId'>
                                <input type='hidden' name='todo' value='2'>
                                <button type='submit'>删除</button>
                            </form>
                        </div>
                    ZHOU;
                }
                echo"</div>";
            }
        }
        function lookUser($username){
            // 他兽界面
            $furryUser = $username;
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "select username,name,info,sex,sort,grade,coin from account where username='$username'";
            $result = mysqli_query($link,$sql);if($result->num_rows==0){return 0;}
            $account = mysqli_fetch_array($result);
            $name=$account['name'];$info=$account['info'];$sex=$account['sex'];$sort=$account['sort'];$grade=$account['grade'];$coin=$account['coin'];
            $outStr = $this->watchUser($username);
            require "./resource/template/lookUser.html";
            return 1;
        }
        function showGalleryUser($username){
            // 在个兽空间展示画廊
            require "galleryClass.php";
            $furryArt = new furryArt;
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "select * from gallery where username='$username' order by id DESC";
            $result = mysqli_query($link,$sql);
            echo "<div class='galleryBox'>";
            while($all = mysqli_fetch_array($result)){
                $galleryId = $all['Id'];
                $username = $all['username'];
                $file = $all['file'];
                $title = $all['title'];
                $info = $all['info'];
                $type = $all['type'];
                $visit = $all['visit'];
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
                        <h2>$outType 画廊ID-$galleryId</h2>
                        <p>$info</p>
                    </div>
                ZHOU;
            }
            echo "</div>";
        }
        function watchUser($username){
            // 展示关注情况
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql1 = "select * from watch where username='$username'";
            $result1 = mysqli_query($link,$sql1);
            $watcherNum = $result1->num_rows;
            $sql2 = "select * from watch where watcher='$username'";
            $result2 = mysqli_query($link,$sql2);
            $watchNum = $result2->num_rows;
            $outStr = "粉丝$watcherNum 关注$watchNum";
            return $outStr;
        }
    }
    class otherPage {
        // 其他页面输出
        function boardForm(){
            // 留言板页面
            $file_url = "./resource/template/boardForm.html";
            $zzww = file_get_contents($file_url);
            echo $zzww;
            return 1;
        }
        function board(){
            // 留言板
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $sql = "select * from board order by id DESC";
            $result = mysqli_query($link,$sql);
            echo "<div class='screen'><div class='boardBox'>";
            while($board=mysqli_fetch_array($result)){
                $username = $board['username'];
                $message = $board['message'];
                $sqlUser = "select name,sex,sort,grade from account where username='$username'";
                $resultAcc = mysqli_query($link,$sqlUser);
                $account = mysqli_fetch_array($resultAcc);
                $name=$account['name'];$sex=$account['sex'];$sort=$account['sort'];$grade=$account['grade'];
                echo "
                    <div class='txtBox'>
                        <p>🐾 $name $sex $sort $grade</p>
                        <h2>$message</h2>
                    </div>
                ";
            }
            echo "</div></div>";
        }
        function star($furryUser){
            // 我的收藏
            require "galleryClass.php";
            $furryArt = new furryArt;
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            {
                $sql = "select * from star where username='$furryUser' order by id DESC";
                $result = mysqli_query($link,$sql);
            }
            {
                $sqlAcc = "select name from account where username='$furryUser'";
                $resultAcc = mysqli_query($link,$sqlAcc);
                $account = mysqli_fetch_array($resultAcc);
                $theName = $account['name'];    
            }
            echo"<div class='screen'><div class='txtBox'><h1>$theName 的收藏</h1></div></div><div class='galleryBox'>";
            while($star = mysqli_fetch_array($result)){
                $starId=$star['Id'];$galleryId=$star['galleryid'];$time=$star['time'];
                {
                    $sqlGallery = "select * from gallery where id='$galleryId'";
                    $resultGallery = mysqli_query($link,$sqlGallery);
                    $gallery = mysqli_fetch_array($resultGallery);
                    $username = $gallery['username'];
                    $file = $gallery['file'];
                    $title = $gallery['title'];
                    $info = $gallery['info'];
                    $type = $gallery['type'];
                    $visit = $gallery['visit'];
                }
                {
                    $sqlAcc = "select name,sex,sort,grade from account where username='$username'";
                    $resultAcc = mysqli_query($link,$sqlAcc);
                    $account = mysqli_fetch_array($resultAcc);
                    $name=$account['name'];$sex=$account['sex'];$sort=$account['sort'];$grade=$account['grade'];    
                }
                {
                    $sqlStar = "select galleryid from star where galleryid='$galleryId'";
                    $resultStar = mysqli_query($link,$sqlStar);
                    $starNum = mysqli_num_rows($resultStar);
                }
                $imgSrc = $furryArt->identifyVisit($visit,$username,$file);
                $outType = $furryArt->identifyType($type);
                echo <<<ZHOU
                    <div class="showGalleryIndex">
                        <form action="./running/delete.php" method="post" onsubmit="return confirm('确定吗？别错过宝藏哦')" id="starFormBox">
                            <input type="hidden" name="starId" value="$starId">
                            <input type="hidden" name="todo" value="3">
                            <button type="submit"><h1>🗑️</h1></button>
                        </form>
                        <a href="gallery.php?galleryId=$galleryId"><img src="$imgSrc" alt="$imgSrc"></a>
                        <a href="gallery.php?galleryId=$galleryId"><h1>$title</h1></a>
                        <h2>$outType 画廊ID-$galleryId ⭐$starNum</h2>
                        <p>收藏时间-$time $info</p>
                    </div>
                ZHOU;
            }
            echo"</div>";
        }
        function tags(){
            // 标签墙纸页面
            $file_url = "./resource/template/tagsForm.html";
            $zzww = file_get_contents($file_url);
            echo $zzww;
            return 1;
        }
        function watch($username=""){
            // 关注页面
            $link = mysqli_connect("localhost","pinkcandyzhou","20240301","zzwwdb");
            $furryUser = $_SESSION['username'];
            if(!$username){$username=$furryUser;}
            $sqlWatcher = "select * from watch where username='$username'";
            $resultWatcher = mysqli_query($link,$sqlWatcher);
            $sqlWatch = "select * from watch where watcher='$username'";
            $resultWatch = mysqli_query($link,$sqlWatch);
            $sqlAcc = "select name from account where username='$username'";
            $resultAcc = mysqli_query($link,$sqlAcc);
            $account = mysqli_fetch_array($resultAcc);
            $theName = $account['name'];
            echo "<div class='screen'><div class='txtBox'><h2>$theName 的粉丝</h2>";
            while($watchWatcher = mysqli_fetch_array($resultWatcher)){
                $watcher = $watchWatcher['watcher'];
                $sql = "select username,name,info,sex,sort from account where username='$watcher'";
                $result = mysqli_query($link,$sql);
                $account = mysqli_fetch_array($result);
                $theUser=$account['username'];
                $name=$account['name'];$info=$account['info'];
                $sex=$account['sex'];$sort=$account['sort'];
                echo "
                    <div class='txtBox'>
                        <a href='user.php?username=$theUser'><h1>$name</h1></a>
                        <h2>$theUser $sex $sort</h2>
                        <p>$info</p>
                    </div>
                ";
            }
            echo "<h2>$theName 的关注</h2>";
            while($watchWatch = mysqli_fetch_array($resultWatch)){
                $watch = $watchWatch['username'];
                $sql = "select username,name,info,sex,sort from account where username='$watch'";
                $result = mysqli_query($link,$sql);
                $account = mysqli_fetch_array($result);
                $theUser=$account['username'];
                $name=$account['name'];$info=$account['info'];
                $sex=$account['sex'];$sort=$account['sort'];
                echo "
                    <div class='txtBox'>
                        <a href='user.php?username=$theUser'><h1>$name</h1></a>
                        <h2>$theUser $sex $sort</h2>
                        <p>$info</p>
                    </div>
                ";
            }
            echo "</div></div>";
        }
        function search(){
            // 搜索页面
            $file_url = "./resource/template/search.html";
            $zzww = file_get_contents($file_url);
            echo $zzww;
            return 1;
        }
    }
}