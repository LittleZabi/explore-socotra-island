<?php 


include_once(ROOT_PATH. '/admin/common.php');
$title = "USERS";
if(isset($_GET['d'])) deleteItem();
$i = 0;
$users = getItems('users', ['id','name', 'email', 'avatar', 'createdAt'], 0);