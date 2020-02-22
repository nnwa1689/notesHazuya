<?php
/**
 * @param $type error,information,msg
 * @param $msg  string
 */
function printmsg($type, $msg)
{ ?>
<div id="msg">
    <?php
    if ($type === "error"): ?>
        <p style="font-size: 40px; color: red;"><i class="fas fa-times"></i></p>
    <?php elseif ($type === "information"): ?>
        <p style="font-size: 40px;"><i class="fas fa-exclamation"></i></p>
    <?php elseif($type==="suc"):?>
        <p style="font-size: 40px;"><i class="fas fa-check-circle"></i></p>
    <?php endif; ?>
    <p><?php echo $msg?></p>
</div>
<?php
}
?>
