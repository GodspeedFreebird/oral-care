<?php session_start(); $_SESSION['user_id']=2; $_SESSION['role']='practitioner'; $_GET['url']='reports'; $_SERVER['REQUEST_METHOD']='GET'; require 'public/index.php';
