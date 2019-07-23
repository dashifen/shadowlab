<!doctype html>
<!--suppress HtmlRequiredLangAttribute -->
<html class="no-js" <?php language_attributes() ?>>
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Shadowlab</title>

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
        <h1 id="site-title">Shadowlab</h1>
      <?php } else { ?>
        <p id="site-title">Shadowlab</p>
      <?php } ?>

      <!--<nav id="main-menu" aria-labelledby="main-menu-label">
        <h2 class="visually-hidden" id="main-menu-label">Main Menu</h2>

        <ul class="menu">
          <li class="item with-submenu">
            <a href="#">Character</a>
            <ul class="submenu">
              <li class="submenu-item item"><a href="#">Statuses</a></li>
              <li class="submenu-item item"><a href="#">Qualities</a></li>
            </ul>
          </li>
          <li class="item with-submenu">
            <a href="#">Combat</a>
            <ul class="submenu">
              <li class="submenu-item item"><a href="#">Martial Arts</a></li>
              <li class="submenu-item item"><a href="#">Something Else</a></li>
              <li class="submenu-item item"><a href="#">A third thing</a></li>
            </ul>
          </li>
          <li class="item with-submenu">
            <a href="#">Gear</a>
            <ul class="submenu">
              <li class="submenu-item item"><a href="#">Guns</a></li>
              <li class="submenu-item item"><a href="#">Electronics</a></li>
            </ul>
          </li>
          <li class="item with-submenu">
            <a href="#">Magic</a>
            <ul class="submenu">
              <li class="submenu-item item"><a href="#">Mentor Spirits</a></li>
              <li class="submenu-item item"><a href="#">Powers</a></li>
              <li class="submenu-item item"><a href="#">Spells</a></li>
              <li class="submenu-item item"><a href="#">Spirits</a></li>
              <li class="submenu-item item"><a href="#">Traditions</a></li>
            </ul>
          </li>
          <li class="item with-submenu">
            <a href="#">Matrix</a>
            <ul class="submenu">
              <li class="submenu-item item"><a href="#">Complex Forms</a></li>
              <li class="submenu-item item"><a href="#">Matrix Actions</a></li>
              <li class="submenu-item item"><a href="#">Sprites</a></li>
            </ul>
          </li>
        </ul>
      </nav>-->
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
