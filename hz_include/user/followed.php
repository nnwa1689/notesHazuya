<?php
if ($_SESSION["username"] != null) {
    $b = $_SESSION["username"];
    $result = mysqli_query($sqli,"SELECT * FROM followRelationship WHERE follow_UserName='$b'");
    if ($_POST["doing"] == null) :
        if (mysqli_num_rows($result) > 0):
            ?>
            <p style="text-align: right"><label style="text-align: right; font-weight: normal;"
                                                name="doing">批次操作：</label>
                <select class="custom-select" name="doing">
                    <option selected="selected" value="Delete">取消追蹤</option>
                </select>
                ｜<input class="btn btn-primary" name="editsub" type="submit" value="批次編輯"/>
            </p>
            <table width="100%" cellpadding="2" cellspacing="5">
                <tr style="background: #f2f2f2;">
                    <th width="5%" scope="row">
                        <div class="custom-control custom-checkbox">
                            <input id="all" name="all" class="custom-control-input" type="checkbox"
                                   onClick="check_all(this,'num[]')"
                                   value="">
                            <label class="custom-control-label" for="all"></label>
                        </div>
                    </th>
                    <td width="95%">追蹤</td>
                </tr>
                <?php
                while ($FPost = mysqli_fetch_array($result)) :
                    //取得文章資料
                    $followed_UserName = $FPost["followed_UserName"];
                    $followeddata = mysqli_fetch_array(mysqli_query($sqli,"SELECT * FROM admin WHERE username = '$followed_UserName'")); ?>
                    <tr style="background: #f2f2f2;">
                        <th scope="row" width="5%">
                            <div class="custom-control custom-checkbox">
                                <input id="num[<? echo $FPost["follow_ID"] ?>]" name="num[]"
                                       class="custom-control-input"
                                       type="checkbox" value="<? echo $FPost["follow_ID"] ?>">
                                <label class="custom-control-label" for="num[<? echo $FPost["follow_ID"] ?>]"></label>
                            </div>
                        </th>
                        <td width="95%">
                            <a href="person?id=<?php echo $followed_UserName ?>"><img class="img-circle"
                                                                                          src="<?php echo $followeddata["Avatar"] ?>"
                                                                                          width="64" height="64"/></a>
                            <a href="<?php echo "person?id=" . $followed_UserName ?>"
                               target="_blank"><?php echo $followeddata["Yourname"] ?> </a>
                        </td>

                    </tr>
                <?php endwhile; ?>
            </table>
        <? else: ?>
            <p style="margin-top: 15px; text-align: center; font-size: 80px; color: #eeeeee; line-height: 100px"><i
                        class="fas fa-rss-square"></i></p>
            <p style="font-size: 48px; color: #eeeeee; text-align: center;">您尚未追蹤任何人</p>
        <?php endif; ?>
    <?php else: ?>
        <?php
        if ($_POST["doing"] == "Delete") :
            $PP = $_POST["num"];
            $errornum = 0;
            if (count($PP) > 0) {
                foreach ($PP as $value) {
                    $sql = "DELETE FROM followRelationship WHERE follow_ID='$value'";
                    if (!mysqli_query($sqli,$sql))
                        $errornum = 1;
                }
            }
            if ($errornum != 1) :
                printmsg("suc", "您的變更已經儲存"); ?>
                <meta http-equiv=REFRESH CONTENT=3;url=setting?action=followed>
            <?php
            else :
                printmsg("error", "變更失敗，請重新嘗試"); ?>
                <meta http-equiv=REFRESH CONTENT=3;url=setting?action=followed>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
<?php } ?>
