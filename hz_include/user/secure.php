<div name="pwpage" style="text-align: center;">
    <?php
    if ($_SESSION["username"] != null):
        if ($_POST["pwo"] == null || $_POST["pw1"] == null || $_POST["pw1"] == null):?>

            <p>新的密碼<br>
                <label for="pw1"></label>
                <input type="password" name="pw1" class="form-control" id="pw1" maxlength="40"/>
            </p>
            <p>確認密碼<br>
                <label for="pw2"></label>
                <input type="password" name="pw2" id="pw2" class="form-control" maxlength="40"/>

            </p>
            <br/>
            請輸入目前的密碼驗證身分<br>
            <label for="pwo"></label>
            <input type="password" name="pwo" id="pwo" class="form-control" maxlength="40"/>
            <p style="text-align: center"><input style="width: 120px; height: 60px" type="submit" name="button"
                                                 class="btn btn-primary" value="確認"/></p>
        <?php else:
            if ($_POST["pw1"] === $_POST["pw2"] && identifyPw($b, $_POST["pwo"])) :
                $pw1 = hashpw(addslashes($_POST["pw1"]));
                if (mysqli_query($sqli, "update admin set pw='$pw1' where username='$b'")):
                    printmsg("suc", "您的密碼已經變更"); ?>
                    <meta http-equiv=REFRESH CONTENT=3;url=setting?action=secure>
                <?php
                else:
                    printmsg("error", "密碼變更失敗，請重新嘗試"); ?>
                    <meta http-equiv=REFRESH CONTENT=3;url=setting?action=secure>
                <?php endif; ?>
            <?php elseif ($_POST["pw1"] !== $_POST["pw2"]) :
                printmsg("error", "密碼變更失敗，兩次密碼不相同，請重新操作"); ?>
                <meta http-equiv=REFRESH CONTENT=3;url=setting?action=secure>
            <? elseif (!identifyPw($b, $_POST["pwo"])) :
                printmsg("error", "密碼變更失敗，原密碼錯誤，請重新操作"); ?>
                <meta http-equiv=REFRESH CONTENT=3;url=setting?action=secure>
            <? endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>
