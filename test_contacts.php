<?php
require 'config/database.php';
$db = Database::getInstance()->getConnection();
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$current_user_id = 14;

try {
    $contactsStmt = $db->prepare("
        SELECT u.user_id, u.role, u.username,
               COALESCE(p.full_name, pr.full_name, a.full_name, r.full_name, u.username) as full_name,
               MAX(m.created_at) as last_interaction,
               (SELECT COUNT(*) FROM messages m2 WHERE m2.sender_id = u.user_id AND m2.receiver_id = ? AND m2.is_read = 0) as unread_count,
               (SELECT content FROM messages m3 WHERE (m3.sender_id = u.user_id AND m3.receiver_id = ?) OR (m3.sender_id = ? AND m3.receiver_id = u.user_id) ORDER BY created_at DESC LIMIT 1) as last_message
        FROM users u
        JOIN messages m ON (m.sender_id = u.user_id AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = u.user_id)
        LEFT JOIN patients p ON u.user_id = p.user_id
        LEFT JOIN practitioners pr ON u.user_id = pr.user_id
        LEFT JOIN admins a ON u.user_id = a.user_id
        LEFT JOIN receptionists r ON u.user_id = r.user_id
        WHERE u.user_id != ?
        GROUP BY u.user_id
        ORDER BY last_interaction DESC
    ");
    $contactsStmt->execute([$current_user_id, $current_user_id, $current_user_id, $current_user_id, $current_user_id, $current_user_id]);
    $contacts = $contactsStmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($contacts);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
