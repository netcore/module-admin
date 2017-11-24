<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

Breadcrumbs::register('home', function ($breadcrumbs) {
    $breadcrumbs->push('Homepage', url('/'));
});

Breadcrumbs::register('admin', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Dashboard', route('admin::dashboard.index'));
});

Breadcrumbs::register('admin.whitelist', function ($breadcrumbs) {
    $breadcrumbs->parent('admin');
    $breadcrumbs->push('Whitelist', route('admin::whitelist.index'));
});

Breadcrumbs::register('admin.whitelist.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.whitelist');
    $breadcrumbs->push('Create', route('admin::whitelist.create'));
});

Breadcrumbs::register('admin.whitelist.edit', function ($breadcrumbs, $model) {
    $breadcrumbs->parent('admin.whitelist');
    $breadcrumbs->push('Edit', route('admin::whitelist.edit', $model));
});
