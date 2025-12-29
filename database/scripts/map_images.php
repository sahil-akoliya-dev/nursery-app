<?php

use App\Models\Product;

echo "Starting Image Mapping...\n";

$products = Product::all();
$updated = 0;

foreach ($products as $product) {
    $name = strtolower($product->name);
    $imagePath = '/assets/images/products/monstera.png'; // Default

    if (str_contains($name, 'banana')) {
        $imagePath = '/assets/images/products/nano-banana.png';
    } elseif (str_contains($name, 'succulent') || str_contains($name, 'cactus') || str_contains($name, 'aloe') || str_contains($name, 'echeveria')) {
        $imagePath = '/assets/images/products/succulent.png';
    } elseif (str_contains($name, 'monstera') || str_contains($name, 'cheese') || str_contains($name, 'deliciosa')) {
        $imagePath = '/assets/images/products/monstera.png';
    }

    // Update the image
    // Assuming 'images' is cast to array or json in Model
    $product->images = [$imagePath];
    $product->save();
    $updated++;

    echo "Updated {$product->name} -> $imagePath\n";
}

echo "Complete! Updated $updated products.\n";
