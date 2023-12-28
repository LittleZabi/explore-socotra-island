<?php $title = 'ADMIN LOGIN';
$user = false;
$message = false;
if(isset($_POST['admin-login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = md5($password);
    $sql = "SELECT username FROM admin WHERE username = '$username' AND password = '$password'";
    $q = $db->query($sql);
    if($q->num_rows > 0){
        $user = $q->fetch_assoc();
        setcookie('admin', $user['username'], time()+606024*30, $path="/");
        header('location: /admin.php');
    }else{
        $message = 'Incorrect credentials please check username or password.';
    }

}