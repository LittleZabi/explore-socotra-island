<?php


$title = "PLACES";
include_once(ROOT_PATH . '/admin/common.php');
if(isset($_GET['d'])) deleteItem();
if (isset($_POST['save-form']) && $_POST['save-form'] == 1) {
    $name = $_POST["name"];
    $desc = $_POST["desc"];
    $imageNames = [];
    $q = $db->query("SELECT name FROM places WHERE name='$name'");
    if ($q->num_rows > 0) {
        $_SESSION['message']['text'] = 'This place name is already exist.';
        $_SESSION['message']['variant'] = 'alert';
    } else {
        $targetDirectory = ROOT_PATH . "/static/media/places/";
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0755, true);
        }

        foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
            $rand = getRandomChar(12, $options = array(
                "numbers" => false,
                "symbols" => false,
                "seperator" => false,
                "lowercase" => true,
                "uppercase" => false
            ));
            $extension = pathinfo(basename($_FILES['images']['name'][$key]), PATHINFO_EXTENSION);
            $filename = $rand . '.' . $extension;
            $targetFilePath = $targetDirectory . $rand . '.' . $extension;
            move_uploaded_file($tmp_name, $targetFilePath);
            $imageNames[] = "/static/media/places/" . $filename;
        }
        $imagesString = implode(",", $imageNames);
        $sql = "INSERT INTO places (name, description, images, active) VALUES ('$name', '$desc', '$imagesString', 1)";
        $q = $db->query($sql);
        if ($q) {
            $_SESSION['message']['text'] = "Place added successfully!";
            $_SESSION['message']['variant'] = 'success';
        } else {
            $successMessage =
                $_SESSION['message']['text'] = "Error please try again";
            $_SESSION['message']['variant'] = 'alert';
        }
        $_POST['save-form'] = 0;
    }
}
unset($_POST['save-form']);
$i = 0;
$places = getItems('places', ['id', 'name', 'images', 'active','createdAt'], 0);
