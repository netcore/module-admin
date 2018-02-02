/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 23);
/******/ })
/************************************************************************/
/******/ ({

/***/ 23:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(24);


/***/ }),

/***/ 24:
/***/ (function(module, exports) {

$(document).ready(function () {

    var EditorService = function EditorService(parent, params) {
        var self = this;

        if (typeof params === 'undefined') params = {};

        this.$parent = parent;

        this.loading = false;

        this.active_translation = Object.keys(parent.languages)[0];

        this.id = params.id ? params.id : 0;
        this.translations = {};

        jQuery.each(parent.languages, function (iso_code, language) {
            Vue.set(self.translations, iso_code, {
                locale: iso_code,
                name: '',
                value: '',
                url: '',
                page_id: '',
                parameters: {}
            });
        });

        jQuery.each(params.translations, function (translation_key, translation) {
            Vue.set(self.translations, translation.locale, {
                locale: translation.locale,
                name: translation.name,
                value: translation.value,
                url: translation.value,
                page_id: translation.value ? translation.value.toString() : '',
                parameters: translation.parameters
            });
        });

        this.icon = params.icon ? params.icon : '';
        this.type = params.type ? params.type : '';
        this.url = params.value ? params.value : '';
        this.page_id = params.value ? params.value.toString() : '';
        this.target = params.target ? params.target : '_self';
        this.is_active = typeof params.is_active !== 'undefined' ? params.is_active : 1;

        this.errors = {
            icon: {
                visible: false,
                value: ''
            },
            name: {
                visible: false,
                value: ''
            },
            value: {
                visible: false,
                value: ''
            },
            type: {
                visible: false,
                value: ''
            },
            url: {
                visible: false,
                value: ''
            },
            page_id: {
                visible: false,
                value: ''
            },
            translations: {},
            parameters: {}
        };

        jQuery.each(parent.languages, function (language_key, language) {
            jQuery.each(self.translations, function (translation_key, translation) {
                if (language_key === translation_key) {
                    Vue.set(self.errors.translations, language_key, {
                        name: {
                            visible: false,
                            value: ''
                        },
                        value: {
                            visible: false,
                            value: ''
                        },
                        page_id: {
                            visible: false,
                            value: ''
                        },
                        url: {
                            visible: false,
                            value: ''
                        },
                        parameters: {}
                    });

                    if (translation.parameters) {
                        jQuery.each(params.parameters, function (key, param) {
                            Vue.set(self.errors.translations[language_key].parameters, key, {
                                visible: false,
                                value: ''
                            });
                        });
                    } else {
                        self.errors.translations[language_key].parameters = {};
                    }
                }
            });
        });
    };

    EditorService.prototype = {
        save: function save() {
            var self = this;

            self.clearErrors();

            var newMenuItem = {
                id: this.id,
                icon: this.icon,
                type: this.type,
                target: this.target,
                is_active: this.is_active,
                translations: jQuery.extend(true, {}, this.translations)
            };

            var validate;

            switch (newMenuItem.type) {
                case 'route':
                    validate = self.validate(['translations.name', 'translations.value', 'translations.parameters']);
                    break;
                case 'url':
                    validate = self.validate(['translations.url']);

                    jQuery.each(newMenuItem.translations, function (iso_code, translation) {
                        if (translation.name === '' && !translation.name.trim()) {
                            translation.name = translation.url;
                        }

                        translation.value = translation.url;
                    });

                    break;
                case 'page':
                    validate = self.validate(['translations.page_id', 'translations.name']);

                    jQuery.each(newMenuItem.translations, function (iso_code, translation) {
                        translation.value = translation.page_id;
                    });

                    break;
                default:
                    validate = true;

                    jQuery.each(newMenuItem.translations, function (iso_code, translation) {
                        translation.value = 'javascript:;';
                    });
            }

            if (validate) {

                self.loading = true;

                jQuery.post(meta.save_item_url, newMenuItem, function (response) {
                    if (response.status !== 'success') {
                        self.loading = false;
                        toastr.error('Something went wrong...');
                        return false;
                    }

                    if (newMenuItem.id) {
                        var item = self.$parent.findItem(newMenuItem.id, self.$parent.menu_items);

                        if (item) {
                            item.name = response.item.name;
                            item.icon = response.item.icon;
                            item.type = response.item.type;
                            item.value = response.item.value;
                            item.target = response.item.target;
                            item.translations = response.item.translations;
                            item.is_active = parseInt(response.item.is_active);

                            self.$parent.services.edit = new EditorService(self.$parent);

                            toastr.success("Menu item updated");
                        } else {
                            toastr.error('Something went wrong...');
                        }
                    } else {
                        response.item.children = [];

                        self.$parent.menu_items.push(response.item);
                        self.$parent.services.edit = new EditorService(self.$parent);

                        toastr.success("Menu item added");
                    }

                    self.loading = false;
                }).fail(function () {
                    toastr.error('Something went wrong...');
                    self.loading = false;
                });
            }
        },
        cancel: function cancel() {
            this.$parent.services.edit = new EditorService(this.$parent);
        },
        clear: function clear() {
            var type = this.type;

            this.$parent.services.edit = new EditorService(this.$parent, {
                type: type
            });
        },
        validate: function validate(fields) {
            var self = this;
            var valid = true;

            if (typeof fields === 'undefined') throw Error('Missing fields');

            fields.forEach(function (field) {
                if (field.indexOf('translations') > -1) {
                    var subField = field.split('.')[1];

                    jQuery.each(self.translations, function (iso_code, translation) {
                        if (subField === 'parameters') {
                            jQuery.each(self.errors.translations[iso_code].parameters, function (key, parameter) {
                                if (typeof translation.parameters[key] !== 'undefined') {
                                    if (translation.parameters[key] !== '' && translation.parameters[key].trim()) {
                                        parameter.visible = false;
                                    } else {
                                        parameter.visible = true;
                                        parameter.value = 'This field is required';
                                        valid = false;
                                    }
                                }
                            });
                        } else {
                            if (translation[subField] === null) {
                                translation[subField] = '';
                            }

                            if (translation[subField] !== '' && translation[subField].trim()) {
                                self.errors.translations[iso_code][subField].visible = false;
                            } else {
                                self.errors.translations[iso_code][subField].visible = true;
                                self.errors.translations[iso_code][subField].value = 'This field is required';
                                valid = false;
                            }
                        }
                    });
                } else {
                    if (self[field] !== '' && self[field].trim()) {
                        self.errors[field].visible = false;
                    } else {
                        self.errors[field].visible = true;
                        self.errors[field].value = 'This field is required';
                        valid = false;
                    }
                }
            });

            return valid;
        },
        clearErrors: function clearErrors(field) {
            if (typeof field !== 'undefined') {
                this.errors[field].visible = false;
            } else {
                jQuery.each(this.errors, function (key, field) {
                    if (key === 'parameters') {
                        jQuery.each(field, function (key, parameter) {
                            parameter.visible = false;
                        });
                    } else {
                        field.visible = false;
                    }
                });
            }
        },
        checkParameters: function checkParameters() {
            var self = this;

            jQuery.each(this.$parent.languages, function (iso_code, language) {
                var route = self.$parent.all_routes[self.translations[iso_code].value];

                if (route) {
                    Vue.set(self.translations[iso_code], 'parameters', {});
                    Vue.set(self.errors.translations[iso_code], 'parameters', {});

                    route.parameters.forEach(function (param) {
                        Vue.set(self.translations[iso_code].parameters, param, '');

                        Vue.set(self.errors.translations[iso_code].parameters, param, {
                            visible: false,
                            value: ''
                        });
                    });
                }
            });
        }
    };

    Vue.component('draggable-menu-items', {
        props: ['items'],
        template: "#draggable-menu-items",
        methods: {
            saveOrder: function saveOrder() {
                this.$emit('change');
            },
            deleteItem: function deleteItem(item) {
                this.$emit('delete-item', item);
            },
            editItem: function editItem(item) {
                this.$emit('edit-item', item);
            }
        },
        delimiters: ['<%', '%>']
    });

    new Vue({
        el: '#menu-edit-container',
        data: {
            menu_types: [{
                id: 'route',
                text: 'Route'
            }, {
                id: 'url',
                text: 'Custom Url'
            }, {
                id: 'page',
                text: 'Page'
            }, {
                id: 'empty',
                text: 'Empty'
            }, {
                id: 'separator',
                text: 'Separator'
            }],
            all_routes: {},
            all_pages: {},
            all_icons: [],
            menu_items: [],
            services: {
                edit: false
            },
            languages: {}
        },
        created: function created() {
            var self = this;

            var items = JSON.parse(jQuery('.loaded-items').val());

            jQuery.each(items, function (key, item) {
                Vue.set(self.menu_items, key, item);
            });

            var routes = JSON.parse(jQuery('.all-routes').val());

            jQuery.each(routes, function (key, route) {
                Vue.set(self.all_routes, key, {
                    id: route.name,
                    name: route.name,
                    parameters: route.parameters
                });
            });

            var icons = JSON.parse(jQuery('.all-icons').val());

            jQuery.each(icons, function (key, icon) {
                Vue.set(self.all_icons, key, icon);
            });

            var pages = JSON.parse(jQuery('.all-pages').val());

            jQuery.each(pages, function (key, page) {
                Vue.set(self.all_pages, key, page);
            });

            var languages = JSON.parse(jQuery('.all-languages').val());

            jQuery.each(languages, function (key, language) {
                Vue.set(self.languages, language.iso_code, language);
            });

            Vue.set(self.services, 'edit', new EditorService(self));
        },
        methods: {
            getRouteList: function getRouteList() {
                var routeList = [];

                jQuery.each(this.all_routes, function (key, route) {
                    routeList.push({
                        id: route.name,
                        text: route.name
                    });
                });

                return routeList;
            },
            getPageList: function getPageList() {
                var pageList = [];

                jQuery.each(this.all_pages, function (key, page) {
                    pageList.push({
                        id: page.id.toString(),
                        text: page.text
                    });
                });

                return pageList;
            },
            findItem: function findItem(id, items) {
                var self = this;
                var findableItem = false;
                var stopLoop = false;

                items.forEach(function (item) {
                    if (!stopLoop) {
                        if (item.id === id) {
                            findableItem = item;
                            stopLoop = true;
                        } else {
                            if (item.children.length > 0) {
                                findableItem = self.findItem(id, item.children);
                                if (findableItem) {
                                    stopLoop = true;
                                }
                            }
                        }
                    }
                });

                return findableItem;
            },
            deleteItem: function deleteItem(item) {
                var self = this;

                swal({
                    title: 'Warning',
                    text: "Are you sure you want to delete this menu item?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonColor: '#8c8b89',
                    confirmButtonText: 'Yes, delete it!'
                }).then(function () {
                    var deleteRequest = function deleteRequest(id) {
                        return jQuery.post(meta.delete_url + '/' + id);
                    };

                    var deleteThis = function deleteThis(id, items) {
                        items.forEach(function (deletableItem, deletableKey) {
                            if (deletableItem) {
                                if (deletableItem.id === item.id) {
                                    items.splice(deletableKey, 1);

                                    if (self.services.edit.id === item.id) {
                                        self.services.edit.cancel();
                                    }
                                } else {
                                    if (deletableItem.children.length > 0) {
                                        deleteThis(id, deletableItem.children);
                                    }
                                }
                            }
                        });
                    };

                    if (item.children.length > 0) {
                        var inputOptions = {
                            0: '-- Delete Child Items'
                        };

                        var getMenuItems = function getMenuItems(level, items) {
                            items.forEach(function (otherItem) {
                                if (otherItem.id !== item.id && otherItem.type !== 'separator' && otherItem.type !== 'empty') {
                                    inputOptions[otherItem.id] = Array(level).join('--') + ' ' + otherItem.name;

                                    if (otherItem.children) {
                                        getMenuItems(level + 1, otherItem.children);
                                    }
                                }
                            });
                        };

                        getMenuItems(1, self.menu_items);

                        swal({
                            title: 'Where should we move the child items?',
                            input: 'select',
                            inputOptions: inputOptions,
                            inputClass: 'form-control',
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            cancelButtonColor: '#8c8b89'
                        }).then(function (result) {
                            if (result) {
                                if (result > 0) {
                                    var newParent = self.findItem(parseInt(result), self.menu_items);
                                    if (newParent) {
                                        item.children.forEach(function (child) {
                                            newParent.children.push(child);
                                        });
                                    }

                                    item.children = [];

                                    self.saveOrder().done(function () {
                                        deleteRequest(item.id).done(function (response) {
                                            if (response.status === 'success') {
                                                deleteThis(item.id, self.menu_items);
                                            }
                                        });
                                    });
                                } else {
                                    deleteRequest(item.id).done(function (response) {
                                        if (response.status === 'success') {
                                            deleteThis(item.id, self.menu_items);
                                        }
                                    });
                                }
                            }
                        }).catch(swal.noop);
                    } else {
                        deleteRequest(item.id).done(function (response) {
                            if (response.status === 'success') {
                                deleteThis(item.id, self.menu_items);
                            }
                        });
                    }
                }).catch(swal.noop);
            },
            editItem: function editItem(item) {
                this.services.edit = new EditorService(this, item);
            },
            saveOrder: function saveOrder() {
                var self = this;
                var orders = [];

                var getCurrentOrder = function getCurrentOrder(orders, items) {
                    items.forEach(function (item, key) {
                        orders[key] = {
                            id: item.id,
                            children: []
                        };

                        if (item.children) {
                            getCurrentOrder(orders[key].children, item.children);
                        }
                    });
                };

                getCurrentOrder(orders, self.menu_items);

                return jQuery.post(meta.save_order_url, {
                    order: JSON.stringify(orders)
                }, function (data) {
                    toastr.success("Menu order updated");
                });
            }
        },
        watch: {
            menu_items: {
                handler: function handler() {
                    if (typeof window[meta.menu_name] === 'undefined') return false;

                    var getNewMenuItems = function getNewMenuItems(items) {
                        var menuItems = [];

                        items.forEach(function (item, key) {
                            if (parseInt(item.is_active)) {

                                item.child_active = false;

                                var isActive = function isActive(items) {
                                    items.forEach(function (childItem) {
                                        if (childItem.active) {
                                            item.child_active = true;
                                        } else {
                                            if (childItem.children.length > 0) {
                                                isActive(childItem.children);
                                            }
                                        }
                                    });
                                };

                                if (item.children.length > 0) {
                                    isActive(item.children);
                                }

                                menuItems.push({
                                    id: item.id,
                                    name: item.name,
                                    icon: item.icon,
                                    type: item.type,
                                    url: item.url,
                                    target: item.target,
                                    active: item.active,
                                    child_active: item.child_active,
                                    toggle: item.children ? 'dropdown' : '',
                                    children: item.children ? getNewMenuItems(item.children) : []
                                });
                            }
                        });

                        return menuItems;
                    };

                    window[meta.menu_name].menu_items = getNewMenuItems(this.menu_items);
                },
                deep: true
            }
        },
        delimiters: ['<%', '%>']
    });
});

/***/ })

/******/ });