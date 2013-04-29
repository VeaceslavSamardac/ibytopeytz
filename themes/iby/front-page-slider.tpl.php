
    <ul class="tabs fp-tabs fp-tabs1">
      <li>
        <a href="/#first" class="tab1">
          <span>Welcome</span>Learn why this community might be right for you.
        </a>
      </li>
      <li>
        <a href="/#second" class="tab2">
          <span>Discover</span>Get acquainted with what our community has to offer.
        </a>
      </li>
    </ul>
    <ul class="fp-tabs fp-tabs2">
      <li>
        <a href="/user/register" class="tab3 current">
          <span>Join us</span>Sign up and join our innovative community. Get started now!
        </a>
      </li>
    </ul>
    <div class="clearfix"></div>

    <div class="fp-presentation clearfix"><!-- #Front page presentation start -->
      <div class="panes"><!-- Front page Sections start -->
        <div class="section3 sections clearfix"><!-- FIRST TAB PRESENTATION START-->
          <!-- root element for scrollable -->
          <div class="fp-scrollable">
             <!-- root element for the items -->
             <div class="items">
                <!-- 1 -->
                <div class="slide item1">
                  <?php if ($title): ?>
                    <h1><?php print $title; ?></h1>
                  <?php endif;?>
                  <?php if ($messages):
                    $messages = str_replace('is already registered. <a', 'is already registered.<br /><a', $messages); ?>
                    <div id="messages"><div class="section clearfix">
                      <?php print $messages; ?>
                    </div></div>
                  <?php endif; ?>
                  <?php
//if((arg(0) == "user") && (arg(1) == "reset")) {
//  $tmpAccount = user_load(arg(2));
//  if(!$tmpAccount->login) {
//    $page['content']['system_main']['message']['#markup'] = "<p>Dear new member.</p><p>You have now used your one-time login link.<br />It is no longer necessary to use this link to log in.<p>Please create your password and click save.</p>";
//  }
//}
       echo drupal_render($page['content']);
                  ?>
                </div>
             </div>
          </div>
        </div><!-- FIRST TAB PRESENTATION END-->
      </div><!-- Front page presentation End -->
    </div><!-- #Front page presentation end -->
