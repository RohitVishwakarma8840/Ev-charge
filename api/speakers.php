<?php
header("Content-Type: application/json");
// header("Access-Control-Allow-Origin: *"); // allow all origins (you can restrict later)
header("Access-Control-Allow-Methods: GET");

include '../db.php';


$response = [];

// ✅ Optional filter by event_id
if (isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);
    $sql = "SELECT s.speaker_id, s.full_name, s.email, s.topic, s.created_at, e.title AS event_name
            FROM speakers s
            JOIN events e ON s.event_id = e.event_id
            WHERE s.event_id = $event_id
            ORDER BY s.created_at DESC";
} else {
    // ✅ Fetch all speakers with their event names
    $sql = "SELECT s.speaker_id, s.full_name, s.email, s.topic, s.created_at, e.title AS event_name
            FROM speakers s
            JOIN events e ON s.event_id = e.event_id
            ORDER BY s.created_at DESC";
}

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $speakers = [];
    while ($row = $result->fetch_assoc()) {
        $speakers[] = $row;
    }
    $response = [
        // "status" => true,
        "message" => "Speakers fetched successfully",
        "data" => $speakers, 
        "status"=> 200
    ];
} else {
    $response = [
        "status" => 404,
        "message" => "No speakers found",
    ];
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
