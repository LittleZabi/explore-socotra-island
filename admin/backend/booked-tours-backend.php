<?php


include_once(ROOT_PATH . '/admin/common.php');
$title = "USERS";
if (isset($_GET['d'])) deleteItem();
$i = 0;
//$users = getItems('booked_tours', ['id','name', 'email', 'avatar', 'createdAt'], 0);
$sql = "SELECT booked_tours.*, users.name AS user_name, tours.title AS tour_title, tours.price as price
FROM booked_tours
JOIN users ON booked_tours.user_id = users.id
JOIN tours ON booked_tours.tour_id = tours.id;
";
$q = $db->query($sql);
$booked = [];
if ($q->num_rows > 0)
    $booked = $q->fetch_all(MYSQLI_ASSOC);
