<?php


$contents = include("api/content.php"); // include the above file



// // Step 1: Fetch the main content
// $content_sql = "SELECT * FROM contents WHERE id='1'";
// $content_result = $conn->query($content_sql);
// // echo "Printing Something";
// // print_r($content_result);
// $content = $content_result->fetch_assoc();
// echo "Printing the reslult ";
// print_r($content);


$selectedContent = null;

foreach ($contents as $content) {
    if ($content['name'] === 'Hero Section') {
        $selectedContent = $content;
        break;
    }
}



// Step 2: Fetch subcontents
// $sub_sql = "SELECT * FROM subcontents WHERE content_id={$content['id']} ORDER BY sort_order";
// $sub_result = $conn->query($sub_sql);

$sub_sql = "SELECT * FROM subcontents WHERE content_id={$selectedContent['id']} ORDER BY sort_order";
$sub_result = $conn->query($sub_sql);

echo "again priniting ";
// print_r($sub_result);


while ($sub = $sub_result->fetch_assoc()) {
    echo "<div class='section {$sub['type']}'>";

    // Fetch attributes
    $attr_sql = "SELECT * FROM attributes WHERE subcontent_id={$sub['id']}";
    $attr_result = $conn->query($attr_sql);
    // echo "Do Something ";
    // print_r($attr_result);

    $attrs = [];
    while ($row = $attr_result->fetch_assoc()) {
        $attrs[$row['attribute_name']] = $row['attribute_value'];
    }

    // echo "<div class="section-body">";
    // Render by type
    echo '<div class="section-body">';


    echo `<div class="section-body-thing> `;

    if ($sub['type'] == 'text') {
        echo "<h1>{$attrs['heading']}</h1>";
        echo "<p>{$attrs['subheading']}</p>";
        echo "<a href='{$attrs['cta_link']}'>{$attrs['cta_text']}</a>";
    }


    if ($sub['type'] == 'qr') {
        echo "<p>{$attrs['qr_text']}</p>";
        echo "<img src='{$attrs['qr_image_url']}' alt='QR Code'>";
        echo "<a href='{$attrs['button_link']}'>{$attrs['button_text']}</a>";
    }


    echo "<div>";


    if ($sub['type'] == 'image') {
        echo "<img src='{$attrs['image_url']}' alt='{$attrs['alt_text']}'>";
    }


    echo "</div>";

    echo "</div>";
}






?>