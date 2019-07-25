<template>
  <button id="menu-toggle" :class="{ 'clicked': clicked }" :title="iconLabel" @click="onClick" ref="button" aria-labelledby="menu-toggle-label">
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
      clicked() {
        return this.$store.getters.isMenuOpen;
      },

      icon() {
        return this.clicked ? "times" : "bars";
      },

      iconLabel() {
        return this.clicked ? "Close Menu" : "Open Menu";
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