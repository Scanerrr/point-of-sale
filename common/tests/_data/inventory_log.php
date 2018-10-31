<?php
$inventoriesLogs = [];
for ($i = 1; $i <= 200; $i++) {

    $inventoriesLogs[] = [
        'id' => $i,
        'location_id' => rand(1, 5),
        'product_id' => rand(1, 20),
        'user_id' => 1,
        'quantity' => rand(-30, 30),
        'comment' => '',
        'created_at' => date('Y-m-d H:i:s', strtotime('-' . $i * 2 . ' day'))
    ];
}
return $inventoriesLogs;