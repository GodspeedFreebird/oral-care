<?php
require 'config/database.php';
$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT user_id, username, role FROM users");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
