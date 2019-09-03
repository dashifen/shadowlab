<template>
  <tbody @click.prevent="clicked = !clicked" :class="entryClasses">
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

    data () {
      return {
        "clicked": false
      };
    },

    computed: {
      entryClasses () {
        let classes = [];

        // there are two classes we might want to add to one of our entries:
        // clicked and hidden.  the first controls if this entry's description
        // is shown or just it's summary.  the second refers to whether or not
        // the current query matches this entry; if not, then it's hidden from
        // view.

        classes.push(this.clicked ? "clicked" : "");
        classes.push(isHidden(this.entry, this.$store.getters.getQuery) ? "hidden" : "");

        // for brevity (and to avoid extra spaces in our class lists), we'll
        // filter out blanks here and then join anything that's left with
        // spaces.  that returns our array in the correct HTML-ready space
        // separated string for the DOM.

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

  // we don't need to export these functions.  they'll get included due to the
  // closure on isHidden() within the entryClasses computed property above.
  // this way, if anyone adds a function that matches one of the names here,
  // we won't collide since these will be out-of-scope with respect to other
  // functions.

  function isHidden (entry, query) {

    // an entry should be hidden (i.e. we return true) if the current query
    // doesn't match it.  the query array is made up of small objects that
    // have a name, type, and value.  the name determines what we analyze
    // within the entry, the type determines how we analyze it, and the value
    // is what we use in our comparison.

    for (let question in query) {
      if (query.hasOwnProperty(question)) {
        if (!entry[question.name]) {

          // if our entry cannot even begin to comprehend this question,
          // then we return true.  frankly, this is probably a bug, so we'll
          // also log a console warning.

          console.warn("Entry unable to answer question.", entry, question);
          return true;
        }

        // here, we assume that this entry is visible until we prove it's
        // not.  thus, we assume that it's data "answers" all of our query's
        // "questions."  once we find a question that's not answered by this
        // entry, we return false.

        let answered = true;
        switch (question.type) {
          case "search":
            answered = doSearch(entry, question);
            break;

          case "filter":
            answered = doFilter(entry, question);
            break;
        }

        if (!answered) {

          // if our entry does not answer the current question, then we return
          // true.  i.e., because it's doesn't answer one or more questions of
          // this query, it should be hidden.  we can't just return !!answered
          // because we want to continue onto the next question if this one
          // worked out for us.

          return true;
        }
      }
    }

    // in the loop above, the moment we identify that this entry doesn't match
    // the answer to one of our questions, we return true so that it's hidden
    // on screen.  thus, if we made it here, this entry matched and we can
    // return false so that it's visible.  note, if our query is empty, then
    // the loop doesn't run and we end up here, which is what we want in that
    // case, too.

    return false;
  }

  function doSearch (entry, question) {

    // if we're searching within an entry's data, we want to see if the
    // question's answer is found anywhere within the appropriate entry
    // property.  so, bar should match bar, foobar, foobarbaz, and barbaz,
    // but it shouldn't match foo, baz, or foobaz, for example.  regular
    // expressions for the win!

    return (new RegExp(question.value)).test(entry[question.name]);
  }

  function doFilter (entry, question) {

    // filters less complex.  they focus on exact matches.  i.e., if we're
    // filtering for only those entries that are in the SR6 book, then the
    // book for this entry better by SR6!  that said:  books are a little
    // more complex because they're broken into abbreviations and titles;
    // our filtering for those focuses on abbreviations.  so, we'll handle
    // them explicitly via the following ternary statement.

    return question.name !== "book"
      ? entry[question.name] === question.value
      : entry.book.abbr === question.value;
  }
</script>

<style scoped>
  tbody:not(.clicked) {
    background-color: #eaeaea;
  }
</style>