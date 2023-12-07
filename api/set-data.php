<?php
include_once('../lib/database.php');
include_once('../lib/__functions.php');
if (isset($_POST['signup'])) {
    $name = $db->real_escape_string($_POST['fullname']);
    $email = $db->real_escape_string($_POST['email']);
    $password = $db->real_escape_string($_POST['password']);
    $roo_path = $db->real_escape_string($_POST['root_path']);
    $response = array('success' => 0, "message" => "");
    $path = '';
    if ($email != '') {
        $sql = "SELECT email FROM users WHERE email='$email'";
        $query = $db->query($sql);
        if ($query->num_rows > 0) {
            $response['success'] = 0;
            $response['message'] = "Email is already registered. please login or use another email address.";
            exit(json_encode($response));
        }
    }
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
        $filename = getRandomChar(12, $options = array("numbers" => false, "symbols" => false, "seperator" => false, "lowercase" => true, "uppercase" => false));
        $extension = pathinfo(basename($_FILES['avatar']['name']), PATHINFO_EXTENSION);
        $path = DIRECTORY_SEPARATOR . 'static' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . $filename . '.' . $extension;
        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $roo_path . $path)) {
            $response["avatar"] = "failed to save image.";
        }
    }
    if ($name != '' && $email != '' && $password != '') {
        $password = md5($password);
        $sql = "INSERT INTO users(name, email, password, avatar) VALUES('$name', '$email', '$password', '$path')";
        $query = $db->query($sql);
        if ($query) {
            $response['success'] = 1;
            $response['message'] = "Account Registered Successfully. now you can use these credentials to login every time.";
            $response['email'] = $email;
            $response['name'] = $name;
            $response['avatar'] = $path;
        }
    }
    exit(json_encode($response));
}
