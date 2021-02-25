document.addEventListener("DOMContentLoaded", () => {
  // SnMenu({
  //   menuId: "HeaderMenu",
  //   toggleButtonID: "HeaderMenu-toggle",
  //   toggleClass: "HeaderMenu-is-show",
  //   contextId: "AdminLayout",
  //   parentClose: true,
  //   menuCloseID: "HeaderMenu-wrapper",
  // });

  SnMenu({
    menuId: "AsideMenu",
    toggleButtonID: "AsideMenu-toggle",
    toggleClass: "AsideMenu-is-show",
    contextId: "AdminLayout",
    parentClose: true,
    menuCloseID: "AsideMenu-wrapper",
    iconClassDown: 'fas fa-chevron-down',
    iconClassUp: 'fas fa-chevron-up',
  });
});
