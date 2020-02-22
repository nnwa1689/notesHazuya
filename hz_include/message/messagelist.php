<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2018/5/17
 * Time: 下午 04:27
 */
//收件夾
function recMessageList($b)
{
    global $sqli;
    ?>
    <form action="" method="post">
        <p style="font-size: 30px;"><i class="fas fa-envelope"></i>&nbsp;收件夾</p>
        <?php //DO為NULL時輸出短訊列表
        if ($_POST["editse"] == null):
            if ($_GET["SendID"] == null) {//依照寄件者查詢
                $sql = "SELECT * FROM Message WHERE CatcherId='$b' AND Del_Catcher='0' ORDER BY Message.MessageTime DESC";
            } else if (preg_match("/^([0-9A-Za-z\x7f-\xff]+)$/", $_GET["SendID"])) {
                $SendID = $_GET["SendID"];
                $sql = "SELECT * FROM Message WHERE CatcherId='$b' AND SenderId='$SendID' AND Del_Catcher='0' ORDER BY Message.MessageTime DESC";
            }
            $per = 20;
            include_once("hz_include/themes/def/pagenum.php"); ?>
            <p align="right">批次操作：<select class="custom-select" name="editse">
                    <option value="del" selected="selected">刪除</option>
                    <option value="setread">標示已讀</option>
                </select>
                <input class="btn btn-primary" name="editsub" type="submit" value="批次編輯"/>
                <?php if ($_GET["SendID"] == null) {
                } else {
                    echo " 寄件者查詢結果：" . $SendID; ?> <a href="message">(取消查詢)</a>
                <?php } ?>
            <table id="mes" width="100%" border="0" cellpadding="2" cellspacing="5">
                <tr>
                    <th width="5%" scope="row">
                        <div class="custom-control custom-checkbox">
                            <input id="all" name="all" class="custom-control-input" type="checkbox"
                                   onClick="check_all(this,'msgid[]')"
                                   value="">
                            <label class="custom-control-label" for="all"></label>
                        </div>
                    </th>
                    <td width="45%">訊息主旨</td>
                    <td width="15%">發送者</td>
                    <td width="35%">發送日期</td>
                </tr>
                <?php while ($msg = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <th scope="row" width="5%">
                            <div class="custom-control custom-checkbox">
                                <input id="msgid[<? echo $msg["MessageId"] ?>]" name="msgid[]"
                                       class="custom-control-input"
                                       type="checkbox" value="<? echo $msg["MessageId"] ?>">
                                <label class="custom-control-label"
                                       for="msgid[<? echo $msg["MessageId"] ?>]"></label>
                            </div>
                        </th>
                        <td width="45%">
                            <?php if ($msg["ReadFlag"] == 0) { ?>
                                <strong><a href="<?php echo "message?id=" . $msg["MessageId"] ?>"><?php echo $msg["MessageTitle"] ?> </a></strong>
                            <?php } else { ?>
                                <a href="<?php echo "message?id=" . $msg["MessageId"] ?>"><?php echo $msg["MessageTitle"] ?> </a>
                            <?php } ?>
                        </td>
                        <td width="25%"><a
                                    href="<?php echo "person?id=" . $msg["SenderId"] ?>"><?php echo $msg["SenderId"] ?> </a><a
                                    href="<?php echo "message?SendID=" . $msg["SenderId"] ?>">( <?php echo $msg["SenderId"] ?>
                                )</a></td>
                        <td width="25%"><?php echo $msg["MessageTime"] ?></td>
                    </tr>
                <?php } ?></table>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <?php
                    for ($i = 1; $i <= $pages; $i++) {
                        if ($_GET["page"] == null) {
                            $nowpagenum = 1;
                        } else {
                            $nowpagenum = $_GET["page"];
                        }
                        if ($nowpagenum == $i) {
                            ?>
                            <li class="page-item active"><a class="page-link"><?php echo $i ?></a></li>
                        <?php } else { ?>
                            <li class="page-item"><a class="page-link"
                                                     href="<?php echo "?page=" . $i . "&SendID=" . $SendID ?>"><?php echo $i ?></a>
                            </li>
                        <?php }
                    } ?>
                </ul>
            </nav>
        <? elseif ($_POST["editse"] != null):
            $editdoing = $_POST["editse"];
            if ($editdoing == "del") {
                $PP = $_POST["msgid"];
                if (count($PP) > 0) {
                    foreach ($PP as $value) {
                        $sql = "UPDATE Message SET Del_Catcher = '1' WHERE `MessageId` = $value";
                        $result = mysqli_query($sqli, $sql);
                    }
                    if ($result) {
                        printmsg("suc", "刪除成功!");
                        ?>
                        <meta http-equiv=REFRESH CONTENT=1;url=message>

                    <?php }
                } else {
                    printmsg("error", "刪除失敗!");
                    ?>
                    <meta http-equiv=REFRESH CONTENT=3;url=message>
                <?php }
            } else if ($editdoing == "setread") {
                $PP = $_POST["msgid"];
                if (count($PP) > 0) {
                    foreach ($PP as $value) {
                        $sql = "UPDATE Message SET ReadFlag = '1' WHERE `MessageId` = $value";
                        $result = mysqli_query($sqli, $sql);
                    }
                    if ($result) {
                        printmsg("suc", "操作成功!");
                        ?>
                        <meta http-equiv=REFRESH CONTENT=1;url=message>

                    <?php }
                } else {
                    printmsg("error", "操作失敗!");
                    ?>
                    <meta http-equiv=REFRESH CONTENT=3;url=message>
                <?php }
            }
        endif; ?>
    </form>
    <?
}

//寄件查閱
function sendMessageList($b)
{
    global $sqli; ?>
    <form action="" method="post">
    <p style="font-size: 30px;"><i class="fas fa-envelope-square"></i>&nbsp;寄件備份</p>
    <?php
    $sql = "SELECT * FROM Message WHERE SenderId='$b' AND Del_Send='0' ORDER BY Message.MessageTime DESC";
    $per = 20;
    include_once("hz_include/themes/def/pagenum.php"); ?>
    <? if ($_POST["editse"] == null): ?>
    <p align="right">批次操作：<select class="custom-select" name="editse">
            <option value="del" selected="selected">刪除</option>
        </select>
        <input class="btn btn-primary" name="editsub" type="submit" value="批次編輯">
    <table id="mes" width="100%" border="0" cellpadding="2" cellspacing="5">
        <tr>
            <th width="5%" scope="row">
                <div class="custom-control custom-checkbox">
                    <input id="all" name="all" class="custom-control-input" type="checkbox"
                           onClick="check_all(this,'msgid[]')"
                           value="">
                    <label class="custom-control-label" for="all"></label>
                </div>
            </th>
            <td width="45%">訊息主旨</td>
            <td width="15%">收件者</td>
            <td width="35%">發送日期</td>
        </tr>
        <?php while ($msg = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <th scope="row" width="5%">
                    <div class="custom-control custom-checkbox">
                        <input id="msgid[<? echo $msg["MessageId"] ?>]" name="msgid[]"
                               class="custom-control-input"
                               type="checkbox" value="<? echo $msg["MessageId"] ?>">
                        <label class="custom-control-label"
                               for="msgid[<? echo $msg["MessageId"] ?>]"></label>
                    </div>
                </th>
                <td width="45%">
                    <a href="<?php echo "message?id=" . $msg["MessageId"] ?>"><?php echo $msg["MessageTitle"] ?> </a>
                </td>
                <td width="25%"><a
                            href="<?php echo "person?id=" . $msg["CatcherId"] ?>"><?php echo $msg["CatcherId"] ?> </a>
                </td>
                <td width="25%"><?php echo $msg["MessageTime"] ?></td>
            </tr>
        <?php } ?></table>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $pages; $i++) {
                if ($_GET["page"] == null) {
                    $nowpagenum = 1;
                } else {
                    $nowpagenum = $_GET["page"];
                }
                if ($nowpagenum == $i) {
                    ?>
                    <li class="page-item active"><a class="page-link"><?php echo $i ?></a></li>
                <?php } else { ?>
                    <li class="page-item"><a class="page-link"
                                             href="<?php echo "?do=sentmail&page=" . $i . "&SendID=" . $SendID ?>"><?php echo $i ?></a>
                    </li>
                <?php }
            } ?>
        </ul>
    </nav>
<? elseif ($_POST["editse"] != null):
    $editdoing = $_POST["editse"];
    if ($editdoing == "del") {
        $PP = $_POST["msgid"];
        if (count($PP) > 0) {
            foreach ($PP as $value) {
                $sql = "UPDATE Message SET Del_Send = '1' WHERE `MessageId` = $value";
                $result = mysqli_query($sqli, $sql);
            }
            if ($result) {
                printmsg("suc", "刪除成功!");
                ?>
                <meta http-equiv=REFRESH CONTENT=1;url=message?do=sentmail>

            <?php }
        } else {
            printmsg("error", "刪除失敗!");
            ?>
            <meta http-equiv=REFRESH CONTENT=3;url=message?do=sentmail>
        <?php }
    }
    ?>
    </form>
<? endif;

}

//信件內容
function showMessage($b)
{
    global $sqli;
    $nowmsg = $_GET["id"];
    $nowsql = "SELECT * FROM Message WHERE MessageId='$nowmsg'";
    $nowresult = mysqli_query($sqli, $nowsql);
    $msgarray = mysqli_fetch_array($nowresult);
    if ($msgarray["CatcherId"] == $b) {
        $readsql = "UPDATE Message SET ReadFlag = 1 WHERE MessageId = '$nowmsg'";
        $resultreadsql = mysqli_query($sqli, $readsql);
        ?>
        <i class="fas fa-calendar-alt"></i><?php echo "發送日期：" . $msgarray["MessageTime"] ?> ｜<i
                class="fas fa-user"></i>發送者帳號：
        <a href="<?php echo "person?id=" . $msgarray["SenderId"] ?>"><?php echo $msgarray["SenderId"] ?> </a>
        <div id="postTitle">
            <?php echo $msgarray["MessageTitle"] ?>
        </div>
        <?php echo $msgarray["MessageText"]; ?>
        <p><br></p>
    <form action="message?do=send&catcherid=<?php echo $msgarray["SenderId"] ?>&re=<?php echo "Re：" . $msgarray["MessageTitle"] ?>"
          method="post">
        <?php if ($msgarray["SenderId"] != "系統管理員") { ?>
            <p><input type="submit" name="button" class="btn btn-primary" value="回信"/></p>
            </form>
        <?php }
    } elseif ($msgarray["SenderId"] == $b) {
        ?>
        <i class="fas fa-calendar-alt"></i><?php echo "發送日期：" . $msgarray["MessageTime"] ?> ｜<i
                class="fas fa-user"></i>收件者帳號：
        <a href="<?php echo "person?id=" . $msgarray["CatcherId"] ?>"><?php echo $msgarray["CatcherId"] ?> </a>
        <div id="postTitle">
            <?php echo $msgarray["MessageTitle"] ?>
        </div>
        <?php echo $msgarray["MessageText"]; ?>
    <?php } else {

    }
}