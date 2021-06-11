<div class="content">
    <a href="./title.php?detailID=<?php echo $line["ChannelID"] ?>">
        <div class="pic">
        <img src="./image/<?php echo $i ?>.jpg" alt="" height="218" width="230">
        </div>
    </a>

    <div class="underpic">

        <label class="fav" id="fav_btn">
            <input type="checkbox">
            <span class="material-icons heart">favorite</span>
            <div class="riple"></div>
        </label>
        <span class="favnum" id='favNum_<?php echo $line["ChannelID"] ?>'
            data-channel="<?php echo $line['ChannelID'] ?>" data-fav="<?php echo $line['Favorites'] ?>">
        </span>

        <span class="material-icons comment">insert_comment</span>
        <span class="commentnum">120</span>

        <span class="share">SHARE</span>
    </div>
</div>