<?php
include("db.php"); // ensure DB connection
$contents = include("api/content.php"); // fetch id & name list from database

// Step 1: Find Navbar content
$selectedContent = null;
foreach ($contents as $content) {
    if ($content['name'] === 'Navbar') {
        $selectedContent = $content;
        break;
    }
}

if (!$selectedContent) {
    echo "<p>❌ Navbar content not found.</p>";
    exit;
}

// Step 2: Fetch subcontents for Navbar
$nav_sub_sql = "SELECT * FROM subcontents WHERE content_id={$selectedContent['id']} ORDER BY sort_order";
$sub_result = $conn->query($nav_sub_sql);

if ($sub_result->num_rows == 0) {
    echo "<p>❌ No subcontents found for Navbar (content_id = {$selectedContent['id']}).</p>";
    exit;
}

echo "<nav class='navbar'>";

// Step 3: Loop through subcontents
while ($sub = $sub_result->fetch_assoc()) {

    // Fetch attributes for this subcontent
    $attr_sql = "SELECT * FROM attributes WHERE subcontent_id={$sub['id']}";
    $attr_result = $conn->query($attr_sql);

    $attrs = [];
    while ($row = $attr_result->fetch_assoc()) {
        $attrs[$row['attribute_name']] = stripslashes($row['attribute_value']);
    }

    // Step 4: Render based on subcontent type
    if ($sub['type'] == 'logo') {
        echo "<div class='logo'>";
        echo "<a href='{$attrs['link']}'><img src='{$attrs['logo_image']}' alt='Logo'></a>";
        echo "<span class='tagline'>{$attrs['tagline']}</span>";
        echo "</div>";
    }

    if ($sub['type'] == 'menu') {
        $jsonStr = $attrs['menu_items'] ?? '';
        $items = json_decode($jsonStr, true);

        if ($items === null) {
            echo "<p>❌ JSON decode error: " . json_last_error_msg() . "</p>";
            echo "<pre>$jsonStr</pre>";
            continue;
        }

        echo "<ul class='menu'>";
        foreach ($items as $item) {
            $highlight = isset($item['highlight']) ? "class='highlight'" : "";

            if (isset($item['dropdown']) && is_array($item['dropdown'])) {
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
