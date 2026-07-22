<?php

declare(strict_types=1);

namespace Database\Factories\Domains\Blog\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Blog\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition(): array
    {
        $content = $this->faker->paragraphs();

        return [
            'slug' => $this->faker->unique()->slug(),
            'title' => $this->faker->sentence(),
            'content' => implode("\n\n", $content),
            'summary' => $content[0],
            'author_user_id' => User::factory(),
            'published_at' => $this->faker->dateTimeThisDecade(),
        ];
    }
}
