<?php
drupal_add_library('jquery_plugin', 'cycle');

if($user->uid) drupal_goto('dashboard');
?>
<div id="page-wrapper">

  <div id="header">
    <?php print render($page['header']); ?>

  	<div class="header-logo">
    <?php if ($logo): ?>
    <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
      <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
    </a>
    </div>
    <div style="clear:both;"></div>
    <?php endif; ?>

    <?php if ($main_menu || $secondary_menu): ?>
    <div id="navigation">
        <?php print theme('links__system_main_menu', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu' , 'class' => array('links' , 'clearfix' )))); ?>
    </div>
    <div style="clear:both;"></div>
    <?php endif; ?>

    <?php if ($breadcrumb): ?>
    <div id="breadcrumb"><?php print $breadcrumb; ?></div>
    <?php endif; ?>

  </div> <!-- #header -->

  <div id="page">

    <ul class="tabs fp-tabs fp-tabs1">
      <li>
        <a href="#first" class="tab1">
          <span>Welcome</span>Learn why this community might be right for you.
        </a>
      </li>
      <li>
        <a href="#second" class="tab2">
          <span>Discover</span>Get acquainted with what our community has to offer.
        </a>
      </li>
    </ul>
    <ul class="fp-tabs fp-tabs2">
      <li>
        <a href="/user/register" class="tab3">
          <span>Join us</span>Sign up and join our innovative community. Get started now!
        </a>
      </li>
    </ul>
    <div class="clearfix"></div>

    <div class="fp-presentation clearfix"><!-- #Front page presentation start -->

      <div class="panes"><!-- Front page Sections start -->

        <div class="sections clearfix"><!-- FIRST TAB PRESENTATION START-->
          <div class="fp-static fp-scrollable fp-scrollable1" style="display:block;">

            <!-- root element for the items -->
            <div class="items">
              <!-- 1 -->
              <div class="slide item1"><?php include_once(sqtools_default_theme_path()."/slider_templates/slider1-item1.tpl.php"); ?></div>
            </div> <!-- class=items -->

          </div> <!-- class=fp-scrollable1 -->

        </div><!-- FIRST TAB PRESENTATION END-->

        <div class="section2 sections clearfix"><!-- SECOND TAB PRESENTATION START-->
          <div class="fp-nav-slides">
            <!-- "previous page" action -->
            <a class="prev browse left"></a>
            <!-- wrapper for navigator elements -->
            <span class="navi2 navi clearfix"></span>
            <!-- "next page" action -->
            <a class="next browse right"></a>
          </div>
          <!-- root element for scrollable -->
          <div class="fp-scrollable fp-scrollable2">

            <!-- root element for the items -->
            <div class="items">
              <!-- 1 -->
              <div class="slide item1"><?php include_once(sqtools_default_theme_path()."/slider_templates/slider2-item1.tpl.php"); ?></div>
              <!-- 2 -->
              <div class="slide item2"><?php include_once(sqtools_default_theme_path()."/slider_templates/slider2-item2.tpl.php"); ?></div>
            </div> <!-- class=items -->

          </div> <!-- class=fp-scrollable2 -->

        </div><!-- SECOND TAB PRESENTATION END-->

      </div><!-- Panes -->

    </div><!-- Front page presentation End -->

    <div style="clear:both;"></div>

  </div> <!-- #page -->

  <div style="clear:both;"></div>
</div> <!-- #page-wrapper -->
