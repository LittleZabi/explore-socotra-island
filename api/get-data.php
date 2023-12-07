<?php
include_once('../lib/database.php');
include_once('../lib/__functions.php');
if (isset($_POST['signin'])) {
    $email = $db->real_escape_string($_POST['email']);
    $password = $db->real_escape_string($_POST['password']);
    $response = array('success' => 0, "message" => "");
    if ($email != '' && $password !='') {
        $password = md5($password);
        $sql = "SELECT id, name, email, avatar, createdAt FROM users WHERE email='$email' AND password = '$password'";
        $query = $db->query($sql);
        if ($query->num_rows > 0) {
            $user = $query->fetch_assoc();
            $response['success'] = 0;
            $response['message'] = "Email is already registered. please login or use another email address.";
            exit(json_encode($user));
        }else{
            $response['success'] = 0;
            $response['message'] = "Please check your email address or password.";
            exit(json_encode($response));
        }
    }else{
        $response['success'] = 0;
        $response['message'] = 'Please check your input fields something is missing.';
        exit(json_encode($response));
    }
}
