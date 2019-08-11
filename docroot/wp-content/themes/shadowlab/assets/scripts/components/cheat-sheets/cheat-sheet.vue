<template>
  <table>
    <thead>
      <tr>
        <th scope="col" id="type" v-text="singular"></th>
        <th scope="col" v-for="(header, i) in headers" class="field" :class="getColumnClass(i)">
          <header-abbr :header="header"></header-abbr>
        </th>
      </tr>
    </thead>
    <tbody v-for="(entry, j) in entries" :class="getEntryClasses(j)" @click.prevent="toggleEntry(j)">
      <tr class="heading-row">
        <th scope="row" headers="type" v-text="entry.title"></th>
        <td v-for="i in headers.length" class="field" :class="getColumnClass(i-1)" v-text="getFieldValue(j, i-1)"></td>
      </tr>
      <tr class="body-row">
        <td :colspan="colspan">
          <div class="entry-content" v-html="entry.description"></div>
          <p class="reference">
            <span class="page" v-text="'p. ' + entry.page"></span>
            <span class="book" v-text="entry.book"></span>
          </p>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
  import HeaderAbbr from "./header-abbr.vue";

  export default {
    name: "cheat-sheet.vue",
    components: { HeaderAbbr },
    props: [
      "singular",
      "headersJson",
      "entriesJson",
    ],

    data: function () {
      return {
        "entries": JSON.parse(this.entriesJson),
        "headers": JSON.parse(this.headersJson),
        "clicked": [],
      }
    },

    computed: {
      columnClasses() {
        let classes = [];

        for (let i = 0; i < this.headers.length; ++i) {
          const values = getValues(this.entries, i);
          const numeric = values.filter((value) => !isNaN(value));

          classes.push(
            values.length === numeric.length ? "field-numeric" : "field-mixed"
          )
        }

        return classes;
      },

      colspan() {
        return this.headers.length + 1;
      }
    },

    methods: {
      getColumnClass(headerIndex) {
        return this.columnClasses[headerIndex];
      },

      getEntryClasses(entryIndex) {
        return this.clicked.includes(entryIndex) ? "clicked" : "";
      },

      toggleEntry(entryIndex) {
        if (!this.clicked.includes(entryIndex)) {

          // if the array doesn't include this entry's index, we push it
          // onto the array.

          this.clicked.push(entryIndex);
        } else {

          // if it did include it, we filter the array to remove it.
          // filter keeps array values when the callback returns true.
          // so, we want to return true for all the numbers that aren't
          // this entry's index, i.e. when they're not equal.

          this.clicked = this.clicked.filter((i) => i !== entryIndex);
        }
      },

      getFieldValue(entryIndex, headerIndex) {
        return this.entries[entryIndex].fields[headerIndex];
      }
    }
  };

  function getValues(entries, headerIndex) {
    let values = [];

    for(let i = 0; i < entries.length; i++) {
      const value = entries[i].fields[headerIndex];

      if (value.length > 0) {
        values.push(value);
      }
    }

    return values;
  }
</script>