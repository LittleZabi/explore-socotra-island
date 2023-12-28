<?php 

$title = "VISA";
include_once(ROOT_PATH. '/admin/common.php');
if(isset($_GET['d'])) deleteItem();
$i = 0;
$visas = getItems('visa', ['id','name', 'nationality', 'passport_no', 'permanent_addr', 'createdAt'], 0);