<?php
$order_products = [];
for ($i = 1; $i <= 15000; $i++) {
    $faker = Faker\Factory::create();
    $price = $faker->randomNumber(4);
    $tax = $faker->randomNumber(3);
    $quantity = rand(1, 10);
    $order_products[] = [
        'id' => $i,
        'order_id' => rand(1, 10000),
        'product_id' => rand(1 ,20),
        'quantity' => rand(1, 10),
        'price' => $price,
        'tax' => $tax,
        'total' => ($price + $tax) * $quantity,
    ];
}
return $order_products;