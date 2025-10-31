<?php
include("../db.php");

$sql = "SELECT * FROM events ORDER BY event_date DESC";
$result = $conn->query($sql);

$events = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($events);

$conn->close();
?>
