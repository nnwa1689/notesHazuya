<?php
/****************************
 * 喜歡及收藏文章按鈕(?)含UI
 * 附加社群分享
 *
 */

//顯示按鈕
//先判斷是否按過
$likedata = mysqli_fetch_array(mysqli_query($sqli,"SELECT * FROM `likepost` WHERE postid='$Nowid' AND username='$predata[0]'"));
$favdata = mysqli_fetch_array(mysqli_query($sqli,"SELECT * FROM `favpost` WHERE postid='$Nowid' AND username='$predata[0]'"));
//喜歡按鈕
if ($_SESSION["username"] == null) { ?>
    <a onclick="javascript:window.alert('請先登入');">
        <button type="button" class="btn btn-primary btn-circle btn-lg like disabled"><i class="fas fa-heart"></i>
        </button>
    </a>
    <?php
} else if ($likedata["num"] != null) {
    ?>
    <a onclick="javascript:window.alert('您已經按過了');">
        <button type="button" class="btn btn-primary btn-circle btn-lg like disabled"><i class="fas fa-heart"></i>
        </button>
    </a>
    <?php
} else { ?>
    <a href="?id=<?php echo($Nowid) ?>&like=<?php echo $Nowid ?>">
        <button type="button" class="btn btn-primary btn-circle btn-lg like"><i class="fas fa-heart"></i></button>
    </a>
    <?php
} ?>
    &nbsp;
<?php
//收藏按鈕
if ($_SESSION["username"] == null) { ?>
    <a onclick="javascript:window.alert('請先登入');">
        <button type="button" class="btn btn-primary btn-circle btn-lg fav disabled"><i class="fas fa-star"></i>
        </button>
    </a>
    <?php
} else if ($favdata["num"] != null) {
    ?>
    <a onclick="javascript:window.alert('您已經按過了');">
        <button type="button" class="btn btn-primary btn-circle btn-lg fav disabled"><i class="fas fa-star"></i>
        </button>
    </a>
    <?php
} else { ?>
    <a href="?id=<?php echo($Nowid) ?>&fav=<?php echo $Nowid ?>">
        <button type="button" class="btn btn-primary btn-circle btn-lg fav"><i class="fas fa-star"></i></button>
    </a>
    <?php
} ?>

<?php
//處理按鈕
if ($_GET["like"] != null) {
    $like = $_GET["like"];
    if (preg_match("/^([0-9]+)$/", $like)) {
        if ($_SESSION["username"] == null) {
            exit("程序錯誤");
        } else if ($likedata["num"] != null) {
            exit("您已經按過了");
        } else {
            $resultlike = mysqli_query($sqli,"INSERT INTO `likepost` (`postid`, `username`) VALUES ('$like', '$b')");
            if ($resultlike) {
                header('Location: blog?id=' . $_GET["id"] . '#like');
                exit;
            }
        }

    } else {

        exit("參數錯誤");
    }

} else if ($_GET["fav"] != null) {
    $fav = $_GET["fav"];
    if (preg_match("/^([0-9]+)$/", $fav)) {
        if ($_SESSION["username"] == null) {
            exit("程序錯誤");
        } else if ($favdata["num"] != null) {
            exit("您已經按過了");
        } else {
            $resultlike = mysqli_query($sqli,"INSERT INTO `favpost` (`postid`, `username`) VALUES ('$fav', '$b')");
            if ($resultlike) {
                header('Location: blog?id=' . $_GET["id"] . '#like');
                exit;
            }
        }

    } else {

        exit("參數錯誤");
    }

}
//社群按鈕
?>
&nbsp;
<a href="https://www.facebook.com/sharer/sharer.php?kid_directed_site=0&u=<?php echo $mate[13] . "/" ?>blog%3Fid%3D<?php echo($_GET["id"]) ?>&display=popup&ref=plugin&src=share_button"
   onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
    <button type="button" class="btn btn-primary btn-circle btn-lg fb"><i
                class="fab fa-facebook-square"></i>
    </button>
</a>
&nbsp;
<a href="https://plus.google.com/share?app=110&url=<?php echo $mate[13] . "/" ?>%2Fblog%3Fid%3D<?php echo($_GET["id"]) ?>"
   onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
    <button type="button" class="btn btn-primary btn-circle btn-lg gp"><i class="fab fa-google-plus"></i>
    </button>
</a>
&nbsp;
<a href="https://twitter.com/intent/tweet?original_referer=<?php echo $mate[13] . "/" ?>blog%3Fid%3D<?php echo($_GET["id"]) ?>&text=<?php echo $thispost->posttitle ?>&tw_p=tweetbutton&url=<?php echo $mate[13] . "/" ?>blog%3Fid%3D<?php echo($_GET["id"]) ?>"
   onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
    <button type="button" class="btn btn-primary btn-circle btn-lg tt"><i class="fab fa-twitter-square"></i>
    </button>
</a>
&nbsp;
<a href="https://www.plurk.com/?qualifier=shares&status=<?php echo $thispost->posttitle ?><?php echo $mate[13] . "/" ?>blog%3Fid%3D<?php echo($_GET["id"]) ?>"
   onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
    <button type="button" class="btn btn-primary btn-circle btn-lg plurk"><i class="fab fa-product-hunt"></i>
    </button>
</a>
&nbsp;
<a href="https://www.tumblr.com/widgets/share/tool/preview?shareSource=legacy&canonicalUrl=&url=<?php echo $mate[13] . "/" ?>blog%3Fid%3D<?php echo($_GET["id"]) ?>&posttype=link&title=<?php echo $thispost->posttitle ?>&caption=&content=<?php echo $mate[13] . "/" ?>blog%3Fid%3D<?php echo($_GET["id"]) ?>"
   onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
    <button type="button" class="btn btn-primary btn-circle btn-lg tumblr"><i class="fab fa-tumblr"></i>
    </button>
</a>
&nbsp;
<a href="mailto:?subject=<?=$thispost->posttitle ?>&body=<?=$thispost->posttitle ?><?=$mate[13] . "/" ?>blog%3Fid%3D<?php echo($_GET["id"]) ?>">
    <button type="button" class="btn btn-primary btn-circle btn-lg mail"><i class="fas fa-at"></i></button>
</a>


