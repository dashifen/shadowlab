<template>
  <tbody :class="rowClasses" @click.prevent="clicked = !clicked">
    <tr class="heading-row">
      <th scope="row" headers="type" v-text="entry.title"></th>
      <td v-for="(field, i) in entry.fields" class="field" :class="columnClasses[i]" v-text="field"></td>
    </tr>
    <tr class="body-row">
      <td is="entry-content" :colspan="colspan" :content="entry.description" :book="entry.book" :page="entry.page"></td>
    </tr>
  </tbody>
</template>

<script>
  import EntryContent from "./entry-content.vue";

  export default {
    name: "cheat-sheet-entry",
    components: {EntryContent},
    props: ["entry", "columnClasses"],

    data: function () {
      return {
        clicked: false,
      }
    },

    computed: {
      rowClasses() {
        return this.classes + (this.clicked ? " clicked" : "");
      },

      colspan() {

        // the columnClasses property enumerates only the ones that aren't
        // for the entry title, i.e. the <th scope="row"> element isn't
        // counted.  hence, we add one to the length of that

        return this.columnClasses.length + 1;
      }
    }
  };
</script>

<style scoped>
  tbody:not(.clicked) {
    background-color: #eaeaea;
  }
</style>