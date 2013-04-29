<div class="display-data-grid-list">
	<div class="display-data-grid-stats">
        <div class="row1">
			<div class="comments">
   <span><?php echo intval($topic->comment_count); ?></span> comments

				<div style="clear:both;"></div>
			</div>
			<div class="views">
				<span><?php echo intval(sq_flags_api_get_count(array('flag_type' => 'view' , 'entity_type' => 'node', 'entity_id' => $topic->nid))); ?></span> views
				<div style="clear:both;"></div>
			</div>
			<div style="clear:both;"></div>
		</div>

        <div style="clear:both;"></div>

		<div class="row2">
			<div class="followers">
				<span><?php echo intval(sq_flags_api_get_count(array('flag_type' => 'follow' , 'entity_type' => 'node', 'entity_id' => $topic->nid))); ?></span> followers
				<div style="clear:both;"></div>
			</div>
			<div class="likes">
        <span><?php echo intval(sq_flags_api_get_count(array('flag_type' => 'like' , 'entity_type' => 'node', 'entity_id' => $topic->nid))); ?></span> likes
				<div style="clear:both;"></div>
			</div>
			<div style="clear:both;"></div>
        </div>

        <div style="clear:both;">&nbsp;</div>

	</div>
</div>
