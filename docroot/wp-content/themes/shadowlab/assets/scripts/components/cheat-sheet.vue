<template>
  <table>
    <thead>
      <tr>
        <th scope="col" id="type" v-text="type"></th>
        <th scope="col" v-for="(header, i) in headers" :id="'col' + i" v-text="header"></th>
      </tr>
    </thead>
    <tbody v-for="(entry, j) in entries" :class="getEntryClasses(j)">
      <tr class="heading-row">>
        <th scope="row" :id="'row' + j" headers="type">
          <a :href="entry.url" @click.prevent="setOpenItem(j)" v-text="entry.title"></a>
        </th>
        <td v-for="i in headers.length" :headers="'row' + j + ' col' + i" v-text="entry.fields[i]"></td>
      </tr>
      <tr class="body-row">
        <td :colspan="entries.length + 1">
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
  export default {
    name: "cheat-sheet.vue",
    props: ["entriesJson", "type"],

    data: function () {
      return {
        "entries": JSON.parse(this.entriesJson),
        "clicked": false,
      }
    },

    computed: {
      headers() {
        return this.entries.fields.keys();
      }
    },

    methods: {
      getEntryClasses(j) {
        return this.clicked === j ? "clicked" : "";
      },

      setOpenItem(j) {
        this.clicked = this.clicked === j ? false : j;
      }
    }
  };
</script>