<?php
include('db.php');

// Step 1: Fetch the main content
$content_sql = "SELECT * FROM contents WHERE name='Hero Section'";
$content_result = $conn->query($content_sql);
$content = $content_result->fetch_assoc();

// Step 2: Fetch subcontents
$sub_sql = "SELECT * FROM subcontents WHERE content_id={$content['id']} ORDER BY sort_order";
$sub_result = $conn->query($sub_sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>EVJoints Dynamic</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
while($sub = $sub_result->fetch_assoc()) {
    echo "<div class='section {$sub['type']}'>";

    // Fetch attributes
    $attr_sql = "SELECT * FROM attributes WHERE subcontent_id={$sub['id']}";
    $attr_result = $conn->query($attr_sql);

    $attrs = [];
    while($row = $attr_result->fetch_assoc()) {
        $attrs[$row['attribute_name']] = $row['attribute_value'];
    }

    // echo "<div class="section-body">";
    // Render by type
        echo '<div class="section-body">';


      echo `<div class="section-body-thing> `;

    if($sub['type'] == 'text'){
        echo "<h1>{$attrs['heading']}</h1>";
        echo "<p>{$attrs['subheading']}</p>";
        echo "<a href='{$attrs['cta_link']}'>{$attrs['cta_text']}</a>";
    }


    if($sub['type'] == 'qr'){
        echo "<p>{$attrs['qr_text']}</p>";
        echo "<img src='{$attrs['qr_image_url']}' alt='QR Code'>";
        echo "<a href='{$attrs['button_link']}'>{$attrs['button_text']}</a>";
    }
   

       echo "<div>";


    if($sub['type'] == 'image'){
        echo "<img src='{$attrs['image_url']}' alt='{$attrs['alt_text']}'>";
    }
     
 
    echo "</div>";

    echo "</div>";
}
?>

</body>
</html>
