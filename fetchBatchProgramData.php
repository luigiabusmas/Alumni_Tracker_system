<?php
require 'session.php';

$query = "SELECT graduation_year AS batch_year, COUNT(*) AS count 
          FROM alumni_profile_table 
          GROUP BY graduation_year";
$result = $conn->query($query);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
