<div class="card bg-light mb-3" style="width: 100%;">
    <div class="card-header"><i class="fas fa-rss-square"></i>我追蹤了誰?</div>
    <div class="card-body">
        <p class="card-text"><?php
            if (mysqli_num_rows($rsfollow) > 0):
            while ($follows = mysqli_fetch_array($rsfollow)) :
                $followid = $follows["followed_UserName"];
                $followeddata = mysqli_fetch_array(mysqli_query($sqli,"SELECT * FROM admin WHERE username = '$followid'")); ?>
                <a href="person?id=<? echo $followid ?>"><img class="img-circle"
                                                                  src="<? echo $followeddata["Avatar"] ?>"
                                                                  width="64" height="64"/></a>&nbsp;
            <? endwhile; ?></p>
        <?
        else:
            echo "還沒追蹤任何人";
            ?>
        <?
        endif;
        ?>
    </div>
</div>