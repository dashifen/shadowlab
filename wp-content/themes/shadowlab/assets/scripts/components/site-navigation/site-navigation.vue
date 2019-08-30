<template>
  <div id="site-navigation" :class="{ 'menu-open': isMenuOpen }">
    <menu-toggle></menu-toggle>
    <main-menu :menu-json="menuJson"></main-menu>
  </div>
</template>

<script>
  import MainMenu from "./main-menu.vue";
  import MenuToggle from "./menu-toggle.vue";

  export default {
    props: ["menuJson"],
    name: "site-navigation",
    components: {MainMenu, MenuToggle},
    computed: {
      isMenuOpen() {
        return this.$store.getters.isMenuOpen
      }
    }
  };
</script>

<style scoped>
  #site-navigation {
    align-items: flex-start;
    display: flex;
    position: absolute;
    right: -250px;
    top: 30px;
    transition: right 300ms ease-in-out;
  }

  #site-navigation.menu-open {
    right: 0;
  }
</style>