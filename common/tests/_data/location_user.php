<?php
$locations_users = [];

for ($i = 1; $i <= 10; $i++) {
    $locations_users[] = [
        'location_id' => rand(1 ,5),
        'user_id' => $i % 2 === 0 ? rand(1, 11) : 1,
    ];
}

return array_unique($locations_users, SORT_REGULAR);
