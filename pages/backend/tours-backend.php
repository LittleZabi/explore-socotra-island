<?php
$title = "TOURS";
$tours = [];
$q = $db->query("SELECT * FROM tours ORDER BY id DESC ");
if ($q->num_rows > 0) {
    $tours = $q->fetch_all(MYSQLI_ASSOC);
}
$booked_tours = [];
if (isset($_COOKIE['user'])) {
    $user = json_decode($_COOKIE['user']);
    if (isset($user->id)) {
        $sql = "SELECT  tour_id FROM booked_tours WHERE user_id = $user->id";
        $q = $db->query($sql);
        if ($q->num_rows > 0) {
            $x = $q->fetch_all(MYSQLI_ASSOC);
            foreach($x as $row){
                $booked_tours[] = $row['tour_id'];
            }
        }
    }
}
