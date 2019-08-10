<template>
  <table>
    <thead>
      <tr>
        <th scope="col" id="type" v-text="type"></th>
        <th scope="col" v-for="(header, i) in headers" class="field" :class="getColumnClass(i)">
          <header-abbr :header="header"></header-abbr>
        </th>
      </tr>
    </thead>
    <tbody v-for="(entry, j) in entries" :class="getEntryClasses(j)">
      <tr class="heading-row" @click.prevent="setOpenItem(j)">
        <th scope="row" headers="type">
          <a :href="entry.url" v-text="entry.title"></a>
        </th>
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
      "type",
      "headersJson",
      "entriesJson",
    ],

    data: function () {
      return {
        "entries": JSON.parse(this.entriesJson),
        "headers": JSON.parse(this.headersJson),
        "clicked": false,
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
      }
    },

    methods: {
      getColumnClass(headerIndex) {
        return this.columnClasses[headerIndex];
      },

      getEntryClasses(entryIndex) {
        return this.clicked === entryIndex ? "clicked" : "";
      },

      setOpenItem(entryIndex) {
        this.clicked = this.clicked === entryIndex ? false : entryIndex;
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