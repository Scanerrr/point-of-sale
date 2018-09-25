<?php
$regions = [];
for ($i = 1; $i <= 10; $i++) {
    $regions[] = [
        'id' => $i,
        'parent_id' => rand(1, 10),
        'name' => 'category-' . $i,
        'image' => null,
        'status' => rand(0, 1),
        'created_at' => date('Y-m-d H:i:s', strtotime('-' . $i * 2 . ' day')),
        'updated_at' => null,
    ];
}
return $regions;