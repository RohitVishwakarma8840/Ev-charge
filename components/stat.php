<?php

//  Stats Section
$stats_sql = "SELECT * FROM contents WHERE name='Stats Section'";
$stats_result = $conn->query($stats_sql);
print_r($stats_result);
$stats = $stats_result->fetch_assoc();

$stats_sub_sql = "SELECT * FROM subcontents WHERE content_id={$stats['id']} ORDER BY sort_order";
$stats_sub_result = $conn->query($stats_sub_sql);

echo "<section class='stats-section'>";

while ($sub = $stats_sub_result->fetch_assoc()) {
    $attr_sql = "SELECT * FROM attributes WHERE subcontent_id={$sub['id']}";
    $attr_result = $conn->query($attr_sql);

    $attrs = [];
    while ($row = $attr_result->fetch_assoc()) {
        $attrs[$row['attribute_name']] = $row['attribute_value'];
    }

    if ($sub['type'] == 'header') {
        echo "<h2>{$attrs['heading']}</h2>";
    }

    if ($sub['type'] == 'stats') {
        $cards = json_decode($attrs['cards'], true);
        echo "<div class='stats-container'>";
        foreach ($cards as $card) {
            echo "<div class='stat-card'>";
            echo "<img src='{$card['icon_url']}' alt='icon'>";
            echo "<h3>{$card['value']}</h3>";
            echo "<p>{$card['label']}</p>";
            echo "</div>";
        }
        echo "</div>";
    }
}
echo "</section>";






?>