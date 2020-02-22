<?php
$pagefilename = basename(__FILE__, ".php");//取得本頁檔案名稱
include_once("SQLC.inc.php");
include_once("hz_include/class-postList.php");

if (preg_match("/^([0-9]+)$/", $_GET["id"]) && $_GET["id"] != null) {
    $Nowid = $_GET["id"];
    $thispost = new post($Nowid, "blog");
}
include_once "hz_include/themes/def/header.php";

$sqlClass = "SELECT * FROM BClasses ORDER BY OrderID ASC";
$resultClass = mysqli_query($sqli, $sqlClass); ?>
<body>
<div id="top">
    <div class="top_contant"><?php include("hz_include/themes/def/header_top.php") ?></div>
</div>
<?include_once ("hz_include/themes/def/breadtraiil.php") ?>
<div id="contant" name="contant">
    <?php
    if ($Nowid == null) {
        $sql = "SELECT * FROM Blog WHERE Blog.Competence='public' OR Blog.Competence='protect' ORDER BY Blog.PostDate DESC";
        $per = $mate[7];//每頁數量?>
        <div id="PageTitle">文章<br/></div>
        <div class="card bg-light mb-3" style="width: 100%;">
            <div class="card-header"><i class="fas fa-folder"></i>&nbsp;分類</div>
            <div class="card-body">
                <?php while ($ClassList = mysqli_fetch_array($resultClass)) :
                    if ($ClassList["SorH"] == "show") : ?>
                        <span class="badge badge-pill badge-primary">
            <a href="category?classid=<?php echo $ClassList["ClassId"] ?>&page=1"><?php echo $ClassList["ClassName"] ?></a></span>
                    <?php endif; ?>
                <?php endwhile; ?>
            </div>
        </div>
        <?php printpost($sql, $per, null);
    } //輸出文章內容
    elseif ($Nowid != null) {
    if ($thispost->verification($userlaw)) {
    if ($thispost->password != null && ($_SESSION[$Nowid . "passed"] == null || $_SESSION[$Nowid . "passed"] != $thispost->password)) { ?>
        <form action="" method="post" name="postpassform">
            <p style="font-size: 80px; text-align: center"><i class="fas fa-lock"></i></p>
            <p style="text-align: center">文章已受密碼保護，請輸入密碼：</p>
            <p style="text-align: center"><input name="postpass" type="password" class="form-control" value=""
                                                 size="50%">&nbsp;
                <?php
                if ($thispost->passwordhint != null): ?>
            <p style="text-align: center"><i class="fas fa-info-circle"></i>密碼提示：<?php echo $thispost->passwordhint ?>
            </p>
            <? endif; ?>
            <p style="text-align: center;"><input name="sub" type="submit" class="btn btn-primary" value="確認"/></p>
        </form>
        <?php
        //驗證密碼
        $ppw = addslashes($_POST["postpass"]);
        $thispost->idenpassword($ppw);
    } else {
    //閱讀次數，會重複計，但不得同一時段內一直重刷(利用COOKIE)
    $thispost->updateNumViews(); ?>
    <div id="PostPerson">

        <div id="perdata">
            <a href="person?id=<?php echo $thispost->userid ?>"><img class="img-circle"
                                                                         src="<?php echo $thispost->wAvatar ?>"
                                                                         width="32" height="32"/></a>
            <a href="person?id=<?php echo $thispost->userid ?>"><?php echo $thispost->wName ?></a>&nbsp;&nbsp;
            <i class="fas fa-clock"></i><?= $thispost->postdate ?>
            &nbsp;&nbsp;
            <i class="fas fa-eye"></i><?php echo $thispost->numViews ?>點閱&nbsp;&nbsp;
            <i class="fas fa-heart"></i><?php echo $thispost->likesnum ?>喜歡&nbsp;
            <i class="fas fa-star"></i><?php echo $thispost->favnum ?>收藏

        </div>
    </div>
    <div id="postTitle">
        <?php echo $thispost->posttitle ?> &nbsp;
        <?php
        if ($userlaw["Law_PostBlog"] == 2 || ($userlaw["Law_PostBlog"] == 1 && $thispost->userid == $b)) :?>
            <a href="admin/blogedit?id=<?php echo $Nowid ?>" class="btn btn-primary" style="font-size: 10px"><i
                        class="far fa-edit"></i></a>
        <?php endif; ?>
    </div>
    <div id="postcontant">
        <?php echo $thispost->postcontant//文章內容 ?>
        <?
        if ($thispost->wSignatureshow >= 2): ?>
            <div id="postSignature">
                <div id="postSignatureleft">
                    <a href="person?id=<?php echo $thispost->userid ?>"><img class="img-circle"
                                                                                 src="<?php echo $thispost->wAvatar ?>"
                                                                                 width="128" height="128"></a>
                </div>
                <div id="postSignatureright">
                    <p style="font-size: 20px">本文作者：<a style="color: #1da1f2;"
                                                       href="person?id=<?php echo $thispost->userid ?>"><?php echo $thispost->wName ?></a>
                        <?php
                        $userlawa=userLawFind($thispost->Permissions);
                        if ($userlawa["Law_PostBlog"] >= 1 && $_SESSION["username"] != null && $thispost->userid != $b):
                            if (mysqli_num_rows(mysqli_query($sqli, "SELECT * FROM followRelationship WHERE follow_UserName='$b' AND followed_UserName='$thispost->userid'")) == 0):?>
                                &nbsp;&nbsp;
                                <a href="<?php echo 'blog?id=' . $Nowid . '&follow=follow' ?>">
                                    <button type="button" class="btn btn-danger"><i class="fas fa-rss-square"></i>&nbsp;追蹤我
                                    </button>
                                </a>
                                <?php if ($_GET["follow"] == "follow") {
                                    follow($b, $thispost->userid);
                                    header('Location: blog?id=' . $Nowid . "#postSignatureright");

                                } ?>
                            <? else: ?>
                                &nbsp;&nbsp;
                                <a onclick="javascript:window.alert('您已經追蹤');">
                                    <button type="button" class="btn btn-danger disabled"><i
                                                class="fas fa-rss-square"></i>&nbsp;已追蹤
                                    </button>
                                </a>
                            <? endif; ?>
                        <? endif; ?>

                    </p>
                    <p style="font-size: 18px"><?
                        echo $thispost->wSignature ?></p>
                </div>
            </div>
        <? endif; ?>
        <div id="like"><?php include_once("hz_include/class-likepost.php") ?></div>
    </div>

    <div id="oldandnewpost">
        <p>
            <?php
            $lrs = $thispost->getLeftPost();
            if ($lrs): ?>
        <div id="backpost">
            <a href="blog?id=<?php echo $lrs["PostId"] ?>"
               class="btn-outlined btn-block btn-primary"><i class="fas fa-chevron-circle-left"></i> &nbsp;
                上一篇<br><?php echo $lrs["PostTittle"] ?></a>
        </div>
        <?php else : ?>
            <div id="backpost"></div>
        <?php endif; ?>
        <?php
        $nrs = $thispost->getNextPost();
        if ($nrs) :?>
            <div id="nextpost">
                <a href="blog?id=<?php echo $nrs["PostId"] ?>"
                   class="btn-outlined btn-block btn-primary">下一篇 &nbsp; <i
                            class="fas fa-chevron-circle-right"></i><br><?php echo $nrs["PostTittle"] ?>
                </a>
            </div>
        <?php else : ?>
            <div id="nextpost">
            </div>
        <?php endif; ?>
        </p>
    </div>
</div>
<div id="contant" name="disqusReply">
    <?php include_once("hz_include/reply.php")?>
</div>
<div id="contant" name="replaycontant">
    <a name="newReply"></a>
    <?php reply($thispost, $userlaw, $predata, $Nowid, $mate); ?>
    <?php //輸出回覆
    $reply = new Reply($sqli, "SELECT * FROM `reply` WHERE PostId = '$Nowid' AND Reply_parent = '0' AND Competence = 'public' ORDER BY `reply`.`ReplyId` ASC", $Nowid); ?>
    <div id="replynum"><i class="fas fa-comment"></i> &nbsp; <?php echo $reply->ReplyNum() ?>則回覆</div>
    <?php
    $reply->ReplyShow();
    }
    } elseif ($thispost->competence == "protect" && !($userlaw["Law_Read"] >= $thispost->readaut)) {
        printmsg("error", "很抱歉！<br>您的閱讀權限無法查閱此文章");
    } elseif ($thispost->competence == "private") {
        printmsg("error", "很抱歉！<br>您所查閱之文章權限為私有，造成您的不便請見諒！<br>有問題請聯絡網站系統管理員！");
    } else {
        printmsg("error", "很抱歉，文章不存在");
    }
    } ?>
</div>
<div id="copyr">
    <?php
    include_once "hz_include/themes/def/footer.php"; ?>
</div>
</body>
</html>
<script type="application/ld+json">
{
	"@context": "http://schema.org",
	"@type": "Article",
	"name" : "<?=$thispost->posttitle?>",
	"author" : "<?=$thispost->wName?>",
	"datePublished" : "<?=$thispost->postdate?>",
	"image" : "null",
	"articleBody" : "<?=htmlentities($thispost->postcontant,3,"UTF-8")?>",
	"headline": "<?=$thispost->posttitle?>",
	"publisher" : {
    "@type" : "Organization",
    "name" : "<?=$mate[0]?>",
    "logo" : "<?=$mate[13]."/".$mate[5]?>"
  }
}
</script>