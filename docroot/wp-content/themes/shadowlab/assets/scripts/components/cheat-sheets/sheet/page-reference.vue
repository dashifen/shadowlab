<template>
  <p class="reference">
    <abbr title="page">p.</abbr>
    <span class="page">{{ page }}</span>,
    <abbr class="book" :title="book.title">{{ book.abbr }}</abbr>
  </p>
</template>

<script>
  export default {
    name: "page-reference",
    props: ["book","page"]
  };
</script>

<style scoped>
  .reference {
    margin-bottom: 0;
    text-align: right;
  }

  abbr {
    text-decoration: none;
  }
</style>