<?php

include_once(ROOT_PATH . '/admin/common.php');
$title = 'DASHBOARD';
$counts = ['tours' => getDataCounts('tours'), 'users' => getDataCounts('users'), 'places' => getDataCounts('places'), 'visa' => getDataCounts('visa'), 'hotel' => getDataCounts('hotel_res'), 'booked_tours' => getDataCounts('booked_tours')];
$items = [
    'hotel' => getItems('hotel_res',  ['id', 'name', 'hotel'], 5),
    'users' => getItems('users', ['id', 'name', 'email'], 5),
    'visa' => getItems('visa', ['id', 'name', 'createdAt'], 5),
];