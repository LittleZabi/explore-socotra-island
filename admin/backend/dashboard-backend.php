<?php

include_once(ROOT_PATH . '/admin/common.php');
$title = 'DASHBOARD';
$counts = ['users' => getDataCounts('users'), 'places' => getDataCounts('places'), 'visa' => getDataCounts('visa'), 'hotel' => getDataCounts('hotel_res')];
$items = [
    'hotel' => getItems('hotel_res',  ['id', 'name', 'hotel'], 5),
    'users' => getItems('users', ['id', 'name', 'email'], 5),
    'visa' => getItems('visa', ['id', 'name', 'createdAt'], 5) 
];