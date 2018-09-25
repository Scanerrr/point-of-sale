<?php

$products = [];
for ($i = 1; $i <= 20; $i++) {
    $faker = Faker\Factory::create();
    $products[] = [

        'id' => $i,
        'category_id' => rand(1, 10),
        'supplier_id' => rand(1, 2),
        'name' => $faker->word,
        'description' => $faker->text,
        'cost_price' => $faker->randomNumber(4),
        'markup_price' => $faker->randomNumber(4),
        'max_price' => $faker->randomNumber(4),
        'tax' => $faker->randomFloat(5, 0, 100),
        'commission_policy_id' => rand(1, 3),
        'image' => $faker->imageUrl(),
        'barcode' => 'ID-' . $faker->randomNumber(5),
        'size' => null,
        'sku' => $faker->randomNumber(6),
        'status' => rand(0, 1),
        'created_at' => date('Y-m-d H:i:s', strtotime('-' . $i * 2 . ' day')),
        'updated_at' => null,


    ];
}
return $products;