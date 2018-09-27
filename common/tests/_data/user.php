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
    'salary_settings' => json_encode([
        'commissions' => [
            'steps' => [
                0 => ['from' => 0, 'to' => 500, 'commission' => rand(5, 10)],
                1 => ['from' => 501, 'to' => 1000, 'commission' => rand(11, 15)],
            ],
            'flat' => rand(5, 10),
            'product' => true
        ],
        'salaries' => [
            'hourly' => ['rate' => rand(10, 100), 'includeBreaks' => false],
            'base' => ['rate' => rand(1000, 5000), 'added' => 'Weekly', 'on' => 'Monday']
        ]
    ]),
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
        'status' => rand(0, 1),
        'salary_settings' => json_encode([
            'commissions' => [
                'steps' => [
                    0 => ['from' => 0, 'to' => 500, 'commission' => rand(11, 10)],
                    1 => ['from' => 501, 'to' => 1000, 'commission' => rand(5, 15)],
                ],
                'flat' => rand(5, 10),
                'product' => true
            ],
            'salaries' => [
                'hourly' => ['rate' => rand(10, 100), 'includeBreaks' => false],
                'base' => ['rate' => rand(1000, 5000), 'added' => 'Weekly', 'on' => 'Monday']
            ]
        ]),
        'created_at' => date('Y-m-d H:i:s', strtotime('-' . $i * 2 . ' day')),
        'updated_at' => null,
    ];
}
var_dump($users);
//return $users;