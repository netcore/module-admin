@extends('admin::layouts.master')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/admin/css/nestable.css') }}">

    <style>
        .dd-list {
            min-height:10px;
            padding-top:10px;
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
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" v-model="services.edit.name">
                            </div>
                            <div class="form-group">
                                <label>Module</label>
                                <input type="text" class="form-control" v-model="services.edit.module">
                            </div>
                            <div class="form-group">
                                <label>Icon</label>
                                <select2 :data="all_icons" :placeholder="'Please Select'" v-model="services.edit.icon"></select2>
                            </div>
                            <div class="form-group">
                                <label>Route</label>
                                <select2 :data="getRouteList()" :placeholder="'Please Select'" v-on:select="services.edit.checkParameters()" v-model="services.edit.value"></select2>
                            </div>
                            <div v-for="(param, index) in services.edit.parameters" class="form-group">
                                <label><% index | capitalize %></label>
                                <input type="text" class="form-control" v-model="services.edit.parameters[index]">
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
                                <label>URL:</label>
                                <input type="text" class="form-control" v-model="services.edit.url">
                            </div>
                            <div class="form-group">
                                <label>Icon</label>
                                <select2 :data="all_icons" :placeholder="'Please Select'" v-model="services.edit.icon"></select2>
                            </div>
                            <div class="form-group">
                                <label>Target</label>
                                <select2 :data="[{id: '_self', text: '_self'}, {id: '_blank', text: '_blank'}]" v-model="services.edit.target"></select2>
                            </div>
                        </div>
                        <div v-show="services.edit.type == 'page'">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" v-model="services.edit.name">
                            </div>
                            <div class="form-group">
                                <label>URL:</label>
                                <input type="text" class="form-control" v-model="services.edit.url">
                            </div>
                            <div class="form-group">
                                <label>Icon</label>
                                <select2 :data="all_icons" :placeholder="'Please Select'" v-model="services.edit.icon"></select2>
                            </div>
                            <div class="form-group">
                                <label>Target</label>
                                <select2 :data="[{id: '_self', text: '_self'}, {id: '_blank', text: '_blank'}]" v-model="services.edit.target"></select2>
                            </div>
                        </div>

                        <div v-if="services.edit.type != ''">
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
                        <div class="btn-group pull-right">
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
                this.module = params.module ? params.module : '';
                this.icon = params.icon ? params.icon : '';
                this.type = params.type ? params.type : '';
                this.value = params.value ? params.value : '';
                this.url = params.value ? params.value : '';
                this.target = params.target ? params.target : '_self';
                this.is_active = params.is_active ? params.is_active : 0;

                Vue.nextTick(function(){
                    if(params.parameters){
                        Vue.set(self, 'parameters', params.parameters);
                    } else {
                        self.parameters = {};
                    }
                });
            };

            EditorService.prototype = {
                save: function(){
                    var self = this;

                    self.loading = true;

                    var newMenuItem = {
                        id: this.id,
                        name: this.name,
                        module: this.module,
                        icon: this.icon,
                        type: this.type,
                        value: this.value,
                        url: this.url,
                        target: this.target,
                        is_active: this.is_active,
                        parameters: this.parameters
                    };

                    switch(newMenuItem.type) {
                        case 'route':
                            //do nothing
                            break;
                        case 'url':
                            if(newMenuItem.name === '' && !newMenuItem.name.trim()){
                                newMenuItem.name = newMenuItem.value;
                            }

                            newMenuItem.value = newMenuItem.url;
                            break;
                        case 'page':
                            if(newMenuItem.name === '' && !newMenuItem.name.trim()){
                                newMenuItem.name = newMenuItem.value;
                            }

                            newMenuItem.value = newMenuItem.url;
                            break;
                        default:
                            newMenuItem.value = 'javascript:;';
                    }

                    jQuery.post('{{route('admin::menu.save-item', $menu->id)}}', newMenuItem, function(response){
                        if(response.status !== 'success'){
                            toastr.error('Something went wrong...');
                            return false;
                        }

                        if(newMenuItem.id){
                            var item = self.$parent.findItem(newMenuItem.id, self.$parent.menu_items);

                            if(item){
                                item.name = response.item.name;
                                item.module = response.item.module;
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
                    });
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

                },
                checkParameters: function(){
                    var self = this;

                    var route = this.$parent.all_routes[this.value];

                    if(route){
                        this.parameters = {};

                        route.parameters.forEach(function(param){
                            Vue.set(self.parameters, param, '')
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
                                });
                            } else {
                                deleteRequest(item.id).done(function(response){
                                    if(response.status === 'success'){
                                        deleteThis(item.id, self.menu_items);
                                    }
                                });
                            }
                        });

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
                                        menuItems.push({
                                            id: item.id,
                                            name: item.name,
                                            icon: item.icon,
                                            type: item.type,
                                            url: item.url,
                                            target: item.target,
                                            active: item.active,
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
