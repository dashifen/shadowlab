<template>
  <li class="search">
    <label>
      <strong>Search<span> {{ element.plural }}</span>:</strong>
      <input type="text" :name="element.name" @change="onChange">
    </label>
  </li>
</template>

<script>
  export default {
    name: "search-element",
    props: ["element"],

    methods: {
      onChange(event) {

      }
    }
  };
</script>

<style scoped>

</style>