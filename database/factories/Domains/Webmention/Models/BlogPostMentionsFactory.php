<?php

namespace Database\Factories\Domains\Webmention\Models;

use App\Domains\Blog\Models\BlogPost;
use App\Domains\Webmention\Enums\Mf2LinkType;
use App\Domains\Webmention\Models\BlogPostMentions;
use App\Domains\Webmention\Models\KnownDomain;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BlogPostMentions>
 */
class BlogPostMentionsFactory extends Factory
{
    protected $model = BlogPostMentions::class;

    public function definition(): array
    {
        return [
            'blog_post_id' => BlogPost::factory(),
            'known_domain_id' => KnownDomain::factory(),
            'url' => $this->faker->url(),
            'verification_attempts' => 0,
            'verification_last_attempted_at' => null,
            'verification_success_at' => null,
            'mf2_type' => $this->faker->optional()->randomElement(Mf2LinkType::cases()),
        ];
    }
}
