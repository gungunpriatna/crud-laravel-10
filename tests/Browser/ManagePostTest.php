<?php

namespace Tests\Browser;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ManagePostTest extends DuskTestCase
{
    /**
     * test view post
     */
    public function testViewPosts(): void
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

    public function testCreatePost(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/post')
                ->clickLink('Create New Post')
                ->assertSee('CREATE NEW POST')
                ->assertPathIs('/post/create')
                ->waitFor('#cke_content')
                ->type('title', 'Test Create Post')
                ->select('status', 1)
                ->script('CKEDITOR.instances.content.setData( "test content post" );');

                $browser->press('SAVE')
                ->assertPathIs('/post')
                ->assertSee('Test Create Post');
        });
    }

    public function testEditPost()
    {
        $post = Post::factory()->create();

        $this->browse(function (Browser $browser) use ($post) {
            $browser->visitRoute('post.edit', $post->id)
                ->assertSee('EDIT POST')
                ->assertPathIs('/post/'. $post->id.'/edit')
                ->waitFor('#cke_content')
                ->type('title', 'Test Update Post')
                ->select('status', 1)
                ->script('CKEDITOR.instances.content.setData( "test update content post" );');

            $browser->press('UPDATE')
                ->assertPathIs('/post')
                ->assertSee('Test Update Post');
        });
    }
}
