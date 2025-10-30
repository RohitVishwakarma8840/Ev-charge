<?php
include("../db.php"); // your database connection file
header("Content-Type: application/json");

//  Get section name from query param (like ?name=Navbar)
$sectionName = $_GET['name'] ?? '';

if (empty($sectionName)) {
    echo json_encode(["error" => "Please provide a section name (e.g. ?name=Navbar)"]);
    exit;
}

//  Fetch the content (section) by name
$content_sql = "SELECT * FROM contents WHERE name='$sectionName' LIMIT 1";
$content_result = $conn->query($content_sql);

if ($content_result->num_rows == 0) {
    echo json_encode(["error" => "Section '$sectionName' not found"]);
    $response = ["error" => "Section '$sectionName' not found"];
    exit;
}

$content = $content_result->fetch_assoc();

//  Fetch all subcontents for that section
$sub_sql = "SELECT * FROM subcontents WHERE content_id={$content['id']} ORDER BY sort_order";
$sub_result = $conn->query($sub_sql);

$subcontents = [];

while ($sub = $sub_result->fetch_assoc()) {
    // Fetch attributes for each subcontent
    $attr_sql = "SELECT * FROM attributes WHERE subcontent_id={$sub['id']}";
    $attr_result = $conn->query($attr_sql);

    $attrs = [];
    while ($row = $attr_result->fetch_assoc()) {
        $attrs[$row['attribute_name']] = stripslashes($row['attribute_value']);
    }

    $sub['attributes'] = $attrs;
    $subcontents[] = $sub;
}

// final JSON structure
$response = [
    "data" => [
    "id" => $content['id'],
    "name" => $content['name'],
    "subcontents" => $subcontents,
    // "attributes" => $attrs,
    "status"=> "200"
    ],
];

// Output as JSON
echo json_encode($response, JSON_PRETTY_PRINT);
echo "it was printed";
?>
