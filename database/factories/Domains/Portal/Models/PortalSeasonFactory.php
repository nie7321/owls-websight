<?php

namespace Database\Factories\Domains\Portal\Models;

use App\Domains\Portal\Models\PortalSeason;
use Illuminate\Database\Eloquent\Factories\Factory;

class PortalSeasonFactory extends Factory
{
    protected $model = PortalSeason::class;

    public function definition(): array
    {
        return [
            'season_number' => $this->faker->unique()->randomDigit(),
            'name' => $this->faker->streetName(),
        ];
    }
}
