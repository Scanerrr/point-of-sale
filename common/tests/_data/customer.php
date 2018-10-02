<?php

$customers = [];
for ($i = 1; $i <= 15; $i++) {
    $faker = Faker\Factory::create();
    $customers[] = [
        'id' => $i,
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'phone' => $faker->phoneNumber,
        'gender' => 'female',
        'email' => $faker->email,
        'added_by' => rand(0, 10),
        'country' => $faker->country,
        'state' => null,
        'city' => $faker->city,
        'address' => $faker->address,
        'zip' => $faker->randomNumber(5),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => null,
    ];
}
return $customers;