<?php

function getSalarySettings() {
    return json_encode([
//            'steps' => [
//                0 => ['from' => 0, 'to' => 500, 'commission' => rand(5, 10)],
//                1 => ['from' => 501, 'to' => 1000, 'commission' => rand(11, 15)],
//            ],
            'flat' => rand(0, 1) ? ['rate' => rand(5, 10)]: false,
            'product' => rand(0, 1) ? true: false,
            'hourly' => rand(0, 1) ? ['rate' => rand(10, 100), 'notIncludeBreaks' => false] : false,
            'base' => rand(0, 1) ? ['rate' => rand(1000, 5000), 'added' => 'Weekly', 'on' => 'Monday'] : false
    ]);
}
$users = [];
$users[] = [
    'id' => 1,
    'auth_key' => Yii::$app->security->generateRandomString(),
    'password_hash' => Yii::$app->security->generatePasswordHash('123456'),
    'password_reset_token' => null,
    'username' => 'admin',
    'email' => 'admin@ex.org',
    'name' => 'admin',
    'avatar' => null,
    'phone' => '',
    'position' => null,
    'country' => '',
    'state' => '',
    'city' => '',
    'zip' => '',
    'address' => '',
    'role' => 3,
    'status' => 1,
    'salary_settings' => getSalarySettings(),
    'created_at' => date('Y-m-d H:i:s', strtotime('-777 day')),
    'updated_at' => date('Y-m-d H:i:s', strtotime('-666 day')),
];
for ($i = 2; $i <= 11; $i++) {
    $faker = Faker\Factory::create();
    $users[] = [
        'id' => $i,
        'auth_key' => Yii::$app->security->generateRandomString(),
        'password_hash' => Yii::$app->security->generatePasswordHash('123456'),
        'password_reset_token' => null,
        'username' => $faker->userName,
        'email' => $faker->email,
        'name' => $faker->name,
        'avatar' => null,
        'phone' => $faker->phoneNumber,
        'position' => null,
        'country' => $faker->country,
        'state' => 'rand',
        'city' => $faker->city,
        'zip' => rand(10000, 99999),
        'address' => $faker->streetAddress,
        'role' => rand(1, 3),
        'status' => $i % 2 === 0 ? rand(0, 1) : 1,
        'salary_settings' => getSalarySettings(),
        'created_at' => $faker->dateTimeBetween('-2 year')->format('Y-m-d'),
        'updated_at' => null,
    ];
}
return $users;