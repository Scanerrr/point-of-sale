<?php
$orderPayments = [];
for ($i = 1; $i <= 15; $i++) {
    $faker = Faker\Factory::create();
    $orderPayments[] = [
        'id' => $i,
        'order_id' => $i,
        'method_id' => rand(0, 1),
        'details' => '',
        'amount' => $faker->randomFloat(5, 0, 10000),
        'created_at' => date('Y-m-d H:i:s', strtotime('-' . $i * 2 . ' day')),
        'updated_at' => null,
    ];
}
return $orderPayments;