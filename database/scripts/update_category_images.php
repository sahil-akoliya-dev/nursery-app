<?php

require __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use Illuminate\Support\Facades\DB;

echo "Starting category image update...\n\n";

// Map category slugs to image filenames
$imageMapping = [
    'indoor-plants' => 'indoor_plants.png',
    'outdoor-plants' => 'outdoor_plants.png',
    'succulents' => 'succulents.png',
    'flowering-plants' => 'flowering_plants.png',
    'herbs' => 'herbs.png',
];

DB::beginTransaction();

try {
    $updated = 0;
    $notFound = [];

    foreach ($imageMapping as $slug => $imageFile) {
        $category = Category::where('slug', $slug)->first();

        if ($category) {
            // Create image path
            $imagePath = '/images/categories/' . $imageFile;

            // Update category with new image
            $category->image = $imagePath;
            $category->save();

            echo "✓ Updated: {$category->name}\n";
            echo "  Image: {$imagePath}\n\n";
            $updated++;
        } else {
            $notFound[] = $slug;
            echo "✗ Category not found: {$slug}\n\n";
        }
    }

    DB::commit();

    echo "\n" . str_repeat("=", 50) . "\n";
    echo "Update Summary:\n";
    echo str_repeat("=", 50) . "\n";
    echo "✓ Successfully updated: {$updated} categories\n";

    if (!empty($notFound)) {
        echo "✗ Not found: " . count($notFound) . " categories\n";
        echo "  Missing slugs: " . implode(', ', $notFound) . "\n";
    }

    echo "\nAll category images have been updated successfully!\n";

} catch (Exception $e) {
    DB::rollBack();
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    echo "Transaction rolled back.\n";
    exit(1);
}
