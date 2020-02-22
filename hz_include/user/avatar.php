<?php
if ($_SESSION["username"] != null) {
    if ($_POST["do"] == null): ?>
        <p style="text-align: center">
            <img class="img-circle" src="<?php echo $predata[7] ?>" width="256" height="256"/>
        <p><br></p>
        <p style="text-align: center">來源網址：<input name="avatar" class="form-control" type="text" size="50"
                                                  maxlength="300"
                                                  value="<?php echo $predata[7] ?>"></p>
        <p style="text-align: center;" ><a href="upload?id=avatar" target="new">點我上傳</a></p>
        <p>
            <input name="do" type="text" maxlength="10" hidden="hidden" value="0">
        </p>
        <div class="alert alert-warning" role="alert">
            請確保圖片是正方形(1:1)之比例，不然圖片效果可能會很差，建議約400*400之解析度
        </div>
        <p style="text-align: center;"><input style="width: 120px; height: 60px" type="submit" name="button"
                                              class="btn btn-primary" value="確認"/></p>
    <?php
    elseif ($_POST["do"] === "0"):
        $avatar = htmlentities(addslashes($_POST["avatar"]), 3, "UTF-8");
        if ($avatar == null) {
            $sqlavatar = "update admin set Avatar='uploadfile/avatar/avatardef.jpg' where username='$b'";
        } else {
            $sqlavatar = "update admin set Avatar='$avatar' where username='$b'";
        }
        if (mysqli_query($sqli, $sqlavatar)):
            printmsg("suc", "您的操作已經儲存"); ?>
            <meta http-equiv=REFRESH CONTENT=3;url=setting?action=avatar>
        <?php
        else:
            printmsg("error", "操作有問題，請再嘗試"); ?>
            <meta http-equiv=REFRESH CONTENT=3;url=setting?action=avatar>
        <?php endif; ?>
    <? endif; ?>
<?php } ?>
