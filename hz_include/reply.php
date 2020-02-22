<!--disqus-->
<?php if ($thispost->reply == "Yes"): ?>
    <div id="disqus_thread"></div>
    <script>
        /**
         *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
         *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/

        var disqus_config = function () {
            this.language = 'zh';
            this.page.url = '<?php echo $mate[13] . '/' . $pagefilename . "?" . $_SERVER['QUERY_STRING']?>';  // Replace PAGE_URL with your page's canonical URL variable
            this.page.identifier = '<?php echo $mate[13] . '/' . $pagefilename . "?" . $_SERVER['QUERY_STRING']?>'; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
        };
        (function () { // DON'T EDIT BELOW THIS LINE
            var d = document, s = d.createElement('script');
            s.src = 'https://noteshazuya.disqus.com/embed.js';
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by
            Disqus.</a></noscript>
<? endif; ?>

<!--OLD Reply-->
<script src="hz_include/tinymce/tinymce.min.js"></script>
<?php

/*回復UI與輸出回復(物件)*/

class Reply
{
    private $sqli, $sql, $Nowid;

    function __construct($sqli, $sql, $Nowid)
    {
        $this->sqli = $sqli;
        $this->sql = $sql;
        $this->Nowid = $Nowid;
    }

    function ReplyNum()
    {
        $numReply = mysqli_query($this->sqli, "SELECT * FROM `reply` WHERE PostId = '$this->Nowid' AND Competence = 'public' ORDER BY `reply`.`ReplyId` ASC");
        return $replynum = mysqli_num_rows($numReply);
    }

    function ReplyShow()
    {
        $showReplyResult = mysqli_query($this->sqli, $this->sql);
        $replycount = 0;
        while ($showReply = mysqli_fetch_array($showReplyResult)) {
            $sqlreplyper = "SELECT * FROM admin WHERE admin.username='$showReply[3]'";
            $resultreplyper = mysqli_query($this->sqli, $sqlreplyper);
            $replyper = mysqli_fetch_row($resultreplyper);
            $replycount++; ?>
            <a name="reply<?php echo $showReply[0] ?>"></a>
            <div id="ReplyPerson">
                <div id="ReplyPersonAva"><a href="person?id=<?php echo $replyper[0] ?>"><img
                                class="img-circle" src="<?php echo $replyper[7] ?>" width="32" height="32"/>
                </div>
                <div id="Replyperdata">
                    <div id="ReplyperdataLeft">
                        <a href="person?id=<?php echo $replyper[0] ?>"><?php echo $showReply[7] ?>
                        </a> &nbsp; 回覆於<?php echo $showReply[4]; ?>
                    </div>
                    <div id="ReplyperdataRight">
                        <a href="?id=<?php echo $this->Nowid ?>&Reply_parent=<?php echo $showReply[0] ?>#parentre"><i
                                    class="fas fa-reply"></i>回覆</a>&nbsp;
                        <?= ' #' . $replycount ?>
                    </div>
                </div>
            </div>
            <div id="ReplyContant">
                <?php echo $showReply[2] ?>
            </div>

            <?php
            //輸出第二層回覆
            $reparentsql = mysqli_query($this->sqli, "SELECT * FROM `reply` WHERE Reply_parent = '$showReply[0]' AND Competence = 'public' ORDER BY `reply`.`ReplyId` ASC");
            while ($reparent = mysqli_fetch_row($reparentsql)) {

                $parentreplyper = mysqli_fetch_row(mysqli_query($this->sqli, "SELECT * FROM admin WHERE admin.username='$reparent[3]'"));
                ?>        <a name="reply<?php echo $reparent[0] ?>"></a>
                <div id="ReplyParent">
                    <div id="#ReplyPersonParent">
                        <div id="ReplyPersonAva"><a href="person?id=<?php echo $parentreplyper[0] ?>"><img
                                        class="img-circle" src="<?php echo $parentreplyper[7] ?>" width="32"
                                        height="32"/></div>
                        <div id="Replyperdata">
                            <div id="ReplyperdataLeft">
                                <a href="person?id=<?php echo $parentreplyper[0] ?>"><?php echo $reparent[7] ?>
                                </a> &nbsp; 回覆於<?php echo $reparent[4]; ?>
                            </div>
                            <div id="ReplyperdataRight">
                                <a href="?id=<?php echo $this->Nowid ?>&Reply_parent=<?php echo $reparent[0] ?>#parentre"><i
                                            class="fas fa-reply"></i>回覆</a>
                            </div>
                        </div>
                    </div>
                    <div id="ReplyContant">
                        <?php echo $reparent[2] ?>

                    </div>
                    <?php //輸出第三層回覆
                    $reparentsql2 = mysqli_query($this->sqli, "SELECT * FROM `reply` WHERE Reply_parent = '$reparent[0]' AND Competence = 'public' ORDER BY `reply`.`ReplyId` ASC");
                    while ($reparent2 = mysqli_fetch_row($reparentsql2)) {

                        $parentreplyper = mysqli_fetch_row(mysqli_query($this->sqli, "SELECT * FROM admin WHERE admin.username='$reparent2[3]'"));
                        ?>            <a name="reply<?php echo $reparent2[0] ?>"></a>
                        <div id="ReplyParent2">
                            <div id="#ReplyPersonParent">
                                <div id="ReplyPersonAva"><a
                                            href="person?id=<?php echo $parentreplyper[0] ?>"><img
                                                class="img-circle" src="<?php echo $parentreplyper[7] ?>"
                                                width="32" height="32"/></div>
                                <div id="Replyperdata">
                                    <div id="ReplyperdataLeft">
                                        <a href="person?id=<?php echo $parentreplyper[0] ?>"><?php echo $reparent2[7] ?>
                                        </a> &nbsp; 回覆於<?php echo $reparent2[4]; ?>
                                    </div>
                                    <div id="ReplyperdataRight">&nbsp;</div>
                                </div>
                            </div>
                            <div id="ReplyContant">
                                <?php echo $reparent2[2] ?>
                            </div>
                        </div>
                    <?php }
                    ?>
                </div>

            <?php }
        }
    }
}

function reply($thispost, $userlaw, $predata, $Nowid, $mate)
{
    global $sqli;
    if ($thispost->reply == "Yes") { ?>
        <a name="parentre"></a>
        <?php
        if ($_SESSION["username"] == null) {
            if ($mate[12] == "on") {
                printmsg("information", '如要發表回覆，請先<a href="login"><i class="fas fa-sign-in-alt"></i>&nbsp;登入</a>或&nbsp; <a href="register"><i class="fas fa-rss"></i>&nbsp;註冊</a>');
            } else {
                printmsg("information", '如要發表回覆，請先<a href="login"><i class="fas fa-sign-in-alt"></i>&nbsp;登入</a>');
            }
        } else if ($_SESSION["username"] != null && $userlaw["Law_PostReply"] == 0) {
            printmsg("error", "抱歉，您的用戶權限無法發表回復");
        } else if ($_SESSION["username"] != null && $userlaw["Law_PostReply"] == 1) {
            if ($_GET["Reply_parent"] != null && preg_match("/^([0-9]+)$/", $_GET["Reply_parent"])) {
                $rp = $_GET["Reply_parent"];
                $sqlparent = mysqli_fetch_row(mysqli_query($sqli, "SELECT * FROM `reply` WHERE ReplyId = '$rp'"));
                if ($sqlparent) {
                    $Reply_parent = $_GET["Reply_parent"]; ?>
                    <p>回覆至&nbsp;<?php echo $sqlparent[7] ?>&nbsp;&nbsp;<a href="?id=<?php echo $Nowid ?>"
                                                                          class="btn btn-primary"
                                                                          style="height: 8px; line-height: 6px;"><i
                                    class="fas fa-times"></i>取消</a></p>
                <?php }
            } ?>
            <form action="" method="post" name="reply">
                <script>
                    tinymce.init({
                        language: 'zh_TW',
                        selector: 'textarea',
                        themes: "modern",
                        branding: false,
                        statusbar: false,
                        plugins: [
                            "advlist autolink lists link image charmap preview hr anchor codesample",
                            "visualblocks visualchars code",
                            "media nonbreaking save table directionality",
                            "template paste textpattern imagetools",
                            "autoresize"
                        ],
                        autoresize_on_init: false,
                        autoresize_bottom_margin: 0,
                        autoresize_min_height: 140,
                        autoresize_max_height: 500,
                        toolbar1: "undo redo | bold italic underline strikethrough subscript superscript | link unlink image media | pagebreak codesample",
                        menubar: false,
                        image_advtab: true,
                        relative_urls: false,
                        convert_urls: false,
                    });
                </script>
                <label for="cont"></label>
                <textarea name="cont" id="cont" value="<?php echo $_GET["parent"] ?>"></textarea>
                <p style="text-align: right">
                    <button type="submit" class="btn btn-primary" name="replyset"
                            style="font-size: 20px; width: 300px;"><i class="fas fa-reply"></i> &nbsp;發表回覆
                    </button>
                </p>
            </form>
            <?php
            date_default_timezone_set('Asia/Taipei');
            $replyarray = array(0 => addslashes($_POST["cont"]), 1 => $predata[0], 2 => $predata[4], 3 => date("Y-m-d H:i:s"), 4 => $_GET["id"]);
            if ($replyarray[0] != null) {
                $newReplySql = "INSERT INTO `reply` (`PostId`, `ReplyContant`, `ReplyUserID`, `ReplyDate`, `Competence`, `Reply_parent`,`ReplyUserName`) VALUES ('$replyarray[4]', '$replyarray[0]', '$replyarray[1]', '$replyarray[3]', 'public', '$Reply_parent','$replyarray[2]');";
                $newReplyResult = mysqli_query($sqli, $newReplySql);
                if ($newReplyResult) {
                    include_once("hz_include/func_notice.php");
                    if ($Reply_parent != null && preg_match("/^([0-9]+)$/", $_GET["Reply_parent"])) {
                        $chreply = mysqli_fetch_row(mysqli_query($sqli, "SELECT ReplyUserID FROM reply WHERE ReplyId='$Reply_parent'"));
                        replyReplied($predata[0], $predata[4], $chreply[0], $thispost->posttitle, $thispost->postid, $Reply_parent);
                        header('Location: blog?id=' . $_GET["id"] . '#reply' . $Reply_parent);
                        exit;
                    } else {
                        myPostReplied($predata[0], $replyarray[2], $thispost->userid, $thispost->posttitle, $thispost->postid);
                        header('Location: blog?id=' . $_GET["id"] . '#newReply');
                        exit;
                    }
                } else {
                    header('Location: blog?id=' . $_GET["id"] . '#newReply');
                    exit;
                }

            }
        }
    }
}

?>