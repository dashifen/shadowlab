<template>
  <nav id="main-menu" :class="{ 'menu-open': isMenuOpen }" aria-labelledby="main-menu-label">
    <span class="visually-hidden" id="main-menu-label">Main Menu</span>


  </nav>
</template>

<script>
  export default {
    name: "main-menu",

    computed: {
      isMenuOpen() {
        return this.$store.getters.isMenuOpen;
      }
    }
  };
</script>

<style scoped>

</style>