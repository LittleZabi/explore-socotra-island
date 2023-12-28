<?php 
include_once(ROOT_PATH. '/admin/common.php');
$title = "HOTEL RESERVATION";
if(isset($_GET['d'])) deleteItem();
$i = 0;
$hotels = getItems('hotel_res', [], 0);