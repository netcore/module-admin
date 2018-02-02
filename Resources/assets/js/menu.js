window.leftAdminMenu = new Vue({
    el: '#left-admin-menu',
    components: {
        'left-menu-child-items': LeftMenuChildItems
    },
    data: {
        menu_items: []
    },
    created: function () {
        var self = this;
        var items = JSON.parse(jQuery('.left-admin-menu-items').val());

        jQuery.each(items, function (key, item) {
            item.child_active = false;

            var isActive = function (items) {
                items.forEach(function (childItem) {
                    if (childItem.active) {
                        item.child_active = true;
                    } else {
                        if (childItem.children.length > 0) {
                            isActive(childItem.children)
                        }
                    }
                });
            };

            if (item.children.length > 0) {
                isActive(item.children);
            }

            Vue.set(self.menu_items, key, item);
        });

        Vue.nextTick(function () {
            $('body > .px-nav').pxNav();
        });
    },
    delimiters: ['<%', '%>']
});

window.topleftAdminMenu = new Vue({
    el: '#top-admin-menu',
    components: {
        'top-menu-child-items': TopMenuChildItems
    },
    data: {
        menu_items: []
    },
    created: function () {
        var self = this;
        var items = JSON.parse(jQuery('.top-admin-menu-items').val());

        jQuery.each(items, function (key, item) {
            item.child_active = false;
            items[key].toggle = '';

            var isActive = function (items) {
                items.forEach(function (childItem) {
                    if (childItem.active) {
                        item.child_active = true;
                    } else {
                        if (childItem.children.length > 0) {
                            isActive(childItem.children)
                        }
                    }
                });
            };

            if (item.children.length > 0) {
                isActive(item.children);
                items[key].toggle = 'dropdown'
            }

            Vue.set(self.menu_items, key, item);
        });
    },
    delimiters: ['<%', '%>']
});