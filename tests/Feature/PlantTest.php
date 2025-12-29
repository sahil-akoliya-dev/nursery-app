<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Plant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlantTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $category;
    protected $plant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
        $this->plant = Plant::factory()->create(['category_id' => $this->category->id]);
    }

    public function test_can_view_plants_index_page()
    {
        $response = $this->get(route('plants.index'));
        $response->assertStatus(200);
        $response->assertViewIs('plants.index');
    }

    public function test_can_view_individual_plant_page()
    {
        $response = $this->get(route('plants.show', $this->plant));
        $response->assertStatus(200);
        $response->assertViewIs('plants.show');
        $response->assertSee($this->plant->name);
    }

    public function test_can_filter_plants_by_category()
    {
        $anotherCategory = Category::factory()->create();
        $anotherPlant = Plant::factory()->create(['category_id' => $anotherCategory->id]);
        
        $response = $this->get(route('plants.index', ['category' => $this->category->slug]));
        $response->assertStatus(200);
        $response->assertSee($this->plant->name);
        $response->assertDontSee($anotherPlant->name);
    }

    public function test_can_search_plants_by_name()
    {
        $searchTerm = substr($this->plant->name, 0, 5);
        
        $response = $this->get(route('plants.index', ['q' => $searchTerm]));
        $response->assertStatus(200);
        $response->assertSee($this->plant->name);
    }

    public function test_can_filter_plants_by_price_range()
    {
        $expensivePlant = Plant::factory()->create(['price' => 100.00]);
        $cheapPlant = Plant::factory()->create(['price' => 10.00]);
        
        $response = $this->get(route('plants.index', ['min_price' => 50, 'max_price' => 150]));
        $response->assertStatus(200);
        $response->assertSee($expensivePlant->name);
        $response->assertDontSee($cheapPlant->name);
    }

    public function test_can_sort_plants_by_price()
    {
        $expensivePlant = Plant::factory()->create(['price' => 100.00]);
        $cheapPlant = Plant::factory()->create(['price' => 10.00]);
        
        $response = $this->get(route('plants.index', ['sort' => 'price_asc']));
        $response->assertStatus(200);
        
        $plants = $response->viewData('plants');
        $this->assertEquals($cheapPlant->id, $plants->first()->id);
    }
}
