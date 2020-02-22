<?php
/****************************
 * -->底部導航模組
 * -->設計:hazuya
 * -->2018/2/10
 ****************************/

/**************************
 * ->輸出底部導航
 ***************************/ ?>
<?php
$nsql = "SELECT * FROM Navigate WHERE Competence='public' AND type='1' ORDER BY NavigateId ASC";
$nresult = mysqli_query($sqli,$nsql);
$numFooterN = mysqli_num_rows($nresult);
$nowNum = 0;
if ($numFooterN > 0) {
    while ($fnarray = mysqli_fetch_array($nresult)) {
        if ($nowNum == 0) {?>
            <a id="fn" href="<?php print $fnarray["URL"] ?>"><?php print $fnarray["NavigateName"] ?></a>
            <?php
        } elseif ($nowNum > 0) {?>
            &nbsp;&nbsp;<a id="fn" href="<?php print $fnarray["URL"] ?>"><?php print $fnarray["NavigateName"] ?></a>
            <?php
        }
        $nowNum++;
    }
} else {

}
?>

