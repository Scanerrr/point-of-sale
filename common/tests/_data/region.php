<?php
$regions = [];
for ($i = 1; $i <= 10; $i++) {
    $regions[] = [
        'id' => $i,
        'name' => 'name-' . $i,
    ];
}
return $regions;