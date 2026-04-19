<?php

namespace Database\Factories\Domains\Portal\Models;

use App\Domains\Portal\Models\PortalCharacter;
use Illuminate\Database\Eloquent\Factories\Factory;

class PortalCharacterFactory extends Factory
{
    protected $model = PortalCharacter::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug(),
            'name' => $this->faker->name(),
            'short_description' => $this->faker->text(),
            'description' => $this->faker->text(),
        ];
    }
}
