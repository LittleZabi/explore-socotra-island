<?php
define('ROOT_PATH', __DIR__);
$title = 'HOME';
include_once('./lib/global-vars.php');
include_once('./lib/__functions.php');
include_once('./lib/database.php');

$b = __DIR__ . '/pages/backend/' . getPage() . '-backend.php';
if (is_file($b)) include_once($b);

include_once(__DIR__ . '/components/Head.phtml');
?>
<div id="main-container">
    <?php
    include_once(__DIR__ . '/components/Header.phtml');
    ?>
    <main>
        <?php
        $p = __DIR__ . '/pages/frontend/' . getPage() . '.phtml';
        if (is_file($p)) include_once($p);
        else include_once(__DIR__ . '/pages/404.php');
        ?>
    </main>
    <?php
    include_once(__DIR__ . '/components/Footer.phtml');
    ?>
</div>