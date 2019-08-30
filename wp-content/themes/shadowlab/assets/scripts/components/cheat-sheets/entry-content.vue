<template>
  <td :colspan="colspan">
    <div class="content" v-html="content"></div>
    <page-reference :page="page" :book="book"></page-reference>
  </td>
</template>

<script>
  import PageReference from "./page-reference.vue";

  export default {
    name: "entry-content",
    props: ["colspan","content","book","page"],
    components: {PageReference}
  };
</script>