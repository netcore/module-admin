<?php

namespace Modules\Admin\Tests;

use App\User;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\Menu;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuItemTest extends TestCase
{
    /**
     * Test all menu item URL responses as admin
     *
     * @return void
     */
    public function testMenuUrlsAsAdmin()
    {
        //Test all URLs as an admin
        $user = app(config('netcore.module-admin.user.model'))->where('is_admin', 1)->first();

        $menus = Menu::get();
        foreach ($menus as $menu) {
            foreach ($menu->items as $item) {
                $response = $this->actingAs($user)->get($item->url);

                $response->assertStatus(200);
            }
        }
    }

    /**
     * Test public menu item URL responses as guest
     *
     * @return void
     */
    public function testPublicUrlsAsGuest()
    {
        $menus = Menu::where('type', 'public')->get();

        if (!$menus->count()) {
            $this->assertTrue(true);
        }

        foreach ($menus as $menu) {
            foreach ($menu->items as $item) {
                $response = $this->get($item->url);

                $response->assertStatus(200);
            }
        }
    }
}
