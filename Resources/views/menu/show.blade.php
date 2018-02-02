@extends('admin::layouts.master')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/admin/css/nestable.css') }}">

    <style>
        .dd-list {
            min-height: 10px;
            padding-top: 10px;
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
                <input class="loaded-items" type="hidden" value="{{ $items->toJson() }}">
                <input class="all-languages" type="hidden" value="{{ $languages->toJson() }}">
                <input class="all-routes" type="hidden" value="{{ $routes->toJson() }}">
                <input class="all-icons" type="hidden" value="{{ $icons->toJson() }}">
                <input class="all-pages" type="hidden" value="{{ $pages->toJson() }}">
            </div>

            <div class="col-md-8">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="{{ route('admin::menus.index') }}" class="btn btn-xs btn-primary">
                                <i class="fa fa-undo"></i> Back to list
                            </a>
                        </div>
                        <span class="panel-title">Edit menu: {{ $menu->name }}</span>
                    </div>

                    <div class="panel-body" style="padding:20px;">
                        <div class="dd">
                            <template v-if="menu_items.length > 0">
                                <draggable-menu-items :items="menu_items" v-on:change="saveOrder"
                                                      v-on:delete-item="deleteItem"
                                                      v-on:edit-item="editItem">
                                </draggable-menu-items>
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
                            <select2 :data="menu_types" :placeholder="'Please Select'"
                                     v-on:select="services.edit.clear()" v-model="services.edit.type"
                                     :disabled="services.edit.id > 0">
                            </select2>
                        </div>
                        <hr>

                        <div v-show="services.edit.type == 'route'">
                            <ul class="nav nav-tabs" role="tablist">
                                <li v-for="(language, iso_code) in languages"
                                    :class="{'active': iso_code == services.edit.active_translation}"
                                    v-on:click="services.edit.active_translation = iso_code">
                                    <a href="#">
                                        <% language.title_localized %>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content" style="padding-bottom: 0;">
                                <div v-if="iso_code == services.edit.active_translation" v-for="(language, iso_code) in languages" class="tab-pane active">
                                    <template v-if="services.edit.errors.translations[iso_code]">
                                        <div class="form-group"
                                             :class="{'has-error': services.edit.errors.translations[iso_code].name.visible}">
                                            <label>Name</label>
                                            <input type="text" class="form-control"
                                                   v-model="services.edit.translations[iso_code].name">
                                            <span v-if="services.edit.errors.translations[iso_code].name.visible"
                                                  class="help-block">
                                                <% services.edit.errors.translations[iso_code].name.value %>
                                            </span>
                                        </div>
                                        <div class="form-group"
                                             :class="{'has-error': services.edit.errors.translations[iso_code].value.visible}">
                                            <label>Route</label>
                                            <select2 :data="getRouteList()" :placeholder="'Please Select'"
                                                     v-on:select="services.edit.checkParameters()"
                                                     v-model="services.edit.translations[iso_code].value">
                                            </select2>
                                            <span v-if="services.edit.errors.translations[iso_code].value.visible"
                                                  class="help-block">
                                                <% services.edit.errors.translations[iso_code].value.value %>
                                            </span>
                                        </div>

                                        <div v-for="(param, index) in services.edit.translations[iso_code].parameters" class="form-group"
                                             :class="{'has-error': services.edit.errors.translations[iso_code].parameters[index].visible}">
                                            <label><% index | capitalize %></label>
                                            <input type="text" class="form-control" v-model="services.edit.translations[iso_code].parameters[index]">
                                            <span v-if="services.edit.errors.translations[iso_code].parameters[index].visible" class="help-block"><% services.edit.errors.translations[iso_code].parameters[index].value %></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div v-show="services.edit.type == 'url'">
                            <ul class="nav nav-tabs" role="tablist">
                                <li v-for="(language, iso_code) in languages"
                                    :class="{'active': iso_code == services.edit.active_translation}"
                                    v-on:click="services.edit.active_translation = iso_code">
                                    <a href="#">
                                        <% language.title_localized %>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content" style="padding-bottom: 0;">
                                <div v-if="iso_code == services.edit.active_translation" v-for="(language, iso_code) in languages" class="tab-pane active">
                                    <template v-if="services.edit.errors.translations[iso_code]">
                                        <div class="form-group"
                                             :class="{'has-error': services.edit.errors.translations[iso_code].name.visible}">
                                            <label>Name</label>
                                            <input type="text" class="form-control"
                                                   v-model="services.edit.translations[iso_code].name">
                                            <span v-if="services.edit.errors.translations[iso_code].name.visible"
                                                  class="help-block">
                                                <% services.edit.errors.translations[iso_code].name.value %>
                                            </span>
                                        </div>
                                        <div class="form-group"
                                             :class="{'has-error': services.edit.errors.translations[iso_code].value.visible}">
                                            <label>URL:</label>
                                            <input type="text" class="form-control" v-model="services.edit.translations[iso_code].url">
                                            <span v-if="services.edit.errors.translations[iso_code].url.visible"
                                                  class="help-block">
                                                <% services.edit.errors.translations[iso_code].url.value %>
                                            </span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div v-show="services.edit.type == 'page'">
                            <ul class="nav nav-tabs" role="tablist">
                                <li v-for="(language, iso_code) in languages"
                                    :class="{'active': iso_code == services.edit.active_translation}"
                                    v-on:click="services.edit.active_translation = iso_code">
                                    <a href="#">
                                        <% language.title_localized %>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content" style="padding-bottom: 0;">
                                <div v-if="iso_code == services.edit.active_translation" v-for="(language, iso_code) in languages" class="tab-pane active">
                                    <template v-if="services.edit.errors.translations[iso_code]">
                                        <div class="form-group"
                                             :class="{'has-error': services.edit.errors.translations[iso_code].name.visible}">
                                            <label>Name</label>
                                            <input type="text" class="form-control"
                                                   v-model="services.edit.translations[iso_code].name">
                                            <span v-if="services.edit.errors.translations[iso_code].name.visible"
                                                  class="help-block">
                                                <% services.edit.errors.translations[iso_code].name.value %>
                                            </span>
                                        </div>
                                        <div class="form-group"
                                             :class="{'has-error': services.edit.errors.translations[iso_code].value.visible}">
                                            <label>Page</label>
                                            <select2 :data="getPageList()" :placeholder="'Please Select'"
                                                     v-model="services.edit.translations[iso_code].page_id">
                                            </select2>
                                            <span v-if="services.edit.errors.translations[iso_code].value.visible"
                                                  class="help-block">
                                                <% services.edit.errors.translations[iso_code].value.value %>
                                            </span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <template v-if="services.edit.type == 'route' || services.edit.type == 'url' || services.edit.type == 'page'">
                            <hr>
                            <div class="form-group">
                                <label>Icon</label>
                                <select2 :data="all_icons" :placeholder="'Please Select'"
                                         v-model="services.edit.icon">
                                </select2>
                            </div>
                            <div class="form-group">
                                <label>Target</label>
                                <select2 :data="[{id: '_self', text: '_self'}, {id: '_blank', text: '_blank'}]"
                                         v-model="services.edit.target">
                                </select2>
                            </div>
                        </template>

                        <div v-if="services.edit.type != '' && services.edit.type">
                            <div class="form-group">
                                <label>State</label>
                                <select2 :data="[{id: '1', text: 'Enabled'}, {id: '0', text: 'Disabled'}]"
                                         v-model="services.edit.is_active">
                                </select2>
                            </div>

                            <div class="form-group">
                                <div class="col-xs-6" style="padding:0">
                                    <button :disabled="services.edit.loading" type="button" class="btn btn-success"
                                            v-on:click="services.edit.save()">
                                        <span v-if="services.edit.loading">
                                            <i class="fa fa-refresh fa-spin"></i>
                                        </span>
                                        <span v-else>
                                            <i class="fa fa-save"></i>
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
                                    <button type="button" class="btn btn-default" v-on:click="services.edit.cancel()">
                                        <i class="fa fa-undo"></i> Cancel
                                    </button>
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
                        <div class="dd-actions btn-group pull-right">
                            <button type="button" class="btn btn-sm btn-danger" v-on:click="deleteItem(item)">
                                <i class="fa fa-trash"></i> Delete
                            </button>
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
                            <button type="button" class="btn btn-sm btn-primary" v-on:click="editItem(item)">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" v-on:click="deleteItem(item)">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                    <div class="dd-handle">
                        <span v-if="item.icon">
                            <i class="fa" :class="item.icon"></i>
                        </span>
                        <span v-html="item.name"></span>
                        <small v-if="item.url" style="font-weight: 100;">
                            <em><% item.url %></em>
                        </small>
                    </div>
                    <draggable-menu-items v-if="item.children" :items="item.children" v-on:change="saveOrder"
                                          v-on:delete-item="deleteItem"
                                          v-on:edit-item="editItem">
                    </draggable-menu-items>
                </template>
            </li>
        </draggable>
    </div>
@endsection

@section('scripts')
    <script>
        var meta = {
            menu_id: {{ $menu->id }},
            menu_name: '{{ $menu->key }}',
            save_item_url: '{{ route('admin::menu.save-item', $menu->id) }}',
            save_order_url: '{{ route('admin::menu.save-order', $menu->id) }}',
            delete_url: '{{url('admin/menus/'.$menu->id.'/delete-item')}}',
        };
    </script>

    <!-- CDNJS :: Sortable (https://cdnjs.com/) -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/Sortable/1.6.0/Sortable.min.js"></script>

    <!-- CDNJS :: Vue.Draggable (https://cdnjs.com/) -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.14.1/vuedraggable.min.js"></script>

    <script src="{{ versionedAsset('/assets/admin/js/menu/app.js') }}"></script>
@append
