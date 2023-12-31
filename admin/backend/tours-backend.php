<?php

$title = "TOURS";
include_once(ROOT_PATH . '/admin/common.php');
if (isset($_GET['d'])) deleteItem();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save-form'])) {
    $title = $db->real_escape_string($_POST['title1']);
    $description = $db->real_escape_string($_POST['desc']);
    $location = $db->real_escape_string($_POST['location']);
    $price = $db->real_escape_string($_POST['price']);
    $members = $db->real_escape_string($_POST['members']);
    $duration = $db->real_escape_string($_POST['duration']);
    $itinerary = $db->real_escape_string($_POST['itinary']);
    $included = $db->real_escape_string($_POST['included']);
    $not_included = $db->real_escape_string($_POST['not_included']);

    $rel_dir = "/static/media/tours/";
    $image_name = $rel_dir . rand(9999, 999999) . '-' . $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($image_tmp, ROOT_PATH . $image_name);
    $sql = "INSERT INTO tours (title, location, description, price, members, duration, itinerary, included, not_included, image) VALUES ('$title', '$location',  '$description',$price, '$members', '$duration', '$itinerary', '$included', '$not_included', '$image_name')";
    if ($db->query($sql) === TRUE) {
        $_SESSION['message'] = ['text' => 'Tour saved successfully', 'variant' => 'success'];
        header('location:/admin.php?p=tours');
        exit('refresh the page');
    } else {
        $_SESSION['message'] = ['text' => 'Error saving tour: ' . $db->error, 'variant' => 'error'];
    }
}
unset($_POST['save-form']);
$i = 0;
$places = getItems('tours', [], 0);
$formData = [
    'title' => '',
    'price' => '',
    'members' => '',
    'location' => '',
    'desc' => '',
    'duration' => '',
    'itinary' => '',
    'included' => '',
    'not_included' => '',
    'image' => ''
];

if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql = "SELECT * FROM tours WHERE id = $edit_id";
    $result = $db->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $formData = [
            'title' => $row['title'],
            'desc' => $row['description'],
            'price' => $row['price'],
            'location' => $row['location'],
            'members' => $row['members'],
            'duration' => $row['duration'],
            'itinary' => $row['itinerary'],
            'included' => $row['included'],
            'not_included' => $row['not_included'],
            'image' => $row['image']
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['udpate-form'])) {
    $title = $db->real_escape_string($_POST['title1']);
    $description = $db->real_escape_string($_POST['desc']);
    $price = $db->real_escape_string($_POST['price']);
$location = $db->real_escape_string($_POST['location']);
    $members = $db->real_escape_string($_POST['members']);
    $duration = $db->real_escape_string($_POST['duration']);
    $itinerary = $db->real_escape_string($_POST['itinary']);
    $included = $db->real_escape_string($_POST['included']);
    $not_included = $db->real_escape_string($_POST['not_included']);
    $image_name = $db->real_escape_string($_POST['prev_image']);
    $edit_id = $_GET['edit_id'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $previousImage = ROOT_PATH . $image_name; // Replace with the path to the previous image
        if (file_exists($previousImage)) {
            unlink($previousImage);
        }
        // Save the new image
        $rel_dir = "/static/media/tours/";
        $image_name = $rel_dir . rand(9999, 999999) . '-' . $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($image_tmp, ROOT_PATH . $image_name);
    }
    $sql = "UPDATE tours 
        SET title = '$title', 
            description = '$description', 
            price = $price,
            location = '$location',
            members = '$members',
            duration = '$duration', 
            itinerary = '$itinerary', 
            included = '$included', 
            not_included = '$not_included', 
            image = '$image_name' 
        WHERE id = $edit_id";
    if ($db->query($sql) === TRUE) {
        $_SESSION['message'] = ['text' => 'Tour updated successfully', 'variant' => 'success'];
        header('location:/admin.php?p=tours&edit_id=' . $edit_id);
        exit('refresh the page');
    } else {
        $_SESSION['message'] = ['text' => 'Error updating tour: ' . $db->error, 'variant' => 'error'];
    }
}


$db->close();
