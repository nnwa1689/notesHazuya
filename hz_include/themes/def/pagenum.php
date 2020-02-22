    <?php $result = mysqli_query($sqli,$sql);
    $List = mysqli_num_rows($result);
    //每頁數量請在該頁面設定
    $pages = ceil($List / $per);
    if (!isset($_GET["page"])) :
        $page = 1;
    else :
        if(preg_match("/^([0-9]+)$/",$_GET["page"]))
            $page = intval($_GET["page"]);
        else
            $page=1;
    endif;
    $start = ($page - 1) * $per;
    $result = mysqli_query($sqli,$sql . ' LIMIT ' . $start . ',' . $per) or die("ERROR");//分業關鍵 ?>
