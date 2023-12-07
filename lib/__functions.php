<?php
function print_($txt)
{
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
function getRandomChar($length, $options = array(
    "numbers" => true,
    "symbols" => true,
    "separator" => true,
    "segment" => 5,
    "uppercase" => true,
    "lowercase" => true,
))
{
    $options = array_merge(array(
        "numbers" => true,
        "symbols" => true,
        "separator" => true,
        "segment" => 5,
        "uppercase" => true,
        "lowercase" => true,
    ), $options);

    $nchar = "";
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    if ($options["numbers"]) $chars .= "1234567890";
    if ($options["symbols"]) $chars .= "!~#@$%^&*()_+={}[])|:;?.]";

    for ($i = 0; $i < $length; $i++) {
        $r = mt_rand(0, strlen($chars) - 1);

        if ($options["separator"] && $options["segment"] && $i % $options["segment"] === 0 && $i !== 0 && $i < $length) {
            $nchar .= $options["separator"];
        }

        if ($nchar === "") $r = mt_rand(0, strlen($chars) - 1);

        $nchar .= $chars[$r];
    }

    $nchar = str_replace("undefined", "zabi", $nchar);

    return $options["uppercase"] ? strtoupper($nchar) : ($options["lowercase"] ? strtolower($nchar) : $nchar);
}
