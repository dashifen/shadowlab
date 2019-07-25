<!doctype html>
<!--suppress HtmlRequiredLangAttribute -->
<html class="no-js" <?php language_attributes() ?>>
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Shadowlab 6th Edition</title>

  <?php wp_head() ?>
</head>
<body <?php body_class() ?>>
<div id="vue-root">
  <a href="#content" class="jump-link visually-hidden">
    <span>Skip to<br>Content</span>
  </a>

  <header role="banner" id="banner" aria-labelledby="site-title">
    <div class="container">
      <?php if (is_home()) { ?>
        <h1 id="site-title">Shadowlab 6<sup>th</sup> Edition</h1>
      <?php } else { ?>
        <p id="site-title">Shadowlab 6<sup>th</sup> Edition</p>
      <?php } ?>

      <site-navigation></site-navigation>
    </div>
  </header>

  <main id="content" aria-labelledby="entry-title">
    <div class="container">


    </div>
  </main>

  <footer role="contentinfo" id="footer">
    <div class="container">
      <p><a href="http://www.topps.com/">The Topps Company, Inc.</a> has sole ownership of the names, logo, artwork, marks, photographs, sounds, audio, video and/or any proprietary material used in connection with the game
        <a href="https://www.shadowrunsixthworld.com/">Shadowrun</a>. The Topps Company, Inc. has granted permission to me to use such names, logos, artwork, marks and/or any proprietary materials for promotional and informational purposes
        within the Shadowlab but does not endorse, and is not affiliated with the Shadowlab or with Dash in any official capacity whatsoever.</p>
      <p>&copy; <?= date("Y") ?> David Dashifen Kees unless held by others.</p>
      <p>Proudly powered by <a href="https://wordpress.org">WordPress</a></p>
    </div>
  </footer>
</div>

<?php wp_footer() ?>
</body>
</html>
