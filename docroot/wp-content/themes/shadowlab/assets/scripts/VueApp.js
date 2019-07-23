import Vue from "vue";
import MenuToggle from "./components/menu-toggle.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {library} from "@fortawesome/fontawesome-svg-core";
import {faWordpress} from "@fortawesome/free-brands-svg-icons";

export default {
  initialize() {
    library.add(faWordpress);

    new Vue({
      el: "#vue-root",

      components: {
        FontAwesomeIcon,
        MenuToggle
      }
    });
  }
}
