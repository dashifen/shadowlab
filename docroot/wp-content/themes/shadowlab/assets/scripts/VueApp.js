import Vue from "vue";
import Vuex from "vuex";

import {library} from "@fortawesome/fontawesome-svg-core";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faWordpress} from "@fortawesome/free-brands-svg-icons";
import {faCopyright} from "@fortawesome/pro-regular-svg-icons/faCopyright";

import SiteNavigation from "./components/site-navigation/site-navigation.vue";
import CheatSheet from "./components/cheat-sheets/cheat-sheet.vue";

Vue.use(Vuex);
Vue.config.productionTip = false;

export default {
  initialize () {
    library.add(faWordpress, faCopyright);

    const store = new Vuex.Store({
      state: {
        "menuOpen": false,
        "query": [],
      },

      getters: {
        isMenuOpen (state) {
          return state.menuOpen;
        },

        getQuery (state) {
          return state.query;
        }
      },

      mutations: {
        switchMenuState (state) {

          // since the menu's state is a toggle (closed to open or vice-versa),
          // no payload is sent here.  instead, we just toggle the menuOpen
          // flag of our state and that does the work we need to do.

          state.menuOpen = !state.menuOpen;
        },

        updateQuery (state, newQuestion) {

          // when a new question is sent here, we need to remove an old
          // question of the same name.  for example, if we were filtering
          // based on the book in which an entry is found, asking about
          // entries from a different book should replace the former.  so,
          // we filter out any old questions with matching names and then
          // push the new question onto our query.

          state.query = state.query.filter((oldQuestion) => {
            return oldQuestion.name !== newQuestion.name;
          });

          state.query.push(newQuestion);
        },

        resetQuery (state) {
          state.query = [];
        }
      }
    });

    new Vue({
      el: "#vue-root",
      components: {FontAwesomeIcon, SiteNavigation, CheatSheet},
      store
    });
  }
};
