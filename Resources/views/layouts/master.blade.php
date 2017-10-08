<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <title>{{ config('app.name') }} : Admin</title>

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin" rel="stylesheet" type="text/css">
    <link href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css">
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <link rel="stylesheet" href="/assets/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/admin/css/pixeladmin.min.css">
    <link rel="stylesheet" href="/assets/admin/css/widgets.min.css">
    <link rel="stylesheet" href="/assets/admin/css/themes/{{ config('admin.theme') }}.min.css">
    <link rel="stylesheet" href="/assets/admin/css/sweetalert.min.css">
    <link rel="stylesheet" href="/assets/admin/css/netcore.css">
    <link rel="stylesheet" href="/assets/admin/css/switchery.min.css">

    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/holder/2.9.0/holder.js"></script>
    <script src="/assets/admin/pace/pace.min.js"></script>
    <style>
        @media (min-width: 992px) {
            .px-nav {
                width: 255px;
            }

            .px-nav-collapse {
                width: 56px;
            }

            .px-nav-left.px-nav-expand ~ .px-content, .px-nav-left ~ .px-content {
                margin-left: 275px;
            }

            .px-nav-content > .px-nav-item {
                width: 255px;
            }

            .px-nav-left.px-nav-expand ~ .px-footer, .px-nav-left ~ .px-footer {
                padding-left: 275px;
            }
        }

        [v-cloak] .v-cloak--block {
            display: block!important;
        }

        [v-cloak] .v-cloak--inline {
            display: inline!important;
        }

        [v-cloak] .v-cloak--inlineBlock {
            display: inline-block!important;
        }

        [v-cloak] .v-cloak--hidden {
            display: none!important;
        }

        [v-cloak] .v-cloak--invisible {
            visibility: hidden!important;
        }

        .v-cloak--block,
        .v-cloak--inline,
        .v-cloak--inlineBlock {
            display: none!important;
        }
    </style>
    @yield('styles')
</head>
<body>
<nav class="px-nav px-nav-left">
    <button type="button" class="px-nav-toggle" data-toggle="px-nav">
        <span class="px-nav-toggle-arrow"></span>
        <span class="navbar-toggle-icon"></span>
        <span class="px-nav-toggle-label font-size-11">HIDE MENU</span>
    </button>

    <ul class="px-nav-content">
        <li class="px-nav-box p-a-3 b-b-1" id="demo-px-nav-box">
            <img src="{{ auth()->user()->gravatar() }}" alt="" class="pull-xs-left m-r-2 border-round" style="width: 54px; height: 54px;">
            <div class="font-size-16">
                <span class="font-weight-light">Welcome,<br> </span>
                <strong>{{ auth()->user()->first_name }}</strong>
            </div>
        </li>

        {{-- AdminMenuViewComposer --}}
        <input type="hidden" class="left-admin-menu-items" value="{{$leftAdminMenu->toJson()}}">
        <div id="left-admin-menu" v-cloak class="left-admin-menu">
            <div class="v-cloak--block text-center" style="padding-top:20px">
                <i class="fa fa-refresh fa-spin fa-2x"></i>
            </div>
            <div class="v-cloak--hidden">
                <template v-if="menu_items.length > 0">
                    <menu-items :items="menu_items"></menu-items>
                </template>
                <template v-else>
                    <span style="padding-left:10px">No menu items</span>
                </template>
            </div>
        </div>
    </ul>
</nav>

<nav class="navbar px-navbar">
    <!-- Header -->
    <div class="navbar-header">
        <a class="navbar-brand px-demo-brand" href="{{ route('admin::dashboard.index') }}">
            <span class="px-demo-logo bg-primary">
                <span class="px-demo-logo-1"></span>
                <span class="px-demo-logo-2"></span>
                <span class="px-demo-logo-3"></span>
                <span class="px-demo-logo-4"></span>
                <span class="px-demo-logo-5"></span>
                <span class="px-demo-logo-6"></span>
                <span class="px-demo-logo-7"></span>
                <span class="px-demo-logo-8"></span>
                <span class="px-demo-logo-9"></span>
            </span>
            {{ config('app.name') }}
        </a>
    </div>

    <!-- Navbar togglers -->
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#px-demo-navbar-collapse" aria-expanded="false"><i class="navbar-toggle-icon"></i></button>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="px-demo-navbar-collapse">
        <ul class="nav navbar-nav">
            <li class="dropdown">
                <a href class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                   aria-expanded="false"><i class="fa fa-bookmark m-r-1"></i>Links</a>
                <ul class="dropdown-menu">
                    <li class="dropdown-toggle">
                        <a href>Products</a>
                        <ul class="dropdown-menu">
                            <li><a href="#">All</a></li>
                            <li><a href="#">Popular</a></li>
                            <li><a href="#">Recent</a></li>
                            <li><a href="#">Featured</a></li>
                            <li class="divider"></li>
                            <li><a href="#"><i class="fa fa-plus m-r-1"></i>Add product</a></li>
                        </ul>
                    </li>
                    <li class="dropdown-toggle">
                        <a href>Users</a>
                        <ul class="dropdown-menu">
                            <li><a href="#">All</a></li>
                            <li><a href="#">Recent</a></li>
                            <li><a href="#">Banned</a></li>
                            <li class="divider"></li>
                            <li><a href="#"><i class="fa fa-user-plus m-r-1"></i>Create user</a></li>
                        </ul>
                    </li>
                    <li class="dropdown-toggle">
                        <a href>Blog</a>
                        <ul class="dropdown-menu">
                            <li><a href="#">All</a></li>
                            <li><a href="#">Popular</a></li>
                            <li class="divider"></li>
                            <li><a href="#"><i class="fa fa-edit m-r-1"></i>New blog post</a></li>
                        </ul>
                    </li>
                    <li class="divider"></li>
                    <li><a href="#">Overview</a></li>
                </ul>
            </li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                   aria-expanded="false">
                    <img src="{{ auth()->user()->gravatar() }}" alt="" class="px-navbar-image">
                    <span class="hidden-md">{{ auth()->user()->fullname }}</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="javascript:;">Account</a></li>
                    <li class="divider"></li>
                    <li><a href="{{ route('admin::auth.logout') }}"><i class="dropdown-icon fa fa-power-off"></i>&nbsp;&nbsp;Log Out</a></li>
                </ul>
            </li>

        </ul>
    </div><!-- /.navbar-collapse -->
</nav>

<div class="px-content">
    @yield('content')
</div>

<footer class="px-footer px-footer-bottom p-t-5">
    <span class="text-muted">Copyright © {{ date('Y') }} {{ config('app.name') }}. All rights reserved. Development from <a href="http://netcore.lv/">netcore</a>.</span>
</footer>

<div id="menu-items" style="display:none">
    <div>
        <li v-for="item in items" class="px-nav-item" :class="{'px-nav-dropdown': item.children.length > 0, 'active': item.active}">
            <template v-if="item.type == 'separator' || item.type == 'empty'">
                <hr>
            </template>
            <template v-else>
                <a :href="item.url" :target="item.target">
                    <i v-if="item.icon" class="px-nav-icon" :class="item.icon"></i>
                    <span class="px-nav-label" v-html="item.name"></span>
                </a>
                <ul v-if="item.children" class="px-nav-dropdown-menu">
                    <menu-items :items="item.children"></menu-items>
                </ul>
            </template>
        </li>
    </div>
</div>

<div id="select2-template" style="display: none">
    <select :data-placeholder="placeholder">
        <slot></slot>
    </select>
</div>

<!-- ==============================================================================
|
|  SCRIPTS
|
=============================================================================== -->

<!-- jQuery -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<!-- Vue.Js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.4.4/vue.js"></script>

<!-- Vue Validate -->
<script src="https://cdn.jsdelivr.net/npm/vee-validate@latest/dist/vee-validate.js"></script>

<script>
    Vue.use(VeeValidate);
</script>

<script src="/assets/admin/js/bootstrap.min.js"></script>
<script src="/assets/admin/js/pixeladmin.min.js"></script>
<script src="/assets/admin/js/sweetalert.min.js"></script>
<script src="/assets/admin/js/switchery.min.js"></script>
<script>
    var csrf_token = '{{ csrf_token() }}';
</script>
<script src="/assets/admin/js/netcore.js"></script>

<script>
    // -------------------------------------------------------------------------
    // Initialize Vue Select2 Plugin

    Vue.component('select2', {
        props: {
            options: {
                type: Object,
                default: function(){
                    return {};
                }
            },
            data: {
                type: Array
            },
            value: {},
            placeholder: {}
        },
        template: '#select2-template',
        mounted: function () {
            var vm = this;

            this.options.data = this.data;

            $(this.$el)
                .select2(this.options)
                .val(this.value)
                .trigger('change')
                .on('change', function () {
                    vm.$emit('input', this.value)
                })
        },
        watch: {
            value: function (value) {
                $(this.$el).val(value).trigger('change');
            },
            options: function (options) {
                $(this.$el).empty().select2({ data: options })
            }
        },
        destroyed: function () {
            $(this.$el).off().select2('destroy')
        }
    });

    // -------------------------------------------------------------------------
    // Initialize leftAdminMenu

    var leftAdminMenu;

    jQuery(document).ready(function(){
        Vue.component('menu-items', {
            props: ['items'],
            template: "#menu-items",
            delimiters: ['<%', '%>']
        });

        leftAdminMenu = new Vue({
            el: '#left-admin-menu',
            data: {
                menu_items: []
            },
            created: function(){
                var self = this;
                var items = JSON.parse(jQuery('.left-admin-menu-items').val());

                jQuery.each(items, function(key, item){
                    Vue.set(self.menu_items, key, item);
                });
            },
            delimiters: ['<%', '%>']
        });
    });
</script>

@yield('scripts')
</body>
</html>
