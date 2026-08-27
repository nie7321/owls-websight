<?php

namespace Database\Factories\Domains\Webmention\Models;

use App\Domains\Blog\Models\BlogPost;
use App\Domains\Webmention\Enums\Mf2LinkType;
use App\Domains\Webmention\Models\BlogPostLinks;
use App\Domains\Webmention\Models\KnownDomain;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BlogPostLinks>
 */
class BlogPostLinksFactory extends Factory
{
    protected $model = BlogPostLinks::class;

    public function definition(): array
    {
        return [
            'blog_post_id' => BlogPost::factory(),
            'known_domain_id' => KnownDomain::factory(),
            'url' => $this->faker->url(),
            'mf2_type' => $this->faker->optional()->randomElement(Mf2LinkType::cases()),
            'webmention_attempts' => 0,
            'webmention_last_attempt_at' => null,
            'webmention_success_at' => null,
        ];
    }
}
