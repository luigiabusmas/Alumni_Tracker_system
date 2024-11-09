<?php
require 'session.php';

$query = "SELECT graduation_year AS year, 
                 SUM(employment_status = 'Employed') AS employed,
                 SUM(employment_status = 'Unemployed') AS unemployed,
                 SUM(employment_status = 'Pursued Studies') AS pursued_studies
          FROM alumni_profile_table 
          GROUP BY graduation_year";
$result = $conn->query($query);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
