<?php 
$title = "HOTEL RESERVATION";

$user = false;
$past_forms = [];
$formView = [];
if(isset($_COOKIE['user'])){
    $user = json_decode($_COOKIE['user']);
    if($user->id){
        $sql = "SELECT id, created_at, updated_at, hotel, check_in_date, check_out_date FROM hotel_res WHERE user_id = $user->id";
        $query = $db->query($sql);
        if($query->num_rows > 0){
            $past_forms = $query->fetch_all(MYSQLI_ASSOC);
        }
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $sql = "SELECT * FROM hotel_res WHERE id=$id AND user_id = $user->id";
            $q = $db->query($sql);
            if($q->num_rows > 0){
                $formView = $q->fetch_assoc();
            }
        }
    }
}