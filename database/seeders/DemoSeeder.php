<?php

namespace Database\Seeders;

use App\Domains\Auth\Models\User;
use App\Domains\Blog\Models\BlogPost;
use App\Domains\Blog\Models\LinkCategory;
use App\Domains\Blog\Models\RelationshipType;
use App\Domains\Blog\Models\Tag;
use App\Domains\Opml\Models\ExternalOpmlList;
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

        $this->posts($owls);
        $this->links();
        $this->opmlFeeds();
    }

    protected function links(): void
    {
        LinkCategory::first()
            ->links()
            ->create([
                'url' => 'https://mastodon.yshi.org/@owls',
                'title' => 'owls on mastodon',
                'description' => 'it me, mario',
                'auto_update_card' => false,
            ])
            ->relationships()
            ->attach(RelationshipType::inRandomOrder()->get()->take(2));
    }
    protected function posts(User $author): void
    {
        // Text heavy post
        $this->post(
            [
                'title' => 'Thoughts on the OneDND Licensing Announcement',
                'content' => file_get_contents(database_path('fixtures/one-dnd-license.md')),
                'published_at' => Carbon::now(),
            ],
            ['demiplane', "dm's guild", 'dnd', 'dnd 5e', 'dnd beyond', 'one dnd'],
            $author
        );

        // Table of contents
        $this->post(
            [
                'title' => 'Review of the Fediverse',
                'content' => file_get_contents(database_path('fixtures/fediverse-review.md')),
                'published_at' => Carbon::now()->subWeeks(2),
            ],
            ['fediverse', 'mastodon', 'social media'],
            $author
        );

        // Code
        $this->post(
            [
                'title' => 'Instantiating an Abstract Class with Dependency Injection',
                'content' => file_get_contents(database_path('fixtures/abstract-class.md')),
                'published_at' => Carbon::now()->subMonths(2),
            ],
            ['php', 'laravel', '🔥 tip'],
            $author
        );
    }

    protected function post(array $data, array $tags, User $author): void
    {
        $firstLine = preg_split('/\r?\n/', $data['content'], 2)[0];

        $post = BlogPost::create([
            ...$data,
            'summary' => $firstLine,
            'slug' => Str::slug($data['title']),
            'author_user_id' => $author->id
        ]);

        $tags = collect($tags)->map(fn (string $tag) => Tag::create(['slug' => $tag, 'label' => $tag]));
        $post->tags()->sync($tags->map->id);
    }

    protected function opmlFeeds(): void
    {
        ExternalOpmlList::create([
            'label' => 'Blaugust 2024',
            'url' => 'https://aggronaut.com/Blaugust2024Feeds.opml',
            'output_filename' => 'Blaugust2024Feeds.opml',
            'docs_url' => null,
            'active' => true,
        ]);
    }
}
