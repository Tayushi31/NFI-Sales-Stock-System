<?php
include "db_conn.php";

$sql = "SELECT * FROM stock";
$result = mysqli_query($conn, $sql);

$data = array();
if ($result) {
    while ($row = mysqli_fetch_array($result)) {
        $a = array($row['id'], $row['particulars']);
        array_push($data, $a);
    }
}

echo json_encode($data);
?>