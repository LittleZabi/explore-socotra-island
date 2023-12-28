<?php
$title = "LOGOUT";
if (isset($_GET['admin-logout'])) {
    setcookie('admin', false, -3234234);
    header('location: /admin.php?p=login');
}
