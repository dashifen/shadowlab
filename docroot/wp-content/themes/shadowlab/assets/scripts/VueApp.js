import Vue from "vue";
import Vuex from "vuex";
import {library} from "@fortawesome/fontawesome-svg-core";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faWordpress} from "@fortawesome/free-brands-svg-icons";
import SiteNavigation from "./components/site-navigation/site-navigation.vue";

Vue.use(Vuex);
Vue.config.productionTip = false;

export default {
  initialize() {
    library.add(faWordpress);

    const store = new Vuex.Store({
      state: {
        "menuOpen": false,
      },

      getters: {
        isMenuOpen(state) {
          return state.menuOpen;
        }
      },

      mutations: {
        switchMenuState(state) {
          state.menuOpen = !state.menuOpen;
        }
      }
    });

    new Vue({
      el: "#vue-root",
      components: {FontAwesomeIcon, SiteNavigation},
      store
    });
  }
}
