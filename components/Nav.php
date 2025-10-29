<?php




// Fetch its subcontents
$sub_sql = "SELECT * FROM subcontents WHERE content_id='3'   ORDER BY sort_order";
$subs = $conn->query($sub_sql);
// print_r($subs);

if ($subs->num_rows == 0) {
    echo "<p>❌ No subcontents found for Navbar (content_id = {$content['id']}).</p>";
    exit;
}

echo "<nav class='navbar'>";

while ($sub = $subs->fetch_assoc()) {
    $attr_sql = "SELECT * FROM attributes WHERE subcontent_id={$sub['id']}";
    $attr_result = $conn->query($attr_sql);

    $attrs = [];
    while ($row = $attr_result->fetch_assoc()) {
        $attrs[$row['attribute_name']] = stripslashes($row['attribute_value']);
    }


    if ($sub['type'] == 'logo') {
        echo "<div class='logo'>";
        echo "<a href='{$attrs['link']}'><img src='{$attrs['logo_image']}' alt='Logo'></a>";
        echo "<span class='tagline'>{$attrs['tagline']}</span>";
        echo "</div>";
    }


    if ($sub['type'] == 'menu') {
        $jsonStr = $attrs['menu_items'];
        $items = json_decode($jsonStr, true);

        // Debug: show if JSON is invalid
        if ($items === null) {
            echo "<p>❌ JSON decode error: " . json_last_error_msg() . "</p>";
            echo "<pre>$jsonStr</pre>";
            continue;
        }

        echo "<ul class='menu'>";
        foreach ($items as $item) {
            $highlight = isset($item['highlight']) ? "class='highlight'" : "";
            if (isset($item['dropdown'])) {
                echo "<li class='dropdown'><a href='{$item['link']}'>" . $item['label'] . "</a>";
                echo "<ul class='dropdown-menu'>";
                foreach ($item['dropdown'] as $d) {
                    echo "<li><a href='{$d['link']}'>" . $d['label'] . "</a></li>";
                }
                echo "</ul></li>";
            } else {
                echo "<li><a $highlight href='{$item['link']}'>" . $item['label'] . "</a></li>";
            }
        }
        echo "</ul>";
    }
}

echo "</nav>";




?>