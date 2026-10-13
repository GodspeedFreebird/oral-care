<?php session_start(); $_SESSION['user_id']=2; $_SESSION['role']='patient'; $_GET['url']='patient/notifications'; $_SERVER['REQUEST_METHOD']='GET'; require 'public/index.php';
