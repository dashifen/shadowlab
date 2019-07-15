<?php

namespace Shadowlab\Theme\Templates;

use Dashifen\WPTemplates\Templates\AbstractTimberTemplate;
use Shadowlab\Theme\Theme;

class AbstractTemplate extends AbstractTimberTemplate {
  /**
   * @var Theme
   */
  protected $theme;

  /**
   * AbstractTemplate constructor.
   *
   * @param Theme $theme
   * @param bool  $getTimberContext
   */
  public function __construct (Theme $theme, bool $getTimberContext = false) {
    parent::__construct($getTimberContext);
    $this->theme = $theme;
  }

  /**
   * setContext
   *
   * Sets the context property filling it with data pertinent to the
   * template being displays.  Typically, all the work to do so exists
   * within this method, but data can be passed to it which should be
   * merged with the work performed herein.
   *
   * @param array $context
   *
   * @return void
   */
  public function setContext (array $context = []): void {
    $this->context = [
      "year"      => date("Y"),
      "siteUrl"   => home_url(),
      "template"  => str_replace("/", "-", strtolower(get_called_class())),
      "siteName"  => ($siteName = get_bloginfo("name")),
      "siteTitle" => $this->getSiteTitle($siteName),
      "menu"      => $this->getMainMenu(),
    ];
  }

  /**
   * getSiteTitle
   *
   * Returns the site logo and header element.
   *
   * @param string $siteName
   *
   * @return string
   */
  private function getSiteTitle (string $siteName): string {
    $logo = $this->getLogo();
    $header = $this->getHeader($siteName);
    return $logo . $header;
  }

  /**
   * getLogo
   *
   * Returns an <img> tag for our logo.
   *
   * @return string
   */
  private function getLogo (): string {
    $imagePath = $this->theme->getStylesheetUrl() . "/assets/images/shadowrun-logo-totem.png";
    return '<img src="' . $imagePath . '" alt="the shadowrun logo" width="150">';
  }

  /**
   * getHeader
   *
   * Returns the header element as either an <h1> or a <span> based on
   * whether or not we're on the homepage.
   *
   * @param string $siteName
   *
   * @return string
   */
  private function getHeader (string $siteName): string {
    $oTag = is_home() ? "<h1" : "<span";
    $cTag = str_replace("<", "</", $oTag);
    return sprintf('%s id="site-title">%s%s>', $oTag, $siteName, $cTag);
  }

  /**
   * @return string
   */
  private function getMenu(): string {

  }

}