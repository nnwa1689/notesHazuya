<?php
/****************************
 * -->前台使用者工作列模組(含UI)
 * -->設計:hazuya
 * -->2018/2/10
 ****************************/

/**************************
 * 重要關聯模組:class-meta.php
 *************************/

/*************************
 * ->狀態:登入與尚未登入狀態
 ***************************/

if ($_SESSION["username"] == null) : //未登入
    if ($mate[12] == "on") : //判斷註冊是否開放
        ?>
        <a href="register"><i class="fas fa-rss"></i>&nbsp;註冊</a>
    <?php endif; ?>
    &nbsp;&nbsp;<a href="login"><i class="fas fa-sign-in-alt"></i>&nbsp;登入</a>
<?php
elseif ($_SESSION["username"] != null) : //登入 //顯示使用者名稱(暱稱)(權限)
//站內信數量查詢
$msgReadnum = 0;
$sqlmsgheader = "SELECT * FROM Message WHERE CatcherId='$b' ORDER BY Message.MessageTime DESC";
$resultmsgheader = mysqli_query($sqli, $sqlmsgheader);
while ($msgnum = mysqli_fetch_array($resultmsgheader)) :
    if ($msgnum["ReadFlag"] == 0 && $msgnum["Del_Catcher"] == 0) :
        $msgReadnum++;
        if ($msgReadnum > 99)
            break;
    endif;
endwhile;

$noticeReadnum = 0;
$sqlnoticeheader = "SELECT * FROM SystemNotice WHERE Catcher='$b' ORDER BY ID DESC";
$resultnoticeheader = mysqli_query($sqli, $sqlnoticeheader);
while ($noticenum = mysqli_fetch_array($resultnoticeheader)) :
    if ($noticenum["ReadFlag"] == 0) :
        $noticeReadnum++;
        if ($noticeReadnum > 99)
            break;
    endif;
endwhile;
?>
<ul class="drop-down-menu">
    <li>
            <a style="color: #0096ff; text-align: right; margin-right: 0px"
               href="person?id=<?php echo $_SESSION["username"] ?>"><img class="img-circle"
                                                                         src="<?php echo $predata[7] ?>" width="32"
                                                                         height="32">
            </a>

        <ul style="right: 0px; left: auto;">
            <a style="line-height: 25px"><?=$predata[4]?><br><?="@".$predata[0]?></a>
            <a href="setting"><i class="fas fa-user"></i>&nbsp;個人設定</a>
            <?php
            if ($msgReadnum > 0) :
                if ($msgReadnum < 100) :?>
                    <a style="font-weight: bold" href="message"><i
                                class="fas fa-envelope"></i>&nbsp;站內信(<?php echo $msgReadnum ?>)</a>
                <?php
                else :?>
                    <a style="font-weight: bold" href="message"><i class="fas fa-envelope"></i>&nbsp;站內信(99+)</a>
                <?endif;
            else :?>
                <a href="message"><i class="far fa-envelope"></i>&nbsp;站內信</a>
            <?php endif; ?>
            <?
            if ($noticeReadnum > 0) :
                if ($noticeReadnum < 100) :?>
                    <a style="font-weight: bold" href="message?do=notice"><i
                                class="fas fa-bullhorn"></i>&nbsp;通知(<?php echo $noticeReadnum ?>)</a>
                <? else :?>
                    <a style="font-weight: bold" href="message?do=notice"><i
                                class="fas fa-bullhorn"></i>&nbsp;通知(99+)</a>
                <?endif;
            else :?>
                <a href="message?do=notice"><i class="fas fa-bullhorn"></i>&nbsp;通知</a>
            <?php endif; ?>
            <?php if ($userlaw["Law_ControlVisit"] == 1) : ?>
                <a href="admin/index"><i class="fas fa-wrench"></i>&nbsp;系統管理</a>
            <?php endif; ?>
            <a href="logout"><i class="fas fa-sign-out-alt"></i>&nbsp;登出</a>
            <?php endif; ?>
        </ul>
    </li>
</ul>

