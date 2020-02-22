<?php
session_start();
if ($_SESSION["username"] != null) {
    /*
     * 個人資料修改前台測試版
     *
     * fav我的收藏
     * avatar修改頭貼
     * information個人資料
     * introduction個人簡介關於我
     * secure密碼與安全性
     * */
    include_once("SQLC.inc.php");
    $pagefilename = basename(__FILE__, ".php");//取得本頁檔名
//檢查動作引數是否正確，否則回傳NULL
    if (preg_match("/^([0-9A-Za-z]+)$/", $_GET["action"])) {
        $action = $_GET["action"];
    } else {
        $action = "information";
    }
    include_once("hz_include/themes/def/header.php"); ?>
    <body>
    <div id="top">
        <div class="top_contant"><?php include_once("hz_include/themes/def/header_top.php") ?></div>
    </div>
    <?include_once ("hz_include/themes/def/breadtraiil.php") ?>
    <div id="contant">
        <div id="PageTitle">個人中心<br></div>
        <div class="pageLeft">
            <p><img class="img-circle"
                    src="<?php print $predata[7] ?>" width="128"
                    height="128"/></p>
            <p>您好，<? echo $predata[4] ?></p>
            <p>（<? echo $b ?>）</p>
            <div class="list-group" style="text-align: left ;">
                <? if ($_GET["action"] == "fav"): ?>
                    <a href="setting?action=fav" class="list-group-item list-group-item-action active"><i
                                class="fas fa-star"></i>&nbsp; 我的收藏</a>
                <? else: ?>
                    <a href="setting?action=fav" class="list-group-item list-group-item-action"><i
                                class="fas fa-star"></i>&nbsp;我的收藏</a>
                <? endif; ?>
                <? if ($_GET["action"] == "followed"): ?>
                    <a href="setting?action=followed" class="list-group-item list-group-item-action active"><i
                                class="fas fa-rss-square"></i>&nbsp;我的追蹤</a>
                <? else: ?>
                    <a href="setting?action=followed" class="list-group-item list-group-item-action"><i
                                class="fas fa-rss-square"></i>&nbsp;我的追蹤</a>
                <? endif; ?>
                <? if ($_GET["action"] == "avatar"): ?>
                    <a href="setting?action=avatar" class="list-group-item list-group-item-action active"><i
                                class="fas fa-user-circle"></i>&nbsp;頭貼</a>
                <? else: ?>
                    <a href="setting?action=avatar" class="list-group-item list-group-item-action"><i
                                class="fas fa-user-circle"></i>&nbsp;頭貼</a>
                <? endif; ?>
                <? if ($_GET["action"] == "information" || $_GET["action"] == null): ?>
                    <a href="setting?action=information" class="list-group-item list-group-item-action active"><i
                                class="fas fa-info-circle"></i>&nbsp;個人資料</a>
                <? else: ?>
                    <a href="setting?action=information" class="list-group-item list-group-item-action"><i
                                class="fas fa-info-circle"></i>&nbsp;個人資料</a>
                <? endif; ?>
                <? if ($_GET["action"] == "introduction"): ?>
                    <a href="setting?action=introduction" class="list-group-item list-group-item-action active"><i
                                class="fas fa-info"></i>&nbsp;關於我</a>
                <? else: ?>
                    <a href="setting?action=introduction" class="list-group-item list-group-item-action"><i
                                class="fas fa-info"></i>&nbsp;關於我</a>
                <? endif; ?>
                <? if ($_GET["action"] == "secure"): ?>
                    <a href="setting?action=secure" class="list-group-item list-group-item-action active"><i
                                class="fas fa-unlock-alt"></i>&nbsp;密碼安全</a>
                <? else: ?>
                    <a href="setting?action=secure" class="list-group-item list-group-item-action"><i
                                class="fas fa-unlock-alt"></i>&nbsp;密碼安全</a>
                <? endif; ?>
            </div>
        </div>

        <div class="pageRight">
            <form action="" method="post">
                <script type="text/javascript">
                    function check_all(obj, cName) {
                        var checkboxs = document.getElementsByName(cName);
                        for (var i = 0; i < checkboxs.length; i++) {
                            checkboxs[i].checked = obj.checked;
                        }
                    }
                </script>
                <?php
                /*我的收藏*/
                if ($action === "fav"): ?>
                    <p style="font-size: 30px;"><i class="fas fa-star"></i>我的收藏</p>
                    <?php include_once("hz_include/user/fav.php"); ?>
                <?php
                /*頭貼*/
                elseif ($action === "avatar"): ?>
                    <p style="font-size: 30px;"><i class="fas fa-user-circle"></i>頭貼</p>
                    <?php include_once("hz_include/user/avatar.php") ?>
                <?php
                elseif ($action === "information"):?>
                    <p style="font-size: 30px;"><i class="fas fa-info-circle"></i>個人資料</p>
                    <?php include_once("hz_include/user/info.php") ?>
                <?php elseif ($action === "introduction"): ?>
                    <p style="font-size: 30px;"><i class="fas fa-info"></i>關於我</p>
                    <?php include_once("hz_include/user/intro.php"); ?>
                <?php elseif ($action === "secure"): ?>
                    <p style="font-size: 30px;"><i class="fas fa-unlock-alt"></i>密碼與安全</p>
                    <?php include_once("hz_include/user/secure.php") ?>
                <?php elseif ($action === "followed"): ?>
                    <p style="font-size: 30px;"><i class="fas fa-rss-square"></i>我的追蹤</p>
                    <?php include_once("hz_include/user/followed.php") ?>
                <? endif; ?>
            </form>
        </div>

    </div>
    </div>
    <div id="copyr">
        <?php include_once "hz_include/themes/def/footer.php"; ?>
    </div>
    </body>
    </html>
<?php } ?>