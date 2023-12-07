<?php

$db = new mysqli('localhost', 'root', '', 'socotra');

if($db->connect_error){
    echo $db->connect_error;
}