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
/******/ 	return __webpack_require__(__webpack_require__.s = 21);
/******/ })
/************************************************************************/
/******/ ({

/***/ 21:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(22);


/***/ }),

/***/ 22:
/***/ (function(module, exports) {

window.leftAdminMenu = new Vue({
    el: '#left-admin-menu',
    components: {
        'left-menu-child-items': LeftMenuChildItems
    },
    data: {
        menu_items: []
    },
    created: function created() {
        var self = this;
        var items = JSON.parse(jQuery('.left-admin-menu-items').val());

        jQuery.each(items, function (key, item) {
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
    created: function created() {
        var self = this;
        var items = JSON.parse(jQuery('.top-admin-menu-items').val());

        jQuery.each(items, function (key, item) {
            item.child_active = false;
            items[key].toggle = '';

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
                items[key].toggle = 'dropdown';
            }

            Vue.set(self.menu_items, key, item);
        });
    },
    delimiters: ['<%', '%>']
});

/***/ })

/******/ });