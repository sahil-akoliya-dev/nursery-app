<?php

require __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\DB;

echo "Starting product image update...\n\n";

// Map product slugs to image filenames
$imageMapping = [
    // Indoor Plants
    'monstera-deliciosa' => 'monstera_deliciosa.png',
    'snake-plant' => 'snake_plant.png',
    'fiddle-leaf-fig' => 'fiddle_leaf_fig.png',
    'pothos-devils-ivy' => 'pothos.png',
    'peace-lily' => 'peace_lily.png',
    'zz-plant' => 'zz_plant.png',

    // Outdoor Plants
    'hybrid-tea-rose-bush' => 'hybrid_tea_rose.png',
    'lavender-plant' => 'lavender.png',
    'hydrangea-bush' => 'hydrangea.png',

    // Succulents
    'aloe-vera' => 'aloe_vera.png',
    'jade-plant' => 'jade_plant.png',
    'echeveria-succulent' => 'echeveria.png',

    // Herbs
    'sweet-basil-plant' => 'sweet_basil.png',
    'mint-plant' => 'mint.png',

    // Flowering Plants
    'phalaenopsis-orchid' => 'phalaenopsis_orchid.png',
    'african-violet' => 'african_violet.png',
    'anthurium' => 'anthurium.png',
];

DB::beginTransaction();

try {
    $updated = 0;
    $notFound = [];

    foreach ($imageMapping as $slug => $imageFile) {
        $product = Product::where('slug', $slug)->first();

        if ($product) {
            // Create image path array
            $imagePath = '/images/products/' . $imageFile;

            // Update product with new image (Laravel will auto-encode to JSON)
            $product->images = [$imagePath];
            $product->save();

            echo "✓ Updated: {$product->name}\n";
            echo "  Image: {$imagePath}\n\n";
            $updated++;
        } else {
            $notFound[] = $slug;
            echo "✗ Product not found: {$slug}\n\n";
        }
    }

    DB::commit();

    echo "\n" . str_repeat("=", 50) . "\n";
    echo "Update Summary:\n";
    echo str_repeat("=", 50) . "\n";
    echo "✓ Successfully updated: {$updated} products\n";

    if (!empty($notFound)) {
        echo "✗ Not found: " . count($notFound) . " products\n";
        echo "  Missing slugs: " . implode(', ', $notFound) . "\n";
    }

    echo "\nAll product images have been updated successfully!\n";

} catch (Exception $e) {
    DB::rollBack();
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    echo "Transaction rolled back.\n";
    exit(1);
}
