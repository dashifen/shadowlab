<template>
  <div id="site-navigation" :class="{ 'menu-open': isMenuOpen }">
    <menu-toggle></menu-toggle>
    <main-menu :menu="menu"></main-menu>
  </div>
</template>

<script>
  import MainMenu from "./main-menu.vue";
  import MenuToggle from "./menu-toggle.vue";

  export default {
    props: ["menu"],
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

</style>