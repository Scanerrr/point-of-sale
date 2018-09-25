<?php
$users = [
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
        'avatar' => $faker->imageUrl(),
        'phone' => $faker->phoneNumber,
        'position' => null,
        'country' => $faker->country,
        'state' => 'rand',
        'city' => $faker->city,
        'zip' => rand(10000, 99999),
        'address' => $faker->streetAddress,
        'role' => rand(1, 3),
        'status' => rand(0, 1),
        'created_at' => date('Y-m-d H:i:s', strtotime('-' . $i * 2 . ' day')),
        'updated_at' => null,
    ];
}
return $users;