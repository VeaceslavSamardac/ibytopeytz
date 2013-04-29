<div class="<?php echo $parent_slug;?> <?php echo $parent_slug;?>-right-box ibyforum-right-box suggestion-box">

  <a name="suggestion_box"></a>
  <h3>Is this thread suited for Tips & Tricks?</h3>

  <div class="<?php echo $parent_slug;?>-divider divider"></div>

  <div class="suggestion-text">
    Lorem ipsum
    <div style="clear:both;"></div>

<?php
$nid = arg(1);
?>
    <div class="rounded-button send-button"><a href="/messages/new/1/<?php echo urlencode("Suggestion for Tips & Tricks. ".urlencode('www.innovationbyyou.com/node/')."{$nid}");?>?destination=node/<?php echo $nid;?>">Send</a></div>
&nbsp;<br />

    <div style="clear:both;"></div>

  </div>

  <div style="clear:both;"></div>

  <div class="<?php echo $parent_slug;?>-divider divider"></div>

<!--  <a href="/node/<?php echo $nid;?>#tags_box" style="font-weight:bold;">Reset choices</a>-->

  <div style="clear:both;"></div>
</div>
