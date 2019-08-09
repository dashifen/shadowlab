<template>
  <nav id="main-menu" aria-labelledby="main-menu-label">
    <span class="visually-hidden" id="main-menu-label">Main Menu</span>

    <ul v-if="menu.length > 0" class="menu">
      <li v-for="(item, i) in menu" :class="getClasses(i)" @click.prevent="openSubmenu(i)">
        <a :href="item.url">
          <font-awesome-icon v-if="item.submenu.length > 0" :icon="['far', getIcon(i)]"></font-awesome-icon>
          {{ item.label }}
        </a>
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
  import { library } from "@fortawesome/fontawesome-svg-core";
  import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
  import { faPlusSquare, faMinusSquare } from "@fortawesome/pro-regular-svg-icons";

  library.add(faPlusSquare, faMinusSquare);

  export default {
    name: "main-menu",
    components: {FontAwesomeIcon},
    props: ["menuJson"],

    data: function() {
      return {
        "menu": JSON.parse(this.menuJson),
        "clicked": false,
      }
    },

    methods: {
      getClasses(i) {
        return this.menu[i].classes
          .concat(this.clicked === i ? ["clicked"] : [])
          .join(" ");
      },

      openSubmenu(i) {
        this.clicked = this.clicked !== i ? i : false;
      },

      getIcon(i) {
        return this.clicked === i ? "minus-square" : "plus-square";
      }
    }
  };
</script>