<?php
session_start();
$pagefilename = basename(__FILE__, ".php");//取得本業面的黨名
include_once("../SQLC.inc.php");
$sql = "SELECT * FROM web ORDER BY `ID` ASC";
$result = mysqli_query($sqli,$sql);
$List = mysqli_num_rows($result);
$mate = array("", "", "", "");
for ($i = 0; $i < $List; $i++) {
    $search = mysqli_fetch_assoc($result);
    $mate[$i] = $search["tittle"];
}
//****************************************
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo "<title>權限組管理 - " . $mate[0] . "管理中心</title>"; ?>
    <link href="include/style.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript">
        function check_all(obj, cName) {
            var checkboxs = document.getElementsByName(cName);
            for (var i = 0; i < checkboxs.length; i++) {
                checkboxs[i].checked = obj.checked;
            }
        }
    </script>
    <style type="text/css">
        body {
            margin-left: 0px;
            margin-top: 0px;
            margin-bottom: 0px;
            font-family: "微軟正黑體";
            background-color: #E9E9E9;
        }
    </style>
</head>

<body>
<div id="leftnav"> <?php include_once("include/admin-nav.php") ?></div>

<div id="tittle"> 權限組管理</div>
<div id="right">
    <div id="rightcontant">
        <div class="card bg-light mb-3" style="width: 100%;">
            <div class="card-header">權限組</div>
            <div class="card-body">
                <div class="alert alert-warning" role="alert">
                    <p>1.系統預設權限不得刪除</p>
                    <p>2.系統預設管理員權限不得編輯</p>
                </div>
                <?php
                if ($_SESSION["username"] != null && $userlaw["Law_member"] == 1) {
                    if (preg_match("/^([0-9]+)$/", $_GET["userlaw"])) {
                        $edituserlaw = $_GET["userlaw"];
                    } else if (preg_match("/^([0-9]+)$/", $_GET["userlawdel"])) {
                        $deluserlaw = $_GET["userlaw"];
                    }
                    if ($edituserlaw == null && $deluserlaw == null) :?>
                        <p style="text-align: right"><a href="?userlaw=<?php echo 0 ?>">新增權限組</a></p>
                        <form action="" method="post">
                            <table width="100%" border="0" cellpadding="2" cellspacing="5">
                                <tr>
                                    <td width="10%">權限組ID</td>
                                    <td width="30%">權限組名稱</td>
                                    <td width="30%">編輯</td>
                                    <td width="30%">類別</td>
                                </tr>

                                <?php
                                $lawsql = mysqli_query($sqli,"SELECT * FROM `userlaw`");
                                while ($alluserlaw = mysqli_fetch_array($lawsql)):?>
                                    <tr>
                                        <td width="10%"><? echo $alluserlaw["Law_ID"] ?></td>
                                        <td width="30%"><? echo $alluserlaw["Lawname"] ?></td>
                                        <td width="30%">
                                            <?php if ($alluserlaw["Law_ID"] != 1): ?>
                                                <a href="?userlaw=<?php echo $alluserlaw["Law_ID"] ?>">編輯</a>
                                            <?php endif; ?>
                                        </td>
                                        <td width="30%"><?php echo $alluserlaw["Type"] ?></td>

                                    </tr>

                                <?php endwhile; ?>
                        </form>
                    <? elseif ($edituserlaw != null && $edituserlaw != 1):
                        if ($edituserlaw != 0) {
                            $thisuserlaw = mysqli_fetch_array(mysqli_query($sqli,"SELECT * FROM `userlaw` WHERE Law_ID='$edituserlaw'"));
                        } else {

                        } ?>
                        <form name="lawedit" action="" method="post">
                        <?php
                        $doing = $_POST["do"];
                        if ($doing == null): ?>

                            <h2><label name="lawname">權限組名稱：</label><input name="lawname" size="10px" maxlength="30"
                                                                           type="text"
                                                                           value="<? echo $thisuserlaw["Lawname"] ?>">
                            </h2>
                            <h2>前台權限</h2>
                            <p><label name="read">閱讀權限:</label><input name="read" size="10px" maxlength="3"
                                                                      type="text"
                                                                      value="<? echo $thisuserlaw["Law_Read"] ?>">(0-999)
                            </p>
                            <p><label name="reply">允許回文:</label>
                                <select name="reply" id="reply">
                                    <option value="1">允許</option>
                                    <option value="0">不允許</option>
                                </select>
                                <script>
                                    $('#reply option[value=<?php echo $thisuserlaw["Law_PostReply"] ?>]').attr('selected', 'selected');//自動取得目前設定值
                                </script>
                            </p>
                            <p><label name="visit">允許維護模式訪問:</label>
                                <select name="visit" id="visit">
                                    <option value="1">允許</option>
                                    <option value="0">不允許</option>
                                </select>
                                <script>
                                    $('#visit option[value=<?php echo $thisuserlaw["Law_Visit"] ?>]').attr('selected', 'selected');//自動取得目前設定值
                                </script>
                            </p>
                            <p><label name="upload">允許上傳檔案:</label>
                                <select name="upload" id="upload">
                                    <option value="1">允許</option>
                                    <option value="0">不允許</option>
                                </select>
                                <script>
                                    $('#upload option[value=<?php echo $thisuserlaw["Law_Upload"] ?>]').attr('selected', 'selected');//自動取得目前設定值
                                </script>
                            </p>
                            <h2>後台權限</h2>
                            <p><label name="cpv">允許訪問後台:</label>
                                <select name="cpv" id="cpv">
                                    <option value="1">允許</option>
                                    <option value="0">不允許</option>
                                </select>
                                <script>
                                    $('#cpv option[value=<?php echo $thisuserlaw["Law_ControlVisit"] ?>]').attr('selected', 'selected');//自動取得目前設定值
                                </script>
                            </p>
                            <p><label name="admin">允許修改網站總體:</label>
                                <select name="admin" id="admin">
                                    <option value="1">允許</option>
                                    <option value="0">不允許</option>
                                </select>
                                <script>
                                    $('#admin option[value=<?php echo $thisuserlaw["Law_admin"] ?>]').attr('selected', 'selected');//自動取得目前設定值
                                </script>
                            </p>
                            <p><label name="web">允許發布公告與站內信:</label>
                                <select name="web" id="web">
                                    <option value="1">允許</option>
                                    <option value="0">不允許</option>
                                </select>
                                <script>
                                    $('#web option[value=<?php echo $thisuserlaw["Law_web"] ?>]').attr('selected', 'selected');//自動取得目前設定值
                                </script>
                            </p>
                            <p><label name="page">允許編輯頁面:</label>
                                <select name="page" id="page">
                                    <option value="1">允許</option>
                                    <option value="0">不允許</option>
                                </select>
                                <script>
                                    $('#page option[value=<?php echo $thisuserlaw["Law_page"] ?>]').attr('selected', 'selected');//自動取得目前設定值
                                </script>
                            </p>
                            <p><label name="nav">允許管理導航:</label>
                                <select name="nav" id="nav">
                                    <option value="1">允許</option>
                                    <option value="0">不允許</option>
                                </select>
                                <script>
                                    $('#nav option[value=<?php echo $thisuserlaw["Law_navigate"] ?>]').attr('selected', 'selected');//自動取得目前設定值
                                </script>
                            </p>
                            <p><label name="member">允許管理會員與權限組:</label>
                                <select name="member" id="member">
                                    <option value="1">允許</option>
                                    <option value="0">不允許</option>
                                </select>
                                <script>
                                    $('#member option[value=<?php echo $thisuserlaw["Law_member"] ?>]').attr('selected', 'selected');//自動取得目前設定值
                                </script>
                            </p>
                            <p><label name="blogpost">允許管理文章:</label>
                                <select name="blogpost" id="blogpost">
                                    <option value="2">允許且可編輯他人文章</option>
                                    <option value="1">允許但僅編輯自己文章</option>
                                    <option value="0">不允許</option>
                                </select>
                                <script>
                                    $('#blogpost option[value=<?php echo $thisuserlaw["Law_PostBlog"] ?>]').attr('selected', 'selected');//自動取得目前設定值
                                </script>
                            </p>
                            <p><label name="Bclass">允許管理文章分類:</label>
                                <select name="Bclass" id="Bclass">
                                    <option value="1">允許</option>
                                    <option value="0">不允許</option>
                                </select>
                                <script>
                                    $('#Bclass option[value=<?php echo $thisuserlaw["Law_BlogClass"] ?>]').attr('selected', 'selected');//自動取得目前設定值
                                </script>
                            </p>
                            <p><label name="filem">允許管理媒體庫:</label>
                                <select name="filem" id="filem">
                                    <option value="1">允許</option>
                                    <option value="0">不允許</option>
                                </select>
                                <script>
                                    $('#filem option[value=<?php echo $thisuserlaw["Law_filem"] ?>]').attr('selected', 'selected');//自動取得目前設定值
                                </script>
                            </p>
                            <p><label name="replym">允許管理回覆:</label>
                                <select name="replym" id="replym">
                                    <option value="1">允許</option>
                                    <option value="0">不允許</option>
                                </select>
                                <script>
                                    $('#replym option[value=<?php echo $thisuserlaw["Law_reply"] ?>]').attr('selected', 'selected');//自動取得目前設定值
                                </script>
                            </p>
                            <p><label name="mes">允許管理站內信</label>
                                <select name="mes" id="mes">
                                    <option value="1">允許</option>
                                    <option value="0">不允許</option>
                                </select>
                                <script>
                                    $('#mes option[value=<?php echo $thisuserlaw["Law_msg"] ?>]').attr('selected', 'selected');//自動取得目前設定值
                                </script>
                            </p>
                            <p><label name="mod">允許管理模組:</label>
                                <select name="mod" id="mod">
                                    <option value="1">允許</option>
                                    <option value="0">不允許</option>
                                </select>
                                <script>
                                    $('#mod option[value=<?php echo $thisuserlaw["Law_mods"] ?>]').attr('selected', 'selected');//自動取得目前設定值
                                </script>
                            </p>
                            <p style="text-align: right"><label name="do">操作</label>
                                <select name="do" id="do">
                                    <? if ($edituserlaw == 0): ?>
                                        <option value="new">新增</option>
                                    <? endif; ?>
                                    <? if ($edituserlaw != 0): ?>
                                        <option value="update">更新</option>
                                    <? endif; ?>
                                    <? if ($thisuserlaw["Type"] != "system" && $edituserlaw != 0): ?>
                                        <option value="del">刪除</option>
                                    <? endif; ?>
                                </select>｜
                                <input class="btn btn-primary" name="editsub" type="submit" value="編輯"/>
                            </p>

                        <? elseif ($doing == "update"):
                            $lawid = $thisuserlaw["Law_ID"];
                            $data = array($_POST["lawname"], $_POST["read"], $_POST["reply"], $_POST["visit"], $_POST["upload"], $_POST["cpv"], $_POST["admin"], $_POST["web"], $_POST["page"], $_POST["nav"], $_POST["member"], $_POST["blogpost"], $_POST["Bclass"], $_POST["filem"], $_POST["replym"], $_POST["mes"], $_POST["mod"]);
                            $sqlname = array("Lawname", "Law_Read", "Law_PostReply", "Law_Visit", "Law_Upload", "Law_ControlVisit", "Law_admin", "Law_web", "Law_page", "Law_navigate", "Law_member", "Law_PostBlog", "Law_BlogClass", "Law_filem", "Law_reply", "Law_msg", "Law_mods");
                            for ($i = 0; $i < count($data); $i++) {
                                $sname = $sqlname[$i];
                                $datacon = $data[$i];
                                $error = 0;
                                if (!mysqli_query($sqli,"UPDATE userlaw SET $sname = '$datacon' WHERE Law_ID = $lawid")) {
                                    $error = 1;
                                }
                            }
                            if ($error == 0):?>
                                <div id="msg">
                                    <p><img src="image/succ.png"></p>
                                    <strong>操作成功!<br/>
                                        請等候轉跳......
                                    </strong>
                                </div>
                                <meta http-equiv=REFRESH CONTENT=3;url=<?php print $pagefilename . ".php"; ?>>
                            <? else: ?>
                                <div id="msg">
                                <p><img src="image/error.png"></p>
                                <strong>操作失敗!<br/>
                                    請等候轉跳......
                                </strong>
                            <?endif;
                        elseif ($doing == "new" && $_POST["lawname"] != null && $_POST["read"] != null):
                            $lawname = $_POST["lawname"];
                            $lawread = $_POST["read"];
                            $lawreply = $_POST["reply"];
                            $lawvisit = $_POST["visit"];
                            $lawupload = $_POST["upload"];
                            $lawcpv = $_POST["cpv"];
                            $lawadmin = $_POST["admin"];
                            $lawweb = $_POST["web"];
                            $lawpage = $_POST["page"];
                            $lawnav = $_POST["nav"];
                            $lawmember = $_POST["member"];
                            $lawblogpost = $_POST["blogpost"];
                            $lawBclass = $_POST["Bclass"];
                            $lawfilem = $_POST["filem"];
                            $lawreplym = $_POST["replym"];
                            $lawmes = $_POST["mes"];
                            $lawmod = $_POST["mod"];
                            $error = 0;
                            if (!mysqli_query($sqli,"INSERT INTO userlaw (Lawname, Law_Read, Law_PostReply, Law_Visit, Law_Upload, Law_ControlVisit, Law_admin, Law_web, Law_page, Law_navigate, Law_member, Law_PostBlog, Law_BlogClass, Law_filem, Law_reply, Law_msg, Law_mods,Type) VALUES ('$lawname','$lawread','$lawreply','$lawvisit','$lawupload','$lawcpv','$lawadmin','$lawweb','$lawpage','$lawnav','$lawmember','$lawblogpost','$lawBclass','$lawfilem','$lawreplym','$lawmes','$lawmod','user')")) {
                                $error = 1;
                            }

                            if ($error == 0):?>
                                <div id="msg">
                                    <p><img src="image/succ.png"></p>
                                    <strong>操作成功!<br/>
                                        請等候轉跳......
                                    </strong>
                                </div>
                                <meta http-equiv=REFRESH CONTENT=3;url=<?php print $pagefilename . ".php"; ?>>
                            <? else: ?>
                                <div id="msg">
                                <p><img src="image/error.png"></p>
                                <strong>操作失敗!<br/>
                                    請等候轉跳......
                                </strong>
                            <?endif;
                        elseif ($doing == "del"):
                            if (!mysqli_query($sqli,"DELETE FROM userlaw WHERE Law_ID='$edituserlaw'")) {
                                $error = 1;
                            } else
                                $error = 0;
                            if ($error == 0):?>
                                <div id="msg">
                                    <p><img src="image/succ.png"></p>
                                    <strong>操作成功!<br/>
                                        請等候轉跳......
                                    </strong>
                                </div>
                                <meta http-equiv=REFRESH CONTENT=3;url=<?php print $pagefilename . ".php"; ?>>
                            <? else: ?>
                                <div id="msg">
                                <p><img src="image/error.png"></p>
                                <strong>操作失敗!<br/>
                                    請等候轉跳......
                                </strong>
                            <?endif;
                            ?>

                        <? endif; ?>
                    <?php endif; ?>
                    </form>
                    <?php
                } else {
                    include("msg/error_msg.php");
                    ?>
                    <p>&nbsp;</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>