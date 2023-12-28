<?php
function deleteItem()
{
    global $db;
    if (isset($_GET['d']) && $_GET['d'] != '') {
        $id = $_GET['d'];
        $t = $_GET['t'];
        if ($t == 'users') {
            $sql = "DELETE FROM visa WHERE user_id = $id";
            $db->query($sql);
        }
        $sql = "DELETE FROM $t WHERE id = $id;";
        $db->query($sql);
        if ($t == 'hotel_res') $t = 'hotel';
        header('location: /admin.php?p=' . $t);
    }
}
function getItems($table, $fields = [], $limit = 0)
{
    global $db;
    $sql = "SELECT ";
    $i = 0;
    if (count($fields) > 0)
        foreach ($fields as $f) {
            $sql .= '`' . $f . '`';
            if ($i != count($fields) - 1) $sql .= ',';
            else $sql .= ' ';
            $i++;
        }
    else $sql .= '* ';
    $sql .= "FROM `$table` ORDER BY id DESC";
    if ($limit) $sql .= " LIMIT $limit";
    $query = $db->query($sql);
    if ($query->num_rows > 0)
        return $query->fetch_all(MYSQLI_ASSOC);
}
function getDataCounts($table)
{
    global $db;
    $sql = "SELECT COUNT(*) as count FROM `$table`";
    $q = $db->query($sql);
    return $q->fetch_assoc()['count'];
}
