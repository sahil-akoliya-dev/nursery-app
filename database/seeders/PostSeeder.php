<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure we have a user to assign posts to
        $user = User::first() ?? User::factory()->create();

        $posts = [
            // Plant Care Guides (5 posts)
            [
                'title' => 'Top 10 Air Purifying Plants for Your Home',
                'image' => 'https://images.unsplash.com/photo-1512428908174-cc784f0b0583?q=80&w=2070&auto=format&fit=crop',
                'excerpt' => 'Discover the best plants to clean the air in your home and improve your indoor environment. NASA-approved plants that remove toxins and boost oxygen levels.',
                'content' => '
<p>Indoor air quality is a major concern for many homeowners, especially in urban environments where pollution and sealed buildings can trap harmful toxins. Fortunately, nature has provided us with a simple, beautiful solution: plants! Here are the top 10 air-purifying plants that are easy to care for and look great in any room.</p>

<h3>1. Snake Plant (Sansevieria)</h3>
<p>Also known as Mother-in-Law\'s Tongue, this plant is one of the best for filtering out formaldehyde, which is commonly found in cleaning products, toilet paper, and personal care products. It\'s also one of the few plants that releases oxygen at night, making it perfect for bedrooms.</p>

<h3>2. Spider Plant (Chlorophytum comosum)</h3>
<p>Great for beginners, the spider plant battles benzene, formaldehyde, carbon monoxide and xylene. It\'s incredibly resilient and produces baby plants that you can propagate and share with friends.</p>

<h3>3. Peace Lily (Spathiphyllum)</h3>
<p>A beautiful flowering plant that removes ammonia, benzene, formaldehyde, and trichloroethylene. The Peace Lily will even tell you when it needs water by drooping its leaves.</p>

<h3>4. Pothos (Devil\'s Ivy)</h3>
<p>This trailing plant is nearly impossible to kill and excels at removing formaldehyde from the air. It\'s perfect for hanging baskets or training up a moss pole.</p>

<h3>5. Rubber Plant (Ficus elastica)</h3>
<p>With its large, glossy leaves, the rubber plant is excellent at removing toxins from the air while adding a bold architectural statement to your space.</p>

<h3>6. Boston Fern</h3>
<p>This lush fern is particularly effective at removing formaldehyde and acts as a natural humidifier, adding moisture to dry indoor air.</p>

<h3>7. Aloe Vera</h3>
<p>Beyond its healing properties, aloe vera removes formaldehyde and benzene from the air. It\'s also incredibly low-maintenance.</p>

<h3>8. Bamboo Palm</h3>
<p>This elegant palm filters out benzene and trichloroethylene while adding a tropical touch to your home.</p>

<h3>9. English Ivy</h3>
<p>Studies have shown that English ivy can reduce airborne mold particles, making it ideal for those with allergies.</p>

<h3>10. ZZ Plant</h3>
<p>This modern, glossy plant removes toxins while tolerating low light and irregular watering.</p>

<p>Adding these plants to your home not only improves air quality but also adds a touch of greenery that can boost your mood, productivity, and overall well-being. Start with 2-3 plants and watch your indoor jungle grow!</p>
',
            ],
            [
                'title' => 'How to Care for Succulents: A Complete Beginner\'s Guide',
                'image' => 'https://images.unsplash.com/photo-1446071103084-c257b5f70672?q=80&w=1584&auto=format&fit=crop',
                'excerpt' => 'Succulents are popular for a reason. Learn the basics of watering, light, and soil to keep them thriving. Master the art of succulent care with our comprehensive guide.',
                'content' => '
<p>Succulents are known for being low-maintenance, but they still have specific needs. The most common mistake beginners make is overwatering, which can quickly lead to root rot. Follow this guide to keep your succulents healthy and happy.</p>

<h3>Understanding Succulents</h3>
<p>Succulents are plants that store water in their leaves, stems, or roots. This adaptation allows them to survive in arid environments. Popular types include echeveria, jade plants, aloe vera, and sedums.</p>

<h3>Light Requirements</h3>
<p>Most succulents love bright, indirect light. A south-facing window is usually ideal, but be careful of intense afternoon sun which can scorch leaves. If your succulent starts stretching (etiolation), it needs more light. Consider using a grow light if natural light is limited.</p>

<h3>The "Soak and Dry" Watering Method</h3>
<p>This is the golden rule of succulent care: water thoroughly, then let the soil dry out completely before watering again. In summer, this might mean watering every 7-10 days. In winter, you might water only once a month. Always check the soil first - if it\'s damp, don\'t water.</p>

<h3>Soil Matters</h3>
<p>Use a well-draining cactus mix to prevent root rot. You can make your own by mixing regular potting soil with perlite or coarse sand in a 1:1 ratio. The soil should dry quickly after watering.</p>

<h3>Pot Selection</h3>
<p>Always use pots with drainage holes. Terracotta pots are ideal because they\'re porous and allow excess moisture to evaporate.</p>

<h3>Fertilizing</h3>
<p>Succulents don\'t need much fertilizer. Feed them with a diluted, balanced fertilizer once during the growing season (spring/summer).</p>

<h3>Common Problems and Solutions</h3>
<ul>
<li><strong>Wrinkled leaves:</strong> Needs water</li>
<li><strong>Soft, mushy leaves:</strong> Overwatered - stop watering immediately</li>
<li><strong>Stretched growth:</strong> Needs more light</li>
<li><strong>Brown spots:</strong> Sunburn - move to less intense light</li>
</ul>

<p>With these simple tips, your succulent collection will flourish! Remember, when in doubt, underwater rather than overwater.</p>
',
            ],
            [
                'title' => 'Monstera Care Guide: Growing the Perfect Swiss Cheese Plant',
                'image' => 'https://images.unsplash.com/photo-1614594975525-e45890e2e122?q=80&w=2070&auto=format&fit=crop',
                'excerpt' => 'Everything you need to know about caring for Monstera deliciosa, from propagation to troubleshooting common problems.',
                'content' => '
<p>The Monstera deliciosa has become one of the most popular houseplants, and for good reason. Its stunning split leaves and easy-going nature make it perfect for plant enthusiasts of all levels.</p>

<h3>Light and Location</h3>
<p>Monsteras thrive in bright, indirect light. East or west-facing windows are ideal. While they can tolerate lower light, growth will slow and new leaves may not develop the characteristic splits and holes (fenestrations).</p>

<h3>Watering Schedule</h3>
<p>Water when the top 2 inches of soil feel dry. Monsteras prefer consistent moisture but hate soggy soil. In summer, this might mean weekly watering; in winter, every 2-3 weeks.</p>

<h3>Humidity and Temperature</h3>
<p>Native to tropical rainforests, Monsteras appreciate 60-80% humidity. Mist leaves 2-3 times per week, use a humidifier, or place on a pebble tray. They prefer temperatures between 65-85°F.</p>

<h3>Support and Training</h3>
<p>As a climbing plant, Monsteras benefit from a moss pole or stake. This encourages larger leaves and more dramatic fenestrations. Secure stems gently with plant ties.</p>

<h3>Fertilizing</h3>
<p>Feed monthly during spring and summer with a balanced liquid fertilizer diluted to half strength. Reduce feeding in fall and winter.</p>

<h3>Propagation</h3>
<p>Monsteras are easy to propagate from stem cuttings. Cut below a node (the bump where leaves emerge), ensure the cutting has at least one leaf and aerial root, and place in water or directly in soil.</p>

<h3>Common Issues</h3>
<ul>
<li><strong>Yellow leaves:</strong> Overwatering or poor drainage</li>
<li><strong>Brown leaf edges:</strong> Low humidity or underwatering</li>
<li><strong>No fenestrations:</strong> Insufficient light or young plant</li>
<li><strong>Pests:</strong> Check for spider mites or mealybugs</li>
</ul>

<p>With proper care, your Monstera can grow into a stunning focal point that brings the jungle indoors!</p>
',
            ],
            [
                'title' => 'Winter Plant Care: Keeping Your Houseplants Thriving in Cold Months',
                'image' => 'https://images.unsplash.com/photo-1463936575829-25148e1db1b8?q=80&w=2070&auto=format&fit=crop',
                'excerpt' => 'Adjust your plant care routine for winter success. Learn how to protect your plants from cold, dry air and reduced light.',
                'content' => '
<p>Winter presents unique challenges for houseplant care. Shorter days, lower humidity, and indoor heating can stress your green friends. Here\'s how to help them thrive through the cold months.</p>

<h3>Adjust Watering</h3>
<p>Most plants enter a dormant or slow-growth phase in winter and need less water. Check soil moisture before watering - many plants need 30-50% less water than in summer. Overwatering is the #1 killer of houseplants in winter.</p>

<h3>Increase Humidity</h3>
<p>Indoor heating drastically reduces humidity. Combat this by:
<ul>
<li>Grouping plants together to create a microclimate</li>
<li>Using a humidifier</li>
<li>Placing plants on pebble trays filled with water</li>
<li>Misting tropical plants regularly</li>
</ul>
</p>

<h3>Maximize Light</h3>
<p>With shorter days, move plants closer to windows. Clean windows and dust plant leaves to maximize light absorption. Consider supplementing with grow lights for light-hungry plants.</p>

<h3>Reduce Fertilizing</h3>
<p>Most plants don\'t need fertilizer during winter dormancy. Resume feeding when you see new growth in spring.</p>

<h3>Watch Temperature</h3>
<p>Keep plants away from cold drafts, heating vents, and radiators. Most houseplants prefer temperatures between 65-75°F. Avoid placing plants near frequently opened doors.</p>

<h3>Pause Repotting</h3>
<p>Winter is not the time to repot unless absolutely necessary. Wait until spring when plants enter active growth.</p>

<h3>Monitor for Pests</h3>
<p>Dry indoor air can encourage spider mites and mealybugs. Inspect plants regularly and treat promptly if you spot pests.</p>

<h3>Prune Wisely</h3>
<p>Light pruning to remove dead or yellowing leaves is fine, but save major pruning for spring when plants can recover quickly.</p>

<p>Remember, some leaf drop and slower growth are normal in winter. Your plants are just resting, preparing for vigorous spring growth!</p>
',
            ],
            [
                'title' => 'Propagation 101: How to Multiply Your Plant Collection for Free',
                'image' => 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?q=80&w=2070&auto=format&fit=crop',
                'excerpt' => 'Learn various propagation methods to expand your plant collection without spending money. From water propagation to division.',
                'content' => '
<p>Propagation is one of the most rewarding aspects of plant parenthood. Not only can you expand your collection for free, but you can also share plants with friends and rescue leggy specimens. Here are the main propagation methods.</p>

<h3>Water Propagation</h3>
<p>Perfect for: Pothos, Philodendron, Monstera, Spider Plants
<br>Method: Cut below a node, remove lower leaves, place in water. Change water weekly. Once roots are 2-3 inches long, pot in soil.</p>

<h3>Stem Cuttings in Soil</h3>
<p>Perfect for: Succulents, Snake Plants, Rubber Plants
<br>Method: Take a 4-6 inch cutting, let it callus for 24 hours, dip in rooting hormone (optional), plant in moist soil. Keep soil lightly moist until established.</p>

<h3>Leaf Cuttings</h3>
<p>Perfect for: Succulents, African Violets, Begonias
<br>Method: Gently remove a healthy leaf, let it callus, place on soil surface. Keep soil lightly moist. New plants will grow from the base.</p>

<h3>Division</h3>
<p>Perfect for: Snake Plants, Peace Lilies, Ferns, Spider Plants
<br>Method: Remove plant from pot, gently separate root ball into sections (each with roots and shoots), pot separately.</p>

<h3>Air Layering</h3>
<p>Perfect for: Rubber Plants, Fiddle Leaf Figs, Monstera
<br>Method: Make a small cut on a stem, wrap with moist sphagnum moss and plastic wrap. Once roots develop, cut below and pot.</p>

<h3>Offsets/Pups</h3>
<p>Perfect for: Aloe, Succulents, Bromeliads, Spider Plants
<br>Method: Wait until offsets are 1/3 the size of the parent, gently separate with roots attached, pot individually.</p>

<h3>Pro Tips for Success</h3>
<ul>
<li>Use clean, sharp tools to prevent disease</li>
<li>Propagate during growing season (spring/summer)</li>
<li>Be patient - some plants take weeks or months</li>
<li>Maintain high humidity for cuttings</li>
<li>Use rooting hormone for difficult-to-root plants</li>
</ul>

<p>Start with easy plants like Pothos or Spider Plants to build confidence, then experiment with more challenging species. Happy propagating!</p>
',
            ],

            // Design Inspiration (3 posts)
            [
                'title' => 'The Benefits of Biophilic Design: Bringing Nature Indoors',
                'image' => 'https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?q=80&w=2072&auto=format&fit=crop',
                'excerpt' => 'Incorporating nature into your interior design can reduce stress, increase creativity, and improve overall well-being. Discover the science behind biophilic design.',
                'content' => '
<p>Biophilic design is more than just a trend; it\'s a way of living that connects us to nature. By bringing the outdoors in, we can create spaces that are calming, restorative, and conducive to well-being.</p>

<h3>What is Biophilic Design?</h3>
<p>Biophilia means "love of life" - our innate tendency to seek connections with nature. Biophilic design incorporates natural elements into built environments through plants, natural light, water features, natural materials, and views of nature.</p>

<h3>Proven Health Benefits</h3>
<p>Studies have shown that biophilic design can:
<ul>
<li>Lower blood pressure and heart rate</li>
<li>Reduce stress and anxiety</li>
<li>Improve concentration and productivity by up to 15%</li>
<li>Boost creativity and problem-solving</li>
<li>Speed up healing and recovery</li>
<li>Enhance mood and emotional well-being</li>
</ul>
</p>

<h3>How to Incorporate Biophilic Design</h3>
<p><strong>1. Add Plants</strong><br>
Start with 2-3 plants per room. Mix sizes and heights for visual interest. Consider air-purifying varieties for added benefits.</p>

<p><strong>2. Maximize Natural Light</strong><br>
Keep windows clean and unobstructed. Use sheer curtains instead of heavy drapes. Position furniture to take advantage of natural light.</p>

<p><strong>3. Use Natural Materials</strong><br>
Incorporate wood, stone, bamboo, wool, and cotton. These materials have textures and patterns that resonate with our connection to nature.</p>

<p><strong>4. Bring in Water Elements</strong><br>
A small fountain or aquarium adds the soothing sound of water and increases humidity.</p>

<p><strong>5. Choose Nature-Inspired Colors</strong><br>
Earth tones, greens, blues, and warm neutrals create a calming, natural atmosphere.</p>

<p><strong>6. Create Views</strong><br>
Position seating to face windows with outdoor views. Use nature photography or botanical prints if outdoor views are limited.</p>

<p><strong>7. Add Natural Patterns</strong><br>
Incorporate patterns found in nature - fractals, spirals, leaf shapes - through textiles, wallpaper, or artwork.</p>

<p>Start small by adding a few potted plants to your workspace or living room. You\'ll be amazed at the difference it makes to your mood, focus, and overall sense of well-being.</p>
',
            ],
            [
                'title' => 'Creating a Stunning Indoor Jungle: Design Tips and Plant Combinations',
                'image' => 'https://images.unsplash.com/photo-1463936575829-25148e1db1b8?q=80&w=2070&auto=format&fit=crop',
                'excerpt' => 'Transform your home into a lush indoor jungle with these expert design tips and plant pairing ideas.',
                'content' => '
<p>Creating an indoor jungle is about more than just adding lots of plants - it\'s about thoughtful design, layering, and choosing the right combinations. Here\'s how to achieve that lush, tropical look.</p>

<h3>Start with a Plan</h3>
<p>Consider your space\'s light levels, humidity, and temperature. Choose plants that will thrive in your conditions rather than fighting against them.</p>

<h3>Layer Heights</h3>
<p>Create visual interest by combining:
<ul>
<li><strong>Tall plants (5-8 feet):</strong> Fiddle Leaf Fig, Rubber Plant, Bird of Paradise</li>
<li><strong>Medium plants (2-4 feet):</strong> Monstera, Philodendron, Peace Lily</li>
<li><strong>Low plants (under 2 feet):</strong> Pothos, Ferns, Calathea</li>
<li><strong>Trailing plants:</strong> String of Hearts, Pothos, Philodendron Brasil</li>
</ul>
</p>

<h3>Perfect Plant Combinations</h3>
<p><strong>Tropical Paradise Corner:</strong>
<br>Monstera deliciosa (tall), Bird\'s Nest Fern (medium), Pothos (trailing from shelf)</p>

<p><strong>Low-Light Oasis:</strong>
<br>Snake Plant (tall), ZZ Plant (medium), Pothos (trailing)</p>

<p><strong>Bright Window Display:</strong>
<br>Fiddle Leaf Fig (tall), Rubber Plant (medium), String of Pearls (trailing)</p>

<h3>Use Varied Containers</h3>
<p>Mix pot styles, materials, and sizes for visual interest. Combine ceramic, terracotta, woven baskets, and modern planters. Ensure all have drainage holes.</p>

<h3>Add Vertical Elements</h3>
<p>Use plant stands, wall-mounted planters, hanging baskets, and shelves to create layers and maximize space.</p>

<h3>Group for Impact</h3>
<p>Cluster plants in odd numbers (3, 5, 7) for a more natural, abundant look. This also creates beneficial microclimates with increased humidity.</p>

<h3>Include Texture Variety</h3>
<p>Mix leaf shapes and textures:
<ul>
<li>Large, glossy leaves (Monstera, Rubber Plant)</li>
<li>Delicate, feathery foliage (Ferns, Asparagus Fern)</li>
<li>Architectural, upright leaves (Snake Plant, Dracaena)</li>
<li>Trailing vines (Pothos, Philodendron)</li>
</ul>
</p>

<h3>Don\'t Forget Maintenance</h3>
<p>More plants mean more care. Group plants with similar needs together for easier watering and maintenance.</p>

<p>Remember, your indoor jungle should bring you joy, not stress. Start with a few plants and add gradually as you gain confidence!</p>
',
            ],
            [
                'title' => 'Small Space Plant Styling: Maximizing Greenery in Apartments',
                'image' => 'https://images.unsplash.com/photo-1512428908174-cc784f0b0583?q=80&w=2070&auto=format&fit=crop',
                'excerpt' => 'Living in a small apartment doesn\'t mean you can\'t have an impressive plant collection. Learn how to maximize vertical space and choose the right plants.',
                'content' => '
<p>Limited square footage doesn\'t mean you have to limit your plant collection. With smart styling and space-saving solutions, even the smallest apartment can become a green oasis.</p>

<h3>Think Vertical</h3>
<p>When floor space is limited, go up!
<ul>
<li><strong>Wall-mounted planters:</strong> Perfect for small succulents and air plants</li>
<li><strong>Hanging planters:</strong> Utilize ceiling space for trailing plants</li>
<li><strong>Tall plant stands:</strong> Create levels without taking up floor space</li>
<li><strong>Ladder shelves:</strong> Display multiple plants in a small footprint</li>
<li><strong>Window shelves:</strong> Add a shelf across your window for sun-loving plants</li>
</ul>
</p>

<h3>Multi-Functional Furniture</h3>
<p>Choose furniture that doubles as plant displays:
<ul>
<li>Bookshelves with plants interspersed</li>
<li>Side tables that can hold plants</li>
<li>Room dividers with built-in planters</li>
<li>Window sills as plant shelves</li>
</ul>
</p>

<h3>Best Plants for Small Spaces</h3>
<p><strong>Compact Growers:</strong>
<br>Succulents, Air Plants, Small Ferns, Peperomia, Pilea</p>

<p><strong>Vertical Growers:</strong>
<br>Snake Plants, ZZ Plants, Dracaena, Bamboo</p>

<p><strong>Trailing Plants for Hanging:</strong>
<br>Pothos, String of Hearts, String of Pearls, Philodendron Brasil</p>

<h3>Corner Solutions</h3>
<p>Corners are often wasted space. Place a tall plant like a Fiddle Leaf Fig or Bird of Paradise in a corner to create a focal point without cluttering the room.</p>

<h3>Bathroom and Kitchen Plants</h3>
<p>These rooms often have unused space perfect for plants:
<ul>
<li><strong>Bathroom:</strong> Ferns, Pothos, Orchids (love humidity)</li>
<li><strong>Kitchen:</strong> Herbs on windowsill, Pothos on top of cabinets</li>
</ul>
</p>

<h3>Styling Tips</h3>
<ul>
<li>Use matching or coordinating pots for a cohesive look</li>
<li>Stick to 2-3 pot colors/materials to avoid visual clutter</li>
<li>Group small plants together for impact</li>
<li>Choose plants with similar care needs for easier maintenance</li>
</ul>

<h3>Avoid Overcrowding</h3>
<p>While you want plenty of plants, leave breathing room. Overcrowding can make a small space feel claustrophobic and make plant care difficult.</p>

<p>With these strategies, you can create a lush, plant-filled home no matter how small your space!</p>
',
            ],

            // Seasonal Tips (3 posts)
            [
                'title' => 'Spring Plant Care Checklist: Preparing Your Plants for Growing Season',
                'image' => 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?q=80&w=2070&auto=format&fit=crop',
                'excerpt' => 'Spring is the perfect time to refresh your plant care routine. Follow this checklist to help your plants thrive.',
                'content' => '
<p>Spring is an exciting time for plant parents! As days lengthen and temperatures warm, your plants will wake from winter dormancy and enter active growth. Here\'s your complete spring plant care checklist.</p>

<h3>1. Assess Plant Health</h3>
<p>Inspect each plant for:
<ul>
<li>Dead or damaged leaves (remove them)</li>
<li>Pest infestations (treat immediately)</li>
<li>Root-bound conditions (time to repot)</li>
<li>Overall vigor and color</li>
</ul>
</p>

<h3>2. Resume Fertilizing</h3>
<p>Start feeding plants as new growth appears. Use a balanced liquid fertilizer at half strength initially, then increase to full strength as growth accelerates. Feed every 2-4 weeks during growing season.</p>

<h3>3. Increase Watering</h3>
<p>As plants grow more actively, they\'ll need more water. Check soil moisture more frequently and adjust your watering schedule accordingly.</p>

<h3>4. Repot as Needed</h3>
<p>Spring is the ideal time to repot. Signs your plant needs repotting:
<ul>
<li>Roots growing through drainage holes</li>
<li>Water runs straight through pot</li>
<li>Plant is top-heavy and tips over</li>
<li>Growth has slowed despite good care</li>
</ul>
Choose a pot 1-2 inches larger and use fresh potting mix.</p>

<h3>5. Prune and Shape</h3>
<p>Remove dead growth, trim leggy stems, and shape plants to encourage bushier growth. Spring pruning stimulates new growth.</p>

<h3>6. Propagate</h3>
<p>Spring is the best time to propagate! Take cuttings from healthy growth for best success rates.</p>

<h3>7. Clean Plants</h3>
<p>Wipe down leaves to remove dust accumulated over winter. This helps plants photosynthesize more efficiently.</p>

<h3>8. Rotate Plants</h3>
<p>Rotate plants 90 degrees weekly for even growth, especially important as they start growing vigorously.</p>

<h3>9. Move Plants Outdoors (Gradually)</h3>
<p>If you plan to summer plants outdoors, acclimate them gradually over 7-10 days to prevent shock.</p>

<h3>10. Refresh Top Soil</h3>
<p>For plants you\'re not repotting, remove the top 1-2 inches of soil and replace with fresh potting mix to replenish nutrients.</p>

<p>Follow this checklist and your plants will reward you with vigorous growth and abundant foliage all season long!</p>
',
            ],
            [
                'title' => 'Summer Plant Care: Keeping Plants Happy in the Heat',
                'image' => 'https://images.unsplash.com/photo-1518621736915-f3b1c41bfd00?q=80&w=2070&auto=format&fit=crop',
                'excerpt' => 'Hot summer weather requires adjustments to your plant care routine. Learn how to protect plants from heat stress and sunburn.',
                'content' => '
<p>Summer brings longer days and warmer temperatures - great for plant growth, but also potential challenges. Here\'s how to keep your plants thriving through the heat.</p>

<h3>Adjust Watering</h3>
<p>Plants need significantly more water in summer:
<ul>
<li>Check soil moisture daily during heat waves</li>
<li>Water deeply rather than frequently</li>
<li>Water in early morning or evening to minimize evaporation</li>
<li>Consider bottom-watering for consistent moisture</li>
</ul>
</p>

<h3>Watch for Sunburn</h3>
<p>Even sun-loving plants can burn in intense summer sun. Signs of sunburn:
<ul>
<li>Brown, crispy patches on leaves</li>
<li>Faded or bleached areas</li>
<li>Curling leaf edges</li>
</ul>
Solution: Move plants away from direct afternoon sun or use sheer curtains to filter light.</p>

<h3>Maintain Humidity</h3>
<p>Air conditioning can dry out indoor air. Maintain humidity by:
<ul>
<li>Grouping plants together</li>
<li>Using pebble trays</li>
<li>Running a humidifier</li>
<li>Misting tropical plants</li>
</ul>
</p>

<h3>Fertilize Regularly</h3>
<p>Active growth requires nutrients. Feed every 2-4 weeks with diluted liquid fertilizer.</p>

<h3>Monitor for Pests</h3>
<p>Warm weather brings increased pest activity. Check regularly for:
<ul>
<li>Spider mites (fine webbing, stippled leaves)</li>
<li>Aphids (clusters on new growth)</li>
<li>Mealybugs (white, cottony masses)</li>
<li>Fungus gnats (small flies around soil)</li>
</ul>
</p>

<h3>Provide Air Circulation</h3>
<p>Good airflow prevents fungal issues and helps plants cool down. Use fans to keep air moving, but avoid direct drafts on plants.</p>

<h3>Consider Outdoor Vacation</h3>
<p>Many houseplants benefit from summering outdoors:
<ul>
<li>Acclimate gradually over 7-10 days</li>
<li>Start in shade, gradually increase light</li>
<li>Protect from harsh afternoon sun</li>
<li>Bring indoors before temperatures drop in fall</li>
</ul>
</p>

<h3>Vacation Care</h3>
<p>Going away? Prepare plants:
<ul>
<li>Water thoroughly before leaving</li>
<li>Move away from direct sun</li>
<li>Group plants to increase humidity</li>
<li>Consider self-watering stakes or asking a friend to water</li>
</ul>
</p>

<p>With these adjustments, your plants will thrive through even the hottest summer!</p>
',
            ],
            [
                'title' => 'Fall Plant Care: Preparing Your Indoor Garden for Cooler Weather',
                'image' => 'https://images.unsplash.com/photo-1499002238440-d264edd596ec?q=80&w=2070&auto=format&fit=crop',
                'excerpt' => 'As temperatures drop and days shorten, adjust your plant care routine for the changing season.',
                'content' => '
<p>Fall marks the transition from active growth to dormancy for many plants. Adjusting your care routine now will help plants stay healthy through winter.</p>

<h3>Bring Outdoor Plants Inside</h3>
<p>Before first frost:
<ul>
<li>Inspect thoroughly for pests</li>
<li>Treat any infestations before bringing indoors</li>
<li>Acclimate gradually over 7-10 days</li>
<li>Clean pots and remove dead foliage</li>
<li>Repot if needed (though spring is better)</li>
</ul>
</p>

<h3>Reduce Watering</h3>
<p>As growth slows, plants need less water. Reduce watering frequency by 25-50%. Always check soil before watering.</p>

<h3>Taper Off Fertilizing</h3>
<p>Stop fertilizing by late fall. Plants entering dormancy don\'t need nutrients and excess fertilizer can cause problems.</p>

<h3>Adjust Light</h3>
<p>With shorter days, move plants closer to windows or supplement with grow lights. Clean windows to maximize available light.</p>

<h3>Monitor Temperature</h3>
<p>As you start heating your home:
<ul>
<li>Keep plants away from heating vents and radiators</li>
<li>Avoid cold drafts from windows and doors</li>
<li>Maintain consistent temperatures (65-75°F for most plants)</li>
</ul>
</p>

<h3>Increase Humidity</h3>
<p>Heating systems dry indoor air. Combat this with humidifiers, pebble trays, or grouping plants.</p>

<h3>Prune Lightly</h3>
<p>Remove dead or yellowing leaves, but save major pruning for spring when plants can recover quickly.</p>

<h3>Pest Prevention</h3>
<p>Inspect plants regularly. Pests brought in from outdoors can spread quickly indoors.</p>

<h3>Prepare for Dormancy</h3>
<p>Some plants (like Alocasia, Caladium) may die back completely. This is normal! Reduce watering and wait for spring regrowth.</p>

<h3>Clean and Organize</h3>
<p>Fall is a good time to:
<ul>
<li>Clean pots and saucers</li>
<li>Organize plant care supplies</li>
<li>Take inventory of what you have</li>
<li>Plan winter plant purchases</li>
</ul>
</p>

<p>With these fall adjustments, your plants will transition smoothly into winter dormancy and emerge strong in spring!</p>
',
            ],

            // Product Spotlights (2 posts)
            [
                'title' => '5 Rare Plants Every Collector Needs',
                'image' => 'https://images.unsplash.com/photo-1614594975525-e45190c55d0b?q=80&w=2070&auto=format&fit=crop',
                'excerpt' => 'Looking for something unique? Check out these rare and stunning plants that will be the envy of your friends.',
                'content' => '
<p>For the plant enthusiast who has it all, these rare finds are the next level. While they may require more investment and care, their beauty and uniqueness make them worth it.</p>

<h3>1. Philodendron Pink Princess</h3>
<p>Known for its stunning pink variegation on dark green leaves, this plant has become incredibly sought-after. The pink coloring is unstable, making each plant unique. Requires bright, indirect light to maintain variegation. Prices range from $50-$300 depending on variegation.</p>

<h3>2. Monstera Albo (Monstera deliciosa \'Albo Variegata\')</h3>
<p>The white and green variegated leaves are absolute showstoppers. This slow-growing plant can command prices of $100-$1000+ for a single cutting. Requires bright light to maintain white variegation and careful watering to prevent rot.</p>

<h3>3. Anthurium Warocqueanum (Queen Anthurium)</h3>
<p>Also known as the Queen Anthurium, it features long, velvety leaves with prominent white veins that can grow over 3 feet long. Requires high humidity (70%+) and consistent care. A true statement plant for serious collectors.</p>

<h3>4. Philodendron Gloriosum</h3>
<p>This crawling philodendron produces large, heart-shaped, velvety leaves with striking white veins. Unlike climbing philodendrons, it grows horizontally, making it unique. Requires high humidity and well-draining soil.</p>

<h3>5. Alocasia Azlanii (Red Mambo)</h3>
<p>Features stunning metallic, iridescent leaves in shades of purple, pink, and green. The underside is deep burgundy. Requires high humidity, warm temperatures, and bright indirect light. Goes dormant in winter.</p>

<h3>Care Tips for Rare Plants</h3>
<ul>
<li>Research specific care requirements before purchasing</li>
<li>Quarantine new plants to prevent pest spread</li>
<li>Invest in a humidifier for tropical rarities</li>
<li>Use well-draining, airy soil mixes</li>
<li>Be patient - rare plants often grow slowly</li>
<li>Join plant communities for care tips and trading</li>
</ul>

<h3>Where to Find Rare Plants</h3>
<ul>
<li>Specialty online retailers</li>
<li>Local plant swaps and sales</li>
<li>Plant collector groups on social media</li>
<li>Botanical gardens sales</li>
<li>Specialty nurseries</li>
</ul>

<p>These plants may require a bit more care and investment, but their beauty and uniqueness make them treasured additions to any collection!</p>
',
            ],
            [
                'title' => 'Best Low-Light Plants for Dark Corners and Offices',
                'image' => 'https://images.unsplash.com/photo-1509587584298-0f3b3a3a1797?q=80&w=2070&auto=format&fit=crop',
                'excerpt' => 'Don\'t let low light stop you from having plants. These varieties thrive in dim conditions and are perfect for offices and north-facing rooms.',
                'content' => '
<p>Not all spaces are blessed with bright, sunny windows. Fortunately, many beautiful plants thrive in low-light conditions, making them perfect for offices, bathrooms, and north-facing rooms.</p>

<h3>Understanding Low Light</h3>
<p>Low light means:
<ul>
<li>North-facing windows</li>
<li>Rooms with no direct sunlight</li>
<li>Spaces 6-8 feet from windows</li>
<li>Offices with only fluorescent lighting</li>
</ul>
Note: No plant can survive in complete darkness. Even low-light plants need some ambient light.</p>

<h3>Top Low-Light Champions</h3>

<h4>1. Snake Plant (Sansevieria)</h4>
<p>The ultimate low-light survivor. Tolerates neglect, irregular watering, and dim conditions. Perfect for beginners and busy people.</p>

<h4>2. ZZ Plant (Zamioculcas zamiifolia)</h4>
<p>Glossy, architectural leaves that look great in modern spaces. Extremely drought-tolerant and pest-resistant.</p>

<h4>3. Pothos</h4>
<p>Trailing vines that tolerate low light (though growth will be slower). Comes in several varieties including golden, marble queen, and neon.</p>

<h4>4. Peace Lily</h4>
<p>Produces elegant white flowers even in low light. Communicates its needs by drooping when thirsty.</p>

<h4>5. Cast Iron Plant (Aspidistra)</h4>
<p>Named for its tough-as-nails nature. Tolerates low light, temperature fluctuations, and neglect.</p>

<h4>6. Chinese Evergreen (Aglaonema)</h4>
<p>Beautiful variegated foliage in shades of green, pink, and red. Very forgiving of low light and irregular watering.</p>

<h4>7. Dracaena</h4>
<p>Many varieties available, all tolerant of low light. Grows slowly but can eventually become a tall, tree-like plant.</p>

<h4>8. Philodendron (Heart-leaf)</h4>
<p>Trailing or climbing plant with heart-shaped leaves. Adapts well to low light though growth slows.</p>

<h3>Care Tips for Low-Light Plants</h3>
<ul>
<li><strong>Water less:</strong> Low light = slower growth = less water needed</li>
<li><strong>Don\'t fertilize as much:</strong> Feed only 2-3 times per year</li>
<li><strong>Dust leaves regularly:</strong> Clean leaves absorb more light</li>
<li><strong>Rotate plants:</strong> Ensure even growth on all sides</li>
<li><strong>Be patient:</strong> Growth will be slower than in bright light</li>
<li><strong>Watch for stretching:</strong> If stems get leggy, plant needs more light</li>
</ul>

<h3>Boosting Low Light</h3>
<p>If your space is very dark, consider:
<ul>
<li>LED grow lights (can be decorative)</li>
<li>Mirrors to reflect available light</li>
<li>Light-colored walls to brighten space</li>
<li>Rotating plants to brighter spots periodically</li>
</ul>
</p>

<p>Don\'t let low light stop you from enjoying plants! These hardy varieties will thrive even in the dimmest corners.</p>
',
            ],

            // Expert Interviews (2 posts)
            [
                'title' => 'Common Plant Problems and How to Fix Them',
                'image' => 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?q=80&w=2070&auto=format&fit=crop',
                'excerpt' => 'Troubleshoot the most common houseplant problems with our expert guide. From yellow leaves to pest infestations.',
                'content' => '
<p>Even experienced plant parents encounter problems. Here\'s how to diagnose and fix the most common issues.</p>

<h3>Yellow Leaves</h3>
<p><strong>Causes:</strong>
<ul>
<li>Overwatering (most common)</li>
<li>Underwatering</li>
<li>Nutrient deficiency</li>
<li>Natural aging (lower leaves)</li>
</ul>
<strong>Solutions:</strong> Check soil moisture. If soggy, let dry out and reduce watering. If bone dry, water thoroughly. If care is correct, it may be natural aging or time to fertilize.</p>

<h3>Brown Leaf Tips</h3>
<p><strong>Causes:</strong>
<ul>
<li>Low humidity</li>
<li>Fluoride/chlorine in water</li>
<li>Fertilizer burn</li>
<li>Underwatering</li>
</ul>
<strong>Solutions:</strong> Increase humidity, use filtered water, flush soil to remove excess fertilizer, or adjust watering schedule.</p>

<h3>Drooping/Wilting</h3>
<p><strong>Causes:</strong>
<ul>
<li>Underwatering</li>
<li>Overwatering (root rot)</li>
<li>Temperature stress</li>
<li>Transplant shock</li>
</ul>
<strong>Solutions:</strong> Check soil moisture. If dry, water thoroughly. If wet, check roots for rot. Ensure plant isn\'t near drafts or heat sources.</p>

<h3>Leggy Growth</h3>
<p><strong>Cause:</strong> Insufficient light
<br><strong>Solution:</strong> Move to brighter location or add grow light. Prune leggy stems to encourage bushier growth.</p>

<h3>No New Growth</h3>
<p><strong>Causes:</strong>
<ul>
<li>Dormancy (winter)</li>
<li>Root-bound</li>
<li>Insufficient light</li>
<li>Lack of nutrients</li>
</ul>
<strong>Solutions:</strong> If winter, this is normal. Otherwise, check if plant needs repotting, more light, or fertilizer.</p>

<h3>Pest Infestations</h3>

<h4>Spider Mites</h4>
<p>Tiny pests that create fine webbing. Leaves appear stippled or dusty.
<br><strong>Treatment:</strong> Spray with water, neem oil, or insecticidal soap. Increase humidity.</p>

<h4>Mealybugs</h4>
<p>White, cottony masses on stems and leaves.
<br><strong>Treatment:</strong> Remove with cotton swab dipped in rubbing alcohol. Spray with neem oil.</p>

<h4>Fungus Gnats</h4>
<p>Small flies around soil surface. Larvae feed on roots.
<br><strong>Treatment:</strong> Let soil dry out between waterings. Use yellow sticky traps. Add sand layer on top of soil.</p>

<h4>Scale</h4>
<p>Brown, shell-like bumps on stems and leaves.
<br><strong>Treatment:</strong> Scrape off manually. Spray with neem oil or insecticidal soap.</p>

<h3>Root Rot</h3>
<p><strong>Signs:</strong> Mushy stems, yellow leaves, foul smell from soil
<br><strong>Treatment:</strong> Remove plant from pot, trim away rotted roots, repot in fresh soil. Adjust watering habits.</p>

<h3>Sunburn</h3>
<p><strong>Signs:</strong> Brown, crispy patches on leaves
<br><strong>Treatment:</strong> Move away from direct sun. Trim damaged leaves. Gradually acclimate to brighter light if desired.</p>

<h3>Prevention is Best</h3>
<ul>
<li>Inspect plants regularly</li>
<li>Quarantine new plants for 2 weeks</li>
<li>Provide appropriate light and water</li>
<li>Maintain good air circulation</li>
<li>Keep leaves clean</li>
<li>Use quality potting mix</li>
</ul>

<p>Remember, most plant problems are fixable if caught early. Regular observation is your best tool!</p>
',
            ],
            [
                'title' => 'The Ultimate Guide to Repotting Houseplants',
                'image' => 'https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?q=80&w=2072&auto=format&fit=crop',
                'excerpt' => 'Learn when and how to repot your plants for optimal health and growth. Step-by-step guide with pro tips.',
                'content' => '
<p>Repotting is essential for plant health, but it can be intimidating for beginners. This comprehensive guide will walk you through everything you need to know.</p>

<h3>When to Repot</h3>
<p><strong>Signs your plant needs repotting:</strong>
<ul>
<li>Roots growing through drainage holes</li>
<li>Water runs straight through pot without absorbing</li>
<li>Plant is top-heavy and tips over easily</li>
<li>Growth has slowed despite good care</li>
<li>Soil dries out very quickly</li>
<li>Roots are circling the root ball (root-bound)</li>
<li>It\'s been 2+ years since last repotting</li>
</ul>
</p>

<h3>Best Time to Repot</h3>
<p>Spring and early summer are ideal when plants are entering active growth. Avoid repotting during winter dormancy or when plant is flowering.</p>

<h3>Choosing the Right Pot</h3>
<ul>
<li>Select a pot 1-2 inches larger in diameter</li>
<li>Must have drainage holes</li>
<li>Material options: terracotta (breathable), plastic (retains moisture), ceramic (decorative)</li>
<li>Don\'t size up too much - excess soil retains too much moisture</li>
</ul>

<h3>Supplies Needed</h3>
<ul>
<li>New pot with drainage</li>
<li>Fresh potting mix (appropriate for plant type)</li>
<li>Newspaper or tarp</li>
<li>Watering can</li>
<li>Scissors or pruning shears</li>
<li>Optional: rooting hormone, perlite, activated charcoal</li>
</ul>

<h3>Step-by-Step Repotting</h3>

<p><strong>1. Prepare Your Workspace</strong><br>
Cover surface with newspaper. Have all supplies ready.</p>

<p><strong>2. Water Plant</strong><br>
Water 1-2 days before repotting. Moist (not soggy) soil is easier to work with.</p>

<p><strong>3. Remove Plant from Pot</strong><br>
Gently squeeze pot sides. Turn upside down, supporting plant. Tap bottom until plant slides out. If stuck, run knife around inside edge.</p>

<p><strong>4. Examine Roots</strong><br>
Healthy roots are white or tan and firm. Brown, mushy roots indicate rot - trim these away.</p>

<p><strong>5. Loosen Root Ball</strong><br>
Gently tease apart circling roots. Trim any dead or damaged roots. If severely root-bound, make 3-4 vertical cuts in root ball.</p>

<p><strong>6. Prepare New Pot</strong><br>
Add 1-2 inches of fresh potting mix to bottom. Don\'t use garden soil - it\'s too dense.</p>

<p><strong>7. Position Plant</strong><br>
Place plant in center of pot at same depth as before. Top of root ball should be 1/2-1 inch below pot rim.</p>

<p><strong>8. Fill with Soil</strong><br>
Add potting mix around roots, gently firming as you go. Don\'t pack too tightly. Leave space at top for watering.</p>

<p><strong>9. Water Thoroughly</strong><br>
Water until it drains from bottom. This settles soil and eliminates air pockets.</p>

<p><strong>10. Place in Appropriate Light</strong><br>
Keep in bright, indirect light for a week while plant adjusts. Resume normal care after.</p>

<h3>Post-Repotting Care</h3>
<ul>
<li>Don\'t fertilize for 4-6 weeks (fresh soil has nutrients)</li>
<li>Monitor moisture - new soil may retain water differently</li>
<li>Some leaf drop is normal as plant adjusts</li>
<li>Be patient - it may take a few weeks to see new growth</li>
</ul>

<h3>Special Considerations</h3>

<p><strong>Cacti and Succulents:</strong> Use cactus mix. Let roots dry for 24 hours before repotting. Wait 1 week before watering.</p>

<p><strong>Orchids:</strong> Use orchid bark mix. Trim dead roots. Soak bark before using.</p>

<p><strong>Large Plants:</strong> Get help! Tip pot on side, slide plant out. May need to break pot if severely root-bound.</p>

<h3>Common Mistakes to Avoid</h3>
<ul>
<li>Pot too large (causes overwatering)</li>
<li>No drainage holes</li>
<li>Using garden soil</li>
<li>Repotting during dormancy</li>
<li>Fertilizing immediately after</li>
<li>Disturbing roots too much</li>
</ul>

<p>With practice, repotting becomes routine. Your plants will thank you with vigorous growth and better health!</p>
',
            ],
        ];

        foreach ($posts as $post) {
            Post::create([
                'title' => $post['title'],
                'slug' => Str::slug($post['title']),
                'excerpt' => $post['excerpt'],
                'content' => $post['content'],
                'image' => $post['image'],
                'user_id' => $user->id,
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 90)),
            ]);
        }
    }
}