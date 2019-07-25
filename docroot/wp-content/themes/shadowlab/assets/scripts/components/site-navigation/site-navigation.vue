<template>
  <div id="site-navigation">
    <menu-toggle></menu-toggle>
    <main-menu></main-menu>
  </div>
</template>

<script>
  import MainMenu from "./main-menu.vue";
  import MenuToggle from "./menu-toggle.vue";

  export default {
    name: "site-navigation",
    components: {MainMenu, MenuToggle},
  };
</script>

<style scoped>

</style>