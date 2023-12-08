<?php
include_once('../lib/database.php');
include_once('../lib/__functions.php');
function sanitizeInput($input)
{
    global $db;
    return $db->real_escape_string($input);
}


if (isset($_POST['save-visa'])) {
    $form_id = false;
    $formType = $_POST['form-type'];
    if ($formType === 'update') $form_id = $_POST['form-id'];
    $name = sanitizeInput($_POST['name']);
    $nationality = sanitizeInput($_POST['nationality']);
    $gender = sanitizeInput($_POST['gender']);
    $dob = sanitizeInput($_POST['dob']);
    $martial = sanitizeInput($_POST['martial']);
    $profession = sanitizeInput($_POST['profession']);
    $passport_no = sanitizeInput($_POST['passport_no']);
    $passport_type = sanitizeInput($_POST['passport_type']);
    $date_of_issue = sanitizeInput($_POST['date_of_issue']);
    $expiry_date = sanitizeInput($_POST['expiry_date']);
    $other_name = sanitizeInput($_POST['other_name']);
    $permanent_addr = sanitizeInput($_POST['permanent_addr']);
    $phone = sanitizeInput($_POST['phone']);
    $purpose_of_visit = sanitizeInput($_POST['purpose_of_visit']);
    $duration_of_visa_req = sanitizeInput($_POST['duration_of_visa_req']);
    $departure_date = sanitizeInput($_POST['departure_date']);
    $stay_period = sanitizeInput($_POST['stay_period']);
    $reference_in_yemen = sanitizeInput($_POST['reference_in_yemen']);
    $user_id = sanitizeInput(intval($_POST['user_id']));
    if ($formType == 'update') $query =  "UPDATE visa SET
        name = '$name',
        nationality = '$nationality',
        gender = '$gender',
        dob = '$dob',
        martial = '$martial',
        profession = '$profession',
        passport_no = '$passport_no',
        passport_type = '$passport_type',
        date_of_issue = '$date_of_issue',
        expiry_date = '$expiry_date',
        other_name = '$other_name',
        permanent_addr = '$permanent_addr',
        phone = '$phone',
        purpose_of_visit = '$purpose_of_visit',
        duration_of_visa_req = '$duration_of_visa_req',
        departure_date = '$departure_date',
        stay_period = '$stay_period',
        reference_in_yemen = '$reference_in_yemen',
        updatedAt = date('Y-m-d H:i:s')
        WHERE id = $form_id";
    else
        $query = "INSERT INTO visa (
            name, nationality, gender, dob, martial, profession, passport_no, passport_type, date_of_issue,
            expiry_date, other_name, permanent_addr, phone, purpose_of_visit, duration_of_visa_req,
            departure_date, stay_period, reference_in_yemen, user_id) VALUES (
            '$name', '$nationality', '$gender', '$dob', '$martial', '$profession', '$passport_no', '$passport_type',
            '$date_of_issue', '$expiry_date', '$other_name', '$permanent_addr', '$phone', '$purpose_of_visit',
            '$duration_of_visa_req', '$departure_date', '$stay_period', '$reference_in_yemen', $user_id)";

    $result = $db->query($query);
    if ($result) {
        echo 'success';
        exit();
    } else {
        echo 'failed_to_save';
        exit();
    }
    $db->close();
}
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
        $path = '/static/media/users/' . $filename . '.' . $extension;
        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $roo_path . $path)) {
            $response["avatar"] = "failed to save image.";
        }
    }
    if ($name != '' && $email != '' && $password != '') {
        $password = md5($password);
        $sql = "INSERT INTO users(name, email, password, avatar) VALUES('$name', '$email', '$password', '$path')";
        $query = $db->query($sql);
        if ($query) {
            $sql = "SELECT id FROM users WHERE email = '$email'";
            $q = $db->query($sql);
            $user_id = $q->fetch_assoc();
            $response['success'] = 1;
            $response['message'] = "Account Registered Successfully. now you can use these credentials to login every time.";
            $response['email'] = $email;
            $response['name'] = $name;
            $response['avatar'] = $path;
            $response['id'] = $user_id['id'];
        }
    }
    exit(json_encode($response));
}
