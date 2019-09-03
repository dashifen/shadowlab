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
      onChange (event) {
        this.$store.commit("updateQuery", {
          type: this.element.type,
          name: this.element.name,
          value: event.target.options[event.target.selectedIndex].value,
        });
      }
    }
  };
</script>

<style scoped>

</style>