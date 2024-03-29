<?php
session_start();
define('ROOT_PATH', __DIR__);
$title = 'ADMIN';
$user = false;
if (isset($_COOKIE['admin'])) {
    $user = $_COOKIE['admin'];
}
include_once('./lib/__functions.php');
include_once('./lib/database.php');

$current_page = getPage();
if ($current_page == 'home') $current_page = 'dashboard';
include_once('./lib/global-vars.php');
$b = __DIR__ . '/admin/backend/' . $current_page . '-backend.php';

if (is_file($b)) include_once($b);

include_once(__DIR__ . '/admin/components/Head.phtml');
?>
<div id="main-container" class="fade-in">
    <?php
    include_once(__DIR__ . '/admin/components/Header.phtml');
    ?>
    <main>
        <div class='admin-view'>
            <?php if ($user) {
                include_once(__DIR__ . '/admin/components/left-side.phtml');
            } ?>
            <div class="admin-right">
                <?php
                $p = __DIR__ . '/admin/frontend/' . $current_page . '.phtml';
                if (is_file($p)) include_once($p);
                else include_once(__DIR__ . '/pages/404.php');
                ?>
            </div>
        </div>
    </main>
    <?php
    include_once(__DIR__ . '/components/Footer.phtml');
    ?>
</div>