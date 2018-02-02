<?php

namespace Modules\Admin\Http\ViewComposers;

use Illuminate\View\View;
use Modules\Admin\Models\Menu;
use Nwidart\Modules\Facades\Module;

class AdminMenuViewComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View $view ,
     * @return void
     */
    public function compose(View $view)
    {
        $mediaModule = Module::find('media');

        $view->with('leftAdminMenu', menu('leftAdminMenu')->getItemTree());
        $view->with('topLeftAdminMenu', menu('topLeftAdminMenu')->getItemTree());
        $view->with('mediaModule', $mediaModule);
    }
}
