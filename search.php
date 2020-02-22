<?php
$pagefilename = basename(__FILE__, ".php");//取得本頁檔名
include_once "hz_include/themes/def/header.php"; ?>
<body>
<div id="top">
    <div class="top_contant"><?php include_once("hz_include/themes/def/header_top.php") ?></div>
</div>
<?include_once ("hz_include/themes/def/breadtraiil.php") ?>
<div id="contant">
    <div id="PageTitle">搜尋文章<br></div>
    <div style="min-height: 400px" name="searchPageHeight">
        <form action="" method="get">

            <div style="text-align: center" id="searchOption">
                <input name="q" id="q" class="form-control" placeholder="搜尋" type="text"
                       value="<?php echo $_GET["q"] ?>">&nbsp;<button
                        type="submit" class="btn btn-primary"><i class="fas fa-search"></i>搜尋</button>
            </div>

            <div id="searchoption" style="text-align: center">
                <div class="custom-control custom-radio custom-control-inline">
                    <?if($_GET["conandtit"]==null || $_GET["conandtit"]=="searchall" || ($_GET["conandtit"]!="searchall" && $_GET["conandtit"]!="onlyt")):?>
                    <input type="radio" id="conandtit1" name="conandtit" value="searchall" checked="checked" class="custom-control-input">
                    <?else:?>
                    <input type="radio" id="conandtit1" name="conandtit" value="searchall" class="custom-control-input">
                    <?endif;?>
                    <label class="custom-control-label" for="conandtit1">文章標題與內容</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <?if($_GET["conandtit"]=="onlyt"):?>
                    <input type="radio" id="conandtit2" name="conandtit" value="onlyt" checked="checked" class="custom-control-input">
                    <?else:?>
                    <input type="radio" id="conandtit2" name="conandtit" value="onlyt" class="custom-control-input">
                    <?endif;?>
                    <label class="custom-control-label" for="conandtit2">僅文章標題</label>
                </div>
            </div>
        </form>
        <?php
        if ($_GET["q"] != null) {

            if (preg_match("/^([0-9A-Za-z\x7f-\xff]+)$/", $_GET["q"])) {
                $q = htmlentities($_GET["q"],3,"UTF-8");
                if ($_GET["conandtit"] == "searchall")
                    $sql = "SELECT * FROM Blog WHERE (PostTittle LIKE '%$q%' OR PostContant LIKE '%$q%') AND (Competence='public' OR Blog.Competence='protect') ORDER BY PostId DESC";
                elseif ($_GET["conandtit"] == "onlyt")
                    $sql = "SELECT * FROM Blog WHERE (PostTittle LIKE '%$q%') AND (Competence='public' OR Blog.Competence='protect') ORDER BY PostId DESC";
                else
                    $sql = "SELECT * FROM Blog WHERE (PostTittle LIKE '%$q%' OR PostContant LIKE '%$q%') AND (Competence='public' OR Blog.Competence='protect') ORDER BY PostId DESC";
                $per = $mate[7];
                $result = mysqli_query($sqli,$sql);
                $qnum = mysqli_num_rows($result);
            }
            if ($qnum > 0) { ?>
                <p><i class="fas fa-search"></i>搜尋"<?php echo $q ?>"共有<?php echo $qnum ?>筆結果：</p>
                <?php
                include_once("hz_include/class-postList.php");
                printpost($sql, $per,$pagefilename);
            } else { ?>
                <p style="margin-top: 15px; text-align: center; font-size: 80px; color: #eeeeee; line-height: 180px"><i class="fas fa-search"></i></p>
                <p style="font-size: 48px; color: #eeeeee; text-align: center;">找不到任何結果</p>
            <?}?>
            </ul>
            </nav>
        <?php }else{?>
            <p style="margin-top: 15px; text-align: center; font-size: 80px; color: #eeeeee; line-height: 180px"><i class="fas fa-search"></i></p>
            <p style="font-size: 48px; color: #eeeeee; text-align: center;">這裡顯示搜尋結果</p>
        <?} ?>
    </div>
</div>
<div id="copyr">
    <?php include_once "hz_include/themes/def/footer.php"; ?>
</div>
</body>
</html>
