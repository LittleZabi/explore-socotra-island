<?php
$title = 'BOOK TOUR NOW';
$post = [];



if (isset($_GET['cancel'])) {
    $cid = $_GET['cancel'];
    $id = $_GET['id'];
    $sql = "DELETE FROM booked_tours WHERE id = $cid";
    $q = $db->query($sql);
    header('location: /?p=book-tour&id=' . $id);
}

$user = false;
$booked_tours = [];
if (isset($_GET['id']) && $_GET['id'] != '') {
    $id = $_GET['id'];
    function red($id)
    {
        header('location:/?p=login&r=book-tour&id=' . $id);
        echo "<script>window.location.href = '/?p=login&r=book-tour'</script>";
        exit('login required');
    }
    if (isset($_COOKIE['user'])) {
        $user = json_decode($_COOKIE['user']);
        if (!isset($user->id)) {
            red($id);
        }
    } else {
        red($id);
    }
    $sql = "SELECT  * FROM booked_tours WHERE user_id = $user->id AND tour_id = $id";
    $q = $db->query($sql);
    if ($q->num_rows > 0) {
        $booked_tours = $q->fetch_assoc();
    }
    $q = "SELECT * FROM tours WHERE id = $id";
    $q = $db->query($q);
    if ($q->num_rows > 0) {
        $post = $q->fetch_assoc();
    } else {
        header('location: /404.php');
        exit('post not found');
    }
} else {
    header('location: /404.php');
    exit('post not found');
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save-form'])) {
    $user_id = mysqli_real_escape_string($db, $_POST['user_id']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $members = mysqli_real_escape_string($db, $_POST['members']);
    $date = mysqli_real_escape_string($db, $_POST['date']);
    $tour_id = mysqli_real_escape_string($db, $_POST['tour_id']);
    if ($date == '') {
        $_SESSION['message'] = ['text' => "Please select a date", 'variant' => 'alert'];
        header('location: /?p=book-tour&id=' . $tour_id);
    }
    $sql = "INSERT INTO booked_tours (user_id, phone, members, date, tour_id) VALUES ('$user_id', '$phone', '$members', '$date', $tour_id)";
    if ($db->query($sql) === TRUE) {
        $_SESSION['message'] = ['text' => 'Tour booked successfully', 'variant' => 'success'];
        header('location: /?p=book-tour&id=' . $tour_id);
    } else {
        $_SESSION['message'] = ['text' => "Error: " . $sql . "<br>" . $conn->error, 'variant' => 'alert'];
    }
}
