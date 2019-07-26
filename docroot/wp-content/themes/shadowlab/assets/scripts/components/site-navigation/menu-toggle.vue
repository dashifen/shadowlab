<template>
  <button id="menu-toggle" :title="iconLabel" @click="onClick" ref="button" aria-labelledby="menu-toggle-label">
    <span id="menu-toggle-label" aria-live="polite" class="visually-hidden">>{{ iconLabel }}</span>
    <font-awesome-icon fixed-width :icon="['fas', icon]"></font-awesome-icon>
  </button>
</template>

<script>
  import { library } from "@fortawesome/fontawesome-svg-core";
  import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
  import { faTimes, faBars } from "@fortawesome/pro-solid-svg-icons";

  library.add(faTimes, faBars);

  export default {
    name: "menu-toggle",

    components: {FontAwesomeIcon},

    computed: {
      icon() {
        return this.$store.getters.isMenuOpen ? "times" : "bars";
      },

      iconLabel() {
        return this.$store.getters.isMenuOpen ? "Close Menu" : "Open Menu";
      }
    },

    methods: {
      onClick() {
        this.$store.commit("switchMenuState");
      }
    }
  };
</script>

<style scoped>
  #menu-toggle {
    background: transparent;
    border-style: none;
    cursor: pointer;
    font-size: 25px;
  }
</style>