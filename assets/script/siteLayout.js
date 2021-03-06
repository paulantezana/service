document.addEventListener("DOMContentLoaded", () => {
    SnMenu({
        menuId: "SiteMenu",
        toggleButtonID: "SiteMenu-toggle",
        toggleClass: "SiteMenu-is-show",
        contextId: "SiteLayout",
        parentClose: true,
        menuCloseID: "SiteMenu-wrapper",
        iconClassDown: 'fas fa-chevron-down',
        iconClassUp: 'fas fa-chevron-up',
    });
});
