<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

Breadcrumbs::register('home', function($breadcrumbs) {
    $breadcrumbs->push('Homepage', url('/'));
});

Breadcrumbs::register('admin', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Dashboard', route('admin::dashboard.index'));
});