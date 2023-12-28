<?php
include_once('../lib/database.php');
include_once('../lib/__functions.php');
if (isset($_GET['deleteForm'])) {
    $id = $db->real_escape_string($_GET['id']);
    $table = $db->real_escape_string($_GET['table']);
    $q = "DELETE FROM $table WHERE id = $id";
    $query = $db->query($q);
    if($query){
        echo 'success';
        exit();
    }else{
        echo 'error';
        exit();
    }
}
