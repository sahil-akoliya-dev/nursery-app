<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plant>
 */
class PlantFactory extends Factory
{
    /**
     * Plant names for realistic data
     */
    private array $plantNames = [
        'Monstera Deliciosa', 'Snake Plant', 'Pothos', 'ZZ Plant', 'Peace Lily',
        'Fiddle Leaf Fig', 'Philodendron', 'Spider Plant', 'Rubber Plant', 'Aloe Vera',
        'Jade Plant', 'English Ivy', 'Boston Fern', 'Bamboo Palm', 'African Violet',
        'Lavender', 'Rosemary', 'Basil', 'Mint', 'Oregano',
        'Sunflower', 'Marigold', 'Petunia', 'Begonia', 'Geranium',
        'Rose Bush', 'Hydrangea', 'Lily', 'Tulip', 'Daffodil'
    ];

    /**
     * Scientific names corresponding to plant names
     */
    private array $scientificNames = [
        'Monstera deliciosa', 'Sansevieria trifasciata', 'Epipremnum aureum', 'Zamioculcas zamiifolia', 'Spathiphyllum',
        'Ficus lyrata', 'Philodendron hederaceum', 'Chlorophytum comosum', 'Ficus elastica', 'Aloe barbadensis',
        'Crassula ovata', 'Hedera helix', 'Nephrolepis exaltata', 'Chamaedorea seifrizii', 'Saintpaulia',
        'Lavandula', 'Rosmarinus officinalis', 'Ocimum basilicum', 'Mentha', 'Origanum vulgare',
        'Helianthus annuus', 'Tagetes', 'Petunia × atkinsiana', 'Begonia', 'Pelargonium',
        'Rosa', 'Hydrangea macrophylla', 'Lilium', 'Tulipa', 'Narcissus'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $index = fake()->numberBetween(0, count($this->plantNames) - 1);
        $name = $this->plantNames[$index];
        $scientificName = $this->scientificNames[$index] ?? null;

        $price = fake()->randomFloat(2, 10, 150);
        $hasSale = fake()->boolean(25);

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 9999),
            'scientific_name' => $scientificName,
            'description' => fake()->paragraph(6),
            'short_description' => fake()->sentence(12),
            'price' => $price,
            'sale_price' => $hasSale ? fake()->randomFloat(2, $price * 0.6, $price * 0.85) : null,
            'stock_quantity' => fake()->numberBetween(5, 50),
            'in_stock' => true, // Will be set correctly based on stock_quantity
            'is_featured' => fake()->boolean(25),
            'is_active' => true,
            'sku' => 'PLT-' . strtoupper(Str::random(8)),
            'images' => [
                'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=500',
                'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?w=500'
            ],
            'care_instructions' => [
                'water' => fake()->randomElement(['Weekly', 'Every 3 days', 'Daily', 'When soil is dry']),
                'light' => fake()->randomElement(['Bright indirect', 'Direct sunlight', 'Low light', 'Partial shade']),
                'humidity' => fake()->randomElement(['High', 'Medium', 'Low']),
            ],
            'plant_characteristics' => [
                'height' => fake()->randomElement(['6-12 inches', '1-3 feet', '3-6 feet', '6+ feet']),
                'toxicity' => fake()->randomElement(['Pet safe', 'Mildly toxic', 'Toxic']),
                'maintenance' => fake()->randomElement(['Low', 'Medium', 'High']),
            ],
            'plant_type' => fake()->randomElement(['indoor', 'outdoor', 'succulent']),
            'category_id' => Category::factory(),
        ];
    }

    /**
     * Indicate that the plant is indoor.
     */
    public function indoor(): static
    {
        return $this->state(fn (array $attributes) => [
            'plant_type' => 'indoor',
        ]);
    }

    /**
     * Indicate that the plant is outdoor.
     */
    public function outdoor(): static
    {
        return $this->state(fn (array $attributes) => [
            'plant_type' => 'outdoor',
        ]);
    }

    /**
     * Indicate that the plant is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}

