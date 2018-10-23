<?php
$orders = [];
for ($i = 1; $i <= 1500; $i++) {
    $faker = Faker\Factory::create();
    $orders[] = [
        'id' => $i,
        'status' => $i % 2 === 0 ? rand(-1, 3) : 3,
        'location_id' => rand(1, 10),
        'employee_id' => rand(1, 10),
        'customer_id' => rand(1, 15),
        'total_tax' => $faker->randomFloat(5, 0, 500),
        'total' => $faker->randomFloat(5, 0, 10000),
        'created_at' => $faker->dateTimeBetween('-2 year')->format('Y-m-d'),
        'updated_at' => null,
    ];
}
return $orders;