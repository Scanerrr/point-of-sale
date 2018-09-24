<?php
$users = [];
$roles = [10, 20, 30];
$statuses = [0, 10];
for ($i = 1; $i <= 10; $i++) {
    $users[] = [
        'id' => $i,
        'auth_key' => Yii::$app->security->generateRandomString(),
        'password_hash' => Yii::$app->security->generatePasswordHash('123456'),
        'password_reset_token' => null,
        'username' => 'somename' . $i,
        'email' => 'someemail' . $i . '@ex.org',
        'name' => 'fullname-' . $i,
        'avatar' => null,
        'phone' => $i . $i . $i . $i . $i . $i . $i . $i,
        'position' => null,
        'country' => 'USA',
        'state' => 'rand',
        'city' => 'rand',
        'zip' => '12345',
        'address' => 'rand',
        'role' => $roles[rand(0, 2)],
        'status' => $statuses[rand(0, 1)],
        'created_at' => date('Y-m-d H:i:s', strtotime('-' . $i * 2 . ' day')),
        'updated_at' => null,
    ];
}
return $users;