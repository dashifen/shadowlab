export default {
  initialize () {
    const menuItems = document.querySelectorAll("#main-menu .menu > .item"),
      containerWidth = document.querySelector("#banner .container").getBoundingClientRect().width;

    menuItems.forEach((menuItem) => {
      menuItem.addEventListener("click", (event) => {
        if(event.target.matches(".submenu-item a")) {
          return;
        }

        event.stopPropagation();
        event.preventDefault();

        // first things first:  we manage our clicked class.  if we click
        // the clicked item, we simply remove the class from it.  otherwise,
        // we remove it from the other items and add it to this one.

        if (this.is(menuItem, "clicked")) {
          this.unclick([menuItem]);
        } else {
          this.unclick(menuItems);
          this.addClass(menuItem, "clicked");
          if(!this.is(menuItem, "analyzed")) {

            // if this menu item has not yet been analyzed, we want to see
            // if the right edge of it's submenu would be offscreen.  first
            // step:  confirm we have a submenu.

            const submenu = this.getSubmenu(menuItem);
            if (submenu) {

              // now that we've done that, we get it's rect, calculate it's
              // right edge, and then determine if that edge is past the edge
              // of our container.  if so, this should be a rightward menu.

              const submenuClientRect = submenu.getBoundingClientRect(),
                rightEdge = submenuClientRect.left + submenuClientRect.width,
                directionClass = rightEdge > containerWidth ? "rightward" : "leftward";

              this.addClass(menuItem, directionClass, "analyzed");
            }
          }
        }
      });
    });
  },

  is(menuItem, className) {
    return menuItem.classList.contains(className);
  },

  unclick(menuItems) {
    menuItems.forEach((menuItem) => {
      menuItem.classList.remove("clicked");
    });
  },

  addClass(menuItem, ...classes) {
    classes.forEach((className) => {
      menuItem.classList.add(className);
    });
  },

  click(menuItem) {
    menuItem.classList.add("clicked");
  },

  getSubmenu(menuItem) {
    return menuItem.querySelector(".submenu");
  }
}