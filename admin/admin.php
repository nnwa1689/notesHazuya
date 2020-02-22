<?php
session_start();
$pagefilename = basename(__FILE__, ".php");//取得本業面的黨名
include("../SQLC.inc.php");
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
    <?php echo "<title>網站總設定 - " . $mate[0] . "管理中心</title>"; ?>
    <link href="include/style.css" rel="stylesheet" type="text/css"/>
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
<div id="tittle"> 網站總設定</div>
<div id="right"><?PHP

    if ($_SESSION["username"] != null && $userlaw["Law_admin"]==1) { ?>
        <?php
        if ($_GET["id"] != "sub") {
            ?>
            <div id="rightcontant">
                <form action="<?php print "?id=sub"; ?>" method="post" enctype="multipart/form-data">
                    <div class="card bg-light mb-3" style="width: 100%;">
                        <div class="card-header"><i class="fas fa-th-list"></i>網站基本資訊</div>
                        <div class="card-body">
                            <p class="card-text">
                            <p>網站名稱(標題)：
                                <input name="newtittle" type="text" value="<?php print $mate[0]; ?>" size="50"/>
                            <div class="alert alert-warning" role="alert">
                                網站名稱禁用字符：&lt;、&gt;，避免被HTML解析。
                            </div>
                            </p>
                            <p>網站網址：
                                <input name="weburl" type="text" value="<?php print $mate[13]; ?>" size="50"/>
                            <p>網站關鍵字：
                                <label for="keywords"></label>
                                <input name="keywords" type="text" id="keywords" size="65"
                                       value="<?php print $mate[1] ?>"/>
                            </p>
                            <p>網站LOGO：
                                <label for="fileField"></label>
                                <label for="logourl"></label>
                                <input name="logourl" type="text" id="logourl" size="65"
                                       value="<?php print $mate[5] ?>"/>
                                或 <a href="../upload.php?id=logo" target="new">點我上傳</a></p>
                            <p>網站描述：</p>
                            <p>
                                <label for="des"></label>
                                <textarea name="des" cols="68" rows="10" id="des"><?php print $mate[2] ?></textarea>
                            </p>
                            <p>網站標頭自訂代碼：</p>
                            <p>
                                <label for="HeadTop"></label>
                                <textarea name="HeadTop" id="HeadTop" cols="68"
                                          rows="10"><?php print $mate[4] ?></textarea>
                            </p>
                            <p>網站底部信息：</p>
                            <p>
                                <label for="footer"></label>
                                <textarea name="footer" id="footer" cols="70" rows="10"
                                          value=""><?php print $mate[3] ?></textarea>
                            </p>
                        </div>
                    </div>
                    <br>
                    <div class="card bg-light mb-3" style="width: 100%;">
                        <div class="card-header"><i class="fas fa-clipboard"></i>網站使用者條款</div>
                        <div class="card-body">
                            <p class="card-text">
                            <p>
                                <label for="userlaw"></label>
                                <textarea name="userlaw" cols="68" rows="10"
                                          id="userlaw"><?php print $mate[15] ?></textarea>
                            </p>
                            在註冊頁顯示：
                            <label for="userlawYN"></label>
                            <select name="userlawYN" id="topbuttonYN">
                                <?php if ($mate[14] == "Yes") { ?>
                                    <option selected="selected">Yes</option>
                                    <option>No</option>
                                <?php } else if ($mate[14] == "No") {
                                    ?>
                                    <option>Yes</option>
                                    <option selected="selected">No</option>
                                <?php } else { ?>
                                    <option selected="selected">Yes</option>
                                    <option>No</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="card bg-light mb-3" style="width: 100%;">
                        <div class="card-header"><i class="fas fa-window-restore"></i>網站顯示格式</div>
                        <div class="card-body">
                            <p class="card-text">
                                公告每頁顯示數量：
                                <label for="HomePostDisplay"></label>
                                <input name="HomePostDisplayNum" type="text" id="HomePostDisplayNum" size="10"
                                       maxlength="3"
                                       value="<?php print $mate[6] ?>"/>
                            <p>部落格文章每頁顯示數量：<input name="BlogDisplayNum" type="text" size="10" maxlength="3"
                                                  value="<?php print $mate[7] ?>"/></p>
                            <p>是否開啟首頁公告：
                                <label for="hpOnAndOff"></label>
                                <label for="hpOnOff"></label>
                                <select name="hpOnOff" id="hpOnOff">
                                    <?php if ($mate[9] == "on") { ?>
                                        <option selected="selected">on</option>
                                        <option>off</option>
                                    <?php } else if ($mate[9] == "off") {
                                        ?>
                                        <option>on</option>
                                        <option selected="selected">off</option>
                                    <?php } else { ?>
                                        <option selected="selected">on</option>
                                        <option>off</option>
                                    <?php } ?>
                                </select>
                                <label for="hpOnOrOff"></label>
                            </p>
                            <p>首頁公告顯示數量：
                                <label for="PostInhome"></label>
                                <input name="PostInhome" type="text" id="PostInhome" size="10" maxlength="3"
                                       value="<?php print $mate[8] ?>"/>
                            </p>
                            <p>底部顯示TOP按鈕：
                                <label for="topbuttonYN"></label>
                                <select name="topbuttonYN" id="topbuttonYN">
                                    <?php if ($mate[10] == "ShowTopButton") { ?>
                                        <option selected="selected">ShowTopButton</option>
                                        <option>off</option>
                                    <?php } else if ($mate[10] == "off") {
                                        ?>
                                        <option>ShowTopButton</option>
                                        <option selected="selected">off</option>
                                    <?php } else { ?>
                                        <option selected="selected">ShowTopButton</option>
                                        <option>off</option>
                                    <?php } ?>
                                </select>
                            </p>

                        </div>
                    </div>
                    <br>
                    <div class="card bg-light mb-3" style="width: 100%;">
                        <div class="card-header"><i class="fas fa-rss"></i>註冊</div>
                        <div class="card-body">
                            <p class="card-text">
                            <p>
                            <p>是否開放讀者註冊：<select name="resOnOff" id="resOnOff">
                                    <?php if ($mate[12] == "on") { ?>
                                        <option selected="selected">on</option>
                                        <option>off</option>
                                    <?php } else if ($mate[12] == "off") {
                                        ?>
                                        <option>on</option>
                                        <option selected="selected">off</option>
                                    <?php } else { ?>
                                        <option selected="selected">on</option>
                                        <option>off</option>
                                    <?php } ?>
                                </select></p>

                            <p>註冊預設權限：
                                <label for="defuse"></label>
                                <select name="defuse" id="defuse">
                                    <?php $lawsql = mysqli_query($sqli,"SELECT * FROM userlaw");
                                    while ($resultlaw = mysqli_fetch_array($lawsql)): ?>
                                        <option value="<? echo $resultlaw["Law_ID"] ?>"><? echo $resultlaw["Lawname"] ?></option>

                                    <? endwhile; ?>
                                </select>
                                <script>
                                    $('#defuse option[value=<?php print $mate[16]; ?>]').attr('selected', 'selected');//自動取得目前設定值
                                </script>
                            </p>
                            </p>
                        </div>
                    </div>
                    <br>
                    <div class="card bg-light mb-3" style="width: 100%;">
                        <div class="card-header">維護模式</div>
                        <div class="card-body">
                            <p>網站開放：<select name="OnOff" id="OnOff">
                                    <?php if ($mate[17] == "on") { ?>
                                        <option selected="selected">on</option>
                                        <option>off</option>
                                    <?php } else if ($mate[17] == "off") {
                                        ?>
                                        <option>on</option>
                                        <option selected="selected">off</option>
                                    <?php } else { ?>
                                        <option selected="selected">on</option>
                                        <option>off</option>
                                    <?php } ?>
                                </select></p>
                            <p>網站關閉訊息：</p>
                            <p>
                                <label for="offin"></label>
                                <textarea name="offin" id="offin" cols="70" rows="10"
                                          value=""><?php print $mate[18] ?></textarea>
                            </p>
                        </div>
                    </div>
                    <br>
                    <div class="card bg-light mb-3" style="width: 100%;">
                        <div class="card-header">管理EMail設定</div>
                        <div class="card-body">
                            <p>
                                <label name="MAdd">站方EMAIL地址</label>
                                <input name="MAdd" value="<?echo $mate[19]?>">
                            </p>
                            <p>※請確保該帳號存在，並與DB保持相同密碼</p>
                        </div>
                    </div>

                    <p><input type="submit" name="button" class="btn btn-primary" value="更改"/>&nbsp;</p>
                </form>
            </div>
            <p>&nbsp;</p>

            <?php
        } else {
            $NewT = addslashes($_POST["newtittle"]);
            $NewKey = addslashes($_POST["keywords"]);
            $NewDes = addslashes($_POST["des"]);
            $NewFooter = addslashes($_POST["footer"]);
            $NewLogoUrl = $_POST["logourl"];
            $NewHPN = $_POST["HomePostDisplayNum"];
            $NewBDN = $_POST["BlogDisplayNum"];
            $NewHPnum = $_POST["PostInhome"];
            $HPonoff = $_POST["hpOnOff"];
            $TOPBonoff = $_POST["topbuttonYN"];
            $resiger = $_POST["resOnOff"];
            $webURL = $_POST["weburl"];
            $userlaw = addslashes($_POST["userlaw"]);
            $userlawYN = $_POST["userlawYN"];
            $defuser = $_POST["defuse"];
            $onoff = $_POST["OnOff"];
            $offin = $_POST["offin"];
            $NewT2 = preg_replace('/\s+/', '&nbsp;', $NewT);
            $NewT2 = str_replace("<", "\&lt;'", $NewT2);
            $NewT2 = str_replace(">", "\&gt;", $NewT2);
            $NewT2 = str_replace('"', "\&quto;", $NewT2);
            $NewH = addslashes($_POST["HeadTop"]);
            $Newoffin = addslashes($offin);
            $NewMadd=addslashes($_POST["MAdd"]);
            $sql = "update web set tittle='$NewT2' where web.id='0'";
            $sql1 = "update web set tittle='$NewKey' where web.id='1'";
            $sql2 = "update web set tittle='$NewDes' where web.id='2'";
            $sql3 = "update web set tittle='$NewFooter' where web.id='3'";
            $sql4 = "update web set tittle='$NewH' where web.id='4'";
            $sql5 = "update web set tittle='$NewLogoUrl' where web.id='5'";
            $sql6 = "update web set tittle='$NewHPN' where web.id='6'";
            $sql7 = "update web set tittle='$NewBDN' where web.id='7'";
            $sql8 = "update web set tittle='$NewHPnum' where web.id='8'";
            $sql9 = "update web set tittle='$HPonoff' where web.id='9'";
            $sql10 = "update web set tittle='$TOPBonoff' where web.id='10'";
            $sql12 = "update web set tittle='$resiger' where web.id='12'";
            $sql13 = "update web set tittle='$webURL' where web.id='13'";
            $sql14 = "update web set tittle='$userlawYN' where web.id='14'";
            $sql15 = "update web set tittle='$userlaw' where web.id='15'";
            $sql16 = "update web set tittle='$defuser' where web.id='16'";
            $sql17 = "update web set tittle='$onoff' where web.id='17'";
            $sql18 = "update web set tittle='$Newoffin' where web.id='18'";
            $sql19 = "update web set tittle='$NewMadd' where web.id='19'";


            $result = mysqli_query($sqli,$sql);
            $result1 = mysqli_query($sqli,$sql1);
            $result2 = mysqli_query($sqli,$sql2);
            $result3 = mysqli_query($sqli,$sql3);
            $result4 = mysqli_query($sqli,$sql4);
            $result5 = mysqli_query($sqli,$sql5);
            $result6 = mysqli_query($sqli,$sql6);
            $result7 = mysqli_query($sqli,$sql7);
            $result8 = mysqli_query($sqli,$sql8);
            $result9 = mysqli_query($sqli,$sql9);
            $result10 = mysqli_query($sqli,$sql10);
            $result12 = mysqli_query($sqli,$sql12);
            $result13 = mysqli_query($sqli,$sql13);
            $result14 = mysqli_query($sqli,$sql14);
            $result15 = mysqli_query($sqli,$sql15);
            $result16 = mysqli_query($sqli,$sql16);
            $result17 = mysqli_query($sqli,$sql17);
            $result18 = mysqli_query($sqli,$sql18);
            $result19 = mysqli_query($sqli,$sql19);
            if ($result && $result1 && $result2 && $result3 && $result4 && $result5 && $result6 && $result7 && $result8 && $result9 && $result10 && $result12 && $result13 && $result14 && $result15 && $result16 && $result17 && $result18&& $result19) {
                include("msg/succ_msg.php");
            } else {
                include("msg/error_msg.php");
            }
        }
    } else {
        print "<br>越權訪問，請重新登入";
        ?>

    <?php } ?></div>
</body>
</html>