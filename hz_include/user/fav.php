<?php
if ($_SESSION["username"] != null) {
    $b = $_SESSION["username"];
    $result = mysqli_query($sqli,"SELECT * FROM favpost WHERE username='$b'");
    if ($_POST["doing"] == null) :
        if (mysqli_num_rows($result) > 0):
            ?>
            <p style="text-align: right"><label style="text-align: right; font-weight: normal;"
                                                name="doing">批次操作：</label>
                <select class="custom-select" name="doing">
                    <option selected="selected">Delete</option>
                </select>
                ｜<input class="btn btn-primary" name="editsub" type="submit" value="批次編輯"/>
            </p>
            <table width="100%" cellpadding="2" cellspacing="5">
                <tr style="background: #f2f2f2;">
                    <th width="5%" scope="row">
                        <div class="custom-control custom-checkbox">
                            <input id="all" name="all" class="custom-control-input" type="checkbox"
                                   onClick="check_all(this,'num[]')"
                                   value=""/>
                            <label class="custom-control-label" for="all"></label>
                        </div>
                    </th>
                    <td width="45%">文章標題</td>
                    <td width="20%">發表日期</td>
                    <td width="15%">作者</td>
                    <td width="15%">分類</td>
                </tr>
                <?php
                while ($FPost = mysqli_fetch_assoc($result)) :
                    //取得文章資料
                    $postid = $FPost["postid"];
                    $postdata = mysqli_fetch_array(mysqli_query($sqli,"SELECT * FROM Blog WHERE PostId = '$postid'"));
                    //取得分類名稱
                    $classID = $postdata["ClassId"];
                    $ClassNameArray = mysqli_fetch_array(mysqli_query($sqli,"SELECT * FROM `BClasses` WHERE ClassId = '$classID'"));
                    $ClassName = $ClassNameArray["ClassName"]; ?>
                    <tr style="background: #f2f2f2;">
                        <th scope="row" width="5%">
                            <div class="custom-control custom-checkbox">
                                <input id="num[<? echo $FPost["num"] ?>]" name="num[]" class="custom-control-input"
                                       type="checkbox" value="<? echo $FPost["num"] ?>">
                                <label class="custom-control-label" for="num[<? echo $FPost["num"] ?>]"></label>
                            </div>
                        <td width="45%">
                            <a href="<?php print "blog?id=" . $postid ?>"
                               target="_blank"><?php print $postdata["PostTittle"] ?> </a>
                        </td>
                        <td width="20%"><?php print $postdata["PostDate"] ?></td>
                        <td width="15%"><?php print $postdata["User"] ?></td>
                        <td width="15%"><?php print $ClassName ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <? else: ?>
            <p style="margin-top: 15px; text-align: center; font-size: 80px; color: #eeeeee; line-height: 100px"><i
                        class="fas fa-star"></i></p>
            <p style="font-size: 48px; color: #eeeeee; text-align: center;">沒有任何收藏</p>
        <?php endif; ?>
    <?php else: ?>
        <?php
        if ($_POST["doing"] == "Delete") :
            $PP = $_POST["num"];
            $errornum = 0;
            if (count($PP) > 0) {
                foreach ($PP as $value) {
                    $sql = "DELETE FROM favpost WHERE num='$value'";
                    if (!mysqli_query($sqli,$sql))
                        $errornum = 1;
                }
            }
            if ($errornum != 1) :
                printmsg("suc", "您的變更已經儲存"); ?>
                <meta http-equiv=REFRESH CONTENT=3;url=setting?action=fav>
            <?php
            else :
                printmsg("error", "變更失敗，請重新嘗試"); ?>
                <meta http-equiv=REFRESH CONTENT=3;url=setting?action=fav>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
<?php } ?>
