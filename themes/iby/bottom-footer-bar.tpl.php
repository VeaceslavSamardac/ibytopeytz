<div class="footerEnd<?php echo(isset($variables['narrow']) ? ' narrow-footer' : '');?>">
	<div class="footerEndWrapper">
		<ul>
		<?php
		$fmc = 0;
		$footerClass = '';
		$footer_menu = menu_load_links('menu-footer');
		foreach($footer_menu as $menu_item) {
			$fmc++;
			if($fmc == 1) {
				$class = 'class="footer_menu_first"';
			}
			
		  echo '<li '.(($fmc == 1) ? $class : "").'><a href="/'.$menu_item['link_path'].'">'.$menu_item['link_title'].'</a></li>';
		}
		?>
		</ul>
		<div class="poweredBy">
			<a href="http://coloplast.com" target="_blank"><img src="/<?php echo sqtools_default_theme_path(); ?>/images/poweredby.png" width="163px" height="24px" /></a>
		</div>
	</div>
</div>
