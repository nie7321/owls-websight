<?php

namespace Database\Seeders;

use App\Domains\Auth\Models\User;
use App\Domains\Blog\Models\BlogPost;
use App\Domains\Blog\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $owls = User::factory()->createOne([
            'email' => 'nick@godless-internets.org',
            'name' => 'owls',
            'is_admin' => true,
        ]);

        $post = BlogPost::create([
            'title' => 'Test Blog Post',
            'slug' => Str::slug('Test Blog Post'),
            'content' => file_get_contents(database_path('fixtures/test-blog-post.md')),
            'author_user_id' => $owls->id,
            'published_at' => Carbon::now(),
        ]);

        $tags = collect(['demiplane', "dm's guild", 'dnd', 'dnd 5e', 'dnd beyond', 'one dnd'])
            ->map(fn (string $tag) => Tag::create(['slug' => $tag, 'label' => $tag]));

        $post->tags()->sync($tags->map->id);
    }
}
