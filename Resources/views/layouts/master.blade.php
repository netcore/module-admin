<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <title>{{ config('app.name') }} : Admin</title>

    <link href="//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300&subset=latin"
          rel="stylesheet" type="text/css">
    <link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <link rel="stylesheet" href="/assets/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/admin/css/pixeladmin.min.css">
    <link rel="stylesheet" href="/assets/admin/css/widgets.min.css">
    <link rel="stylesheet" href="/assets/admin/css/themes/{{ config('admin.theme') }}.min.css">
    <link rel="stylesheet" href="/assets/admin/css/sweetalert.min.css">
    <link rel="stylesheet" href="{{ versionedAsset('/assets/admin/css/netcore.css') }}">
    <link rel="stylesheet" href="/assets/admin/css/switchery.min.css">
    <link href="/assets/admin/plugins/x-editable/css/bootstrap-editable.css" rel="stylesheet">

    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/holder/2.9.0/holder.js"></script>
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
            display: block !important;
        }

        [v-cloak] .v-cloak--inline {
            display: inline !important;
        }

        [v-cloak] .v-cloak--inlineBlock {
            display: inline-block !important;
        }

        [v-cloak] .v-cloak--hidden {
            display: none !important;
        }

        [v-cloak] .v-cloak--invisible {
            visibility: hidden !important;
        }

        .v-cloak--block,
        .v-cloak--inline,
        .v-cloak--inlineBlock {
            display: none !important;
        }

    </style>
    @yield('styles')
    @stack('styles')
    <script>var init = [];</script>
</head>
<body>
<input type="hidden" class="left-admin-menu-items" value="{{ $leftAdminMenu->toJson() }}">
<input type="hidden" class="top-admin-menu-items" value="{{ $topLeftAdminMenu->toJson() }}">

<nav id="left-admin-menu" v-cloak class="px-nav px-nav-left">
    <button type="button" class="px-nav-toggle" data-toggle="px-nav">
        <span class="px-nav-toggle-arrow"></span>
        <span class="navbar-toggle-icon"></span>
        <span class="px-nav-toggle-label font-size-11">HIDE MENU</span>
    </button>

    <ul class="px-nav-content">
        <li class="px-nav-box p-a-3 b-b-1" id="demo-px-nav-box">
            <img src="{{ auth()->user()->gravatar() }}" alt="" class="pull-xs-left m-r-2 border-round"
                 style="width: 54px; height: 54px;">
            <div class="font-size-16">
                <span class="font-weight-light">Welcome,<br> </span>
                <strong>{{ auth()->user()->fullName }}</strong>
            </div>
        </li>

        {{-- AdminMenuViewComposer --}}

        <template v-if="menu_items.length > 0">
            <li v-for="item in menu_items" class="px-nav-item"
                :class="{'px-nav-dropdown': item.children.length > 0, 'active': item.active, 'px-open': item.child_active, 'js-file-manager': item.module == 'Media'}">
                <template v-if="item.type == 'separator' || item.type == 'empty'">
                    <hr>
                </template>
                <template v-else>
                    <a :href="item.url" :target="item.target">
                        <i v-if="item.icon" class="px-nav-icon fa" :class="item.icon"></i>
                        <span class="px-nav-label" v-html="item.name"></span>
                    </a>
                    <template v-if="item.children">
                        <left-menu-child-items :item="item"></left-menu-child-items>
                    </template>
                </template>
            </li>
        </template>
        <template v-else>
            <span style="padding-left:10px">No menu items</span>
        </template>
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
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#px-demo-navbar-collapse"
            aria-expanded="false"><i class="navbar-toggle-icon"></i></button>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="px-demo-navbar-collapse">
        <div id="top-admin-menu" v-cloak>
            <div class="v-cloak--hidden">
                <ul class="nav navbar-nav">
                    <li v-for="item in menu_items" :class="{'dropdown': item.children.length > 0}" v-if="item.type !== 'seperator' && item.type !== 'empty'">
                        <a :href="item.url" :data-toggle="item.toggle"
                           :class="{'dropdown-toggle': item.children.length > 0}">
                            <i v-if="item.icon" class="fa m-r-1" :class="item.icon"></i>
                            <span v-html="item.name"></span>
                        </a>

                        <template v-if="item.children">
                            <top-menu-child-items :item="item"></top-menu-child-items>
                        </template>
                    </li>
                </ul>
            </div>
        </div>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                   aria-expanded="false">
                    <img src="{{ auth()->user()->gravatar() }}" alt="" class="px-navbar-image">
                    <span class="hidden-md">{{ auth()->user()->fullname }}</span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('user::users.edit', auth()->id()) }}">Account</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="{{ route('admin::auth.logout') }}">
                            <i class="dropdown-icon fa fa-power-off"></i>&nbsp;&nbsp;Log Out
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </div><!-- /.navbar-collapse -->
</nav>

<div class="px-content">
    @yield('content')
</div>

<footer class="px-footer px-footer-bottom p-t-5">
    Copyright © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    @if (config('netcore.module-admin.show_copyrights'))
        Development from <a href="http://netcore.lv/" class="developed"><strong>netcore</strong></a>.
    @endif
</footer>

@if($mediaModule && $mediaModule->enabled())
    @include('media::_partials.file-manager')
@endif

<!-- ==============================================================================
|
|  SCRIPTS
|
=============================================================================== -->

<script>
    // -------------------------------------------------------------------------
    // Catch all unhandled errors

    window.addEventListener('error', function (e) {
        toastr.error("Whoops! Something went wrong...");
    });

</script>

<!-- jQuery -->
<script src="/assets/admin/js/jquery.min.js"></script>

<!-- Vue.Js -->
<script src="/assets/admin/js/vue.js"></script>

<!-- Vue2Filters -->
<script src="/assets/admin/js/vue2-filters.min.js"></script>

<!-- Vue Validate -->
<script src="/assets/admin/js/vee-validate.min.js"></script>

<!-- Vue Components -->
<script src="/assets/admin/js/components.js"></script>

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
<script src="{{ versionedAsset('/assets/admin/js/netcore.js') }}"></script>
<script src="{{ versionedAsset('/assets/admin/js/menu.js') }}"></script>
@if($mediaModule && $mediaModule->enabled())
    <script src="/assets/media/js/media.js"></script>
@endif

<script src="/assets/admin/plugins/x-editable/js/bootstrap-editable.min.js"></script>

<script>

    // -------------------------------------------------------------------------
    // Initialize DEMO

    $(function () {
        $('body > .px-footer').pxFooter();

        $('#navbar-notifications').perfectScrollbar();
        $('#navbar-messages').perfectScrollbar();
    });

    // -------------------------------------------------------------------------
    // Initialize leftAdminMenu

    jQuery(document).ready(function () {

        if (typeof init !== 'undefined') {
            $.each(init, function (index, f) {
                f();
            });
        }

        $.fn.editableform.buttons = '<button type="submit" class="btn btn-primary btn-xs editable-submit">Save</button>' +
            '<button type="button" class="btn btn-xs editable-cancel">Cancel</button>';
    });
</script>

@yield('scripts')
@stack('scripts')

</body>
</html>
