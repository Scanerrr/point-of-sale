<?php

$names = [];

for($i = 1; $i <= 20; $i++) {
    $faker = Faker\Factory::create();
    $names[] = $faker->word;
}

$names = array_unique($names);

$locations = [];
for ($i = 1; $i <= 5; $i++) {
    $faker = Faker\Factory::create();
    $locations[] = [
        'id' => $i,
        'prefix' => $faker->word . $i,
        'name' => $names[$i],
        'region_id' => rand(1, 10),
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'country' => $faker->country,
        'state' => '',
        'city' => $faker->city,
        'address' => $faker->streetAddress,
        'zip' => rand(10000, 99999),
        'tax_rate' => $faker->randomFloat(5, 0, 100),
        'status' => rand(0, 1),
        'created_at' => date('Y-m-d H:i:s', strtotime('-' . $i * 2 . ' day')),
        'updated_at' => null,
    ];
}
return $locations;
