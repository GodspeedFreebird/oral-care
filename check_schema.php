<?php
$db = new PDO('mysql:host=localhost;dbname=oauthc_dental_db', 'root', '');
$stmt = $db->query('DESCRIBE tooth_chart_entries');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
