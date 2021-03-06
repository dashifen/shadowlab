import VueApp from "./vue.js";

document.addEventListener("DOMContentLoaded", () => {
  Dashifen.initialize();
  VueApp.initialize();
});

const Dashifen  = {
  /**
   * initialize
   *
   * Initializes the Dashifen object.  At the moment, it's a pretty straight-
   * forward JS availability check that we can use within the DOM, but if we
   * need to do more in the future, this is where the magic happens.
   *
   * @return void
   */
  initialize() {
    const htmlClasses = document.documentElement.classList;
    htmlClasses.remove("no-js");
    htmlClasses.add("js");
  }
};