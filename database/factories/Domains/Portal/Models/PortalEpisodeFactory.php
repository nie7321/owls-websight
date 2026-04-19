<?php

namespace Database\Factories\Domains\Portal\Models;

use App\Domains\Portal\Models\PortalEpisode;
use App\Domains\Portal\Models\PortalSeason;
use Illuminate\Database\Eloquent\Factories\Factory;

class PortalEpisodeFactory extends Factory
{
    protected $model = PortalEpisode::class;

    public function definition(): array
    {
        return [
            'portal_season_id' => PortalSeason::factory(),
            'episode_number' => $this->faker->numberBetween(1, 100),
            'name' => $this->faker->name(),
            'air_date' => $this->faker->date(),
            'watch_url' => $this->faker->url(),
            'g4_description' => $this->faker->text(),
            'short_description' => $this->faker->text(),
            'full_description' => $this->faker->text(),
        ];
    }
}
