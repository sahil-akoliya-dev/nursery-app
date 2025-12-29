<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $indoorCategory = Category::where('slug', 'indoor-plants')->first();
        $outdoorCategory = Category::where('slug', 'outdoor-plants')->first();
        $succulentCategory = Category::where('slug', 'succulents')->first();
        $floweringCategory = Category::where('slug', 'flowering-plants')->first();
        $herbCategory = Category::where('slug', 'herbs')->first();

        $products = [
            // Indoor Plants - Premium Collection
            [
                'name' => 'Monstera Deliciosa',
                'slug' => 'monstera-deliciosa',
                'description' => 'The Monstera Deliciosa, affectionately known as the Swiss Cheese Plant, is a stunning tropical houseplant that brings the lush beauty of the rainforest into your home. With its iconic large, glossy leaves featuring natural fenestrations (holes), this plant makes a bold architectural statement in any interior space. Native to Central American rainforests, the Monstera thrives in bright, indirect light and appreciates regular watering when the top inch of soil becomes dry. As it matures, the leaves develop more dramatic splits and holes, creating an ever-evolving display of natural artistry. Perfect for plant enthusiasts who want to make a statement, this easy-care beauty can grow quite large indoors, reaching heights of 6-8 feet with proper care.',
                'short_description' => 'Iconic tropical plant with stunning split leaves',
                'price' => 29.99,
                'sale_price' => null,
                'stock_quantity' => 15,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'sku' => 'MON-001',
                'category_id' => $indoorCategory->id,
                'care_instructions' => [
                    'Water when the top 2 inches of soil feels dry to touch',
                    'Place in bright, indirect light near east or west-facing window',
                    'Mist leaves 2-3 times per week for humidity',
                    'Fertilize monthly during spring and summer with balanced liquid fertilizer',
                    'Wipe leaves with damp cloth to remove dust and promote photosynthesis',
                    'Provide moss pole or stake for climbing support'
                ],
                'plant_characteristics' => [
                    'height' => '6-8 feet indoors',
                    'light_requirements' => 'Bright, indirect light',
                    'water_needs' => 'Moderate - weekly watering',
                    'toxicity' => 'Toxic to pets and humans if ingested',
                    'humidity' => '60-80% preferred',
                    'temperature' => '65-85°F (18-29°C)'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1614594975525-e45890e2e122?w=800&q=80',
                    'https://images.unsplash.com/photo-1614594895304-fe7116ac3b58?w=800&q=80',
                    'https://images.unsplash.com/photo-1614594975525-e45890e2e122?w=800&q=80'
                ]
            ],
            [
                'name' => 'Snake Plant (Sansevieria)',
                'slug' => 'snake-plant',
                'description' => 'The Snake Plant, also called Mother-in-Law\'s Tongue or Sansevieria, is the ultimate low-maintenance houseplant and a favorite among beginners and busy plant parents. This architectural wonder features striking upright leaves with beautiful variegated patterns in shades of green and yellow. Renowned for its air-purifying qualities, the Snake Plant removes toxins like formaldehyde and benzene from indoor air, making it an excellent choice for bedrooms and offices. Incredibly resilient, it tolerates low light conditions, irregular watering, and a wide range of temperatures. NASA studies have shown it\'s one of the best plants for improving indoor air quality, even producing oxygen at night. Whether you\'re a forgetful waterer or new to plant care, this hardy beauty will thrive with minimal attention.',
                'short_description' => 'Nearly indestructible air-purifying plant',
                'price' => 19.99,
                'sale_price' => 16.99,
                'stock_quantity' => 25,
                'in_stock' => true,
                'is_featured' => false,
                'is_active' => true,
                'sku' => 'SNA-001',
                'category_id' => $indoorCategory->id,
                'care_instructions' => [
                    'Water every 2-3 weeks, allowing soil to dry completely between waterings',
                    'Tolerates low to bright indirect light - very adaptable',
                    'Use well-draining cactus or succulent soil mix',
                    'Fertilize sparingly, only 2-3 times during growing season',
                    'Wipe leaves occasionally to remove dust',
                    'Avoid overwatering - root rot is the main concern'
                ],
                'plant_characteristics' => [
                    'height' => '2-4 feet',
                    'light_requirements' => 'Low to bright indirect light',
                    'water_needs' => 'Very low - drought tolerant',
                    'toxicity' => 'Mildly toxic to pets if ingested',
                    'humidity' => 'Average household humidity',
                    'temperature' => '55-85°F (13-29°C)'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1599598425947-70e021653e5c?w=800&q=80',
                    'https://images.unsplash.com/photo-1593482892290-f54927ae1bb6?w=800&q=80',
                    'https://images.unsplash.com/photo-1599598424975-c4b9c0b9f8c9?w=800&q=80'
                ]
            ],
            [
                'name' => 'Fiddle Leaf Fig',
                'slug' => 'fiddle-leaf-fig',
                'description' => 'The Fiddle Leaf Fig is the crown jewel of indoor plants, beloved by interior designers and plant enthusiasts worldwide for its dramatic, violin-shaped leaves and sculptural presence. This stunning tree-like plant features large, deeply veined leaves that can grow up to 15 inches long, creating an impressive focal point in any room. Native to West African rainforests, the Fiddle Leaf Fig has become an Instagram sensation and a must-have for modern homes. While it has a reputation for being finicky, with the right care and consistent conditions, it will reward you with lush, vibrant growth. The key to success is finding the perfect spot with bright, filtered light and maintaining a regular watering schedule. Once established, this majestic plant can grow 6-10 feet tall indoors, transforming your space into a sophisticated urban jungle.',
                'short_description' => 'Dramatic statement plant with violin-shaped leaves',
                'price' => 49.99,
                'sale_price' => null,
                'stock_quantity' => 8,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'sku' => 'FID-001',
                'category_id' => $indoorCategory->id,
                'care_instructions' => [
                    'Water when top 2 inches of soil is dry - consistency is key',
                    'Needs bright, filtered light - avoid direct sun which can scorch leaves',
                    'Rotate plant 90 degrees weekly for even growth',
                    'Wipe leaves with damp cloth monthly to remove dust',
                    'Fertilize monthly during growing season with diluted liquid fertilizer',
                    'Avoid moving once established - dislikes change',
                    'Maintain consistent temperature and avoid drafts'
                ],
                'plant_characteristics' => [
                    'height' => '6-10 feet indoors',
                    'light_requirements' => 'Bright, filtered indirect light',
                    'water_needs' => 'Moderate - consistent watering schedule',
                    'toxicity' => 'Toxic to pets and humans',
                    'humidity' => '30-65% - appreciates occasional misting',
                    'temperature' => '60-75°F (15-24°C)'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1612435889250-5283e22032e7?w=800&q=80',
                    'https://images.unsplash.com/photo-1614594975525-e45890e2e122?w=800&q=80',
                    'https://images.unsplash.com/photo-1614594975525-e45890e2e122?w=800&q=80'
                ]
            ],
            [
                'name' => 'Pothos (Devil\'s Ivy)',
                'slug' => 'pothos-devils-ivy',
                'description' => 'The Pothos, affectionately called Devil\'s Ivy for its ability to stay green even in the dark, is one of the most forgiving and versatile houseplants available. This trailing beauty features heart-shaped leaves in various shades of green, often with stunning yellow or white variegation. Perfect for beginners, the Pothos thrives on neglect and can adapt to almost any indoor environment. Its cascading vines can grow several feet long, making it ideal for hanging baskets, shelves, or training up a moss pole. Beyond its good looks, Pothos is an excellent air purifier, removing indoor pollutants like formaldehyde and benzene. Whether you want a lush hanging plant or a climbing specimen, this adaptable beauty will flourish with minimal care.',
                'short_description' => 'Easy-care trailing plant perfect for beginners',
                'price' => 14.99,
                'sale_price' => 12.99,
                'stock_quantity' => 35,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'sku' => 'POT-001',
                'category_id' => $indoorCategory->id,
                'care_instructions' => [
                    'Water when soil is dry 1-2 inches down',
                    'Thrives in low to bright indirect light',
                    'Trim leggy vines to encourage bushier growth',
                    'Propagates easily in water from stem cuttings',
                    'Fertilize every 2-3 months during growing season',
                    'Wipe leaves occasionally to keep them glossy'
                ],
                'plant_characteristics' => [
                    'height' => 'Vines can grow 6-10 feet long',
                    'light_requirements' => 'Low to bright indirect light',
                    'water_needs' => 'Low to moderate',
                    'toxicity' => 'Toxic to pets and humans',
                    'humidity' => 'Average household humidity',
                    'temperature' => '65-85°F (18-29°C)'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1593482892290-f54927ae1bb6?w=800&q=80',
                    'https://images.unsplash.com/photo-1614594975525-e45890e2e122?w=800&q=80',
                    'https://images.unsplash.com/photo-1614594975525-e45890e2e122?w=800&q=80'
                ]
            ],
            [
                'name' => 'Peace Lily',
                'slug' => 'peace-lily',
                'description' => 'The Peace Lily is an elegant flowering houseplant that combines lush green foliage with stunning white blooms, bringing serenity and sophistication to any space. Known for its air-purifying prowess, this plant was featured in NASA\'s Clean Air Study for its ability to remove harmful toxins like ammonia, benzene, and formaldehyde from indoor air. The Peace Lily is remarkably communicative - its leaves will droop when it needs water, then perk right back up after a drink. Thriving in low to medium light, it\'s perfect for offices and rooms without bright windows. The elegant white spathes (often mistaken for flowers) appear throughout the year, adding a touch of grace to your indoor garden. This low-maintenance beauty is ideal for those seeking both aesthetic appeal and health benefits.',
                'short_description' => 'Elegant flowering plant with air-purifying qualities',
                'price' => 24.99,
                'sale_price' => null,
                'stock_quantity' => 18,
                'in_stock' => true,
                'is_featured' => false,
                'is_active' => true,
                'sku' => 'PEA-001',
                'category_id' => $indoorCategory->id,
                'care_instructions' => [
                    'Water when leaves begin to droop slightly',
                    'Prefers low to medium indirect light',
                    'Mist leaves regularly for humidity',
                    'Remove spent blooms and yellow leaves',
                    'Fertilize monthly during growing season',
                    'Keep away from cold drafts and heating vents'
                ],
                'plant_characteristics' => [
                    'height' => '1-3 feet',
                    'light_requirements' => 'Low to medium indirect light',
                    'water_needs' => 'Moderate - prefers consistently moist soil',
                    'toxicity' => 'Toxic to pets and humans',
                    'humidity' => '50-60% - appreciates misting',
                    'temperature' => '65-80°F (18-27°C)'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1593482892290-f54927ae1bb6?w=800&q=80',
                    'https://images.unsplash.com/photo-1614594975525-e45890e2e122?w=800&q=80'
                ]
            ],
            [
                'name' => 'ZZ Plant (Zamioculcas)',
                'slug' => 'zz-plant',
                'description' => 'The ZZ Plant is a modern marvel of the plant world, featuring glossy, waxy leaves that look almost artificial in their perfection. This virtually indestructible plant is perfect for those who travel frequently or tend to forget about their plants. Native to East Africa, the ZZ Plant stores water in its thick rhizomes, allowing it to survive weeks without watering. Its upright, architectural growth habit and deep green foliage make it a favorite in contemporary interiors and offices. The ZZ Plant tolerates low light better than almost any other houseplant and is resistant to pests and diseases. Whether you\'re a beginner or an experienced plant parent, this stunning, low-maintenance beauty will thrive with minimal attention while adding a touch of modern elegance to your space.',
                'short_description' => 'Virtually indestructible plant with glossy leaves',
                'price' => 27.99,
                'sale_price' => null,
                'stock_quantity' => 22,
                'in_stock' => true,
                'is_featured' => false,
                'is_active' => true,
                'sku' => 'ZZ-001',
                'category_id' => $indoorCategory->id,
                'care_instructions' => [
                    'Water every 2-3 weeks - allow soil to dry completely',
                    'Tolerates low to bright indirect light',
                    'Use well-draining potting mix',
                    'Fertilize 2-3 times during growing season',
                    'Wipe leaves monthly to maintain glossy appearance',
                    'Avoid overwatering - rhizomes store water'
                ],
                'plant_characteristics' => [
                    'height' => '2-3 feet',
                    'light_requirements' => 'Low to bright indirect light',
                    'water_needs' => 'Very low - drought tolerant',
                    'toxicity' => 'Toxic to pets and humans',
                    'humidity' => 'Average household humidity',
                    'temperature' => '60-75°F (15-24°C)'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1614594975525-e45890e2e122?w=800&q=80',
                    'https://images.unsplash.com/photo-1593482892290-f54927ae1bb6?w=800&q=80'
                ]
            ],

            // Outdoor Plants - Garden Collection
            [
                'name' => 'Hybrid Tea Rose Bush',
                'slug' => 'hybrid-tea-rose-bush',
                'description' => 'The Hybrid Tea Rose is the quintessential garden rose, renowned for its large, perfectly formed blooms and intoxicating fragrance. These classic beauties produce elegant flowers on long stems, making them ideal for cutting and creating stunning bouquets. Each bloom unfolds slowly, revealing layers of velvety petals in rich, vibrant colors. Hybrid Tea Roses are the result of careful breeding to combine the best traits of different rose varieties - large blooms, strong fragrance, and continuous flowering throughout the growing season. While they require more care than some other roses, the reward is spectacular blooms from late spring through fall. Perfect for formal gardens, rose beds, or as a focal point, these roses will transform your outdoor space into a romantic paradise. With proper care including regular watering, feeding, and pruning, your rose bush will provide years of breathtaking beauty.',
                'short_description' => 'Classic garden rose with large, fragrant blooms',
                'price' => 39.99,
                'sale_price' => 34.99,
                'stock_quantity' => 12,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'sku' => 'ROS-001',
                'category_id' => $outdoorCategory->id,
                'care_instructions' => [
                    'Plant in well-draining, nutrient-rich soil',
                    'Water deeply 2-3 times per week during growing season',
                    'Prune in late winter to encourage new growth',
                    'Fertilize every 4-6 weeks during blooming season',
                    'Deadhead spent blooms to encourage more flowers',
                    'Apply mulch around base to retain moisture',
                    'Monitor for pests like aphids and treat promptly'
                ],
                'plant_characteristics' => [
                    'height' => '3-5 feet',
                    'light_requirements' => 'Full sun (6+ hours daily)',
                    'water_needs' => 'Moderate to high',
                    'toxicity' => 'Non-toxic',
                    'bloom_time' => 'Late spring through fall',
                    'hardiness' => 'USDA zones 5-9'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1518621736915-f3b1c41bfd00?w=800&q=80',
                    'https://images.unsplash.com/photo-1490750967868-88aa4486c946?w=800&q=80',
                    'https://images.unsplash.com/photo-1518621736915-f3b1c41bfd00?w=800&q=80'
                ]
            ],
            [
                'name' => 'Lavender Plant',
                'slug' => 'lavender-plant',
                'description' => 'Lavender is a beloved Mediterranean herb that brings beauty, fragrance, and versatility to any garden. With its silvery-green foliage and stunning purple flower spikes, lavender creates a romantic, cottage-garden atmosphere while attracting beneficial pollinators like bees and butterflies. Beyond its ornamental value, lavender is prized for its aromatic essential oils, used in aromatherapy, cooking, and crafts. The flowers can be harvested and dried for sachets, potpourri, or culinary use. This drought-tolerant perennial thrives in sunny locations with well-draining soil, making it perfect for water-wise gardens. Plant lavender along pathways to release its calming scent when brushed against, or create a stunning lavender hedge. Once established, it requires minimal care and will reward you with fragrant blooms year after year.',
                'short_description' => 'Fragrant Mediterranean herb with purple blooms',
                'price' => 16.99,
                'sale_price' => null,
                'stock_quantity' => 28,
                'in_stock' => true,
                'is_featured' => false,
                'is_active' => true,
                'sku' => 'LAV-001',
                'category_id' => $outdoorCategory->id,
                'care_instructions' => [
                    'Plant in full sun location',
                    'Use well-draining, slightly alkaline soil',
                    'Water sparingly once established - drought tolerant',
                    'Prune after flowering to maintain shape',
                    'Avoid overwatering - prefers dry conditions',
                    'Harvest flowers in morning for strongest fragrance'
                ],
                'plant_characteristics' => [
                    'height' => '2-3 feet',
                    'light_requirements' => 'Full sun',
                    'water_needs' => 'Low - drought tolerant',
                    'toxicity' => 'Non-toxic, edible flowers',
                    'bloom_time' => 'Late spring to summer',
                    'hardiness' => 'USDA zones 5-9'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1499002238440-d264edd596ec?w=800&q=80',
                    'https://images.unsplash.com/photo-1595239244993-24b1c0bf5c1f?w=800&q=80'
                ]
            ],
            [
                'name' => 'Hydrangea Bush',
                'slug' => 'hydrangea-bush',
                'description' => 'Hydrangeas are show-stopping flowering shrubs that produce massive, globe-shaped flower clusters in stunning shades of blue, pink, purple, or white. These garden favorites are beloved for their long-lasting blooms that appear in summer and can be dried for beautiful arrangements. The flower color of some varieties can be influenced by soil pH - acidic soil produces blue flowers, while alkaline soil creates pink blooms. Hydrangeas are perfect for creating dramatic focal points, hedges, or foundation plantings. They thrive in partial shade and appreciate consistent moisture, making them ideal for those shady spots in your garden. With their lush foliage and spectacular blooms, hydrangeas bring a touch of elegance and old-world charm to any landscape.',
                'short_description' => 'Spectacular flowering shrub with large bloom clusters',
                'price' => 44.99,
                'sale_price' => 39.99,
                'stock_quantity' => 10,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'sku' => 'HYD-001',
                'category_id' => $outdoorCategory->id,
                'care_instructions' => [
                    'Plant in partial shade to morning sun',
                    'Keep soil consistently moist but not waterlogged',
                    'Mulch around base to retain moisture',
                    'Fertilize in spring with balanced fertilizer',
                    'Prune after flowering for best results',
                    'Adjust soil pH to influence flower color'
                ],
                'plant_characteristics' => [
                    'height' => '3-6 feet',
                    'light_requirements' => 'Partial shade to morning sun',
                    'water_needs' => 'Moderate to high',
                    'toxicity' => 'Toxic to pets',
                    'bloom_time' => 'Summer to fall',
                    'hardiness' => 'USDA zones 3-9'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1595239244993-24b1c0bf5c1f?w=800&q=80',
                    'https://images.unsplash.com/photo-1499002238440-d264edd596ec?w=800&q=80'
                ]
            ],

            // Succulents - Desert Collection
            [
                'name' => 'Aloe Vera',
                'slug' => 'aloe-vera',
                'description' => 'Aloe Vera is far more than just a pretty succulent - it\'s a living first-aid kit! This versatile plant has been used for thousands of years for its medicinal properties, particularly for soothing burns, cuts, and skin irritations. The thick, fleshy leaves contain a clear gel rich in vitamins, minerals, and antioxidants. Beyond its healing properties, Aloe Vera is an attractive, low-maintenance plant perfect for sunny windowsills, desks, or outdoor gardens in warm climates. Its architectural rosette of spiky leaves adds a modern, sculptural element to any space. Aloe Vera is incredibly easy to care for, requiring minimal water and thriving on neglect. It also produces offsets (baby plants) that can be separated and shared with friends. Whether you\'re growing it for its beauty or its benefits, Aloe Vera is a must-have plant.',
                'short_description' => 'Medicinal succulent with healing gel',
                'price' => 12.99,
                'sale_price' => null,
                'stock_quantity' => 30,
                'in_stock' => true,
                'is_featured' => false,
                'is_active' => true,
                'sku' => 'ALO-001',
                'category_id' => $succulentCategory->id,
                'care_instructions' => [
                    'Water every 2-3 weeks, allowing soil to dry completely',
                    'Needs bright, indirect light - can tolerate some direct sun',
                    'Use well-draining cactus or succulent soil mix',
                    'Fertilize sparingly, 2-3 times during growing season',
                    'Remove offsets to propagate new plants',
                    'Avoid overwatering - main cause of problems'
                ],
                'plant_characteristics' => [
                    'height' => '1-2 feet',
                    'light_requirements' => 'Bright, indirect to direct light',
                    'water_needs' => 'Very low - drought tolerant',
                    'toxicity' => 'Mildly toxic to pets, safe for topical use on humans',
                    'humidity' => 'Low - prefers dry conditions',
                    'temperature' => '55-80°F (13-27°C)'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1596547609652-9cf5d8d76921?w=800&q=80',
                    'https://images.unsplash.com/photo-1509587584298-0f3b3a3a1797?w=800&q=80',
                    'https://images.unsplash.com/photo-1596547609652-9cf5d8d76921?w=800&q=80'
                ]
            ],
            [
                'name' => 'Jade Plant',
                'slug' => 'jade-plant',
                'description' => 'The Jade Plant, also known as the Money Tree or Lucky Plant, is a beloved succulent that symbolizes prosperity and good fortune in many cultures. This charming plant features thick, glossy, oval-shaped leaves that resemble jade stones, growing on sturdy, woody stems that develop a tree-like appearance over time. With proper care, Jade Plants can live for decades, becoming treasured family heirlooms passed down through generations. They\'re incredibly easy to care for, requiring minimal water and thriving in bright light. Mature plants may even produce delicate white or pink star-shaped flowers in winter. The Jade Plant\'s compact size and sculptural form make it perfect for desks, shelves, or as a bonsai specimen. Whether you believe in its lucky properties or simply appreciate its beauty, this timeless succulent is a wonderful addition to any plant collection.',
                'short_description' => 'Lucky succulent symbolizing prosperity',
                'price' => 18.99,
                'sale_price' => 15.99,
                'stock_quantity' => 24,
                'in_stock' => true,
                'is_featured' => false,
                'is_active' => true,
                'sku' => 'JAD-001',
                'category_id' => $succulentCategory->id,
                'care_instructions' => [
                    'Water when soil is completely dry - every 2-3 weeks',
                    'Needs bright light, can tolerate some direct sun',
                    'Use cactus/succulent potting mix',
                    'Prune to maintain desired shape',
                    'Fertilize monthly during growing season',
                    'Rotate plant for even growth'
                ],
                'plant_characteristics' => [
                    'height' => '1-3 feet',
                    'light_requirements' => 'Bright light to full sun',
                    'water_needs' => 'Very low',
                    'toxicity' => 'Toxic to pets',
                    'humidity' => 'Low',
                    'temperature' => '55-75°F (13-24°C)'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1509587584298-0f3b3a3a1797?w=800&q=80',
                    'https://images.unsplash.com/photo-1596547609652-9cf5d8d76921?w=800&q=80'
                ]
            ],
            [
                'name' => 'Echeveria Succulent',
                'slug' => 'echeveria-succulent',
                'description' => 'Echeveria succulents are nature\'s living sculptures, forming perfect rosettes of fleshy leaves in an stunning array of colors from pale green and blue-gray to pink, purple, and even black. These Mexican natives are among the most popular and collectible succulents, prized for their geometric beauty and low-maintenance care requirements. Each variety has its own unique characteristics, with some featuring ruffled edges, powdery coatings, or vibrant color tips. Echeverias are perfect for dish gardens, fairy gardens, or as standalone specimens in decorative pots. They produce tall flower stalks with bell-shaped blooms in shades of coral, pink, or yellow. These drought-tolerant beauties thrive in bright light and require minimal water, making them ideal for busy plant lovers or those new to succulents.',
                'short_description' => 'Geometric rosette succulent in stunning colors',
                'price' => 9.99,
                'sale_price' => null,
                'stock_quantity' => 40,
                'in_stock' => true,
                'is_featured' => false,
                'is_active' => true,
                'sku' => 'ECH-001',
                'category_id' => $succulentCategory->id,
                'care_instructions' => [
                    'Water every 2-3 weeks when soil is completely dry',
                    'Needs bright light - some varieties tolerate full sun',
                    'Use well-draining succulent soil',
                    'Avoid getting water on leaves to prevent rot',
                    'Remove dead lower leaves as plant grows',
                    'Propagate from leaf cuttings or offsets'
                ],
                'plant_characteristics' => [
                    'height' => '4-8 inches',
                    'light_requirements' => 'Bright light to full sun',
                    'water_needs' => 'Very low',
                    'toxicity' => 'Non-toxic',
                    'humidity' => 'Low',
                    'temperature' => '60-80°F (15-27°C)'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1509587584298-0f3b3a3a1797?w=800&q=80',
                    'https://images.unsplash.com/photo-1596547609652-9cf5d8d76921?w=800&q=80'
                ]
            ],

            // Herbs - Kitchen Garden Collection
            [
                'name' => 'Sweet Basil Plant',
                'slug' => 'sweet-basil-plant',
                'description' => 'Sweet Basil is the king of culinary herbs, essential for Italian cuisine and beloved by home cooks worldwide. This aromatic annual produces lush, fragrant leaves perfect for pesto, caprese salad, pasta dishes, and countless other recipes. Growing your own basil ensures you always have fresh, flavorful leaves at your fingertips, far superior to store-bought alternatives. The plant thrives in warm, sunny conditions and grows quickly, providing abundant harvests throughout the growing season. Regular harvesting actually encourages bushier growth and more leaves. Beyond its culinary uses, basil is attractive enough to grow as an ornamental, with some varieties featuring purple leaves or beautiful flowers. Plant basil in your kitchen garden, in containers on a sunny patio, or even on a bright windowsill for year-round fresh herbs.',
                'short_description' => 'Essential culinary herb for fresh cooking',
                'price' => 8.99,
                'sale_price' => null,
                'stock_quantity' => 20,
                'in_stock' => true,
                'is_featured' => false,
                'is_active' => true,
                'sku' => 'BAS-001',
                'category_id' => $herbCategory->id,
                'care_instructions' => [
                    'Water when soil surface feels dry - keep consistently moist',
                    'Needs 6-8 hours of direct sunlight daily',
                    'Pinch off flower buds to encourage leaf production',
                    'Harvest regularly by pinching off top leaves',
                    'Fertilize every 2-3 weeks with balanced fertilizer',
                    'Protect from cold temperatures - sensitive to frost'
                ],
                'plant_characteristics' => [
                    'height' => '1-2 feet',
                    'light_requirements' => 'Full sun (6-8 hours)',
                    'water_needs' => 'Moderate - keep soil moist',
                    'toxicity' => 'Non-toxic, edible',
                    'harvest_time' => '4-6 weeks from planting',
                    'growing_season' => 'Spring through fall'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1618375569909-3c92e63ccd61?w=800&q=80',
                    'https://images.unsplash.com/photo-1545165375-ce8d5e8b9f8e?w=800&q=80'
                ]
            ],
            [
                'name' => 'Rosemary Plant',
                'slug' => 'rosemary-plant',
                'description' => 'Rosemary is a fragrant, evergreen herb that brings the essence of the Mediterranean to your garden or kitchen. This woody perennial features needle-like leaves packed with aromatic oils, perfect for flavoring roasted meats, vegetables, bread, and infused oils. Beyond its culinary prowess, rosemary is steeped in history and symbolism, representing remembrance and fidelity. The plant produces delicate blue, purple, or white flowers that attract bees and butterflies. Rosemary is remarkably versatile - grow it as a hedge, in containers, or even train it as a topiary. It\'s drought-tolerant once established and thrives in sunny, well-drained locations. The fresh, pine-like scent is invigorating and can be used in aromatherapy. Whether you\'re an avid cook or simply appreciate beautiful, fragrant plants, rosemary is an excellent choice.',
                'short_description' => 'Aromatic Mediterranean herb for cooking',
                'price' => 11.99,
                'sale_price' => null,
                'stock_quantity' => 16,
                'in_stock' => true,
                'is_featured' => false,
                'is_active' => true,
                'sku' => 'ROS-002',
                'category_id' => $herbCategory->id,
                'care_instructions' => [
                    'Water sparingly - allow soil to dry between waterings',
                    'Needs full sun (6+ hours daily)',
                    'Use well-draining soil - avoid waterlogged conditions',
                    'Prune regularly to maintain shape and encourage growth',
                    'Harvest sprigs as needed, avoiding more than 1/3 at once',
                    'Protect from harsh winter winds in cold climates'
                ],
                'plant_characteristics' => [
                    'height' => '2-4 feet',
                    'light_requirements' => 'Full sun',
                    'water_needs' => 'Low - drought tolerant',
                    'toxicity' => 'Non-toxic, edible',
                    'harvest_time' => 'Year-round once established',
                    'hardiness' => 'USDA zones 7-10'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1545165375-ce8d5e8b9f8e?w=800&q=80',
                    'https://images.unsplash.com/photo-1618375569909-3c92e63ccd61?w=800&q=80'
                ]
            ],
            [
                'name' => 'Mint Plant',
                'slug' => 'mint-plant',
                'description' => 'Mint is an vigorous, refreshing herb that\'s perfect for teas, cocktails, desserts, and savory dishes. This fast-growing perennial is incredibly easy to cultivate, spreading enthusiastically through underground runners (which is why many gardeners prefer to grow it in containers). The aromatic leaves release their fresh, cooling scent when brushed or crushed, making mint a delightful sensory experience. There are many varieties to choose from, including peppermint, spearmint, chocolate mint, and apple mint, each with its own unique flavor profile. Mint is not only useful in the kitchen but also beneficial in the garden, attracting pollinators and repelling some pests. Harvest leaves regularly to encourage new growth and prevent flowering. Whether you\'re making mojitos, brewing tea, or garnishing desserts, fresh mint elevates any dish.',
                'short_description' => 'Refreshing herb for teas and cocktails',
                'price' => 7.99,
                'sale_price' => 6.99,
                'stock_quantity' => 32,
                'in_stock' => true,
                'is_featured' => false,
                'is_active' => true,
                'sku' => 'MIN-001',
                'category_id' => $herbCategory->id,
                'care_instructions' => [
                    'Water regularly to keep soil consistently moist',
                    'Grows in partial shade to full sun',
                    'Contain in pots to prevent aggressive spreading',
                    'Harvest frequently to encourage bushy growth',
                    'Pinch off flowers to focus energy on leaves',
                    'Divide plants every 2-3 years to maintain vigor'
                ],
                'plant_characteristics' => [
                    'height' => '1-2 feet',
                    'light_requirements' => 'Partial shade to full sun',
                    'water_needs' => 'Moderate to high',
                    'toxicity' => 'Non-toxic, edible',
                    'harvest_time' => 'Continuous throughout growing season',
                    'hardiness' => 'USDA zones 3-9'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1628556270448-4d4e4148e1b1?w=800&q=80',
                    'https://images.unsplash.com/photo-1545165375-ce8d5e8b9f8e?w=800&q=80'
                ]
            ],

            // Flowering Plants
            [
                'name' => 'Orchid (Phalaenopsis)',
                'slug' => 'phalaenopsis-orchid',
                'description' => 'Phalaenopsis orchids, commonly known as Moth Orchids, are the most popular and easiest orchids to grow indoors. These elegant plants produce stunning, long-lasting blooms that can persist for months, making them excellent value and a favorite gift. The gracefully arching flower spikes bear multiple blooms in colors ranging from pure white and soft pink to vibrant purple and yellow, often with intricate patterns and markings. Despite their exotic appearance, Phalaenopsis orchids are surprisingly easy to care for, requiring only bright, indirect light and weekly watering. They thrive in typical household temperatures and humidity levels. After the flowers fade, with proper care, the plant will rebloom, providing years of beauty. These sophisticated plants add a touch of luxury to any interior, whether displayed on a windowsill, desk, or as an elegant centerpiece.',
                'short_description' => 'Elegant orchid with long-lasting blooms',
                'price' => 34.99,
                'sale_price' => null,
                'stock_quantity' => 14,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'sku' => 'ORC-001',
                'category_id' => $floweringCategory->id,
                'care_instructions' => [
                    'Water weekly by soaking roots for 10-15 minutes',
                    'Place in bright, indirect light - avoid direct sun',
                    'Use orchid-specific potting mix (bark-based)',
                    'Fertilize monthly with diluted orchid fertilizer',
                    'Maintain 50-70% humidity if possible',
                    'Trim spent flower spikes to encourage reblooming'
                ],
                'plant_characteristics' => [
                    'height' => '1-2 feet including flower spike',
                    'light_requirements' => 'Bright, indirect light',
                    'water_needs' => 'Low to moderate',
                    'toxicity' => 'Non-toxic',
                    'bloom_time' => '2-6 months per flowering cycle',
                    'temperature' => '65-80°F (18-27°C)'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1550859492-d5da9d8e45f3?w=800&q=80',
                    'https://images.unsplash.com/photo-1551218808-94e220e084d2?w=800&q=80',
                    'https://images.unsplash.com/photo-1550859492-d5da9d8e45f3?w=800&q=80'
                ]
            ],
            [
                'name' => 'African Violet',
                'slug' => 'african-violet',
                'description' => 'African Violets are charming, compact flowering houseplants that bring continuous color to indoor spaces. These beloved plants produce clusters of delicate, velvety flowers in shades of purple, pink, white, or blue, set against fuzzy, dark green leaves. What makes African Violets special is their ability to bloom year-round with proper care, providing constant cheerful color on windowsills, desks, or shelves. They\'re perfect for small spaces and make excellent gifts. While they have a reputation for being finicky, success comes down to understanding their simple needs: consistent moisture (but never wet leaves), bright indirect light, and warm temperatures. Many enthusiasts become collectors, drawn to the hundreds of varieties available with different flower forms, colors, and leaf patterns. These compact beauties prove that good things come in small packages.',
                'short_description' => 'Compact plant with year-round colorful blooms',
                'price' => 13.99,
                'sale_price' => 11.99,
                'stock_quantity' => 26,
                'in_stock' => true,
                'is_featured' => false,
                'is_active' => true,
                'sku' => 'AFR-001',
                'category_id' => $floweringCategory->id,
                'care_instructions' => [
                    'Water from bottom to avoid wetting leaves',
                    'Keep soil consistently moist but not soggy',
                    'Provide bright, indirect light',
                    'Use African Violet-specific potting mix',
                    'Fertilize every 2 weeks with African Violet fertilizer',
                    'Remove spent flowers to encourage more blooms'
                ],
                'plant_characteristics' => [
                    'height' => '6-8 inches',
                    'light_requirements' => 'Bright, indirect light',
                    'water_needs' => 'Moderate - consistent moisture',
                    'toxicity' => 'Non-toxic',
                    'bloom_time' => 'Year-round with proper care',
                    'temperature' => '65-75°F (18-24°C)'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1551218808-94e220e084d2?w=800&q=80',
                    'https://images.unsplash.com/photo-1550859492-d5da9d8e45f3?w=800&q=80'
                ]
            ],
            [
                'name' => 'Anthurium',
                'slug' => 'anthurium',
                'description' => 'Anthuriums are striking tropical plants known for their glossy, heart-shaped "flowers" (actually modified leaves called spathes) in vibrant shades of red, pink, white, or coral. These exotic beauties bring a touch of the tropics to any interior, with their shiny, waxy blooms lasting for weeks or even months. The true flowers are the tiny bumps on the central spadix, while the colorful spathe provides the dramatic display. Native to Central and South American rainforests, Anthuriums appreciate warmth, humidity, and bright, indirect light. They\'re surprisingly easy to care for and bloom repeatedly throughout the year with proper conditions. The glossy, heart-shaped leaves are attractive even when the plant isn\'t flowering. Perfect for adding a bold pop of color to modern interiors, Anthuriums make sophisticated statement plants that never go out of style.',
                'short_description' => 'Tropical plant with glossy, heart-shaped blooms',
                'price' => 28.99,
                'sale_price' => null,
                'stock_quantity' => 11,
                'in_stock' => true,
                'is_featured' => false,
                'is_active' => true,
                'sku' => 'ANT-001',
                'category_id' => $floweringCategory->id,
                'care_instructions' => [
                    'Water when top inch of soil is dry',
                    'Needs bright, indirect light',
                    'Maintain high humidity (60-80%)',
                    'Use well-draining, peat-based potting mix',
                    'Fertilize monthly during growing season',
                    'Wipe leaves to keep them glossy'
                ],
                'plant_characteristics' => [
                    'height' => '1-2 feet',
                    'light_requirements' => 'Bright, indirect light',
                    'water_needs' => 'Moderate',
                    'toxicity' => 'Toxic to pets and humans',
                    'bloom_time' => 'Year-round with proper care',
                    'temperature' => '65-80°F (18-27°C)'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1551218808-94e220e084d2?w=800&q=80',
                    'https://images.unsplash.com/photo-1550859492-d5da9d8e45f3?w=800&q=80'
                ]
            ],
        ];

        // Create the manually defined products
        foreach ($products as $product) {
            Product::create($product);
        }

        // Generate 50+ additional products using factory
        /*
        $allCategories = Category::whereNull('parent_id')->get();

        Product::factory()
            ->count(50)
            ->create([
                'category_id' => fn() => $allCategories->random()->id,
            ]);

        // Create some featured products
        Product::factory()
            ->count(5)
            ->featured()
            ->create([
                'category_id' => fn() => $allCategories->random()->id,
            ]);

        // Create some products on sale
        Product::factory()
            ->count(10)
            ->onSale()
            ->create([
                'category_id' => fn() => $allCategories->random()->id,
            ]);
        */
    }
}