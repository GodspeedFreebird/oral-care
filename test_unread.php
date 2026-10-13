<?php
require 'config/database.php';
$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT receiver_id, COUNT(*) as c FROM messages WHERE is_read = 0 GROUP BY receiver_id");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
