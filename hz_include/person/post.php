<div class="card-header"><i class="fas fa-list-alt"></i>&nbsp;我的文章</div>
    <div>
        <?php
        $PostNum = 0;
        if ($Mypostnum != 0) {
            include_once("hz_include/class-postList.php");
            printpost("SELECT * FROM Blog WHERE Blog.UserID = '$perid' AND (Competence='public' OR Competence='protect') ORDER BY Blog.PostDate DESC", 10,$pagefilename);
        } else {
            echo "<p>這個人還沒有發表過任何文章!!</p>";
        } ?>
    </div>
