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
    <tbody is="cheat-sheet-entry" v-for="entry in entries" :entry="entry" :column-classes="columnClasses"></tbody>
  </table>
</template>

<script>
  import HeaderAbbr from "./header-abbr.vue";
  import CheatSheetEntry from "./cheat-sheet-entry.vue";

  export default {
    name: "cheat-sheet.vue",
    components: {HeaderAbbr, CheatSheetEntry},
    props: [
      "singular",
      "headersJson",
      "entriesJson",
    ],

    data: function () {
      return {
        "entries": JSON.parse(this.entriesJson),
        "headers": JSON.parse(this.headersJson),
      };
    },

    computed: {
      columnClasses () {
        let classes = [];

        for (let i = 0; i < this.headers.length; ++i) {
          const values = getValues(this.entries, i);
          const numeric = values.filter((value) => !isNaN(value));

          classes.push(
            values.length === numeric.length ? "field-numeric" : "field-mixed"
          );
        }

        return classes;
      }
    },

    methods: {
      getColumnClass (headerIndex) {
        return this.columnClasses[headerIndex];
      }
    }
  };

  function getValues (entries, headerIndex) {
    let values = [];

    for (let i = 0; i < entries.length; i++) {
      const value = entries[i].fields[headerIndex];

      if (value.length > 0) {
        values.push(value);
      }
    }

    return values;
  }
</script>
