<?php
session_start();
define('ROOT_PATH', __DIR__);
$title = 'HOME';
include_once('./lib/__functions.php');
include_once('./lib/database.php');

$current_page = getPage();
include_once('./lib/global-vars.php');
$b = __DIR__ . '/pages/backend/' . $current_page . '-backend.php';
if (is_file($b)) include_once($b);

include_once(__DIR__ . '/components/Head.phtml');
?>
<div id="main-container" class="">
    <?php
    include_once(__DIR__ . '/components/Header.phtml');
    ?>
    <main>
        <?php
        $p = __DIR__ . '/pages/frontend/' . getPage() . '.phtml';
        if (is_file($p)) include_once($p);
        else {
            echo 'page => ';
            echo $p;
            echo '<br/>';
            include_once(__DIR__ . '/404.php');
        }
        ?>
    </main>
    <?php
    include_once(__DIR__ . '/components/Footer.phtml');
    ?>
</div>