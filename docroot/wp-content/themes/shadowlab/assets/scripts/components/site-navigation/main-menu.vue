<template>
  <nav id="main-menu" aria-labelledby="main-menu-label">
    <span class="visually-hidden" id="main-menu-label">Main Menu</span>

    <ul v-if="menuObj.length > 0" class="menu">
      <li v-for="item in menuObj" :class="toString(item.classes)">
        <a :href="item.url" v-text="item.label"></a>
        <ul v-if="item.submenu.length > 0" class="submenu">
          <li v-for="submenuItem in item.submenu" :class="toString(submenuItem.classes)">
            <a :href="submenuItem.url" v-text="submenuItem.label"></a>
          </li>
        </ul>
      </li>
    </ul>

  </nav>
</template>

<script>
  const jsonDecode = require('locutus/php/json/json_decode');

  export default {
    name: "main-menu",
    props: ["menu"],
    computed: {
      menuObj() {
        return jsonDecode(this.menu);
      }
    },

    methods: {
      toString(array) {
        return array.join(" ");
      }
    }
  };
</script>

<style scoped>

</style>