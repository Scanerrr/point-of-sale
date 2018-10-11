<?php
$names = [];

for($i = 1; $i <= 50; $i++) {
    $faker = Faker\Factory::create();
    $names[] = $faker->word;
}

$names = array_unique($names);

$products = [];
for ($i = 1; $i <= 20; $i++) {
    $faker = Faker\Factory::create();
    $products[] = [
        'id' => $i,
        'category_id' => rand(1, 10),
        'supplier_id' => rand(1, 2),
        'name' => $names[$i] ?? $faker->word,
        'description' => $faker->text,
        'cost_price' => $faker->randomNumber(4),
        'markup_price' => $faker->randomNumber(4),
        'max_price' => $faker->randomNumber(4),
        'tax' => $faker->randomFloat(5, 0, 100),
        'commission_policy_id' => rand(1, 3),
        'commission' => $faker->randomNumber(2),
        'image' => null,
        'barcode' => 'ID-' . $faker->ean8,
        'size' => null,
        'sku' => $faker->ean8,
        'status' => $i % 2 === 0 ? rand(0, 1) : 1,
        'created_at' => date('Y-m-d H:i:s', strtotime('-' . $i * 2 . ' day')),
        'updated_at' => null,


    ];
}
return $products;