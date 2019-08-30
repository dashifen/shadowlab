<template>
  <li class="filter">
    <label>
      <strong>Search within<span> {{ element.plural }}</span>:</strong>
      <select :name="element.name" @change="onChange">
        <option value="">All {{ element.plural }}</option>
        <option v-for="(option, value) in element.options" :value="value" v-text="option"></option>
      </select>
    </label>
  </li>
</template>

<script>
  export default {
    name: "filter-element",
    props: ["element"],

    methods: {
      onChange(event) {

      }
    }
  };
</script>

<style scoped>

</style>