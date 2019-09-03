<template>
  <li class="reset">
    <button type="reset" v-text="element.label" @click="onClick"></button>
  </li>
</template>

<script>
  export default {
    name: "reset-element",
    props: ["element"],

    methods: {
      onClick() {
        this.$store.commit("resetQuery");
      }
    }
  };
</script>

<style scoped>

</style>