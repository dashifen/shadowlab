<template>
  <div class="cheat-sheet">
    <search-bar :searchbar="searchbar"></search-bar>
    <cheat-sheet-entries :headers="headers" :entries="entries" :type="type"></cheat-sheet-entries>
  </div>
</template>

<script>
  import SearchBar from "./search-bar/search-bar.vue";
  import CheatSheetEntries from "./sheet/cheat-sheet-entries.vue";

  export default {
    name: "cheat-sheet",
    components: {SearchBar, CheatSheetEntries},
    props: ["type", "headersJson", "entriesJson", "searchbarJson"],

    data: function () {
      return {
        "entries": JSON.parse(this.entriesJson),
        "headers": JSON.parse(this.headersJson),
        "searchbar": JSON.parse(this.searchbarJson),
      };
    }
  };
</script>