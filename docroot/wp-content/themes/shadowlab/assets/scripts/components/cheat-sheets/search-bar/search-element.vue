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
        this.$store.commit("updateQuery", {
          type: this.element.type,
          name: this.element.name,
          value: event.target.value,
        });
      }
    }
  };
</script>

<style scoped>

</style>