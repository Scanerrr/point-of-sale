<?php
$locations_users = [];
for ($i = 1; $i <= 10; $i++) {
    $locations_users[] = [
        'id' => $i,
        'location_id' => rand(1 ,5),
        'user_id' => rand(1, 11),
    ];
}
return $locations_users;
