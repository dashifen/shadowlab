<template>
  <nav id="main-menu" aria-labelledby="main-menu-label">
    <span class="visually-hidden" id="main-menu-label">Main Menu</span>

    <ul v-if="menuObj.length > 0" class="menu">
      <li v-for="(item, i) in menuObj" :class="getClasses(i)" @click.prevent="openSubmenu(i)">
        <a :href="item.url" v-text="item.label"></a>
        <ul v-if="item.submenu.length > 0" class="submenu">
          <li v-for="submenuItem in item.submenu" :class="submenuItem.classes">
            <a :href="submenuItem.url" v-text="submenuItem.label"></a>
          </li>
        </ul>
      </li>
    </ul>

  </nav>
</template>

<script>
  export default {
    name: "main-menu",
    props: ["menu"],

    data: function() {
      return {
        "clicked": -1,
        "menuObj": JSON.parse(this.menu),
      }
    },

    computed: {

    },

    methods: {
      getClasses(i) {
        const clicked = this.clicked === i ? ["clicked"] : [];
        return this.menuObj[i].classes.concat(clicked).join(" ");
      },

      openSubmenu(i) {
        this.clicked = i;
      }
    }
  };
</script>

<style scoped>

</style>