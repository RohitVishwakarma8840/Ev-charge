<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow frontend from any origin

include '../db.php';

// Query chapters with creator name
$sql = "
    SELECT c.chapter_id, c.name, c.slug, c.city, c.description, c.created_at, c.status,
           u.user_id AS creator_id, u.name AS creator_name, u.email AS creator_email
    FROM chapters c
    JOIN users u ON c.created_by = u.user_id
";

$result = $conn->query($sql);

$chapters = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chapters[] = $row;
    }
}

// Return JSON
echo json_encode([
    "success" => true,
    "data" => $chapters, 
    "status" => 200
]);

$conn->close();
?>
