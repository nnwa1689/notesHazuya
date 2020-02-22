<?php
$pagefilename = basename(__FILE__, ".php");//取得本業面的
include_once("SQLC.inc.php");
if (preg_match("/^([0-9A-Za-z]+)$/", $_GET["id"])) {
    $perid = $_GET["id"];
    $sqlPerid = "SELECT * FROM admin WHERE admin.username='$perid'";
    $resultPerid = mysqli_query($sqli,$sqlPerid);
    if ($resultPerid) {
        $periddata = mysqli_fetch_row($resultPerid);
    }
}
include_once "hz_include/themes/def/header.php";
$userlawa = userLawFind($periddata[5]); ?>
<style type="text/css">
    #postlist {
        padding: 10px;
    }

    #perTitle {
        font-size: 16px;
        text-align: center;
        border-bottom-width: thin;
        border-bottom-style: solid;
        border-bottom-color: #EBEBEB;
        line-height: 25px;
        margin-top: -20px;
        margin-right: -20px;
        margin-bottom: 10px;
        margin-left: -20px;
        padding-top: 30px;
        padding-right: 0px;
        padding-bottom: 30px;
        padding-left: 0px;
        color: #585858;
        background: #f7f7f7;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        height: auto;
    }

    <? if($periddata[10]!=null): ?>
    #perTitle {
        text-shadow: 0px 0px 15px #000;
        color: #ffffff;
        background-image: url(<?php echo $periddata[10]?>);
        background-size: cover;
    }

    #perTitle a:link {
        color: #FFFFFF;
    }

    #perTitle a:visited {
        color: #FFFFFF;
    }

    <?endif;?>
</style>
<body>
<div id="top">
    <div class="top_contant"><?php include_once("hz_include/themes/def/header_top.php") ?></div>
</div>
<?include_once ("hz_include/themes/def/breadtraiil.php") ?>
<div id="contant">
    <?php
    if ($perid != null) {
        if (($periddata[9] == "public") || ($periddata[9] == "protect" && $_SESSION["username"] != null) || ($periddata[9] == "protect" && $_SESSION["username"] == $perid)) {
            if (preg_match("/^([0-9A-Za-z]+)$/", $_GET["act"]))
                $act = $_GET["act"];
            else
                $act = null;
            $rsfollowed = mysqli_query($sqli,"SELECT * FROM followRelationship WHERE followed_UserName='$perid'");//被誰關注
            $rsfollow = mysqli_query($sqli,"SELECT * FROM followRelationship WHERE follow_UserName='$perid'");//關注誰
            $follownum = mysqli_num_rows($rsfollow);
            $followednum = mysqli_num_rows($rsfollowed);
            $resultMypost = mysqli_query($sqli,"SELECT * FROM Blog WHERE Blog.UserID = '$perid' AND (Competence='public' OR Competence='protect') ORDER BY Blog.PostDate DESC");
            $Mypostnum = mysqli_num_rows($resultMypost); ?>
            <div id="perTitle">
                <div id="personname">
                    <div id="personnameleft">
                        <img class="img-circle" src="<?php echo $periddata[7] ?>" width="170" height="170"/>
                    </div>
                    <div id="personnameright">
                        <p style="font-size: 24px;font-weight: bold;"><?=$periddata[4]?><br>(<?php echo $periddata[0] ?>)</p>
                        <p><?php echo userLawName($periddata[5]) ?></p>
                        <?
                        if ($periddata[17] == 1 || $periddata[17] == 3):?>
                            <p><?php echo $periddata[16] ?></p>
                        <? endif; ?>
                        <p>
                            <?
                            if ($userlawa["Law_PostBlog"] >= 1):?>
                                <a href="person?id=<? echo $perid ?>&act=post"><?
                                    echo $Mypostnum ?>篇文章</a>．
                                <a href="person?id=<? echo $perid ?>&act=followed">粉絲<?
                                    echo $followednum ?>人</a>．
                            <? endif; ?>
                            <a href="person?id=<? echo $perid ?>&act=follow">追蹤<?
                                echo $follownum ?>人</a>
                        </p>
                    </div>
                </div>
                <div name="bottom"
                     style="text-align: right;width: 600px;height: auto; margin-right: auto;margin-left: auto;padding-top: 10px">
                    <?php if ($b != $periddata[0] && $b != null) : ?>
                        <a href="message?do=send&catcherid=<?php echo $periddata[0] ?>">
                            <button type="button" class="btn btn-success"><i class="fas fa-envelope"></i>&nbsp;站內信
                            </button>
                        </a>
                    <?php endif; ?>
                    <?php if ($periddata[13] == "show") : ?>
                        &nbsp;
                        <a href="mailto:<?php echo $periddata[2] ?>">
                            <button type="button" class="btn btn-info"><i class="fas fa-at"></i>&nbsp;E-mail</button>
                        </a>
                    <?php endif; ?>
                    <?php if ($periddata[14] == "show") : ?>
                        &nbsp;&nbsp;
                        <a href="<?php echo $periddata[3] ?>">
                            <button type="button" class="btn btn-warning"><i class="fas fa-link"></i>&nbsp;個人網站</button>
                        </a>
                    <?php endif; ?>
                    <?php if ($userlawa["Law_PostBlog"] >= 1 && $_SESSION["username"] != null && $perid != $b):
                        if (mysqli_num_rows(mysqli_query($sqli,"SELECT * FROM followRelationship WHERE follow_UserName='$b' AND followed_UserName='$perid'")) == 0):?>
                            &nbsp;&nbsp;
                            <a href="<?php echo 'person?id=' . $perid . '&follow=follow' ?>">
                                <button type="button" class="btn btn-danger"><i class="fas fa-rss-square"></i>&nbsp;追蹤我
                                </button>
                            </a>
                            <?php if ($_GET["follow"] == "follow") {
                                follow($b, $perid);
                                header('Location: person?id=' . $perid);

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
                </div>
            </div>
            <br>
            <?
            /*********************內容開始******************/
            if ($act == "followed") {
                include_once("hz_include/person/followed.php");
            } elseif ($act == "follow") {
                include_once("hz_include/person/follow.php");
            } elseif ($act == "post") {
                include_once("hz_include/person/post.php");
            } elseif ($act == null) {
                include_once("hz_include/person/aboutme.php");
            } ?>
            <?php
        } elseif ($periddata[9] == "private") {
            echo "個人檔案已關閉";
        } else {
            echo "引數錯誤:用戶不存在(系統管理員並非一個實體用戶，只是一個代表名稱)";
        }
    } ?>
    </form>
</div>
<div id="copyr">
    <?php include_once "hz_include/themes/def/footer.php"; ?>
</div>
</body>
</html>
