<?php
function print_($txt){
    echo '<pre style="background:darkblue;color:white;font-family:consolas;font-weight:bold">';
    print_r($txt);
    echo '</pre>';
}
function getPage()
{
    if (isset($_GET['p'])) {
        return $_GET['p'];
    } else {
        return 'home';
    }
}
