<?php

namespace Database\Factories\Domains\Webmention\Models;

use App\Domains\Webmention\Models\KnownDomain;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KnownDomain>
 */
class KnownDomainFactory extends Factory
{
   protected $model = KnownDomain::class;

    public function definition(): array
    {
        $domain = $this->faker->domainName();
        $supports = $this->faker->boolean();

        return [
            'domain' => $domain,
            'supports_webmentions' => $supports,
            'outbound_webmentions_enabled' => false,
            'inbound_webmentions_enabled' => false,
        ];
    }

    public function webmentionRocks(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'domain' => 'webmention.rocks',
                'supports_webmentions' => true,
                'outbound_webmentions_enabled' => true,
                'inbound_webmentions_enabled' => false,
            ];
        });
    }
}
