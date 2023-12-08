<?php
$title = "GET VISA";

$countries = file_get_contents(ROOT_PATH.'/lib/countries.json');
$countries= json_decode($countries, true);

$user = false;
$past_forms = [];
$formView = [];
if(isset($_COOKIE['user'])){
    $user = json_decode($_COOKIE['user']);
    if($user->id){
        $sql = "SELECT id, createdAt, updatedAt FROM visa WHERE user_id = $user->id";
        $query = $db->query($sql);
        if($query->num_rows > 0){
            $past_forms = $query->fetch_all(MYSQLI_ASSOC);
        }
        if(isset($_GET['form-id'])){
            $id = $_GET['form-id'];
            $sql = "SELECT * FROM visa WHERE id=$id AND user_id = $user->id";
            $q = $db->query($sql);
            if($q->num_rows > 0){
                $formView = $q->fetch_assoc();
            }
        }
    }
}