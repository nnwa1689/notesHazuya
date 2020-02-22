<?php
if ($_SESSION["username"] != null) {
    if ($_POST["do"] != "50") { ?>
        <div style="text-align: center">
            <div class="card bg-light mb-3" style="width: 100%;">
                <div class="card-header"><i class="fas fa-user"></i>使用者名稱與權限</div>
                <div class="card-body">
                    <p class="card-text">
                    <p>名稱：<?php echo "$b"; ?>　｜　權限：<?php echo userLawName($predata[5]) ?>　｜　
                        資料權限： <select class="custom-select" name="competance" id="competance">
                            <option value="public">public</option>
                            <option value="protect">protect</option>
                            <option value="private">private</option>
                        </select>
                        <script>
                            $('#competance option[value=<?php echo $predata[9]?>]').attr('selected', 'selected');//自動取得目前設定值
                        </script>
                    </p>
                    </p>
                </div>
            </div>
            <br>
            <div class="card bg-light mb-3" style="width: 100%;">
                <div class="card-header"><i class="fas fa-pencil-alt"></i> 個人簽名 <select class="custom-select"
                                                                                        name="SignatureShow"
                                                                                        id="SignatureShow">
                        <option value="0">不顯示</option>
                        <option value="1">個人頁面</option>
                        <?php if ($userlaw["Law_PostBlog"] > 0): ?>
                            <option value="2">文章</option>
                            <option value="3">個人頁面與文章</option>
                        <? endif; ?>
                    </select>
                    <script>
                        $('#SignatureShow option[value=<?php echo $predata[17]?>]').attr('selected', 'selected');//自動取得目前設定值
                    </script>
                </div>
                <div class="card-body">
                    <p class="card-text">
                    <p><textarea class="form-control" name="Signature" id="Signature" cols="20"
                                 rows="5" maxlength="80"><?php echo $predata[16] ?></textarea></p>
                    </p>
                </div>
            </div>
            <br>
            <div class="card bg-light mb-3" style="width: 100%;">
                <div class="card-header"><i class="far fa-keyboard"></i>暱稱＊</div>
                <div class="card-body">
                    <p class="card-text">
                    <p><input class="form-control" name="name" type="text" maxlength="10"
                              value="<?php echo $predata[4] ?>">
                    <p>*暱稱名稱僅能包含中文及英數</p>
                    </p>
                </div>
            </div>
            <br>
            <div class="card bg-light mb-3" style="width: 100%;">
                <div class="card-header">
                    <i class="fas fa-at"></i>信箱＊&nbsp;
                    <select class="custom-select" name="mailshow" id="mailshow">
                        <option value="show">顯示</option>
                        <option value="hide">隱藏</option>
                    </select>
                    <script>
                        $('#mailshow option[value=<?php echo $predata[13]?>]').attr('selected', 'selected');//自動取得目前設定值
                    </script>
                </div>
                <div class="card-body">
                    <p class="card-text">
                    <p><input class="form-control" name="email" type="text" size="50"
                              value="<?php echo $predata[2] ?>"/>
                    </p>
                    </p>
                </div>
            </div>
            <br>
            <div class="card bg-light mb-3" style="width: 100%;">
                <div class="card-header">
                    <i class="fas fa-link"></i>個人網頁&nbsp;
                    <select class="custom-select" name="webshow" id="webshow">
                        <option value="show">顯示</option>
                        <option value="hide">隱藏</option>
                    </select>
                    <script>
                        $('#webshow option[value=<?php echo $predata[14]?>]').attr('selected', 'selected');//自動取得目前設定值
                    </script>
                </div>
                <div class="card-body">
                    <p class="card-text">
                    <p><input class="form-control" name="website" type="text" size="50"
                              value="<?php echo $predata[3] ?>"/>
                    </p>
                    </p>
                </div>
            </div>
            <br>
            <div class="card bg-light mb-3" style="width: 100%;">
                <div class="card-header">
                    <i class="fas fa-image"></i>個人頁面標題背景&nbsp;
                </div>
                <div class="card-body">
                    <p class="card-text">
                    <p><input class="form-control" name="perbg" size="50" type="text"
                              value="<?php echo $predata[10] ?>"/><a
                                href="upload?id=perbg" target="new">點我上傳</a><br/>若留空代表使用預設標題</p>
                    </p>
                </div>
            </div>
            <br>
            <input name="do" type="text" maxlength="10" hidden="hidden" value="50">
            <p style="text-align: center"><input style="width: 120px; height: 60px" type="submit" name="button"
                                                 class="btn btn-primary" value="確認"/></p>
        </div>
    <?php } elseif ($_POST["do"] === "50") {
        $errornum = 0;
        $name = addslashes($_POST["name"]);
        $email = addslashes($_POST["email"]);
        $website = addslashes($_POST["website"]);
        $perbg = htmlentities(addslashes($_POST["perbg"]), 3, "UTF-8");
        $mailshow = $_POST["mailshow"];
        $webshow = $_POST["webshow"];
        $competence = $_POST["competance"];
        $SignatureShow = $_POST["SignatureShow"];
        $Signature = htmlentities(addslashes($_POST["Signature"]), 3, "UTF-8");
        //儲存查詢語句
        $sql = array("name" => "", "email" => "", "web" => "", "competence" => "", "perbg" => "", "mailshow" => "", "webshow" => "", "Signature" => "", "SignatureShow" => "");
        //格式檢查
        if (preg_match("/^([0-9A-Za-z\x7f-\xff]+)$/", $name) && mb_strlen($name, "UTF-8") <= 10)
            $sql["name"] = "update admin set Yourname='$name' where username='$b'";
        else
            $errornum = 1;

        if (preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/", $email))
            $sql["email"] = "update admin set Email='$email' where username='$b'";
        else
            $errornum = 2;
        if (preg_match("/^((http|https):\/\/)?[\w-_.]+(\/[\w-_]+)*\/?$/", $website) || $website == null)
            $sql["web"] = "update admin set Website='$website' where username='$b'";
        else
            $errornum = 3;
        $sql["competence"] = "update admin set Competence='$competence' where username='$b'";
        $sql["perbg"] = "update admin set PersonBackground='$perbg' where username='$b'";
        $sql["mailshow"] = "update admin set EmailShow='$mailshow' where username='$b'";
        $sql["webshow"] = "update admin set URLshow='$webshow' where username='$b'";
        if (mb_strlen($Signature, "UTF-8") <= 80)
            $sql["Signature"] = "update admin set Signature='$Signature' where username='$b'";
        else
            $errornum = 4;
        $sql["SignatureShow"] = "update admin set SignatureShow='$SignatureShow' where username='$b'";
        //處理查詢
        if ($errornum > 0) {

        } else {
            foreach ($sql as $value) {
                if (mysqli_query($sqli, $value) != true)
                    $errornum = 1;
            }
        }
        if ($errornum > 0):
            switch ($errornum){
                case 1:
                    printmsg("error", "變更資料發生問題，暱稱長度過長或格式錯誤！");
                    break;
                case 2:
                    printmsg("error", "變更資料發生問題，EMAIL格是錯誤！");
                    break;
                case 3:
                    printmsg("error", "變更資料發生問題，個人網站格式錯誤！");
                    break;
                case 4:
                    printmsg("error", "變更資料發生問題，個人簽章長度過長或有非法字元！");
                    break;
                default:
                    printmsg("error", "變更資料發生問題，請檢察資料格式是否正確！");
            }
             ?>
            <meta http-equiv=REFRESH CONTENT=3;url=setting?action=information>
        <?
        else:
            printmsg("suc", "您的變更已經儲存"); ?>
            <meta http-equiv=REFRESH CONTENT=3;url=setting?action=information>
        <?php endif;
    }
}
?>