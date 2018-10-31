<?php
$inventories = [];
$count = 0;
for ($i = 1; $i <= 5; $i++) {
    for ($j = 1; $j <= 20; $j++) {
        $inventories[] = [
            'id' => ++$count,
            'location_id' => $i,
            'product_id' => $j,
            'quantity' => rand(0, 1000),
        ];
    }
}
return $inventories;