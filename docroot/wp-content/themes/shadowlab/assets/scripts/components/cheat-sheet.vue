<template>
  <table>
    <thead>
      <tr>
        <th scope="col" v-text="type"></th>
        <th scope="col" v-for="header in headers" v-text="header"></th>
      <tr>
    </thead>
    <tbody v-for="entry in entries">
</template>

<script>
  export default {
    name: "cheat-sheet.vue",
    props: ["entriesJson", "type"],

    data: function () {
      return {
        "entries": JSON.parse(this.entriesJson),
      }
    },

    computed: {
      headers() {
        return this.entries.fields.keys();
      }
    }
  };
</script>