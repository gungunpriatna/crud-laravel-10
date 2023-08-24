<?php

namespace Tests\Browser;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ManagePostTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function user_can_browse_data(): void
    {
        // generate posts
        Post::factory()->count(5)->create();
        Post::factory()->create([
            'title' => 'post 1',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/post')
                    ->assertSee('post 1');
        });
    }
}
