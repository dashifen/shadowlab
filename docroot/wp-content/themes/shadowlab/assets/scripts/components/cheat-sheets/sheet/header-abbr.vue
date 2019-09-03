<template>
  <abbr :title="header" v-text="abbr"></abbr>
</template>

<script>
  export default {
    name: "header-abbr",
    props: ["header"],
    computed: {
      abbr() {
        return this.header                    // start with the specified header
          .split("")                          // split it into individual letters
          .filter((ch) => /[A-Z]/.test(ch))   // then only keep the capital ones
          .join("");                          // and join it back into a string
      }
    }
  };
</script>

<style scoped>

</style>