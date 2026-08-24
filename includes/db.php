<?php
/**
 * Udyojika - Database Connection & Data Provider
 * Compatible with XAMPP (Apache + MySQL / MariaDB)
 * Fallback to embedded demo data if MySQL service is not yet initialized.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db_host = 'localhost';
$db_name = 'udyojika_db';
$db_user = 'root';
$db_pass = '';
$db_charset = 'utf8mb4';

$pdo = null;
$db_connected = false;

try {
    $dsn = "mysql:host={$db_host};dbname={$db_name};charset={$db_charset}";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    $db_connected = true;
} catch (PDOException $e) {
    $db_connected = false;
    // Graceful fallback to static array if database is not created yet
}

// Fallback / In-Memory Mock Data when running without active MySQL
$fallback_categories = [
    [
        'id' => 1,
        'name' => 'Homemade Food',
        'slug' => 'homemade-food',
        'icon' => 'fa-utensils',
        'image' => 'https://images.unsplash.com/photo-1599488615731-7e5c2823ff28?q=80&w=600&auto=format&fit=crop',
        'product_count' => 142,
        'description' => 'Authentic regional snacks, pure ghee sweets, traditional pickles, sun-dried papads, and secret family recipe spice blends.',
        'popular_items' => ['Chakli & Chivda', 'Ladoos & Karanji', 'Mango & Lime Pickles', 'Ghar ka Masala']
    ],
    [
        'id' => 2,
        'name' => 'Fashion & Clothing',
        'slug' => 'fashion-clothing',
        'icon' => 'fa-shirt',
        'image' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600&auto=format&fit=crop',
        'product_count' => 88,
        'description' => 'Handloom cotton kurtas, custom-stitched saree blouses, block printed dupattas, and handcrafted sustainable ethnic wear.',
        'popular_items' => ['Handloom Dupattas', 'Cotton Kurtas', 'Embroidered Blouses', 'Kids Ethnic Wear']
    ],
    [
        'id' => 3,
        'name' => 'Jewellery & Accessories',
        'slug' => 'jewellery-accessories',
        'icon' => 'fa-gem',
        'image' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?q=80&w=600&auto=format&fit=crop',
        'product_count' => 115,
        'description' => 'Eco-friendly terracotta jewellery sets, silk thread bangles, oxidized silver jhumkas, and handcrafted bridal hair accessories.',
        'popular_items' => ['Terracotta Sets', 'Beaded Jhumkas', 'Silk Bangles', 'Oxidized Chokers']
    ],
    [
        'id' => 4,
        'name' => 'Handicrafts & Decor',
        'slug' => 'handicrafts-decor',
        'icon' => 'fa-palette',
        'image' => 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?q=80&w=600&auto=format&fit=crop',
        'product_count' => 94,
        'description' => 'Hand-painted terracotta diyas, Lippan art wall plates, macrame hangings, brass artifacts, and festive torans.',
        'popular_items' => ['Lippan Art Plates', 'Terracotta Diyas', 'Macrame Hangings', 'Torans & Hangings']
    ],
    [
        'id' => 5,
        'name' => 'Natural Candles & Aromas',
        'slug' => 'candles-aromas',
        'icon' => 'fa-fire-flame-curved',
        'image' => 'https://images.unsplash.com/photo-1603006905003-be475563bc59?q=80&w=600&auto=format&fit=crop',
        'product_count' => 67,
        'description' => 'Hand-poured 100% soy wax candles, organic essential oils, aromatic dhoop cones, and soothing wax melts.',
        'popular_items' => ['Mogra Soy Candles', 'Sandalwood Cones', 'Aromatherapy Melts', 'Himalayan Bath Salts']
    ],
    [
        'id' => 6,
        'name' => 'Organic Beauty & Soaps',
        'slug' => 'beauty-wellness',
        'icon' => 'fa-spa',
        'image' => 'https://images.unsplash.com/photo-1608248597359-0098e7228807?q=80&w=600&auto=format&fit=crop',
        'product_count' => 53,
        'description' => 'Cold-processed goat milk soaps, herbal hair growth oils, Ayurvedic ubtan packs, and chemical-free lip balms.',
        'popular_items' => ['Goat Milk Soap', 'Bhringraj Hair Oil', 'Haldi Chandan Ubtan', 'Beeswax Lip Balm']
    ]
];

$fallback_sellers = [
    [
        'id' => 1,
        'business_name' => 'Annapurna Swaad',
        'owner_name' => 'Sunita Kulkarni',
        'category' => 'Homemade Food',
        'location' => 'Pune, Maharashtra',
        'rating' => 4.9,
        'review_count' => 184,
        'product_count' => 16,
        'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=300&auto=format&fit=crop',
        'banner_image' => 'https://images.unsplash.com/photo-1599488615731-7e5c2823ff28?q=80&w=800&auto=format&fit=crop',
        'short_bio' => 'Carrying forward 40-year-old Maharashtrian heirloom recipes with pure Gir cow ghee and zero preservatives.',
        'full_story' => 'Sunita started Annapurna Swaad from her kitchen in Pune in 2021. What began as gifting festive Diwali Faral to neighbors turned into a thriving venture. Today, she employs 8 local homemakers, sourcing stone-ground flours and cold-pressed oils locally.',
        'specialty' => 'Maharashtrian Festive Faral & Poha Chivda',
        'joined_year' => '2021',
        'badges' => ['Top Seller', 'Hygiene Verified', '100% Ghee'],
        'is_verified' => 1,
        'whatsapp' => '+919822012345'
    ],
    [
        'id' => 2,
        'business_name' => 'Mrittika Clay Art',
        'owner_name' => 'Ananya Sengupta',
        'category' => 'Jewellery & Accessories',
        'location' => 'Kolkata, West Bengal',
        'rating' => 4.8,
        'review_count' => 142,
        'product_count' => 22,
        'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=300&auto=format&fit=crop',
        'banner_image' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?q=80&w=800&auto=format&fit=crop',
        'short_bio' => 'Hand-sculpted terracotta jewellery & decorative pottery inspired by Bengal rural heritage.',
        'full_story' => 'A fine arts graduate who wanted to work while nurturing her children, Ananya turned her balcony into a pottery workshop. Each piece is molded by hand, sun-cured, kiln-fired, and hand-painted in vibrant earthy tones.',
        'specialty' => 'Terracotta Choker Sets & Festive Diyas',
        'joined_year' => '2022',
        'badges' => ['Artisan Choice', 'Eco Friendly'],
        'is_verified' => 1,
        'whatsapp' => '+919830112345'
    ],
    [
        'id' => 3,
        'business_name' => 'Sugandham Fragrance',
        'owner_name' => 'Radha Deshmukh',
        'category' => 'Natural Candles & Aromas',
        'location' => 'Nashik, Maharashtra',
        'rating' => 5.0,
        'review_count' => 96,
        'product_count' => 12,
        'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=300&auto=format&fit=crop',
        'banner_image' => 'https://images.unsplash.com/photo-1603006905003-be475563bc59?q=80&w=800&auto=format&fit=crop',
        'short_bio' => 'Hand-poured 100% natural soy wax candles infused with Indian floral essential oils.',
        'full_story' => 'Radha was disturbed by toxic paraffin candles in the market. She researched organic soy wax and pure flower distillates. Now her candles in reusable brass and glass jars are featured in boutique hotels and homes.',
        'specialty' => 'Mogra & Sandalwood Soy Candles',
        'joined_year' => '2022',
        'badges' => ['Toxin Free', 'Zero Waste'],
        'is_verified' => 1,
        'whatsapp' => '+919823312345'
    ],
    [
        'id' => 4,
        'business_name' => 'Virasat Handlooms',
        'owner_name' => 'Meenakshi Iyer',
        'category' => 'Fashion & Clothing',
        'location' => 'Coimbatore, Tamil Nadu',
        'rating' => 4.9,
        'review_count' => 128,
        'product_count' => 19,
        'avatar' => 'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?q=80&w=300&auto=format&fit=crop',
        'banner_image' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=800&auto=format&fit=crop',
        'short_bio' => 'Organic cotton kurtis, naturally dyed dupattas, and handcrafted fabric bags supporting rural weavers.',
        'full_story' => 'Meenakshi works directly with a network of 14 women handloom weavers across Tamil Nadu and Andhra Pradesh. She merges traditional Ajrakh and Bagru block prints with modern relaxed fits.',
        'specialty' => 'Hand Block Printed Chanderi Dupattas',
        'joined_year' => '2021',
        'badges' => ['Handloom Certified', 'Direct Weaver'],
        'is_verified' => 1,
        'whatsapp' => '+919443212345'
    ]
];

$fallback_products = [
    [
        'id' => 1,
        'name' => 'Authentic Crunchy Bhajani Chakli (500g)',
        'slug' => 'authentic-crunchy-bhajani-chakli',
        'category' => 'Homemade Food',
        'category_slug' => 'homemade-food',
        'seller_id' => 1,
        'seller_name' => 'Annapurna Swaad',
        'seller_location' => 'Pune, Maharashtra',
        'seller_avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=300&auto=format&fit=crop',
        'price' => 280,
        'original_price' => 320,
        'unit' => 'pack of 500g',
        'rating' => 4.9,
        'review_count' => 142,
        'badge' => 'Bestseller',
        'in_stock' => 1,
        'stock_quantity' => 45,
        'images' => [
            'https://images.unsplash.com/photo-1599488615731-7e5c2823ff28?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1601050690597-df0568f70950?q=80&w=800&auto=format&fit=crop'
        ],
        'description' => 'Traditional Maharashtrian roasted grain flour chakli prepared with roasted rice, split lentils, coriander seeds, and cumin seeds in pure groundnut oil. Perfectly spiced, spiral-shaped and non-greasy.',
        'features' => [
            '100% Homemade fresh in small batches',
            'No preservatives, artificial colors or palm oil',
            'Prepared in cold-pressed groundnut oil',
            'Crisp spiral texture with distinct cumin aroma'
        ],
        'ingredients' => ['Roasted Rice Flour', 'Roasted Chana Dal', 'Urad Dal', 'Sesame Seeds', 'Ajwain', 'Cumin', 'Red Chilli', 'Cold-Pressed Groundnut Oil'],
        'prep_time' => 'Freshly prepared daily (Ships in 24 hrs)',
        'is_featured' => 1
    ],
    [
        'id' => 2,
        'name' => 'Shahi Besan Ladoo with Pure Desi Ghee (400g)',
        'slug' => 'shahi-besan-ladoo-desi-ghee',
        'category' => 'Homemade Food',
        'category_slug' => 'homemade-food',
        'seller_id' => 1,
        'seller_name' => 'Annapurna Swaad',
        'seller_location' => 'Pune, Maharashtra',
        'seller_avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=300&auto=format&fit=crop',
        'price' => 340,
        'original_price' => 390,
        'unit' => 'box of 10 pcs',
        'rating' => 5.0,
        'review_count' => 98,
        'badge' => 'Festive',
        'in_stock' => 1,
        'stock_quantity' => 30,
        'images' => [
            'https://images.unsplash.com/photo-1601050690597-df0568f70950?q=80&w=800&auto=format&fit=crop'
        ],
        'description' => 'Slow-roasted coarse gram flour with pure A2 Desi Cow Ghee, organic raw sugar (bura), green cardamom, and roasted pistachio slivers that melt in your mouth.',
        'features' => [
            'Made exclusively with pure A2 Desi Cow Ghee',
            'Slow roasted on low flame for 45 minutes',
            'Mouth-melting texture with coarse danedaar crunch',
            'Cardamom & Pistachio loaded'
        ],
        'ingredients' => ['Coarse Gram Flour (Besan)', 'A2 Desi Cow Ghee', 'Boora / Raw Cane Sugar', 'Green Cardamom Powder', 'Pistachios', 'Almonds'],
        'prep_time' => 'Made on order for maximum freshness',
        'is_featured' => 1
    ],
    [
        'id' => 3,
        'name' => 'Handcrafted Terracotta Floral Necklace Set',
        'slug' => 'handcrafted-terracotta-floral-necklace-set',
        'category' => 'Jewellery & Accessories',
        'category_slug' => 'jewellery-accessories',
        'seller_id' => 2,
        'seller_name' => 'Mrittika Clay Art',
        'seller_location' => 'Kolkata, West Bengal',
        'seller_avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=300&auto=format&fit=crop',
        'price' => 650,
        'original_price' => 799,
        'unit' => 'set (Necklace + Jhumkas)',
        'rating' => 4.8,
        'review_count' => 76,
        'badge' => 'Handmade',
        'in_stock' => 1,
        'stock_quantity' => 18,
        'images' => [
            'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?q=80&w=800&auto=format&fit=crop'
        ],
        'description' => 'Hand-molded and sun-dried clay jewellery set, kiln-baked and hand-painted in traditional mustard yellow and terracotta red with gold leaf accents.',
        'features' => [
            '100% Eco-friendly organic riverbed clay',
            'Adjustable soft cotton dori back string',
            'Lightweight, skin-safe and water-resistant coated',
            'Includes matching floral teardrop jhumkas'
        ],
        'ingredients' => ['Natural Terracotta Clay', 'Non-toxic Acrylic Paints', 'Cotton Thread Cord', 'Gold Leaf Lacquer'],
        'prep_time' => 'Takes 2-3 days for handcrafting & curing',
        'is_featured' => 1
    ],
    [
        'id' => 4,
        'name' => 'Hand-Poured Mogra & Sandalwood Soy Candle',
        'slug' => 'hand-poured-mogra-sandalwood-soy-candle',
        'category' => 'Natural Candles & Aromas',
        'category_slug' => 'candles-aromas',
        'seller_id' => 3,
        'seller_name' => 'Sugandham Fragrance',
        'seller_location' => 'Nashik, Maharashtra',
        'seller_avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=300&auto=format&fit=crop',
        'price' => 499,
        'original_price' => 599,
        'unit' => 'jar (220g - 45 hrs burn)',
        'rating' => 5.0,
        'review_count' => 64,
        'badge' => 'Bestseller',
        'in_stock' => 1,
        'stock_quantity' => 25,
        'images' => [
            'https://images.unsplash.com/photo-1603006905003-be475563bc59?q=80&w=800&auto=format&fit=crop'
        ],
        'description' => '100% natural soy wax candle infused with pure Madurai Mogra flower essential oil and Mysore sandalwood notes in a reusable frosted amber glass jar.',
        'features' => [
            '100% Pure Soy Wax - Clean toxin-free burn',
            'Lead-free natural cotton braided wick',
            '45+ hours clean burn time',
            'Includes natural wooden dust-lid'
        ],
        'ingredients' => ['Natural Soy Wax', 'Pure Mogra Absolute Oil', 'Sandalwood Essential Oil', 'Cotton Wick', 'Amber Glass Jar'],
        'prep_time' => 'Cured for 7 days before dispatch',
        'is_featured' => 1
    ],
    [
        'id' => 5,
        'name' => 'Hand Block Printed Cotton Chanderi Dupatta',
        'slug' => 'hand-block-printed-chanderi-dupatta',
        'category' => 'Fashion & Clothing',
        'category_slug' => 'fashion-clothing',
        'seller_id' => 4,
        'seller_name' => 'Virasat Handlooms',
        'seller_location' => 'Coimbatore, Tamil Nadu',
        'seller_avatar' => 'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?q=80&w=300&auto=format&fit=crop',
        'price' => 850,
        'original_price' => 1100,
        'unit' => 'piece (2.5 meters)',
        'rating' => 4.9,
        'review_count' => 88,
        'badge' => 'Handmade',
        'in_stock' => 1,
        'stock_quantity' => 12,
        'images' => [
            'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=800&auto=format&fit=crop'
        ],
        'description' => 'Feather-light Chanderi cotton-silk dupatta handcrafted with hand-carved wooden blocks using natural vegetable indigo and madder root dyes.',
        'features' => [
            'Traditional Ajrakh & floral hand-block motifs',
            'Subtle zari border detailing with tassels',
            'Super soft, breathable and drape-friendly',
            'Hand-dyed by skilled women artisans'
        ],
        'ingredients' => ['Pure Chanderi Cotton-Silk Blend', 'Natural Vegetable Dyes', 'Zari Thread Weft'],
        'prep_time' => 'Handloom craft (Ships in 24 hrs)',
        'is_featured' => 1
    ],
    [
        'id' => 6,
        'name' => 'Spicy Kachi Kairi Raw Mango Pickle',
        'slug' => 'spicy-raw-mango-pickle-ghar-ka-achaar',
        'category' => 'Homemade Food',
        'category_slug' => 'homemade-food',
        'seller_id' => 1,
        'seller_name' => 'Annapurna Swaad',
        'seller_location' => 'Pune, Maharashtra',
        'seller_avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=300&auto=format&fit=crop',
        'price' => 240,
        'original_price' => 280,
        'unit' => 'glass jar 400g',
        'rating' => 4.9,
        'review_count' => 112,
        'badge' => '100% Organic',
        'in_stock' => 1,
        'stock_quantity' => 35,
        'images' => [
            'https://images.unsplash.com/photo-1627308595229-7830a5c91f9f?q=80&w=800&auto=format&fit=crop'
        ],
        'description' => 'Traditional sun-cured raw mango pickle seasoned with roasted fenugreek, mustard seeds, asafoetida, red chili and steeped in cold-pressed mustard oil.',
        'features' => [
            'Sun-fermented for 14 days in ceramic barnis',
            'Zero synthetic vinegar or chemical preservatives',
            'Raw mango chunks with authentic home pungency',
            'Cold-pressed mustard oil base'
        ],
        'ingredients' => ['Rajapuri Raw Mango Chunks', 'Mustard Seeds', 'Fenugreek Seeds', 'Hing (Asafoetida)', 'Turmeric', 'Red Chilli Powder', 'Sea Salt', 'Kachi Ghani Mustard Oil'],
        'prep_time' => 'Aged & ready to eat',
        'is_featured' => 1
    ]
];

// Helper query functions
function get_all_products($pdo = null) {
    global $fallback_products;
    return $fallback_products;
}

function get_product_by_slug($slug, $pdo = null) {
    global $fallback_products;
    foreach ($fallback_products as $p) {
        if ($p['slug'] === $slug || (string)$p['id'] === $slug) {
            return $p;
        }
    }
    return $fallback_products[0];
}

function get_categories($pdo = null) {
    global $fallback_categories;
    return $fallback_categories;
}

function get_sellers($pdo = null) {
    global $fallback_sellers;
    return $fallback_sellers;
}

function get_seller_by_id($id, $pdo = null) {
    global $fallback_sellers;
    foreach ($fallback_sellers as $s) {
        if ((int)$s['id'] === (int)$id) {
            return $s;
        }
    }
    return $fallback_sellers[0];
}
?>
