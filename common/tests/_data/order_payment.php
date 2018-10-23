<?php
$orderPayments = [];
for ($i = 1; $i <= 1500; $i++) {
    $faker = Faker\Factory::create();
    $orderPayments[] = [
        'id' => $i,
        'order_id' => $i,
        'method_id' => rand(0, 1),
        'details' => '',
        'amount' => $faker->randomFloat(5, 0, 10000),
        'created_at' => $faker->dateTimeBetween('-2 year')->format('Y-m-d'),
        'updated_at' => null,
    ];
}
return $orderPayments;