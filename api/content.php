// api to extract all id from the database ?

<?php 


// $content_array = "SELECT id,name FROM contents";
// print_r($content_array);
// // extracting the id and name from this variable 

$sql = "SELECT id, name FROM contents";
$result = $conn->query($sql);

$contents = [];

while ($row = $result->fetch_assoc()) {
    $contents[] = $row;   // store each row as an associative array
}

// print_r($contents);

// You can either return it or make it available globally:
return $contents;

// OR store it in a variable that can be included:
// $GLOBALS['contents'] = $contents;


?>