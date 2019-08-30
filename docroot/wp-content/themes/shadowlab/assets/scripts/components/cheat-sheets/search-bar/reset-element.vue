<template>
  <li class="reset">
    <button type="reset" v-text="element.label" @click="$emit('reset')"></button>
  </li>
</template>

<script>
  export default {
    name: "reset-element",
    props: ["element"]
  };
</script>

<style scoped>

</style>