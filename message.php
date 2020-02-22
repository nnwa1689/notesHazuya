<?php
$pagefilename = basename(__FILE__, ".php");//取得本業面的
include "hz_include/themes/def/header.php"; ?>
<script src="hz_include/common/js/jquery.js"></script>
<script src="hz_include/common/js/bootstrap.js"></script>
<script src="hz_include/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    function check_all(obj, cName) {
        var checkboxs = document.getElementsByName(cName);
        for (var i = 0; i < checkboxs.length; i++) {
            checkboxs[i].checked = obj.checked;
        }
    }
</script>
<body>
<div id="top">
    <div class="top_contant"><?php include("hz_include/themes/def/header_top.php") ?></div>
</div>
<?php
include_once ("hz_include/themes/def/breadtraiil.php");
include_once("hz_include/func-message.php");
?>
<div id="contant">
    <div id="PageTitle">站內信<br></div>
    <?php
    if ($_SESSION["username"] != null) { //判斷是否登入
    if (true/*$predata[5]!="guest"*/) { //不為Guest用戶才可使用?>

    <div class="pageLeft">
        <div class="list-group" style="text-align: left ;">
            <? if ($_GET["do"] == null): ?>
                <a href="message" class="list-group-item list-group-item-action active"><i
                            class="fas fa-envelope"></i>&nbsp;收件夾</a>
            <? else: ?>
                <a href="message" class="list-group-item list-group-item-action"><i
                            class="fas fa-envelope"></i>&nbsp;收件夾</a>
            <? endif; ?>
            <? if ($_GET["do"] == "send"): ?>
                <a href="message?do=send" class="list-group-item list-group-item-action active"><i
                            class="fas fa-share-square"></i>&nbsp;發送信件</a>
            <? else: ?>
                <a href="message?do=send" class="list-group-item list-group-item-action"><i
                            class="fas fa-share-square"></i>&nbsp;發送信件</a>
            <? endif; ?>
            <? if ($_GET["do"] == "sentmail"): ?>
                <a href="message?do=sentmail" class="list-group-item list-group-item-action active"><i
                            class="fas fa-envelope-square"></i>&nbsp;寄件備份</a>
            <? else: ?>
                <a href="message?do=sentmail" class="list-group-item list-group-item-action"><i
                            class="fas fa-envelope-square"></i>&nbsp;寄件備份</a>
            <? endif; ?>
            <? if ($_GET["do"] == "notice"): ?>
                <a href="message?do=notice" class="list-group-item list-group-item-action active"><i
                            class="fas fa-bullhorn"></i>&nbsp;系統通知</a>
            <? else: ?>
                <a href="message?do=notice" class="list-group-item list-group-item-action"><i
                            class="fas fa-bullhorn"></i>&nbsp;系統通知</a>
            <? endif; ?>
        </div>
    </div>
    <div class="pageRight">
        <?php
        if ($_GET["do"] == null && $_GET["id"] == null) {
            include_once("hz_include/message/messagelist.php");
            recMessageList($b);
        } //查閱寄件
        elseif ($_GET["do"] == "sentmail" && $_GET["id"] == null) {
            include_once("hz_include/message/messagelist.php");
            sendMessageList($b); ?>
            <?php
        } else if ($_GET["do"] == "send") {
            include_once("hz_include/message/sendmessage.php");
            sendMessageUI($b);
        } else if ($_GET["do"] == 'notice') {
            include_once("hz_include/message/notice.php");
        } else if ($_GET["id"] != null && preg_match("/^([0-9]+)$/", $_GET["id"])) {
            include_once("hz_include/message/messagelist.php");
            showMessage($b);
        }
        } else {
            printmsg("error", "站內信無法使用"); ?>
        <?php }
        }
        ?>
    </div>
</div>
<div id="copyr">
    <?php include "hz_include/themes/def/footer.php"; ?>
</div>
</body>
</html>
