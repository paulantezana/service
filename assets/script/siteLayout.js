document.addEventListener("DOMContentLoaded", () => {
    // SnMenu({
    //     menuId: 'SiteMenu',
    //     toggleButtonID: 'SiteMenu-toggle',
    //     toggleClass: 'SiteMenu-is-show',
    //     menuCloseID: 'SiteMenu-wrapper',
    // });
    console.log('COMO_COMO');
    SnMenu({
        menuId: "SiteMenu",
        toggleButtonID: "SiteMenu-toggle",
        toggleClass: "SiteMenu-is-show",
        contextId: "SiteLayout",
        parentClose: true,
        menuCloseID: "SiteMenu-wrapper",
        // iconClassDown: 'fas fa-chevron-down',
        // iconClassUp: 'fas fa-chevron-up',
    });
});
