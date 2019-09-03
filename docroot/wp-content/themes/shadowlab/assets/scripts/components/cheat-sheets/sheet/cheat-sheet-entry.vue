<template>
  <tbody :class="entryClasses" @click.prevent="clicked = !clicked">
    <tr class="heading-row">
      <th scope="row" headers="type" v-text="entry.title"></th>
      <td v-for="(field, i) in entry.fields"
          :class="columnClasses[i]"
          v-text="field"
          class="field"
      ></td>
    </tr>
    <tr class="body-row">
      <td is="cheat-sheet-entry-content"
          :colspan="colspan"
          :content="entry.description"
          :book="entry.book"
          :page="entry.page"
      ></td>
    </tr>
  </tbody>
</template>

<script>
  import CheatSheetEntryContent from "./cheat-sheet-entry-content.vue";

  export default {
    name: "cheat-sheet-entry",
    components: {CheatSheetEntryContent},
    props: ["entry", "columnClasses"],

    data() {
      return {
        "clicked": false
      }
    },

    computed: {
      entryClasses () {
        let classes = [];
        classes.push(this.clicked ? "clicked" : "");
        classes.push(isHidden(this.entry, this.$store.getters.getQuery) ? "hidden" : "");
        return classes.filter((x) => x !== "").join(" ");
      },

      colspan () {

        // the columnClasses property enumerates only the ones that aren't
        // for the entry title, i.e. the <th scope="row"> element isn't
        // counted.  hence, we add one to the length of that array.

        return this.columnClasses.length + 1;
      }
    }
  };

  function isHidden (entry, query) {
    console.log(query);
    return true;
  }
</script>

<style scoped>
  tbody:not(.clicked) {
    background-color: #eaeaea;
  }
</style>