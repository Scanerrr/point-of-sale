<?php
$orders = [];
for ($i = 1; $i <= 15; $i++) {
    $faker = Faker\Factory::create();
    $orders[] = [
        'id' => $i,
        'invoice_id' => rand(0, 50),
        'status_id' => rand(1, 6),
        'employee_id' => rand(1, 10),
        'customer_id' => rand(1, 15),
        'total_tax' => $faker->randomFloat(5, 0, 500),
        'total' => $faker->randomFloat(5, 0, 10000),
        'created_at' => date('Y-m-d H:i:s', strtotime('-' . $i * 2 . ' day')),
        'updated_at' => null,
    ];
}
return $orders;