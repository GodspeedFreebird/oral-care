<?php
session_start();
$_SESSION['user_id'] = 14;
$_SESSION['role'] = 'patient';
$_GET['contact_id'] = 8;
require 'config/database.php';
require 'controllers/MessageController.php';

$controller = new MessageController();
$controller->load();
