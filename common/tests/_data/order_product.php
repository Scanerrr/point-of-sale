<?php
$order_products = [];
for ($i = 1; $i <= 20; $i++) {
    $faker = Faker\Factory::create();
    $order_products[] = [
        'id' => $i,
        'order_id' => rand(1, 15),
        'product_id' => rand(1 ,20),
        'quantity' => rand(1, 10),
        'price' => $faker->randomNumber(4),
        'tax' => $faker->randomNumber(3),
        'total' => $faker->randomNumber(4),
    ];
}
return $order_products;