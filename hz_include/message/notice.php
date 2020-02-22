<p style="font-size: 30px;"><i class="fas fa-bullhorn"></i>&nbsp;系統通知</p>
<?php
$notic = mysqli_query($sqli, "SELECT * FROM SystemNotice WHERE Catcher='$predata[0]' ORDER BY ID DESC ");
if (mysqli_num_rows($notic) > 0):
    $noticnum = 0;
    while ($noticarray = mysqli_fetch_array($notic)):
        $noticid = $noticarray["ID"];
//輸出7日內的通知、或超過7日但是還尚未讀取的通知
        if ((floor((strtotime(date("Y-m-d")) - strtotime(date_format(date_create($noticarray["NoticeTime"]), "Y-m-d"))) / 86400) < 8) || ((floor((strtotime(date("Y-m-d")) - strtotime(date_format(date_create($noticarray["NoticeTime"]), "Y-m-d"))) / 86400) > 8) && ($noticarray["ReadFlag"] == 0))):?>
            <p<? if ($noticarray["ReadFlag"] == 0):
                $updateR = mysqli_query($sqli, "UPDATE SystemNotice SET ReadFlag='1' WHERE ID='$noticid'"); ?>
                style="font-weight: bold";
            <? endif; ?>><i class="fas fa-bullhorn"></i><?= $noticarray["NoticeTime"] ?>．<?= $noticarray["Title"] ?></p>
            <? $noticnum++; ?>
            <hr>
        <? else:
            break; ?>
        <? endif; ?>
    <? endwhile; ?>
    <? if ($noticnum <= 0): ?>
    <p style="margin-top: 15px; text-align: center; font-size: 80px; color: #eeeeee; line-height: 100px"><i
                class="fas fa-bullhorn"></i></p>
    <p style="font-size: 48px; color: #eeeeee; text-align: center;">太好了，沒有任何通知!!</p>
<? endif; ?>
<? else: ?>
    <p style="margin-top: 15px; text-align: center; font-size: 80px; color: #eeeeee; line-height: 100px"><i
                class="fas fa-bullhorn"></i></p>
    <p style="font-size: 48px; color: #eeeeee; text-align: center;">太好了，沒有任何通知!!</p>
<? endif; ?>
