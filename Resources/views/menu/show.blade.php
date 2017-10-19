@extends('admin::layouts.master')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/admin/css/nestable.css') }}">

    <style>
        .dd-list {
            min-height:10px;
            padding-top:10px;
        }

        .dd-actions {
            margin-top: 12px;
            margin-right: 12px;
        }
    </style>
@append


@section('content')

    <div id="menu-edit-container" v-cloak class="row">
        <div class="v-cloak--block text-center" style="padding-top:20px">
            <i class="fa fa-refresh fa-spin fa-3x"></i>
        </div>
        <div class="v-cloak--hidden">
            <div id="variables" style="display: none">
                <input class="loaded-items" type="hidden" value="{{$items->toJson()}}">
                <input class="all-routes" type="hidden" value="{{$routes->toJson()}}">
                <input class="all-icons" type="hidden" value="{{$icons->toJson()}}">
                <input class="all-pages" type="hidden" value="{{$pages->toJson()}}">
            </div>

            <div class="col-md-8">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">Edit menu: {{ $menu->name }}</span>
                    </div>

                    <div class="panel-body" style="padding:20px;">
                        <div class="dd">
                            <template v-if="menu_items.length > 0">
                                <draggable-menu-items :items="menu_items" v-on:change="saveOrder" v-on:delete-item="deleteItem" v-on:edit-item="editItem"></draggable-menu-items>
                            </template>
                            <template v-else>
                                No menu items
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div v-if="services.edit" class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">
                            <span v-if="services.edit.id > 0">Edit menu item</span>
                            <span v-else>Add new menu item</span>
                        </span>
                    </div>

                    <div class="panel-body" style="padding:20px;">
                        <div class="form-group">
                            <select2 :data="menu_types" :placeholder="'Please Select'" v-on:select="services.edit.clear()" v-model="services.edit.type" :disabled="services.edit.id > 0"></select2>
                        </div>
                        <hr>

                        <div v-show="services.edit.type == 'route'">
                            <div class="form-group" :class="{'has-error': services.edit.errors.name.visible}">
                                <label>Name</label>
                                <input type="text" class="form-control" v-model="services.edit.name">
                                <span v-if="services.edit.errors.name.visible" class="help-block"><% services.edit.errors.name.value %></span>
                            </div>
                            <div class="form-group" :class="{'has-error': services.edit.errors.icon.visible}">
                                <label>Icon</label>
                                <select2 :data="all_icons" :placeholder="'Please Select'" v-model="services.edit.icon"></select2>
                                <span v-if="services.edit.errors.icon.visible" class="help-block"><% services.edit.errors.icon.value %></span>
                            </div>
                            <div class="form-group" :class="{'has-error': services.edit.errors.value.visible}">
                                <label>Route</label>
                                <select2 :data="getRouteList()" :placeholder="'Please Select'" v-on:select="services.edit.checkParameters()" v-model="services.edit.value"></select2>
                                <span v-if="services.edit.errors.value.visible" class="help-block"><% services.edit.errors.value.value %></span>
                            </div>
                            <div v-for="(param, index) in services.edit.parameters" class="form-group" :class="{'has-error': services.edit.errors.parameters[index].visible}">
                                <label><% index | capitalize %></label>
                                <input type="text" class="form-control" v-model="services.edit.parameters[index]">
                                <span v-if="services.edit.errors.parameters[index].visible" class="help-block"><% services.edit.errors.parameters[index].value %></span>
                            </div>
                            <div class="form-group">
                                <label>Target</label>
                                <select2 :data="[{id: '_self', text: '_self'}, {id: '_blank', text: '_blank'}]" v-model="services.edit.target"></select2>
                            </div>
                        </div>
                        <div v-show="services.edit.type == 'url'">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" v-model="services.edit.name">
                            </div>
                            <div class="form-group">
                                <label>Icon</label>
                                <select2 :data="all_icons" :placeholder="'Please Select'" v-model="services.edit.icon"></select2>
                            </div>
                            <div class="form-group" :class="{'has-error': services.edit.errors.url.visible}">
                                <label>URL:</label>
                                <input type="text" class="form-control" v-model="services.edit.url">
                                <span v-if="services.edit.errors.url.visible" class="help-block"><% services.edit.errors.url.value %></span>
                            </div>
                            <div class="form-group">
                                <label>Target</label>
                                <select2 :data="[{id: '_self', text: '_self'}, {id: '_blank', text: '_blank'}]" v-model="services.edit.target"></select2>
                            </div>
                        </div>
                        <div v-show="services.edit.type == 'page'">
                            <div class="form-group" :class="{'has-error': services.edit.errors.name.visible}">
                                <label>Name</label>
                                <input type="text" class="form-control" v-model="services.edit.name">
                                <span v-if="services.edit.errors.name.visible" class="help-block"><% services.edit.errors.name.value %></span>
                            </div>
                            <div class="form-group">
                                <label>Icon</label>
                                <select2 :data="all_icons" :placeholder="'Please Select'" v-model="services.edit.icon"></select2>
                            </div>
                            <div class="form-group" :class="{'has-error': services.edit.errors.page_id.visible}">
                                <label>Page</label>
                                <select2 :data="getPageList()" :placeholder="'Please Select'" v-model="services.edit.page_id"></select2>
                                <span v-if="services.edit.errors.page_id.visible" class="help-block"><% services.edit.errors.page_id.value %></span>
                            </div>
                            <div class="form-group">
                                <label>Target</label>
                                <select2 :data="[{id: '_self', text: '_self'}, {id: '_blank', text: '_blank'}]" v-model="services.edit.target"></select2>
                            </div>
                        </div>

                        <div v-if="services.edit.type != '' && services.edit.type">
                            <div class="form-group">
                                <label>State</label>
                                <select2 :data="[{id: '1', text: 'Enabled'}, {id: '0', text: 'Disabled'}]" v-model="services.edit.is_active"></select2>
                            </div>

                            <div class="form-group">
                                <div class="col-xs-6" style="padding:0">
                                    <button :disabled="services.edit.loading" type="button" class="btn btn-success" v-on:click="services.edit.save()">
                                        <span v-if="services.edit.loading">
                                            <i class="fa fa-refresh fa-spin"></i>
                                        </span>
                                            <span v-else>
                                            <span v-if="services.edit.id">
                                                Save
                                            </span>
                                            <span v-else>
                                                Add
                                            </span>
                                        </span>
                                    </button>
                                </div>
                                <div class="col-xs-6 text-right" style="padding:0">
                                    <button type="button" class="btn btn-default" v-on:click="services.edit.cancel()">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="draggable-menu-items" style="display: none">
        <draggable :element="'ol'" :list="items" class="dd-list" :options="{group:{ name:'g1'}}" v-on:end="saveOrder">
            <li v-for="item in items" class="dd-item">
                <template v-if="item.type == 'separator' || item.type == 'empty'">
                    <div class="pull-right">
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-sm btn-danger" v-on:click="deleteItem(item)">Delete</button>
                        </div>
                    </div>
                    <div class="dd-handle">
                        <hr>
                    </div>
                    <div style="min-height:10px;padding-top:10px"></div>
                </template>
                <template v-else>
                    <div class="pull-right">
                        <div class="dd-actions btn-group pull-right">
                            <button type="button" class="btn btn-sm btn-primary" v-on:click="editItem(item)">Edit</button>
                            <button type="button" class="btn btn-sm btn-danger" v-on:click="deleteItem(item)">Delete</button>
                        </div>
                    </div>
                    <div class="dd-handle">
                        <span v-if="item.icon">
                            <i class="fa" :class="item.icon"></i>
                        </span>
                        <span v-html="item.name"></span>
                        {{--<small class="url"></small>--}}
                    </div>
                    <draggable-menu-items v-if="item.children" :items="item.children" v-on:change="saveOrder" v-on:delete-item="deleteItem" v-on:edit-item="editItem"></draggable-menu-items>
                </template>
            </li>
        </draggable>
    </div>



@endsection

@section('scripts')
    <!-- CDNJS :: Sortable (https://cdnjs.com/) -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/Sortable/1.6.0/Sortable.min.js"></script>

    <!-- CDNJS :: Vue.Draggable (https://cdnjs.com/) -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.14.1/vuedraggable.min.js"></script>

    <script>
        $(document).ready(function () {

            var EditorService = function(parent, params){
                var self = this;

                if(typeof params === 'undefined') params = {};

                this.$parent = parent;

                this.loading = false;

                this.id = params.id ? params.id : 0;
                this.name = params.name ? params.name : '';
                this.icon = params.icon ? params.icon : '';
                this.type = params.type ? params.type : '';
                this.value = params.value ? params.value : '';
                this.url = params.value ? params.value : '';
                this.page_id = params.value ? params.value.toString() : '';
                this.target = params.target ? params.target : '_self';
                this.is_active = params.is_active ? params.is_active : 1;

                this.errors = {
                    name: {
                        visible: false,
                        value: ''
                    },
                    icon: {
                        visible: false,
                        value: ''
                    },
                    type: {
                        visible: false,
                        value: ''
                    },
                    value: {
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
                    parameters: {}
                };

                if(params.parameters){
                    Vue.set(self, 'parameters', params.parameters);

                    jQuery.each(params.parameters, function(key, param){
                        Vue.set(self.errors.parameters, key, {
                            visible: false,
                            value:  ''
                        });
                    });
                } else {
                    self.parameters = {};
                }
            };

            EditorService.prototype = {
                save: function(){
                    var self = this;

                    self.clearErrors();

                    var newMenuItem = {
                        id: this.id,
                        name: this.name,
                        icon: this.icon,
                        type: this.type,
                        value: this.value,
                        url: this.url,
                        page_id: this.page_id,
                        target: this.target,
                        is_active: this.is_active,
                        parameters: this.parameters
                    };

                    var validate;

                    switch(newMenuItem.type) {
                        case 'route':
                            validate = self.validate(['name', 'value', 'parameters']);
                            break;
                        case 'url':
                            validate = self.validate(['url']);

                            if(newMenuItem.name === '' && !newMenuItem.name.trim()){
                                newMenuItem.name = newMenuItem.url;
                            }

                            newMenuItem.value = newMenuItem.url;
                            break;
                        case 'page':
                            validate = self.validate(['page_id', 'name']);

                            newMenuItem.value = newMenuItem.page_id;
                            break;
                        default:
                            validate = true;
                            newMenuItem.value = 'javascript:;';
                    }

                    if(validate) {
                        self.loading = true;

                        jQuery.post('{{route('admin::menu.save-item', $menu->id)}}', newMenuItem, function(response){
                            if(response.status !== 'success'){
                                self.loading = false;
                                toastr.error('Something went wrong...');
                                return false;
                            }

                            if(newMenuItem.id){
                                var item = self.$parent.findItem(newMenuItem.id, self.$parent.menu_items);

                                if(item){
                                    item.name = response.item.name;
                                    item.icon = response.item.icon;
                                    item.type = response.item.type;
                                    item.value = response.item.value;
                                    item.target = response.item.target;
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
                        }).fail(function(){
                            toastr.error('Something went wrong...');
                            self.loading = false;
                        });
                    }
                },
                cancel: function(){
                    this.$parent.services.edit = new EditorService(this.$parent);
                },
                clear: function(){
                    var type = this.type;

                    this.$parent.services.edit = new EditorService(this.$parent, {
                        type: type
                    });
                },
                validate: function(fields){
                    var self = this;
                    var valid = true;

                    if(typeof fields === 'undefined') throw Error('Missing fields');

                    fields.forEach(function(field){
                        if(field === 'parameters'){
                            jQuery.each(self.errors.parameters, function(key, parameter){
                                if(self.parameters[key] !== '' && self.parameters[key].trim()){
                                    parameter.visible = false;
                                } else {
                                    parameter.visible = true;
                                    parameter.value = 'This field is required';
                                    valid = false;
                                }
                            });
                        } else {
                            if(self[field] !== '' && self[field].trim()){
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
                clearErrors: function(field){
                    if(typeof field !== 'undefined'){
                        this.errors[field].visible = false;
                    } else {
                        jQuery.each(this.errors, function(key, field){
                            if(key === 'parameters'){
                                jQuery.each(field, function(key, parameter){
                                    parameter.visible = false;
                                });
                            } else {
                                field.visible = false;
                            }
                        });
                    }
                },
                checkParameters: function(){
                    var self = this;

                    var route = this.$parent.all_routes[this.value];

                    if(route){
                        this.parameters = {};

                        route.parameters.forEach(function(param){
                            Vue.set(self.parameters, param, '');

                            self.errors.parameters = {};

                            Vue.set(self.errors.parameters, param, {
                                visible: false,
                                value: ''
                            })
                        });
                    }
                }
            };

            Vue.component('draggable-menu-items', {
                props: ['items'],
                template: "#draggable-menu-items",
                methods: {
                    saveOrder: function(){
                        this.$emit('change');
                    },
                    deleteItem: function(item){
                        this.$emit('delete-item', item)
                    },
                    editItem: function(item){
                        this.$emit('edit-item', item)
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
                    },{
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
                    }
                },
                created: function(){
                    var self = this;

                    var items = JSON.parse(jQuery('.loaded-items').val());

                    jQuery.each(items, function(key, item){
                        Vue.set(self.menu_items, key, item);
                    });

                    var routes = JSON.parse(jQuery('.all-routes').val());

                    jQuery.each(routes, function(key, route){
                        Vue.set(self.all_routes, key, {
                            id: route.name,
                            name: route.name,
                            parameters: route.parameters
                        });
                    });

                    var icons = JSON.parse(jQuery('.all-icons').val());

                    jQuery.each(icons, function(key, icon){
                        Vue.set(self.all_icons, key, icon);
                    });

                    var pages = JSON.parse(jQuery('.all-pages').val());

                    jQuery.each(pages, function(key, page){
                        Vue.set(self.all_pages, key, page);
                    });

                    Vue.set(self.services, 'edit', new EditorService(self))
                },
                methods: {
                    getRouteList: function(){
                        var routeList = [];

                        jQuery.each(this.all_routes, function(key, route){
                            routeList.push({
                                id: route.name,
                                text: route.name
                            });
                        });

                        return routeList;
                    },
                    getPageList: function(){
                        var pageList = [];

                        jQuery.each(this.all_pages, function(key, page){
                            pageList.push({
                                id: page.id.toString(),
                                text: page.text
                            });
                        });

                        return pageList;
                    },
                    findItem: function(id, items){
                        var self = this;
                        var findableItem = false;
                        var stopLoop = false;

                        items.forEach(function(item){
                            if(!stopLoop){
                                if(item.id === id){
                                    findableItem = item;
                                    stopLoop = true;
                                } else {
                                    if(item.children.length > 0){
                                        findableItem = self.findItem(id, item.children);
                                        if(findableItem){
                                            stopLoop = true;
                                        }
                                    }
                                }
                            }
                        });

                        return findableItem;
                    },
                    deleteItem: function(item){
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
                            var deleteRequest = function(id){
                                return jQuery.post('{{url('admin/menus/'.$menu->id.'/delete-item')}}/'+id);
                            };

                            var deleteThis = function(id, items){
                                items.forEach(function(deletableItem, deletableKey){
                                    if(deletableItem){
                                        if(deletableItem.id === item.id){
                                            items.splice(deletableKey, 1);

                                            if(self.services.edit.id === item.id){
                                                self.services.edit.cancel();
                                            }
                                        } else {
                                            if(deletableItem.children.length > 0){
                                                deleteThis(id, deletableItem.children);
                                            }
                                        }
                                    }
                                });
                            };


                            if(item.children.length > 0){
                                var inputOptions = {
                                    0: '-- Delete Child Items'
                                };

                                var getMenuItems = function(level, items){
                                    items.forEach(function(otherItem){
                                        if(otherItem.id !== item.id && otherItem.type !== 'separator' && otherItem.type !== 'empty'){
                                            inputOptions[otherItem.id] = Array(level).join('--')+' '+otherItem.name;

                                            if(otherItem.children){
                                                getMenuItems(level+1, otherItem.children);
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
                                    if(result){
                                        if(result > 0){
                                            var newParent = self.findItem(parseInt(result), self.menu_items);
                                            if(newParent){
                                                item.children.forEach(function(child){
                                                    newParent.children.push(child);
                                                });
                                            }

                                            item.children = [];

                                            self.saveOrder().done(function(){
                                                deleteRequest(item.id).done(function(response){
                                                    if(response.status === 'success'){
                                                        deleteThis(item.id, self.menu_items);
                                                    }
                                                });
                                            });
                                        } else {
                                            deleteRequest(item.id).done(function(response){
                                                if(response.status === 'success'){
                                                    deleteThis(item.id, self.menu_items);
                                                }
                                            });
                                        }
                                    }
                                }).catch(swal.noop);
                            } else {
                                deleteRequest(item.id).done(function(response){
                                    if(response.status === 'success'){
                                        deleteThis(item.id, self.menu_items);
                                    }
                                });
                            }
                        }).catch(swal.noop);

                    },
                    editItem: function(item){
                        this.services.edit = new EditorService(this, item);
                    },
                    saveOrder: function(){
                        var self = this;
                        var orders = [];

                        var getCurrentOrder = function(orders, items){
                            items.forEach(function(item, key){
                                orders[key] = {
                                    id: item.id,
                                    children: []
                                };

                                if(item.children){
                                    getCurrentOrder(orders[key].children, item.children)
                                }
                            });
                        };

                        getCurrentOrder(orders, self.menu_items);

                        return jQuery.post('{{ route('admin::menu.save-order', $menu->id) }}', {
                            order: JSON.stringify(orders)
                        }, function (data) {
                            toastr.success("Menu order updated");
                        });
                    }
                },
                watch: {
                    menu_items: {
                        handler: function(){
                            if(typeof window['{{$menu->name}}'] === 'undefined') return false;

                            var getNewMenuItems = function(items){
                                var menuItems = [];

                                items.forEach(function(item, key){
                                    if(item.is_active){

                                        item.child_active = false;

                                        var isActive = function(items){
                                            items.forEach(function(childItem){
                                                if(childItem.active){
                                                    item.child_active = true;
                                                } else {
                                                    if(childItem.children.length > 0){
                                                        isActive(childItem.children)
                                                    }
                                                }
                                            });
                                        };

                                        if(item.children.length > 0){
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
                                            children: item.children ? getNewMenuItems(item.children) : []
                                        });
                                    }
                                });

                                return menuItems;
                            };

                            window['{{$menu->name}}'].menu_items = getNewMenuItems(this.menu_items);
                        },
                        deep: true
                    }
                },
                delimiters: ['<%', '%>']
            });
        });
    </script>
@append
