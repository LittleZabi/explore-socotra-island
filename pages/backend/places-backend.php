<?php
$title = "PLACES";

$places = [];
$q = $db->query("SELECT * FROM places");
if($q->num_rows > 0){
    $places = $q->fetch_all(MYSQLI_ASSOC);
}