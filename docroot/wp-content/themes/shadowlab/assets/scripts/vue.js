import Vue from "vue";
import Vuex from "vuex";

// rather than use up the time to download the full CSS for font awesome,
// we're using the Vue component and their library to load the specific icons
// we need and only those.  this does inflate our JS a bit, but our it keeps
// us from downloading scads of CSS so we're probably coming out ahead this
// way.

import {library} from "@fortawesome/fontawesome-svg-core";
import {faWordpress} from "@fortawesome/free-brands-svg-icons";
import {faCopyright} from "@fortawesome/pro-regular-svg-icons/faCopyright";

// now we import the Vue components, including font awesome's, that we use
// in our application.  this are added as components to the Vue instance we
// construct at the bottom of this file.

import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import SiteNavigation from "./components/site-navigation/site-navigation.vue";
import CheatSheet from "./components/cheat-sheets/cheat-sheet.vue";

Vue.use(Vuex);
Vue.config.productionTip = false;

export default {
  initialize () {
    library.add(faWordpress, faCopyright);

    // the primary purpose of this initialize() script is to construct the Vuex
    // store object that we can use to track the state of our Vue components
    // and how they interact.  for example, the current state of the menu,
    // i.e. opened or closed, is stored within.

    const store = new Vuex.Store({
      state: {
        "menuOpen": false,        // the menu's current state
        "query": [],              // a search bar query
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
