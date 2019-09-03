<template>
  <form @submit.prevent="onSubmit" class="search-bar">
    <fieldset>
      <legend>
        <label>Use these fields to search within and/or filter the data below.</label>
      </legend>

      <ol>
        <li v-for="element in searchbar" :is="element.type + '-element'" :element="element"></li>
      </ol>

    </fieldset>
  </form>
</template>

<script>
  import FilterElement from "./filter-element.vue";
  import SearchElement from "./search-element.vue";
  import ResetElement from "./reset-element.vue";

  export default {
    name: "search-bar",
    props: ["searchbar"],
    components: {FilterElement, SearchElement, ResetElement},

    methods: {
      onSubmit () {
        return false;
      }
    }
  };
</script>